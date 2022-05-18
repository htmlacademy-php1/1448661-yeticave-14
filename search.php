<?php

/**
 * @var array $searchResult
 * @var array $categories
 * @var mysqli $link
 * @var array $_SESSION
 * @var array $config
 */

require_once __DIR__ . '/bootstrap.php';
$categories = getCategories($link);

$currentPage = $_GET['page'] ?? 1;
$currentPage = intval($currentPage);
checkCurrentPage($currentPage, $categories);

$paginationLimit = $config['pagination_limit'];

$userName = checkSessionsName($_SESSION);

$search = filter_input(INPUT_GET, "search", FILTER_SANITIZE_SPECIAL_CHARS);
$search = trim(strip_tags($search));

$searchResult = getLotBySearch($link, $search, $currentPage, $paginationLimit);

$countLotsFromSearch = countLotsFromSearch($link, $search);

$pageCount = ceil($countLotsFromSearch / $paginationLimit);
$pageCount = intval($pageCount);
$pages = range(1, $pageCount);

if (!in_array($currentPage, $pages)) {
    responseNotfound($categories);
}
if ($search === "" || empty($searchResult)) {
    $search = 'Ничего не найдено по вашему запросу';
}

    $currentCount = $searchResult;
    $content = includeTemplate('search.php', [
        'categories' => $categories,
        'search' => $search,
        'searchResult' => $searchResult,
        'currentPage' => $currentPage,
        'pageCount' => $pageCount,
        'pages' => $pages
    ]);

    $layoutContent = includeTemplate('layout.php', [
        'content' => $content,
        'userName' => $userName,
        'categories' => $categories,
        'title' => 'Результаты поиска'
    ]);

    print($layoutContent);
