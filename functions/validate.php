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

/**
 * @param mysqli $link
 * @param string $email
 * @param array $newAccount
 * @return string|void
 */
function validateEmail(mysqli $link, string $email, array $newAccount)
{

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT `email` FROM `users` WHERE email= '" . $newAccount['email'] . "'";
        $result = mysqli_query($link, $sql);
        if ($result) {
            if (!empty(mysqli_fetch_all($result, MYSQLI_ASSOC))) {
                return 'email используется другим пользователем';
            }

        } else {
            print("Error: Запрос не выполнен" . mysqli_connect_error());
            exit();
        }
    } else {
        return 'Некорректно введен e-mail';
    }

}

/**
 * Функция валидирует форму регистрации
 * @param mysqli $link
 * @param array $newAccount
 * @return array
 */
function validateFormRegistration(mysqli $link, array $newAccount): array
{
    $requiredFields = ['email', 'password', 'name', 'contacts'];

    $errors = [];
    foreach ($newAccount as $key => $value) {

        if (in_array($key, $requiredFields) and empty($value)) {
            $errors[$key] = "Поле надо заполнить";
        } elseif ($key === 'email') {
            $errors['email'] = validateEmail($link, $value, $newAccount);
        }

    }
    return $errors;
}


function validateLoginForm(mysqli $link, array $formLogin)
{
    $errors = [];
    $requiredFields = ['email', 'password'];

    foreach ($formLogin as $key => $value) {
        if (in_array($key, $requiredFields) and empty($value)) {
            $errors[$key] = "Поле надо заполнить";
        } else {
            if (filter_var($formLogin['email'], FILTER_VALIDATE_EMAIL)) {
                $sql = "SELECT `id`, `name`, `password` FROM `users` WHERE email= '" . $formLogin['email'] . "'";
                $result = mysqli_query($link, $sql);
                $ArrayFromDB = mysqli_fetch_all($result, MYSQLI_ASSOC);
                if (empty($ArrayFromDB)) {
                    $errors['email'] = 'Пользователь с указанным email не существует';
                } else {
                    if (!password_verify($formLogin['password'], $ArrayFromDB[0]['password'])) {
                        $errors['password'] = 'Введен неверный пароль';
                    } else {
                        $_SESSION['id'] = $ArrayFromDB[0]['id'];
                        $_SESSION['name'] = $ArrayFromDB[0]['name'];
                    }
                }

            } else {
                $errors['email'] = 'Некорректно введен e-mail';
            }
        }
    }

    return $errors;
}
