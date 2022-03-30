<?php
$user_name = 'Михаил';
$title = 'Главная страница';

require_once('data.php');
require_once('helpers.php');

$content = include_template('main.php', ['lots' => $lots, 'categories'=> $categories] );
$layout_content = include_template('layout.php',['content' => $content, 'user_name' => $user_name, 
'categories'=> $categories, 'title'=>$title]);

print($layout_content);
