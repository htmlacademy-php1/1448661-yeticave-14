<?php

/**
 * @var array $categories
 * @var mysqli $link
 * @var array $_SESSION
 * @var array $config
 */

require_once __DIR__ . '/bootstrap.php';
$categories = getCategories($link);
$categoriesIds = getCategoriesIds($categories);

$currentPage = $_GET['page'] ?? 1;
checkCurrentPage($currentPage, $categories);

$paginationLimit = $config['pagination_limit'];

$userName = checkSessionsName($_SESSION);

$categoryId = filter_input(INPUT_GET, 'categoryId', FILTER_SANITIZE_NUMBER_INT);

if (!in_array($categoryId, $categoriesIds)) {
    responseNotfound($categories);
}
$lots = getLotByCategory($link, $categoryId, $currentPage, $paginationLimit);

$countLots = getQuantitiesLotsByCategory($link, $categoryId);
$pageCount = ceil($countLots / $paginationLimit);
$pages = range(1, $pageCount);

if (!in_array($currentPage, $pages)) {
    responseNotfound($categories);
}

$content = includeTemplate('all-lots.php', [
    'categories' => $categories,
    'lots' => $lots,
    'categoryId' => $categoryId,
    'currentPage' => $currentPage,
    'pageCount' => $pageCount,
    'pages' => $pages,
    'link' => $link
]);

$layoutContent = includeTemplate('layout.php', [
    'content' => $content,
    'userName' => $userName,
    'categories' => $categories,
    'title' => 'Все лоты'
]);

print($layoutContent);
