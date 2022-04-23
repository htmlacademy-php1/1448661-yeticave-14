<?php
/**
 * Функция валидирует форму регистрации
 * @param mysqli $link
 * @param array $newAccountData
 * @return array
 */
function validateSignUpForm(mysqli $link, array $newAccountData): array
{
    $errors = [
        'email' => validateEmail($link, $newAccountData['email']),
        'password' => validateEmptyFields($newAccountData['password']),
        'name' => validateEmptyFields($newAccountData['name']),
        'contacts' => validateEmptyFields($newAccountData['contacts'])
    ];
    return array_filter($errors);
}

/**
 * Функция валидирует поле email  в форме регистрации
 * @param mysqli $link
 * @param string $email
 * @return string|null
 */
function validateEmail(mysqli $link, string $email): ?string
{
    if ($email === "") {
        return "Поле надо заполнить";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Некорректно введен e-mail';
    }
    if (getUserByEmail($link, $email)) {
        return 'email используется другим пользователем';
    }

    return null;
}


