-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 16 2024 г., 17:53
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ProDog`
--

-- --------------------------------------------------------

--
-- Структура таблицы `breed`
--

CREATE TABLE `breed` (
  `id_breed` int NOT NULL,
  `name_breed` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `breed`
--

INSERT INTO `breed` (`id_breed`, `name_breed`) VALUES
(1, 'Корги');

-- --------------------------------------------------------

--
-- Структура таблицы `color`
--

CREATE TABLE `color` (
  `id_color` int NOT NULL,
  `name_color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `color`
--

INSERT INTO `color` (`id_color`, `name_color`) VALUES
(1, 'бурый'),
(2, 'серый'),
(3, 'белый'),
(4, 'белый'),
(6, 'Белый с серо-коричневым носом и хвостом');

-- --------------------------------------------------------

--
-- Структура таблицы `owner`
--

CREATE TABLE `owner` (
  `id_owner` int NOT NULL,
  `fio` varchar(150) NOT NULL,
  `phone` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `owner`
--

INSERT INTO `owner` (`id_owner`, `fio`, `phone`) VALUES
(1, 'fio', '111'),
(2, 'f', '1'),
(3, 'fio', '889922');

-- --------------------------------------------------------

--
-- Структура таблицы `pet`
--

CREATE TABLE `pet` (
  `id_pet` int NOT NULL,
  `id_owner` int NOT NULL,
  `id_color` int NOT NULL,
  `id_breed` int NOT NULL,
  `name_pet` varchar(50) NOT NULL,
  `year_birth` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `pet`
--

INSERT INTO `pet` (`id_pet`, `id_owner`, `id_color`, `id_breed`, `name_pet`, `year_birth`) VALUES
(3, 1, 1, 1, 'гавыч', '2021-09-27');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `login` varchar(5) NOT NULL,
  `password` varchar(4) NOT NULL,
  `admin` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id_user`, `login`, `password`, `admin`) VALUES
(1, 'admin', 'demo', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `breed`
--
ALTER TABLE `breed`
  ADD PRIMARY KEY (`id_breed`);

--
-- Индексы таблицы `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id_color`);

--
-- Индексы таблицы `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id_owner`);

--
-- Индексы таблицы `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`id_pet`),
  ADD KEY `id_owner` (`id_owner`,`id_color`,`id_breed`),
  ADD KEY `id_breed` (`id_breed`),
  ADD KEY `id_color` (`id_color`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `breed`
--
ALTER TABLE `breed`
  MODIFY `id_breed` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `color`
--
ALTER TABLE `color`
  MODIFY `id_color` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `owner`
--
ALTER TABLE `owner`
  MODIFY `id_owner` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `pet`
--
ALTER TABLE `pet`
  MODIFY `id_pet` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `pet_ibfk_1` FOREIGN KEY (`id_breed`) REFERENCES `breed` (`id_breed`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pet_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `color` (`id_color`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pet_ibfk_3` FOREIGN KEY (`id_owner`) REFERENCES `owner` (`id_owner`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
