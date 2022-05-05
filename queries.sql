
-- Список категорий;
INSERT INTO `categories` (`name`, `character_code`)
  VALUES
  ('Доски и лыжи', 'boards'),
  ('Крепления', 'attachment'),
  ('Ботинки', 'boots'),
  ('Одежда', 'clothing'),
  ('Инструменты', 'tools'),
  ('Разное','other');

-- Список пользователей;
INSERT INTO `users` (`email`, `name` ,`password` , `contacts`)
 VALUES
 ('sam@sam.ru', 'Михаил', 'Mikhail123', '+64 9 111 111'),
 ('arckh@arckh.ru', 'Радик', 'Arckh123', '+ 64 9 123 4567'),
 ('albina@123.com', 'Альбина', 'Aly123', '+ 64 9 456 7890');

-- Список объявлений;
INSERT INTO `lots` (`title`,`description`,`image`, `price`, `end_date`,`step_bet`, `author_id` ,`category_id`,`winner_id`)
 VALUES
 ('2014 Rossignol District Snowboard', 'Практичная доска средней жесткости', 'img/lot-1.jpg', 10999, '2022-03-30',
   1000, '1', '1', '3'),
  ('DC Ply Mens 2016/2017 Snowboard', 'Современная «all mountain» доска', 'img/lot-2.jpg', 15999, '2022-04-01',
  1000, '2', '1', '1'),
  ('Крепления Union Contact Pro 2015 года размер L/XL', 'Специальный хайбэк и стрэпы с безукоризненно сильной фиксацией.', 'img/lot-3.jpg', 8000, '2022-04-01',
  100, '2', '2', '3'),
  ('Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки удобные новые', 'img/lot-4.jpg', 10999, '2022-04-01',
  100, '1', '3', '3'),
  ('Куртка для сноуборда DC Mutiny Charocal', 'Куртка для сноуборда до -10', 'img/lot-5.jpg', 7500, '2022-04-01',
  100, '1', '4', '3'),
  ('Маска Oakley Canopy', 'Покрытие линзы, устойчивое к запотеванию F3 Anti-fog', 'img/lot-6.jpg', 5400, '2022-04-01',
  100, '1', '6', '3');

-- Список ставок
INSERT INTO `bets` (`price`, `user_id`, `lot_id`)
 VALUES
(11999,'2', '1'), (12999,'2', '3'),
(8100,'2', '1'), (8200,'2', '3');

 -- Запросы
-- получить все категории;
   SELECT * FROM `categories`;

-- получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;
   SELECT l.id, l.title, l.price, l.image as url_image, l.end_date, MAX(b.price), c.name FROM lots l
   JOIN categories c ON l.category_id = c.id
   LEFT JOIN bets b ON l.id = b.lot_id WHERE l.end_date > NOW() GROUP BY (l.id) ORDER BY l.date_creation DESC;

-- показать лот по его ID. Получите также название категории, к которой принадлежит лот;
   SELECT l.id, title, name FROM lots l LEFT JOIN categories c ON  l.category_id = c.id WHERE l.id = 6;

-- обновить название лота по его идентификатору;
   UPDATE `lots` SET `title` = 'Маска Oakley Canopy Super Sun' WHERE id = 6;

-- получить список ставок для лота по его идентификатору с сортировкой по дате.
   SELECT * FROM `bets` WHERE lot_id = 3 ORDER BY date_creation;

-- Добаввление на таблицу lots полнотекстовый индекс на поля title и description:
   CREATE FULLTEXT INDEX lotsFullTextSearch ON lots(title, description);
