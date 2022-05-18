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
        'password' => validateUserPassword($newAccountData['password']),
        'name' => validateUserName($newAccountData['name']),
        'contacts' => validateUserContacts($newAccountData['contacts'])
    ];
    return array_filter($errors);
}

/**
 * Функция валидирует поле email  в форме регистрации
 * @param mysqli $link
 * @param string $email
 * @return string|null
 */
function validateEmail(mysqli $link, string $email): string|null
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
    if (mb_strlen($email) > 128) {
        return "Не более 128 символов";
    }
    return null;
}

/**
 * Функция проверяет поле имя на заполненость и на количество символов
 * @param string $name
 * @return string|null
 */
function validateUserName(string $name): string|null
{
    if ($name === "") {
        return "Поле надо заполнить";
    }
    if (mb_strlen($name) > 50) {
        return "Не более 50 символов";
    }
    return null;
}

/**
 * Функция проверяет поле контакты на заполненость и на количество символов
 * @param string $contacts
 * @return string|null
 */
function validateUserContacts(string $contacts): string|null
{
    if ($contacts === "") {
        return "Поле надо заполнить";
    }
    if (mb_strlen($contacts) > 255) {
        return "Не более 255 символов";
    }
    return null;
}

/**
 * Функция проверяет поле пароль на заполненость и на количество символов
 * @param string $password
 * @return string|null
 */
function validateUserPassword(string $password): string|null
{
    if ($password === "") {
        return "Поле надо заполнить";
    }
    if (mb_strlen($password) > 20) {
        return "Не более 20 символов";
    }
    return null;
}
