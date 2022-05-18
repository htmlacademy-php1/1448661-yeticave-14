<?php

/**
 * Функция валидирует форму ставки и возвращает массив с ошибками
 * @param array $formOfBets
 * @param array $lotData
 * @return array
 */
function validateBetsForm(array $formOfBets, array $lotData): array
{
    $errors = [
        'price' => validateBet($formOfBets['price'], $lotData),

    ];
    return array_filter($errors);
}

/**
 * Валидация формы ставок
 * @param $price
 * @param array $lotData
 * @return string|null
 */

function validateBet($price, array $lotData): string|null
{
    if ($price > 15000000) {
        return "Значение должно быть меньше 15 000 000";
    }

    if ($price === '') {
        return 'Заполните поле';
    }

    $price = intval($price);
    if ($price <= 0) {
        return "Не корректно введено значение";
    }


    $currentPrice = $lotData['max_price'] ?? $lotData['price'];
    $minBet = $currentPrice + $lotData['step_bet'];
    if ($price < $minBet) {
        return 'Значение должно быть больше или равно';
    }

    return null;
}
