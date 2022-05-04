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
 * @param $email
 * @return string|null
 */
function validateEmailField($email): string|null
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
 * @param $link
 * @param $email
 * @param $password
 * @return string|null
 */
function validatePassword($link, $email, $password): string|null
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
