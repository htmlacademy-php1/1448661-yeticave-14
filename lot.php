<?php
require_once __DIR__ . '/bootstrap.php';
//Подключение к БД
$link = getLink();
//Получает массив категорий и БД
$categories = getCategories($link);
////Проверяет существование параметра запроса с ID лота.
$lotId = filter_input(INPUT_GET, 'id');
//Проверяет параметр
if ($lotId === NULL) {
    $content = includeTemplate('404.php',
        ['categories'=> $categories]);

    $layoutContent = includeTemplate('layout.php',
        ['content' => $content,
            'categories'=> $categories, 'userName' => 'Михаил', 'title' => 'Страница лота']);
    print($layoutContent);
    exit();
}

//Получает массив с лотами из БД
$lots = getLotId ($link, $lotId) ;
//Проверяет на наличие в запросе id
if ($lots[0]['id']) {
    $content = includeTemplate('lot.php',
        ['categories'=> $categories,
            'lots' => $lots] );

    $layoutContent = includeTemplate('layout.php',
        ['content' => $content,
            'categories'=> $categories, 'userName' => 'Михаил', 'title' => 'Страница лота']);

    print($layoutContent);
} else {
    $content = includeTemplate('404.php',
        ['categories'=> $categories]);

    $layoutContent = includeTemplate('layout.php',
        ['content' => $content,
            'categories'=> $categories, 'userName' => 'Михаил', 'title' => 'Страница лота']);

    print($layoutContent);
    exit();
}

