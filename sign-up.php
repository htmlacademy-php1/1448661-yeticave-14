<?php
/**
 * @var array $categories
 * @var mysqli $link
 * @var array $errors
 */

require_once __DIR__ . '/bootstrap.php';
$newAccountData = [];
$errors =[];
$categories = getCategories($link);
$userName = checkSessionsName($_SESSION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newAccountData = filter_input_array(INPUT_POST, [
        'email' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT,
        'name' => FILTER_DEFAULT, 'contacts' => FILTER_DEFAULT
    ], add_empty: true);

    $errors = validateSignUpForm ($link ,$newAccountData);
    if (!$errors) {
        addUser($link, $newAccountData);
        header("Location: /login.php");
        exit();
    }
}
$content = includeTemplate('sign-up.php', [
    'categories' => $categories,
    'newAccountData' => $newAccountData,
    'errors' => $errors
]);

$layoutContent = includeTemplate('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => 'Вход'
]);

print($layoutContent);
