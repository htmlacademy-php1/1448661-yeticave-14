<?php
/**
 * @var array $lots
 * @var array $categories
 * @var mysqli $link
 * @var array $_SESSION
 */

require_once __DIR__ . '/bootstrap.php';

$categories = getCategories($link);
$userName = checkSessionsName($_SESSION);


$content = includeTemplate('my-bets.php', ['categories' => $categories]);

$layoutContent = includeTemplate('layout.php', [
    'content' => $content,
    'userName' => $userName,
    'categories' => $categories,
    'title' => 'Мои ставки'
]);

print($layoutContent);
