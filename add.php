<?php
/**
 * @var array $categories
 * @var mysqli $link
 */
require_once __DIR__ . '/bootstrap.php';
$categories = getCategories($link);




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $requiredFields = ['title', 'category_id', 'description', 'price', 'step_bet', 'end_date'];

$categoryIds = getCategoriesId($categories);

$rules = [
    'category_id' => function($categoryId) use ($categoryIds) {
    return validateCategory($categoryId, $categoryIds );
    },
    'price' => function($price) {
    return validateValue($price);
    },
    'end_date' => function($endDate) {
    return validateEndDate ($endDate);
    },
    'step_bet' => function($stepBet){
        return validateValue($stepBet);
    }
];

$lot = filter_input_array(INPUT_POST, ['title' => FILTER_DEFAULT, 'category_id' => FILTER_DEFAULT,
    'description' => FILTER_DEFAULT, 'price' => FILTER_DEFAULT, 'step_bet' => FILTER_DEFAULT, 'end_date' => FILTER_DEFAULT], true );

    $errors = [];

    foreach ($lot as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
        if (in_array($key, $requiredFields) and empty($value)){
              $errors[$key] = "Поле надо заполнить";
        }
    };


if (!empty($_FILES['image']['name'])) {
    $tmpName = $_FILES['image']['tmp_name'];
    $name = $_FILES['image']['name'];
    $fileType = mime_content_type($tmpName);

    if ($fileType === 'image/png'){
        $name = uniqid() . '.png';
        move_uploaded_file($tmpName, 'uploads/' . $name);
        $lot['image'] = 'uploads/' . $name;
    } elseif ($fileType === 'image/jpeg'){
        $name = uniqid() . '.jpeg';
        move_uploaded_file($tmpName, 'uploads/' . $name);
        $lot['image'] = 'uploads/' . $name;
    } else {
        $errors['file'] = 'Загрузите картинку в формате png или jpeg';
    }
} else {
    $errors['file'] = 'Загрузите картинку';
};

    $errors = array_filter($errors);

    $content = includeTemplate('add.php',
        ['categories'=> $categories, 'errors' => $errors] );



if (empty($errors)) {

    addLot($link, $lot, $categories );

}
} else {
    $content = includeTemplate('add.php',
        ['categories'=> $categories] );
}
$layoutContent = includeTemplate('layout.php',
    ['content' => $content,
        'userName' => 'Михаил',
        'categories'=> $categories,
        'title'=> 'Добавление лота']);
print($layoutContent);

