<?php

/**
 * Функция собирает страницу с 404 ошибкой
 * @param array $categories
 * @return void
 */
function responseNotfound(array $categories): void
{
    $content = includeTemplate('404.php', ['categories' => $categories]);

    $layoutContent = includeTemplate('layout.php', [
        'content' => $content,
        'categories' => $categories,
        'title' => 'Страница не найдена'
    ]);

    print($layoutContent);
    exit();
}

/**
 * Функция собирает страницу с 403 ошибкой
 * @param array $categories
 * @return void
 */
function responseForbidden(array $categories): void
{
    $content = includeTemplate('403.php', ['categories' => $categories]);

    $layoutContent = includeTemplate('layout.php', [
        'content' => $content,
        'categories' => $categories,
        'title' => 'Доступ запрещен'
    ]);

    print($layoutContent);
    exit();
}
