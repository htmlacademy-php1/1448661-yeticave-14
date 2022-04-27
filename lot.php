<?php
/**
 * @var array $lots
 * @var array $categories
 * @var mysqli $link
 */
require_once __DIR__ . '/bootstrap.php';

$errors = [];
$categories = getCategories($link);

$lotId = filter_input(INPUT_GET, 'id');
$lotData = getLotById($link, $lotId);


$userId = getUserIdFromSession();

if ($lotId === NULL) {
    responseNotfound($categories);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formOfBets = filter_input_array(INPUT_POST, ['price' => FILTER_DEFAULT], add_empty: true);
    $errors = validateBetsForm($formOfBets, $lotData);

    if (!$errors) {
        if (addBet($link, $formOfBets, $userId, $lotId)){
            header("Location: lot.php?id=" . $lotId);
        }
    }
}

$getBets = getBets($link, $lotId);

if ($lotData[0]['id']) {
    $content = includeTemplate('lot.php', [
        'categories' => $categories,
        'lotData' => $lotData,
        'errors' => $errors,
        'getBets' => $getBets
    ]);

    $layoutContent = includeTemplate('layout.php', [
        'content' => $content,
        'categories' => $categories,
        'title' => $lotData[0]['title'],
    ]);

    print($layoutContent);
} else {
    responseNotfound($categories);
}


