<?php

/**
 * Функция записывает в массив $_SESSION id и  name пользователя
 * @param mysqli $link
 * @param array $formLogin
 * @return bool
 */
function login(mysqli $link, array $formLogin): bool
{
    $user = getUserByEmail($link, $formLogin['email']);
    if ($user === null) {
        return false;
    }
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    return true;
}

/**
 * Функция получает id пользователя из $_SESSION
 * @return mixed|null
 */
function getUserIdFromSession(): ?string
{
    return $_SESSION['user_id'] ?? null;
}
