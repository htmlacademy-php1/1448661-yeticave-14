<?php
/**
 * @var array $searchResult
 * @var array $categories
 * @var mysqli $link
 * @var array $_SESSION
 */

require_once __DIR__ . '/bootstrap.php';

$currentPage = $_GET['page'] ?? 1;
$lotsLimit = 9;
$offset = $lotsLimit * ($currentPage - 1);


$categories = getCategories($link);
$userName = checkSessionsName($_SESSION);
$search = filter_input(INPUT_GET, "search");
$search = trim(strip_tags($search));

$searchResult = getLotBySearch($link, $search, $lotsLimit, $offset );

$countLotsFromSearch = countLotsFromSearch($link, $search);


$pageCount = ceil($countLotsFromSearch / $lotsLimit);
$pages = range(1, $pageCount);


if ($search === "" || empty($searchResult) ) {
    $search = 'Ничего не найдено по вашему запросу';
}

$currentCount = $searchResult;

$content = includeTemplate('search.php', [
    'categories' => $categories,
    'search' => $search,
    'searchResult'=> $searchResult,
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









