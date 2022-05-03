<?php
/**
 * @var array $_SESSION
 * @var mysqli $link
 * @var $config
 */

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once __DIR__ . '/bootstrap.php';
$dsn = getConnectData($config);
$transport = Transport::fromDsn($dsn);




$errorsWinnerScript = [];
$userName = checkSessionsName($_SESSION);

$lotList = getLotWithoutWinner($link);

foreach ($lotList as $lot) {
    $lastBetsLots = getLastBetLot($link, $lot['lot_id']);

}
if (!empty($lastBetsLots)){
    $result = writeWinnerToLot($link,$lastBetsLots['user_id'], $lastBetsLots['lot_id']);



}


// Формирование сообщения
/*$message = new Email();
$message->to("m.samorodov@internet.ru");
$message->from("m.samorodov@internet.ru");
$message->subject("тест");
$message->text("Тест");

// Отправка сообщения
$mailer = new Mailer($transport);
$mailer->send($message);*/


