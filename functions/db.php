<?php

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 * @return mysqli_stmt Подготовленное выражение
 */
function dbGetPrepareStmt(mysqli $link, string $sql, array $data = []): mysqli_stmt
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
            } elseif (is_string($value)) {
                $type = 's';
            } elseif (is_double($value)) {
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
 * @param array $config
 * @return mysqli
 */
function dbConnect(array $config): mysqli
{
    $link = mysqli_connect(
        $config['db']['host'],
        $config['db']['user'],
        $config['db']['password'],
        $config['db']['database']
    );
    if (!$link) {
        print("Error:  нет подключения к базе данных MySQL " . mysqli_connect_error());
        exit();
    }
    mysqli_set_charset($link, "utf8");
    return $link;
}

/**
 * Функция возвращает строку с данными для подключения почты
 * @param $config
 * @return string
 */
function getConnectData($config): string
{
    return "smtp://{$config['dsn'][ 'login']}:{$config['dsn'][ 'password']}{$config['dsn'][ 'serverAddress']}:{$config['dsn'][ 'port']}";
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
        print("Error: Запрос не выполнен" . mysqli_error($link));
        exit();
    }
}

/**
 * Функция возвращает массив с новыми открытыми лотами
 * @param $link mysqli Ресурс соединения
 * @return array Массив с лотами из базы данных
 */
function getOpenLots(mysqli $link): array
{
    $sql = 'SELECT l.id, l.title, l.description, l.price, l.image as url_image, l.end_date, MAX(b.price), c.name FROM lots l
            JOIN categories c ON l.category_id = c.id
            LEFT JOIN bets b ON l.id = b.lot_id
            WHERE l.end_date > NOW() GROUP BY (l.id)  ORDER BY l.date_creation DESC LIMIT 6 OFFSET 0 ';
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Error: Запрос не выполнен" . mysqli_error($link));
        exit();
    }
}

/**
 * Функция возвращает массив с лотами по id
 * @param $link mysqli Ресурс соединения
 * @param int $lotId int ID лота
 * @return array Массив с лотами из базы данных
 */
function getLotById(mysqli $link, int $lotId): array
{
    $sql = "SELECT l.id, l.title, l.description, l.price, l.image as url_image, l.end_date, l.step_bet, l.user_id,MAX(b.price) as max_price, c.name FROM lots l JOIN categories c ON l.category_id = c.id
            LEFT JOIN bets b ON l.id = b.lot_id
            WHERE l.id =  {$lotId} ;";

    $result = mysqli_query($link, $sql);
    return mysqli_fetch_array($result, MYSQLI_ASSOC);
}

/**
 * Функция добавляет новый лот в бд.
 * @param mysqli $link Ресурс соединения
 * @param array $lotData Данные получены из формы от пользователя
 * @param string $userId id пользователя, который создает лот
 * @return bool Возвращает результат записи из бд
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
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        return true;
    } else {
        print("Error: Запрос не выполнен" . mysqli_error($link));
        exit();
    }
}

/**
 * Функция возвращает массив с id категориями.
 * @param array $arrayCategories массив с категориями и БД
 * @return  array Возвращает массив с id категориями
 */
function getCategoriesIds(array $arrayCategories): array
{
    $categoryIds = [];
    foreach ($arrayCategories as $val) {
        $categoryIds[] = $val['id'];
    }
    return $categoryIds;
}


/**
 * Функция записывает в БД нового пользователя
 * @param mysqli $link Ресурс соединения
 * @param array $newAccount Данные пользователя полученные из формы регистрации
 * @return bool
 */
function addUser(mysqli $link, array $newAccount): bool
{
    $newAccount['password'] = password_hash($newAccount['password'], PASSWORD_DEFAULT);

    $sql = 'INSERT INTO `users`(`email`, `password`, `name`, `contacts`)
 VALUES(?,?,?,?)';
    $stmt = dbGetPrepareStmt($link, $sql, $newAccount);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        return true;
    } else {
        print("Error: Запрос не выполнен" . mysqli_error($link));
        exit();
    }
}

/**
 * Функция получает пользователя из БД по email
 * @param mysqli $link Ресурс соединения
 * @param string $email email пользователя
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
 * @param mysqli $link Ресурс соединения
 * @param string $search Поисковый запрос
 * @param int $currentPage Текущая страница для пагинации
 * @param int $paginationLimit Ограничение по количеству элементов для запроса к БД
 * @return array возвращает массив с получены результатом
 */
function getLotBySearch(mysqli $link, string $search, int $currentPage, int $paginationLimit): array
{
    $offset = $paginationLimit * ($currentPage - 1);
    $sql = "SELECT l.id, l.title, l.description, l.price, l.image as url_image, l.end_date, c.name
            FROM lots l
            JOIN categories c ON l.category_id = c.id
            WHERE  MATCH(l.title, l.description) AGAINST(? IN BOOLEAN MODE) ORDER BY l.date_creation DESC LIMIT $paginationLimit OFFSET $offset ";

    $stmt = dbGetPrepareStmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция возвращает количество элементов из поискового запроса к БД
 * @param mysqli $link Ресурс соединения
 * @param string $search Поисковый запрос
 * @return int
 */
function countLotsFromSearch(mysqli $link, string $search): int
{
    $sql = "SELECT l.id, l.title, l.description, l.price, l.image as url_image, l.end_date, c.name
            FROM lots l
            JOIN categories c ON l.category_id = c.id
            WHERE  MATCH(l.title, l.description) AGAINST(? IN BOOLEAN MODE)  ORDER BY l.date_creation DESC ";

    $stmt = dbGetPrepareStmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return count(mysqli_fetch_all($result, MYSQLI_ASSOC));
}

/**
 * Функция добавляет ставку в бд
 * @param mysqli $link Ресурс соединения
 * @param array $formOfBets Данные полученные из формы ставок
 * @param string $userId id текщего пользователя
 * @param string $lotId id лота по которыму делается ставка
 * @return bool
 */
function addBet(mysqli $link, array $formOfBets, string $userId, string $lotId): bool
{
    $formOfBets['user_id'] = $userId;
    $formOfBets['lot_id'] = $lotId;

    $sql = "INSERT INTO `bets` (`price`, `user_id`, `lot_id`) value (?, ?, ?)";

    $stmt = dbGetPrepareStmt($link, $sql, $formOfBets);
    return mysqli_stmt_execute($stmt);
}

/**
 * Функция получает ставки лота по id
 * @param mysqli $link Ресурс соединения
 * @param string $lotId
 * @return array
 */

function getLotBets(mysqli $link, string $lotId): array
{
    $sql = "SELECT b.id, b.price, b.user_id, b.lot_id, b.date_creation , users.name  FROM bets b
            JOIN users on b.user_id = users.id
            WHERE b.lot_id = {$lotId} ORDER BY b.date_creation DESC";
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Error: Запрос не выполнен" . mysqli_error($link));
        exit();
    }
}

/**
 * Функция получает ставки пользователя
 * @param mysqli $link Ресурс соединения
 * @param int $userId id текущего пользователя
 * @return array массив с данными, ставки пользователя
 */
function getUserBets(mysqli $link, int $userId): array
{
    $sql = "SELECT bets.date_creation, bets.price, contacts, users.id as user_id, lots.title, lots.image, winner_id, lots.id, lots.end_date, lots.user_id as lot_creator,  categories.name AS cat_name
FROM bets
JOIN lots ON bets.lot_id = lots.id
JOIN categories ON lots.category_id = categories.id
JOIN users ON bets.user_id = users.id
WHERE bets.user_id = {$userId}  ORDER BY bets.date_creation DESC";
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Error: Запрос не выполнен" . mysqli_error($link));
        exit();
    }
}

/**
 * Функция получает
 * @param mysqli $link Ресурс соединения
 * @param int $categoryId
 * @param int $currentPage
 * @param int $paginationLimit
 * @return array
 */
function getLotByCategory(mysqli $link, int $categoryId, int $currentPage, int $paginationLimit): array
{
    $offset = $paginationLimit * ($currentPage - 1);
    $sql = "SELECT lots.id, title, image, lots.price, lots.end_date, winner_id, lots.category_id, categories.name FROM lots
    JOIN categories  ON lots.category_id = categories.id WHERE lots.category_id = {$categoryId} AND lots.end_date > NOW() GROUP BY (lots.id) ORDER BY lots.date_creation DESC LIMIT {$paginationLimit} OFFSET {$offset}";
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Error: Запрос не выполнен" . mysqli_error($link));
        exit();
    }
}

/**
 * Функция получает количество лотов по категории
 * @param mysqli $link Ресурс соединения
 * @param int $categoryId
 * @return int
 */
function getQuantitiesLotsByCategory(mysqli $link, int $categoryId): int
{
    $sql = "SELECT lots.id, title, image, price, lots.end_date, winner_id, categories.name
            FROM lots
            JOIN categories  ON lots.category_id = categories.id
            WHERE lots.category_id = {$categoryId} AND lots.end_date > NOW() ORDER BY lots.date_creation DESC  ";
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_num_rows($result);
    } else {
        print("Error: Запрос не выполнен" . mysqli_error($link));
        exit();
    }
}

/**
 * Функция возвращает список лотов без победителей, у которых дата истечения меньше или равна текущей дате.
 * @param mysqli $link Ресурс соединения
 * @return array
 */
function getLotsWithoutWinner(mysqli $link): array
{
    $sql = "SELECT id as lot_id, title as lot_title, winner_id FROM lots
            WHERE winner_id IS NULL AND end_date <= CURRENT_DATE()";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        print("Error: Запрос не выполнен" . mysqli_error($link));
        exit();
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция возвращает последнюю ставку лота
 * @param mysqli $link Ресурс соединения
 * @param int $lotId
 * @return array|bool|null
 */
function getLastBetLot(mysqli $link, int $lotId): array|bool|null
{
    $sql = "SELECT users.id as user_id, users.name as user_name, users.email,
       bets.price as max_price, bets.lot_id as lot_id, lots.title
            FROM bets
            JOIN lots ON bets.lot_id = lots.id
            JOIN users ON bets.user_id = users.id WHERE bets.lot_id = {$lotId} ORDER BY bets.price DESC LIMIT 1";
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        print("Error: Запрос не выполнен" . mysqli_error($link));
        print $sql;
        exit();
    }
}

/**
 * Функция записывает победителя в лот
 * @param mysqli $link Ресурс соединения
 * @param int $userId
 * @param int $lotId
 * @return mysqli_result|bool
 */
function writeWinnerToLot(mysqli $link, int $userId, int $lotId): mysqli_result|bool
{
    $sql = "UPDATE lots SET winner_id = {$userId} WHERE id ={$lotId}";
    return mysqli_query($link, $sql);
}

/**
 * Функция получает контакты создателя лота
 * @param mysqli $link Ресурс соединения
 * @param int $lotId
 * @return array|null
 */
function getLotCreatorContacts(mysqli $link, int $lotId): array|null
{
    $sql = "SELECT users.contacts FROM lots
JOIN users ON lots.user_id = users.id WHERE lots.winner_id IS NOT NULL AND lots.id = {$lotId}";
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        print("Error: Запрос не выполнен" . mysqli_error($link));
        exit();
    }
}
