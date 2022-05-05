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

    if ($price === '') {
        return 'Заполните поле';
    }

    $price = intval($price);
    if ($price <= 0) {
        return "Не корректно введено значение";
    }


    $currentPrice = $lotData[0]['max_price'] ?? $lotData[0]['price'];
    $minBet = $currentPrice + $lotData[0]['step_bet'];
    if ($price < $minBet) {
        return 'Значение должно быть больше или равно';
    }

    return null;
}
