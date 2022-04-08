<?php
/**
 * @var array $lots
 * @var array $categories
 * @var mysqli $link
 */


require_once __DIR__ .'/bootstrap.php';
$categories = getCategories($link);
$lots = getOpenLots($link);


$content = includeTemplate('main.php',
    ['lots' => $lots, 'categories'=> $categories] );

$layoutContent = includeTemplate('layout.php',
       ['content' => $content,
        'userName' => 'Михаил',
        'categories'=> $categories,
        'title'=> 'Главная страница']);

print($layoutContent);


