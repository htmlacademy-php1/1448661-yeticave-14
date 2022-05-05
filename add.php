<?php

/**
 * @var array $categories
 * @var mysqli $link
 */

require_once __DIR__ . '/bootstrap.php';
$errors = [];
$lotData = [];

$categories = getCategories($link);
$categoryIds = getCategoriesIds($categories);
$userId = getUserIdFromSession();

if ($userId === null) {
    responseForbidden($categories);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lotData = filter_input_array(INPUT_POST, [
        'title' => FILTER_DEFAULT,
        'category_id' => FILTER_DEFAULT,
        'description' => FILTER_DEFAULT,
        'price' => FILTER_DEFAULT,
        'step_bet' => FILTER_DEFAULT,
        'end_date' => FILTER_DEFAULT,
        'image' => FILTER_DEFAULT
        ], true);

    $errors = validateAddLotForm($lotData, $categoryIds);

    if (!$errors) {
        if (addLot($link, $lotData, $userId)) {
            $lotId = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lotId);
        }
    }
}
$content = includeTemplate('add.php', [
    'categories' => $categories,
    'errors' => $errors,
    'lotData' => $lotData
]);

$layoutContent = includeTemplate('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => 'Добавление лота'
]);
print($layoutContent);
