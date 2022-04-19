<?php

/**
 * @var array $categories
 * @var mysqli $link
 * @var array $errors
 */
session_start();
if (!empty($_SESSION)) {
    http_response_code(403);
    header('Location: ./');
    exit();
}
require_once __DIR__ . '/bootstrap.php';
$categories = getCategories($link);
$userName = checkSessionsName($_SESSION);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $content = includeTemplate('login.php',
        ['categories' => $categories]);
    $layoutContent = includeTemplate('layout.php',
        ['content' => $content,
            'categories' => $categories,
            'userName' => $userName,
            'title' => 'Вход']);
    print($layoutContent);
} else {
    $formLogin = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT], add_empty: true);

    $errors = validateLoginForm($link, $formLogin);
    $errors = array_filter($errors);
    if(!empty($errors)) {
        $content = includeTemplate('login.php',
            ['categories' => $categories, 'errors' => $errors]);
        $layoutContent = includeTemplate('layout.php',
            ['content' => $content,
                'userName' => $userName,
                'categories' => $categories,
                'title' => 'Регистрация']);
        print($layoutContent);
    } else {
        if (!empty($_SESSION)) {
            header("Location: ./");
        }
    }
}


