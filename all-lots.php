<?php
/**
 * @var array $categories
 * @var mysqli $link
 * @var array $_SESSION
 */


require_once __DIR__ . '/bootstrap.php';

$categories = getCategories($link);
$userName = checkSessionsName($_SESSION);


$content = includeTemplate('all-lots.php', ['categories' => $categories]);

$layoutContent = includeTemplate('layout.php', [
    'content' => $content,
    'userName' => $userName,
    'categories' => $categories,
    'title' => 'Все лоты'
]);

print($layoutContent);

