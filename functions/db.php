<?php
/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
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
 * @param $link mysqli Ресурс соединения
 * @return array Массив с лотами из базы данных
 */
function getOpenLots(mysqli $link): array
{

    $sql = 'SELECT l.id, l.title, l.description, l.price, l.image as url_image, l.end_date, MAX(b.price), c.name FROM lots l
   JOIN categories c ON l.category_id = c.id
   LEFT JOIN bets b ON l.id = b.lot_id WHERE l.end_date > NOW() GROUP BY (l.id)  ORDER BY l.date_creation DESC LIMIT 6 OFFSET 0 ';
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
 * @param string $lotId int ID лота
 * @return array Массив с лотами из базы данных
 */
function getLogById(mysqli $link, string $lotId): array
{
    $lotId = intval($lotId);
    $sql = "SELECT l.id, l.title, l.description, l.price, l.image as url_image, l.end_date, MAX(b.price) as max_price,
       MIN(b.price) as min_price, c.name FROM lots l
   JOIN categories c ON l.category_id = c.id
   LEFT JOIN bets b ON l.id = b.lot_id
    WHERE l.id =  {$lotId} ;";

    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Функция добавляет новый лот в бд.
 * @param mysqli $link
 * @param array $lotData
 * @param $userId
 * @return bool возвращает результат записи из бд
 */

function addLot(mysqli $link, array $lotData, string $userId): bool
{
    $lotData['end_date'] = date("Y-m-d H:i:s", strtotime($lotData['end_date']));
    $lotData['image'] = uploadFile($_FILES);
    $lotData['user_id'] = $userId;
    $sql = 'INSERT INTO `lots`( `title`, `category_id`, `description`, `price`, `step_bet`, `end_date`, `image`, `user_id`)
             VALUES
             (?, ?, ?, ?, ?, ?, ?, ?)';

    $stmt = dbGetPrepareStmt($link, $sql, $lotData);
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
function addUser(mysqli $link, array $newAccount): bool
{

    $newAccount['password'] = password_hash($newAccount['password'], PASSWORD_DEFAULT);

    $sql = 'INSERT INTO `users`(`email`, `password`, `name`, `contacts`)
 VALUES(?,?,?,?)';
    $stmt = dbGetPrepareStmt($link, $sql, $newAccount);
    return mysqli_stmt_execute($stmt);
}

/**
 * Функция получает пользователя из БД по email
 * @param mysqli $link
 * @param string $email
 * @return mixed|null
 */
function getUserByEmail(mysqli $link, string $email): array|null
{

    $sql = "SELECT `id`, `name`, `password`, `email` FROM `users` WHERE email= ?";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $arrayFromDB = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $arrayFromDB[0] ?? null;
}

/**
 * Функция делает полнотекстовый поиск по полям title, description с ограничением по количеству элементов
 * @param mysqli $link
 * @param string $search
 * @param $lotsLimit
 * @param $offset
 * @return array возвращает массив с полученны результатом
 */
function getLotBySearch (mysqli $link, string $search, $lotsLimit, $offset ): array
{
    $sql = "SELECT l.id, l.title, l.description, l.price, l.image as url_image, l.end_date, c.name
            FROM lots l
            JOIN categories c ON l.category_id = c.id
            WHERE  MATCH(l.title, l.description) AGAINST(? IN BOOLEAN MODE) ORDER BY l.date_creation DESC LIMIT $lotsLimit OFFSET $offset ";

        $stmt = dbGetPrepareStmt($link, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);

}

/**
 * Функция возвращает количество элементов из поискового запроса к БД
 * @param mysqli $link
 * @param string $search
 * @return int
 */
function countLotsFromSearch (mysqli $link,  string $search): int
{
    $sql = "SELECT l.id, l.title, l.description, l.price, l.image as url_image, l.end_date, c.name
            FROM lots l
            JOIN categories c ON l.category_id = c.id
            WHERE  MATCH(l.title, l.description) AGAINST(? IN BOOLEAN MODE) ORDER BY l.date_creation DESC";

    $stmt = dbGetPrepareStmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return  count(mysqli_fetch_all($result, MYSQLI_ASSOC));
}


