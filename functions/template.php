<?php

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function includeTemplate(string $name, array $data = []): string
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function getNounPluralForm(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}


/**
 * Возвращает отформатированную цену со знаком рубля.
 * @param integer $price Входящая цена.
 * @return string Отформатированная цена с пробелом после первых двух знаков со знаком рубля.
 */
function priceFormatting(int $price): string
{
    $price = ceil($price);
    if ($price > 1000) {
        $price = number_format($price, 0, null, ' ');
    }
    return $price . ' &#8381';
}


/**
 * Функция возвращает массив, где первый элемент — целое количество часов до даты, а второй — остаток в минутах.
 * @param string $date
 * @param string $currentDate Текущая дата.
 * @return array Массив часы и минуты до завершения лота.
 */
function getDtRange(string $date, string $currentDate): array
{
    $date = date_create($date);
    $currentDate = date_create($currentDate);

    if ($date <= $currentDate) {
        return [0, 0];
    }
    $diff = date_diff($currentDate, $date);

    $days = $diff->days;
    $hours = $diff->h;
    $minutes = $diff->i;

    if ($days > 0) {
        $hours = $days * 24 + $hours;
        return [$hours, $minutes];
    }

    return [$hours, $minutes];
}

/**
 * Функция проверяет не пуст ли массив $_SESSION по ключу name;
 * @param array $sessions
 * @return string
 */
function checkSessionsName(array $sessions): string
{
    if (!empty($_SESSION)) {
        return $_SESSION['name'];
    } else {
        return ' ';
    }
}

/**
 * Функция возвращает отформатированную цену без знаку рубль
 * @param int $price
 * @return string
 */
function createPricePlaceholder(int $price): string
{
    $price = ceil($price);
    if ($price > 1000) {
        $price = number_format($price, 0, null, ' ');
    }
    return $price;
}

/**
 * Функция делает приведение типа текщей страницы к типу int и проверяет на целое число.
 * @param string $currentPage
 * @param array $categories
 * @return void
 */

function checkCurrentPage(string $currentPage, array $categories)
{
    $currentPage = (int)$currentPage;
    if ($currentPage <= 0) {
        responseNotfound($categories);
    }
}

/**
 * Функция возвращает разницу во времени в человеческом формате (5 минут назад, час назад и т. д.).
 * @param string $date
 * @param string $currentDate
 * @return string|null
 */
function getPassedTimeBet(string $date, string $currentDate): null|string
{
    $date = date_create($date);
    $currentDate = date_create($currentDate);

    $diff = date_diff($currentDate, $date);

    $days = $diff->days;
    $hours = $diff->h;
    $minutes = $diff->i;

    if ($days === 0 && $hours !== 0) {
        return $hours . ' ' . getNounPluralForm($hours, 'час назад', 'часа назад', 'часов назад');
    }

    if ($hours === 0) {
        return $minutes . ' ' . getNounPluralForm($minutes, 'минуту назад', 'минуты назад', 'минут назад');
    }

    if ($days === 1) {
        return date_format($date, 'Вчера в H:i');
    }

    if ($days > 1) {
        return date_format($date, 'd-m-y в H:i');
    }

    return null;
}

/**
 * Функция скрывает форму ставки на странице лота
 * @param string $endDate
 * @param string $currentDate
 * @param string $currentUserId
 * @param string $lotCreatorId
 * @param string $lastBetUserId
 * @return bool
 */

function hideBetForm(string $endDate, string $currentDate, string $currentUserId, string $lotCreatorId, string $lastBetUserId): bool
{

    $endDate = date_create($endDate);
    $currentDate = date_create($currentDate);

    if ($lotCreatorId === $currentUserId || $endDate <= $currentDate || $lastBetUserId === $currentUserId) {
        return true;
    } else {
        return false;
    }
}
