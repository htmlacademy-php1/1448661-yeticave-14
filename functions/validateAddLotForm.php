<?php
/**
 * Функция валидирует поля из формы добавления лота.
 * @param array $lotData
 * @param array $categoryIds
 * @return array возвращает массив с ошибками или пустой массив.
 */

function validateAddLotForm(array $lotData, array $categoryIds): array
{
    $errors = [
        'title' => validateEmptyFields($lotData['title']),
        'category_id' => validateCategory($lotData['category_id'], $categoryIds),
        'description' => validateEmptyFields($lotData['description']),
        'image' => validateImage($_FILES),
        'price' => validateValue($lotData['price']),
        'step_bet' => validateValue($lotData['step_bet']),
        'end_date' => validateEndDate($lotData['end_date'])
    ];
    return array_filter($errors);
}

/**
 * Функция валидирует категории в форме добавления лота
 * @param string $id
 * @param array $categoryIds
 * @return string|null
 */

function validateCategory(string $id, array $categoryIds): string|null
{
    if (!in_array($id, $categoryIds)) {
        return "Указана несуществующая категория";
    }
    return null;
}

/**
 * Функция варидирует поле "Начальная цена" и "Шаг ставки"
 * @param string $value
 * @return string|null
 */
function validateValue(string $value): string|null
{
    if ($value === "") {
        return "Поле надо заполнить";
    }
    $value = intval($value);
    if ($value <= 0) {
        return "Значение должно быть больше нуля";
    }
    return null;
}

/**
 * Функция валидирует поле "Дата окончания торгов"
 * @param string $endDate
 * @return string|null
 */
function validateEndDate(string $endDate): string|null
{
    if ($endDate === "") {
        return "Поле надо заполнить";
    }
    if (!isDateValid($endDate)) {
        return "Значение должно быть датой в формате «ГГГГ-ММ-ДД»";
    }
    $time = getDtRange($endDate, 'now');
    if ($time[0] < 24) {
        return "Дата должна быть больше текущей даты, хотя бы на один день.";
    }
    return null;
}

/**
 * Функция валидирует загрузку файла
 * @param $files
 * @return string|void
 */

function validateImage($files)
{
    if ($files['image']['name'] === "") {
        return 'Загрузите картинку';
    }

    $tmpName = $files['image']['tmp_name'];
    $fileType = mime_content_type($tmpName);

    if ($fileType !== 'image/png' && $fileType !== 'image/jpeg') {
        return 'Загрузите картинку в формате png или jpeg';
    }
}

/**
 * Функция переименовывает файл и перемещает в папку uploads
 * @param array $files
 * @return string
 */
function uploadFile(array $files): string
{
    $tmpName = $files['image']['tmp_name'];
    $fileType = mime_content_type($tmpName);


    if ($fileType === 'image/png') {
        $name = uniqid() . '.png';
    } else {
        $name = uniqid() . '.jpeg';
    }
    move_uploaded_file($tmpName, 'uploads/' . $name);
    return 'uploads/' . $name;
}
