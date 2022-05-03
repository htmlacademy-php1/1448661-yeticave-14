<?php
/**
 * @var array $lots
 * @var array $categories
 * @var mysqli $link
 * @var array $_SESSION
 */

require_once __DIR__ . '/bootstrap.php';

$userName = checkSessionsName($_SESSION);

$categories = getCategories($link);
$userId = getUserIdFromSession();


if ($userId !== null) {
    $userAllBets = getUserBets($link, $userId);

    $contact = getLotCreatorContacts($link, 35);
    myPrint( $contact);

    $content = includeTemplate('my-bets.php', [
        'categories' => $categories,
        'userAllBets' => $userAllBets,
        'link' => $link
    ]);

    $layoutContent = includeTemplate('layout.php', [
        'content' => $content,
        'userName' => $userName,
        'categories' => $categories,
        'title' => 'Мои ставки'
    ]);

    print($layoutContent);
} else {
    responseForbidden($categories);
}


