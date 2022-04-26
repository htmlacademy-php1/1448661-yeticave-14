<?php
/**
 * @var array $lots
 * @var array $categories
 * @var mysqli $link
 */

require_once __DIR__ . '/bootstrap.php';

$categories = getCategories($link);

$lotId = filter_input(INPUT_GET, 'id');

if ($lotId === NULL) {
    responseNotfound($categories);
}


$lots = getLogById($link, $lotId);

if ($lots[0]['id']) {
    $content = includeTemplate('lot.php', [
        'categories' => $categories,
        'lots' => $lots
    ]);

    $layoutContent = includeTemplate('layout.php', [
        'content' => $content,
        'categories' => $categories,
        'title' => $lots[0]['title'],
    ]);

    print($layoutContent);
} else {
    responseNotfound($categories);
}

