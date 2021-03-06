<?php

/**
 * @var array $lots
 * @var array $categories
 * @var mysqli $link
 */
require_once __DIR__ . '/bootstrap.php';

$errors = [];
$categories = getCategories($link);
$lotId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($lotId === '') {
    responseNotfound($categories);
}

$lotData = getLotById($link, $lotId);

$userId = getUserIdFromSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formOfBets = filter_input_array(INPUT_POST, ['price' => FILTER_DEFAULT], add_empty: true);
    $errors = validateBetsForm($formOfBets, $lotData);

    if (!$errors) {
        if (addBet($link, $formOfBets, $userId, $lotId)) {
            header("Location: lot.php?id=" . $lotId);
        }
    }
}

$lotBets = getLotBets($link, $lotId);


if (!isset($lotData['id'])) {
    responseNotfound($categories);
}

$endDate = $lotData['end_date'];
$lotCreatorId = $lotData['user_id'];

$content = includeTemplate('lot.php', [
    'categories' => $categories,
    'lotData' => $lotData,
    'errors' => $errors,
    'lotBets' => $lotBets,
    'userId' => $userId,
    'endDate' => $endDate,
    'lotId' => $lotId,
    'lotCreatorId' => $lotCreatorId
]);

$layoutContent = includeTemplate('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => $lotData['title']

]);

print($layoutContent);
