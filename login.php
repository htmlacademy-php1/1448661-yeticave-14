<?php

/**
 * @var array $categories
 * @var mysqli $link
 * @var array $errors
 */

require_once __DIR__ . '/bootstrap.php';

$categories = getCategories($link);
$userId = getUserIdFromSession();

$errors = [];
$formLogin = [];

if ($userId !== null) {
    responseForbidden($categories);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formLogin = filter_input_array(INPUT_POST, [
        'email' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT
    ], true);

    $errors = validateLoginForm($link, $formLogin);

    if (!$errors && login($link, $formLogin)) {
        header("Location: /");
        exit();
    }
}
$content = includeTemplate('login.php', [
    'categories' => $categories,
    'formLogin' => $formLogin,
    'errors' => $errors
]);

$layoutContent = includeTemplate('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => 'Вход'
]);

print($layoutContent);
