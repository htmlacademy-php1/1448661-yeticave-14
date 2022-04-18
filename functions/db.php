<?php
$config = require_once __DIR__ . '/../config/config.php';
$link = dbConnect($config);

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 * @return mysqli_stmt Подготовленное выражение
 */
function dbGetPrepareStmt(mysqli $link, string $sql, array $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;

            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Функция для подключения к БД
 * @return mysqli|void
 */
function dbConnect($config)
{
    $link = mysqli_connect($config['db']['host'], $config['db']['user'],
        $config['db']['password'], $config['db']['database']);
    if (!$link) {
        print("Error:  нет подключения к базе данных MySQL " . mysqli_connect_error());
        exit();
    }
    mysqli_set_charset($link, "utf8");
    return $link;
}

/**
 * Функция возвращает массив с категориями
 *
 * @param $link mysqli Ресурс соединения
 * @return array Массив с категориями из базы данныз
 */
function getCategories(mysqli $link): array
{

    $sql = 'SELECT `id`, `name`, `character_code`  FROM `categories`';
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);

    } else {
        print("Error: Запрос не выполнен" . mysqli_connect_error());
        exit();

    }

}

/**
 * Функция возвращает массив с новыми открытими лотами
 *
 * @param $link mysqli Ресурс соединения
 * @return array Массив с лотами из базы данных
 */
function getOpenLots(mysqli $link): array
{

    $sql = 'SELECT l.id, l.title, description, l.price, l.image as url_image, l.end_date, MAX(b.price), c.name FROM lots l
   JOIN categories c ON l.category_id = c.id
   LEFT JOIN bets b ON l.id = b.lot_id WHERE l.end_date > NOW() GROUP BY (l.id)  ORDER BY l.date_creation DESC LIMIT 6';
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);

    } else {
        print("Error: Запрос не выполнен" . mysqli_connect_error());
        exit();
    }

}

/**
 * Функция возвращает массив с лотами по id
 * @param $link mysqli Ресурс соединения
 * @param $lotId int ID лота
 * @return array Массив с лотами из базы данных
 */
function getLogById(mysqli $link, int $lotId): array
{
    $lotId = intval($lotId);
    $sql = "SELECT l.id, l.title, description, l.price, l.image as url_image, l.end_date, MAX(b.price) as max_price,
       MIN(b.price) as min_price, c.name FROM lots l
   JOIN categories c ON l.category_id = c.id
   LEFT JOIN bets b ON l.id = b.lot_id
    WHERE l.id = " . $lotId . ";
    ";
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);

    } else {
        print("Error: Запрос не выполнен" . mysqli_connect_error());
        exit();
    }
}

/**
 * Функция добавляет новый лот в бд и делает переадресацию на страницу с новым лотом.
 * @param mysqli $link
 * @param array $lot
 * @return bool возвращает результат записив бд
 */
function addLot(mysqli $link, array $lot,)
{
    $lot['end_date'] = date("Y-m-d H:i:s", strtotime($lot['end_date']));

    $sql = 'INSERT INTO `lots`( `title`, `category_id`, `description`, `price`, `step_bet`, `end_date`, `image`,  `author_id`)
             VALUES
             (?, ?, ?, ?, ?, ?, ?, 1)';

    $stmt = dbGetPrepareStmt($link, $sql, $lot);
    return mysqli_stmt_execute($stmt);
}

/**
 * Функция возвращает массив с id категориями.
 * @return  array Возвращает массив с id категориями
 */
function getCategoriesId(array $arrayCategories): array
{
    $categoryId = [];
    foreach ($arrayCategories as $val) {
        $categoryId[] = $val['id'];
    }
    return $categoryId;
}


/**
 * Функция записывает в БД нового пользователя
 * @param mysqli $link
 * @param array $newAccount
 * @return bool
 */
function addUser (mysqli $link, array $newAccount): bool
{
    if (isset($newAccount['password'])){
        $newAccount['password'] = password_hash($newAccount['password'], PASSWORD_DEFAULT);
    }

    $sql = 'INSERT INTO `users`(`email`, `password`, `name`, `contacts`)
 VALUES(?,?,?,?)';
    $stmt = dbGetPrepareStmt($link, $sql, $newAccount);
    return mysqli_stmt_execute($stmt);
}
