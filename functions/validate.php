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
function getPriceFormat($price): ?string
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
 * Функция проверяет поля на заполение, возвращает текст с ошибкой, если поле не заполнено.
 * @param $value
 * @return string|void
 */
function validateEmptyFields($value)
{
    if ($value === "") {
        return "Поле надо заполнить";
    }
}


