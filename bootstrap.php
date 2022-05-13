<?php

session_start();
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/functions/db.php";
require_once __DIR__ . "/functions/template.php";
require_once __DIR__ . "/functions/validate.php";
require_once __DIR__ . "/functions/user.php";
require_once __DIR__ . "/functions/response.php";
require_once __DIR__ . "/functions/validateAddLotForm.php";
require_once __DIR__ . "/functions/validateLoginForm.php";
require_once __DIR__ . "/functions/validateSignUpForm.php";
require_once __DIR__ . "/functions/validateBetsForm.php";
require_once __DIR__ . "/functions/pagination.php";

if (!file_exists(__DIR__ . '/config/config.php')) {
    exit('Создайте файл config/config.php на основе файла config/config.sample.php и сконфигурируйте его');
}
$config = require_once __DIR__ . '/config/config.php';
$link = dbConnect($config);
