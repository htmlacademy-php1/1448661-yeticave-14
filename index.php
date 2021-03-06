<?php

/**
 * @var array $lots
 * @var array $categories
 * @var mysqli $link
 * @var array $_SESSION
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/getwinner.php';

$categories = getCategories($link);
$userName = checkSessionsName($_SESSION);
$lots = getOpenLots($link);

$content = includeTemplate('main.php', ['lots' => $lots, 'categories' => $categories]);

$layoutContent = includeTemplate('layout.php', [
    'content' => $content,
    'userName' => $userName,
    'categories' => $categories,
    'title' => 'Главная'
]);

print($layoutContent);
