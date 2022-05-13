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


$lots = getLotsWithoutWinner($link);

$lastBetsLots = [];
foreach ($lots as $lot) {
    $lastBetsLots[] = getLastBetLot($link, $lot['lot_id']);
}
$lastBetsLots = array_filter($lastBetsLots);


foreach ($lastBetsLots as $lastBetLot) {
    writeWinnerToLot($link, $lastBetLot['user_id'], $lastBetLot['lot_id']);
}

$winners = $lastBetsLots;

$mailer = new Mailer($transport);
$message = new Email();
$message->subject("Ваша ставка победила");
$message->from("m.samorodov@internet.ru");


foreach ($winners as $winner) {
    $message->to($winner['email']);
    $msgContent = includeTemplate('email.php', ['winner' => $winner]);
    $message->html($msgContent);
    try {
        $mailer->send($message);
    } catch (Exception $e) {
        echo $e->getMessage();
    } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
        echo $e->getMessage();
    }
}
