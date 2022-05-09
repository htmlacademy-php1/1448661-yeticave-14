
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
 `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `title` VARCHAR(255) NOT NULL ,
 `description` TEXT NOT NULL ,
 `image` varchar(50) NOT NULL , `price` INT UNSIGNED NOT NULL ,
 `end_date` DATETIME NOT NULL ,
 `step_bet` INT UNSIGNED NOT NULL ,
 `user_id` INT UNSIGNED NOT NULL ,
 `category_id` INT UNSIGNED NOT NULL ,
 `winner_id` INT UNSIGNED NULL ,
  PRIMARY KEY (`id`), INDEX (`user_id`), INDEX (`category_id`), INDEX (`winner_id`)) ENGINE = InnoDB;


-- --------------------------------------------------------
--
-- Структура таблицы `bets`
--
CREATE TABLE `yeti`.`bets`
 ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
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
 `date_registration` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
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
  ADD CONSTRAINT `lots_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lots_ibfk_3` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
-- --------------------------------------------------------
-- Добавление на таблицу lots полнотекстовый индекс на поля title и description:
--
CREATE FULLTEXT INDEX lotsFullTextSearch ON lots(title, description);

-- --------------------------------------------------------

