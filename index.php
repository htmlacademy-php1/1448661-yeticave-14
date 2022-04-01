<?php
$userName = 'Михаил';
$title = 'Главная страница';

require_once __DIR__ .'/data.php';
require_once __DIR__ .'/bootstrap.php';

$content = includeTemplate('main.php', ['lots' => $lots, 'categories'=> $categories] );
$layoutContent = includeTemplate('layout.php',['content' => $content, 'userName' => $userName,
'categories'=> $categories, 'title'=>$title]);

print($layoutContent);
