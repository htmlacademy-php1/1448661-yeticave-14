<?php
$user_name = 'Михаил';
$title = 'Главная страница';

require_once __DIR__ .'/data.php';
require_once __DIR__ .'/bootstrap.php';

$content = include_template('main.php', ['lots' => $lots, 'categories'=> $categories] );
$layout_content = include_template('layout.php',['content' => $content, 'user_name' => $user_name, 
'categories'=> $categories, 'title'=>$title]);

print($layout_content);
