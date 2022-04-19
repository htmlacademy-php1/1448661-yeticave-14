<?php
/**
 * @var array $lots
 * @var array $categories
 * @var mysqli $link
 * @var array $_SESSION
 */

session_start();

require_once __DIR__ . '/bootstrap.php';
$categories = getCategories($link);
$userName = checkSessionsName($_SESSION);
$lots = getOpenLots($link);

$content = includeTemplate('main.php',
    ['lots' => $lots, 'categories' => $categories]);

$layoutContent = includeTemplate('layout.php',
    ['content' => $content,
        'userName' => $userName,
        'categories' => $categories,
        'title' => 'Главная страница']);

print($layoutContent);


