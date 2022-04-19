<?php
/**
 * @var array $lots
 * @var array $categories
 * @var mysqli $link
 */
session_start();
require_once __DIR__ . '/bootstrap.php';
$userName = checkSessionsName($_SESSION);


//Получает массив категорий и БД
$categories = getCategories($link);
////Проверяет существование параметра запроса с ID лота.
$lotId = filter_input(INPUT_GET, 'id');
//Проверяет параметр
if ($lotId === NULL) {
    $content = includeTemplate('404.php',
        ['categories' => $categories]);

    $layoutContent = includeTemplate('layout.php',
        ['content' => $content,
            'categories' => $categories, 'userName' => $userName, 'title' => 'Страница лота']);
    print($layoutContent);
    exit();
}

//Получает массив с лотами из БД
$lots = getLogById($link, $lotId);
//Проверяет на наличие в запросе id
if ($lots[0]['id']) {
    $content = includeTemplate('lot.php',
        ['categories' => $categories,
            'lots' => $lots]);

    $layoutContent = includeTemplate('layout.php',
        ['content' => $content,
            'categories' => $categories, 'title' => 'Страница лота', 'userName' => $userName]);

    print($layoutContent);
} else {
    $content = includeTemplate('404.php',
        ['categories' => $categories]);

    $layoutContent = includeTemplate('layout.php',
        ['content' => $content,
            'categories' => $categories, 'userName' => $userName, 'title' => 'Страница лота']);

    print($layoutContent);
    exit();
}

