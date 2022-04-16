
--
-- SQL-код для создания новой базы данных.
--
CREATE DATABASE yeti
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Структура таблицы `categories`
--
  CREATE TABLE `yeti`.
  `categories`
  ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `character_code` CHAR(255) NOT NULL ,
   PRIMARY KEY (`id`),
   UNIQUE (`character_code`)) ENGINE = InnoDB;


-- --------------------------------------------------------
--
-- Структура таблицы `lots`
--
CREATE TABLE `yeti`. `lots`
 ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `date_creation` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `title` VARCHAR(255) NOT NULL ,
 `description` TEXT NOT NULL ,
 `image` varchar(50) NOT NULL , `price` INT UNSIGNED NOT NULL ,
 `end_date` DATETIME NOT NULL ,
 `step_bet` INT UNSIGNED NOT NULL ,
 `author_id` INT UNSIGNED NOT NULL ,
 `category_id` INT UNSIGNED NOT NULL ,
 `winner_id` INT UNSIGNED NULL ,
  PRIMARY KEY (`id`), INDEX (`author_id`), INDEX (`category_id`), INDEX (`winner_id`)) ENGINE = InnoDB;


-- --------------------------------------------------------
--
-- Структура таблицы `bets`
--
CREATE TABLE `yeti`.`bets`
 ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `date_creation` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `price` INT UNSIGNED NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `lot_id` INT UNSIGNED NOT NULL ,
   PRIMARY KEY (`id`), INDEX (`user_id`),
   INDEX (`lot_id`)) ENGINE = InnoDB;

-- --------------------------------------------------------
--
-- Структура таблицы `users`
--
CREATE TABLE `yeti`. `users`
 ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `date_registration` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `email` VARCHAR(128) NOT NULL ,
 `name` VARCHAR(50) NOT NULL ,
 `password` CHAR(64) NOT NULL ,
 `contacts` VARCHAR(255) NOT NULL ,
 PRIMARY KEY (`id`), UNIQUE (`email`)) ENGINE = InnoDB;

-- --------------------------------------------------------
ALTER TABLE `bets`
  ADD CONSTRAINT `bets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `bets_ibfk_2` FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `lots`
--
ALTER TABLE `lots`
  ADD CONSTRAINT `lots_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lots_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lots_ibfk_3` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
-- --------------------------------------------------------
--

-- ЭКСПОРТ БАЗЫ ДАННЫХ
--
-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 31 2022 г., 10:36
-- Версия сервера: 8.0.24
-- Версия PHP: 8.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yeti`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bets`
--

CREATE TABLE `bets` (
  `id` int UNSIGNED NOT NULL,
  `date_creation` datetime NOT NULL,
  `price` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `lot_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `character_code` char(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `lots`
--

CREATE TABLE `lots` (
  `id` int UNSIGNED NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int UNSIGNED NOT NULL,
  `end_date` DATETIME NOT NULL,
  `step_bet` int UNSIGNED NOT NULL,
  `author_id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `winner_id` int UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `date_registration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contacts` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bets`
--
ALTER TABLE `bets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lot_id` (`lot_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `character_code` (`character_code`);

--
-- Индексы таблицы `lots`
--
ALTER TABLE `lots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `winner_id` (`winner_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bets`
--
ALTER TABLE `bets`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lots`
--
ALTER TABLE `lots`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `bets`
--
ALTER TABLE `bets`
  ADD CONSTRAINT `bets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `bets_ibfk_2` FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `lots`
--
ALTER TABLE `lots`
  ADD CONSTRAINT `lots_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lots_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lots_ibfk_3` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
