<?php

/**
 * Функция варидуриет поля из формы входа
 * @param mysqli $link
 * @param array $formLogin
 * @return array
 */
function validateLoginForm(mysqli $link, array $formLogin): array
{
    $errors = [
        'email' => validateEmailField($formLogin['email']),
        'password' => validatePassword($link, $formLogin['email'], $formLogin['password'])
    ];
    return array_filter($errors);
}

/**
 * Функция валидирует поле ввода email
 * @param string $email
 * @return string|null
 */
function validateEmailField(string $email): string|null
{
    if ($email === "") {
        return "Поле надо заполнить";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Некорректно введен e-mail';
    }

    return null;
}

/**
 * Функция валидирует поле ввода password
 * @param mysqli $link
 * @param string $email
 * @param string $password
 * @return string|null
 */
function validatePassword(mysqli $link, string $email, string $password): string|null
{
    if ($password === "") {
        return "Поле надо заполнить";
    }
    $user = getUserByEmail($link, $email);
    if ($user === null) {
        return "Неверный email или password";
    }
    if (!password_verify($password, $user['password'])) {
        return 'Введен неверный пароль';
    }
    return null;
}
