<?php
/**
 * @var array $categories
 * @var mysqli $link
 */
session_start();
if (empty($_SESSION)) {
    http_response_code(403);
    header('Location: /login.php');
    exit();
}

require_once __DIR__ . '/bootstrap.php';
$categories = getCategories($link);
$categoryIds = getCategoriesId($categories);
$userName = checkSessionsName($_SESSION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lot = filter_input_array(INPUT_POST, ['title' => FILTER_DEFAULT, 'category_id' => FILTER_DEFAULT,
        'description' => FILTER_DEFAULT, 'price' => FILTER_DEFAULT, 'step_bet' => FILTER_DEFAULT, 'end_date' => FILTER_DEFAULT], true);

    $errors = validateFormLot($lot, $categoryIds);

    if (!empty($_FILES['image']['name'])) {
        $fileName = uploadFile($_FILES);
        if (empty($fileName)) {
            $errors['image'] = 'Загрузите картинку в формате png или jpeg';
        } else {
            $lot['image'] = $fileName;
        }
    } else {
        $errors['image'] = 'Загрузите картинку';
    }

    $errors = array_filter($errors);

    $content = includeTemplate('add.php',
        ['categories' => $categories, 'errors' => $errors]);


    if (empty($errors)) {
        $result = addLot($link, $lot, $_SESSION);
        if ($result) {
            $lotId = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lotId);
        } else {
            $content = includeTemplate('404.php',
                ['categories' => $categories]);

            $layoutContent = includeTemplate('layout.php',
                ['content' => $content,
                    'categories' => $categories, 'userName' => $userName, 'title' => 'Страница лота']);

            print($layoutContent);
            exit();
        }
    }
} else {
    $content = includeTemplate('add.php',
        ['categories' => $categories]);
}
$layoutContent = includeTemplate('layout.php',
    ['content' => $content,
        'userName' => $userName,
        'categories' => $categories,
        'title' => 'Добавление лота']);
print($layoutContent);

