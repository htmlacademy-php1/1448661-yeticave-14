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


$winners = getWinners($link);

$message = new Email();
$message->subject("Ваша ставка победила");
$message->from("m.samorodov@internet.ru");


foreach ($winners as $winner) {
    $message->to($winner['email']);
    $msgContent = includeTemplate('email.php', ['winner' => $winner]);
    $message->html($msgContent);

    $mailer = new Mailer($transport);
    $result = $mailer->send($message);

}











