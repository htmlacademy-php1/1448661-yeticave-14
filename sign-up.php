<?php
/**
 * @var array $categories
 * @var mysqli $link
 * @var array $errors
 */
session_start();
require_once __DIR__ . '/bootstrap.php';
$categories = getCategories($link);
$userName = checkSessionsName($_SESSION);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $content = includeTemplate('sign-up.php',
        ['categories' => $categories]);
    $layoutContent = includeTemplate('layout.php',
        ['content' => $content,
            'categories' => $categories,
            'title' => 'Регистрация']);
    print($layoutContent);
} else {
    $newAccount = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT, 'name' => FILTER_DEFAULT, 'contacts' => FILTER_DEFAULT], add_empty: true);

    $errors = validateFormRegistration($link, $newAccount);
    $errors = array_filter($errors);

    if(!empty($errors)) {
        $content = includeTemplate('sign-up.php',
            ['categories' => $categories, 'errors' => $errors]);
        $layoutContent = includeTemplate('layout.php',
            ['content' => $content,
                'categories' => $categories,
                'title' => 'Регистрация']);
        print($layoutContent);
    } else {
        $result = addUser( $link, $newAccount);
        if ($result) {
            header("Location: /login.php");
        } else {
            mysqli_error($link);
        }
    }
}

