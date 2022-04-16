<?php

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function isDateValid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Функция возвращает отформатированную цену и пустую строку если переменная переданная в атрибут не имеент значения
 * @param  $price
 * @return string|null
 */
function checkPriceValue($price): ?string
{
    if (isset($price)) {
        return priceFormatting($price);
    } else {
        return "";
    }
}

/**
 * Функция получает значений из POST-запроса для сохранения введённых значений в полях формы.
 * @param mixed $val
 * @return mixed|string
 */
function getPostVal(mixed $val): ?string
{
    return $_POST[$val] ?? "";
}


/**
 * Проверка категорий
 * @param string $id
 * @param array $allowed_list
 * @return string|null
 */
function validateCategory(string $id, array $allowed_list): ?string
{
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
    return null;
}

/**
 * @param string $value
 * @return string|null
 */
function validateValue(string $value): ?string
{
    $value = intval($value);
    if ($value <= 0) {
        return "Значение должно быть больше нуля";
    }
    return null;
}

/**
 * Функция проверяе дату завершения, должна быть больше текущей даты, хотя бы на один день.
 * @param string $endDate
 * @return string|null
 */

function validateEndDate(string $endDate): ?string
{
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
 * Функция валидирует поля формы и возвращает массив с ошибками
 * @param array $lot
 * @param array $categoryIds
 * @return array
 */
function validateFormLot(array $lot, array $categoryIds): array
{
    $requiredFields = ['title', 'category_id', 'description', 'price', 'step_bet', 'end_date'];

    $rules = [
        'category_id' => function ($categoryId) use ($categoryIds) {
            return validateCategory($categoryId, $categoryIds);
        },
        'price' => function ($price) {
            return validateValue($price);
        },
        'end_date' => function ($endDate) {
            return validateEndDate($endDate);
        },
        'step_bet' => function ($stepBet) {
            return validateValue($stepBet);
        }
    ];

    $errors = [];
    foreach ($lot as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
        if (in_array($key, $requiredFields) and empty($value)) {
            $errors[$key] = "Поле надо заполнить";
        }
    }

    return $errors;
}

/**
 * Функция меняет название файла и загружает в uploads
 * @param array $file
 * @return string
 */

function uploadFile(array $file): string
{
    $tmpName = $file['image']['tmp_name'];
    $name = $file['image']['name'];
    $fileType = mime_content_type($tmpName);


    if ($fileType === 'image/png') {
        $name = uniqid() . '.png';
        move_uploaded_file($tmpName, 'uploads/' . $name);
        return 'uploads/' . $name;
    } elseif ($fileType === 'image/jpeg') {
        $name = uniqid() . '.jpeg';
        move_uploaded_file($tmpName, 'uploads/' . $name);
        return 'uploads/' . $name;
    } else {
        return '';
    }
}

