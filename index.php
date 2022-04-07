<?php
/**
 * @var array $lots
 * @var array $categories
 */


require_once __DIR__ .'/bootstrap.php';
$link = getLink();
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


