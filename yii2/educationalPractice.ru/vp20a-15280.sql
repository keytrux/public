-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 12 2023 г., 15:04
-- Версия сервера: 10.8.4-MariaDB
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `vp20a-15280`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1111, 'Дороги'),
(2222, 'Детские площадки'),
(2223, 'ЖКХ');

-- --------------------------------------------------------

--
-- Структура таблицы `problem`
--

CREATE TABLE `problem` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `description` text CHARACTER SET utf8mb4 NOT NULL,
  `time_create` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_user` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `status` set('Новая','Решена','Отклонена') CHARACTER SET utf8mb4 NOT NULL DEFAULT 'Новая',
  `photo_before` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `photo_after` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `problem`
--

INSERT INTO `problem` (`id`, `name`, `description`, `time_create`, `id_user`, `id_category`, `status`, `photo_before`, `photo_after`, `reason`) VALUES
(1, 'Ямы на дорогах', 'На улице Маяковского ямы на дорогах', '2023-11-02 20:06:44', 1, 1111, 'Решена', 'yambef.jpg', 'yamaft.jpg', ''),
(2, 'Сломанные весы', 'На детской площадке по адресу Ленина 63 сломаны весы', '2023-11-01 20:06:44', 3, 2222, 'Решена', 'plbef.jpg', 'plaft.jpg', ''),
(5, 'Отопление', 'Так и не дали отопление', '2023-11-12 10:52:15', 1, 2223, 'Решена', 'a1a9cc0bdd72c191fdd921fded6b5e05.jpg', 'd3bfab0dd57539cd80abb006a6f8c6f8.jpg', 'Ещё слишком рано');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fio` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_german2_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `admin` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `fio`, `login`, `email`, `password`, `admin`) VALUES
(1, 'Носова Виктория Владиславовна', 'nvv', 'vika20072003@mail.ru', '1234', 0),
(3, 'Зоя Аркадиевна Михайлова', 'zam', 'Z@mail.ru', '1234', 0),
(4, 'Администратор', 'admin', 'admin@mail.ru', 'admin', 1),
(45, 'Александр Дмитриевич Мысливченко', 'adm', 'a@mail.ru', '1234', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `problem`
--
ALTER TABLE `problem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_category` (`id_category`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2224;

--
-- AUTO_INCREMENT для таблицы `problem`
--
ALTER TABLE `problem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `problem`
--
ALTER TABLE `problem`
  ADD CONSTRAINT `problem_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `problem_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
