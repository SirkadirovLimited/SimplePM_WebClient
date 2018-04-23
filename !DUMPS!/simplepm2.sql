-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Апр 23 2018 г., 11:23
-- Версия сервера: 5.7.21-log
-- Версия PHP: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `simplepm2`
--
CREATE DATABASE IF NOT EXISTS `simplepm2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `simplepm2`;

DELIMITER $$
--
-- Функции
--
DROP FUNCTION IF EXISTS `RatingBase`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `RatingBase` (`urId` BIGINT UNSIGNED) RETURNS FLOAT UNSIGNED READS SQL DATA
    SQL SECURITY INVOKER
begin

DECLARE sumVal BIGINT DEFAULT 0;
DECLARE rProblemsCount BIGINT DEFAULT 0;

SELECT
	SUM(`b`)
INTO
	`sumVal`
FROM
	`spm_submissions`
WHERE
	`status` = 'ready'
AND
	`testType` = 'release'
AND
    (
        `userId` = urId 
    AND
        `b` >= 0
    AND
        `classworkId` = 0
    AND
        `olympId` = 0
    )
ORDER BY
	`b` DESC
LIMIT
	30
;

SELECT
COUNT(`submissionId`)
INTO
`rProblemsCount`
FROM
`spm_submissions`
WHERE
	`status` = 'ready'
AND
	`testType` = 'release'
AND
	(
        `userId` = urId
    AND
        `b` >= 0
    AND
        `classworkId` = 0
    AND
        `olympId` = 0
    )
ORDER BY
	`b` DESC
LIMIT
	30
;

RETURN (sumVal / rProblemsCount);

end$$

DROP FUNCTION IF EXISTS `RatingCount`$$
CREATE DEFINER=`*`@`localhost` FUNCTION `RatingCount` (`uId` BIGINT UNSIGNED) RETURNS BIGINT(20) READS SQL DATA
    SQL SECURITY INVOKER
begin

DECLARE sumVal BIGINT DEFAULT 0;

SELECT
	SUM(`b`)
INTO
	sumVal
FROM
	`spm_submissions`
WHERE
	`status` = 'ready'
AND
	`testType` = 'release'
AND
	(
        `userId` = uId
    AND
        `b` > 0
    AND
        `olympId` = 0
    );

RETURN sumVal;

end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `spm_olympiads`
--
-- Создание: Апр 14 2018 г., 11:06
--

DROP TABLE IF EXISTS `spm_olympiads`;
CREATE TABLE IF NOT EXISTS `spm_olympiads` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `description` text,
  `startTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `endTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `teacherId` bigint(20) UNSIGNED NOT NULL,
  `type` enum('Private','Public') NOT NULL,
  `judge` tinytext NOT NULL,
  `problems_list` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `spm_problems`
--
-- Создание: Апр 22 2018 г., 15:49
--

DROP TABLE IF EXISTS `spm_problems`;
CREATE TABLE IF NOT EXISTS `spm_problems` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `difficulty` tinyint(3) UNSIGNED NOT NULL,
  `category_id` smallint(5) UNSIGNED NOT NULL,
  `name` tinytext NOT NULL,
  `description` mediumtext NOT NULL,
  `input_description` mediumtext,
  `output_description` mediumtext,
  `authorSolution` mediumblob NOT NULL,
  `authorSolutionLanguage` tinytext NOT NULL,
  `adaptProgramOutput` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `spm_problems`
--

INSERT INTO `spm_problems` (`id`, `enabled`, `difficulty`, `category_id`, `name`, `description`, `input_description`, `output_description`, `authorSolution`, `authorSolutionLanguage`, `adaptProgramOutput`) VALUES
(1001, 1, 1, 1, 'Hello, world!', '<p>Напишіть програму, що виведе у <strong>вихідний потік</strong> рядок \"Hello, world!\" і одразу ж завершить свою роботу.</p>', '', '<p>В <strong>вихідний потік</strong> вивести фразу \"Hello, world!\".</p>', 0x7573696e672053797374656d3b0d0a0d0a6e616d6573706163652070726f626c656d0d0a7b0d0a202020200d0a20202020636c617373204d61696e436c6173730d0a202020207b0d0a20202020202020200d0a20202020202020207075626c69632073746174696320766f6964204d61696e28290d0a20202020202020207b0d0a202020202020202020202020436f6e736f6c652e57726974654c696e65282248656c6c6f2c20776f726c642122293b0d0a20202020202020207d0d0a20202020202020200d0a202020207d0d0a202020200d0a7d, 'csharp', 1),
(1002, 1, 1, 1, 'A+B', 'Вивести у стандартний вихідний потік суму A і B.', 'У вхідному потоці надаються 2 цілих числа через пробіл, що знаходяться у проміжку від -32000 до 32000.', 'В вихідний потік вивести суму чисел, що надані у вхідному потоці.', 0x76617220612c623a696e74656765723b0d0a626567696e0d0a20202020726561646c6e28612c62293b0d0a20202020777269746528612b62293b0d0a656e642e, 'freepascal', 1),
(1003, 1, 1, 1, 'C+D', 'Вивести на екран суму речових&amp;nbsp;чисел C и D.', 'У вхідному потоці надано 2 речових числа, що знаходяться у діапазоні від -32000 до 32000.', 'Вивести на екран суму наданих чисел із точністю 3 знаки після точки.', 0x76617220632c643a7265616c3b0d0a626567696e0d0a20202020726561646c6e28632c64293b0d0a2020202077726974652828632b64293a303a33293b0d0a656e642e, 'freepascal', 1),
(1004, 1, 1, 8, '1937. Первый символ', '<p>Дана строка, состоящая из заглавных английских букв и цифр. Вывести первый символ строки.</p>', '<p>В единственной строке входного потока дана строка символов, длина которой не превышает 255.</p>', '<p>В выходной поток вывести единственный символ.</p>', 0x76617220733a737472696e673b0d0a2020202020206e3a696e74656765723b0d0a626567696e0d0a20202020726561646c6e2873293b0d0a20202020777269746528735b315d293b0d0a656e642e, 'freepascal', 1),
(1005, 1, 4, 8, '1944. Буква в окружении', '<p>Определить количество букв, у которых оба соседа - гласные буквы.</p>', '<p>Во входном потоке дана строка, состоящая только из ЗАГЛАВНЫХ букв английского алфавита. Длина строки не превышает 255 символов.</p>', '<p>В выходной поток вывести единственное целое число.</p>', 0x76617220733a737472696e673b0d0a202020202020722c693a696e74656765723b0d0a626567696e0d0a20202020726561646c6e2873293b0d0a20202020723a3d303b0d0a20202020666f7220693a3d3220746f206c656e6774682873292d3120646f0d0a202020202020202069662028735b692d315d20696e205b2749272c2755272c274f272c2759272c2745272c2741275d2920414e442028735b692b315d20696e205b2749272c2755272c274f272c2759272c2745272c2741275d29207468656e20723a3d722b313b0d0a2020202077726974652872293b0d0a656e642e, 'freepascal', 1),
(1006, 1, 3, 8, '1948. Предыдущая буква', '<p>Определить предудущую заданной в английском алфавите букву. Если дана первая буква, то вывести последнюю.</p>', '<p>Во входном потоке задана единственная заглавная буква английского алфавита.</p>', '<p>В выходной поток вывести единственную букву.</p>', 0x76617220633a636861723b0d0a626567696e0d0a20726561642863293b0d0a2069662028633d27412729207468656e2077726974652028275a27290d0a2020202020656c736520777269746528636872286f72642863292d3129293b0d0a656e642e, 'freepascal', 1),
(1007, 1, 4, 8, '1958. Только цифры', '<p>Дана строка, состоящая из маленьких английских букв и цифр. Получить строку, состоящую их цифр исходной строки сохраняя порядок следования.&nbsp;</p><p>Если в строке нет символов-цифр, то вывести \"NO\".</p>', '<p>Во входном потоке задана строка символов состоящая из маленьких букв и цифр.</p>', '<p>В выходной поток вывести строку символов.</p>', 0x76617220732c723a737472696e673b0d0a202020202020693a696e74656765723b0d0a626567696e0d0a20202020726561646c6e2873293b0d0a20202020723a3d27273b0d0a20202020666f7220693a3d3120746f206c656e67746828732920646f0d0a202020202020202069662028735b695d20696e205b2730272e2e2739275d29207468656e20723a3d722b735b695d3b0d0a20202020696620286c656e677468287229203e203029207468656e2077726974652872290d0a2020202020202020656c736520777269746528274e4f27293b0d0a656e642e, 'freepascal', 1),
(1008, 1, 3, 8, '1979. Наибольший символ', '<p>Дана строка, состоящая из заглавных английских букв и цифр. Определить наибольший символ. Символ считается большим, если его код больший.</p>', '<p>В единственной строке входного потока дана строка символов, длина которой не превышает 255.</p>', '<p>В выходной поток вывести единственный символ.</p>', 0x76617220733a737472696e673b0d0a202020202020692c723a696e74656765723b0d0a626567696e0d0a20202020726561646c6e2873293b0d0a20202020723a3d303b0d0a20202020666f7220693a3d3120746f206c656e67746828732920646f0d0a20202020202020206966202872203c206f726428735b695d2929207468656e20723a3d6f726428735b695d293b0d0a20202020777269746528636872287229293b0d0a656e642e, 'freepascal', 1),
(1009, 1, 3, 8, '1990. Четырёхзначный палиндром', '<p>Требуется написать программу, определяющую, является ли четырехзначное натуральное число N палиндромом, т.е. числом, которое одинаково читается слева направо и справа налево.</p>', '<p>Во входном потоке записано натуральное число N (1000 ≤ N ≤ 9999).</p>', '<p>В выходной поток вывести слово \'YES\', если число N является палиндромом, или \'NO\' - если нет.</p>', 0x76617220733a737472696e673b0d0a202020202020692c722c6e3a696e74656765723b0d0a626567696e0d0a20202020726561646c6e2873293b0d0a20202020723a3d303b0d0a202020206e3a3d6c656e6774682873293b0d0a20202020666f7220693a3d3120746f206e20646f0d0a202020202020202069662028735b695d203c3e20735b6e2b312d695d29207468656e20723a3d722b313b0d0a2020202069662028723d3029207468656e207772697465282759455327290d0a2020202020202020656c736520777269746528274e4f27293b0d0a656e642e, 'freepascal', 1),
(1010, 1, 6, 8, '2046. Центральный символ (НЕТ АВТ. РЕШ,!)', '<p>Строка разбивается на элементы по 5 символов в каждом. Сформировать слово, составленное из вторых символов элементов, содержащих 25-й от начала массива символ.</p>', '<p>Во входном потоке дана единственная строка, состоящая из заглавных английских букви и цифр. Длинна строки - 50 символов.</p>', '<p>В выходной поток вывести единственную строку.</p>', 0x626567696e0d0a656e642e, 'freepascal', 1),
(1011, 1, 4, 8, '2075. Удалить начальные цифры', '<p>Дана строка. Удалить цифры с начала строки до первой буквы (начальные цифры). Вывести преобразованную строку и количество удаленных символов.</p>', '<p>В единственной строке входного потока дана строка, длинна которой не превышает 255 символов. Строка состоит из маленьких английских букв и цифр.</p>', '<p>В выходной поток вывести преобразованную строку символов и через пробел целое число - количество удаленных символов.</p>', 0x76617220733a737472696e673b0d0a202020202020723a696e74656765723b0d0a626567696e0d0a20202020726561646c6e2873293b0d0a20202020723a3d303b0d0a202020207768696c652028735b315d20696e205b2730272e2e2739275d2920616e6420286c656e6774682873293e302920646f20626567696e0d0a202020202020202064656c65746528732c312c31293b0d0a2020202020202020723a3d722b313b0d0a2020202020202020656e643b0d0a20202020777269746528732c2720272c72293b0d0a656e642e, 'freepascal', 1),
(1012, 1, 4, 8, '2079. Цифры цифры цифры цифры', '<p>Дана строка. Удалить цифры, окруженные с двух строн цифрами и в полученной строке найти сумму индексов символов-цифр.</p>', '<p>В единственной строке входного потока дана строка, длинна которой не превышает 255 символов. Строка состоит из маленьких английских букв и цифр.</p>', '<p>В выходной поток вывести преобразованную строку символов и через пробел целое число - сумму индексов.</p>', 0x76617220733a737472696e673b0d0a202020202020722c693a696e74656765723b0d0a626567696e0d0a20202020726561646c6e2873293b0d0a20202020723a3d303b0d0a20202020693a3d323b0d0a202020200d0a202020207768696c652028693c6c656e6774682873292920646f20626567696e0d0a20202020202020202069662028735b692d315d20696e205b2730272e2e2739275d2920616e642028735b692b315d20696e205b2730272e2e2739275d2920616e642028735b695d20696e205b2730272e2e2739275d29207468656e20626567696e0d0a2020202020202020202020202064656c65746528732c692c31293b0d0a20202020202020202020202020693a3d692d313b0d0a202020202020202020656e643b0d0a202020202020202020693a3d692b313b0d0a20202020656e643b0d0a202020200d0a20202020666f7220693a3d3120746f206c656e67746828732920646f0d0a202020202020202069662028735b695d20696e205b2730272e2e2739275d29207468656e20723a3d722b693b0d0a202020200d0a20202020777269746528732c2720272c72293b0d0a656e642e, 'freepascal', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `spm_problems_categories`
--
-- Создание: Мар 29 2018 г., 18:12
--

DROP TABLE IF EXISTS `spm_problems_categories`;
CREATE TABLE IF NOT EXISTS `spm_problems_categories` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort` smallint(5) UNSIGNED DEFAULT NULL,
  `name` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `spm_problems_categories`
--

INSERT INTO `spm_problems_categories` (`id`, `sort`, `name`) VALUES
(1, 1, 'Лінійні'),
(3, 2, 'Розгалуження'),
(4, 3, 'Цикли з параметром'),
(5, 4, 'Цикли з умовою'),
(6, 6, 'Одномірні массиви'),
(7, 7, 'Багатомірні массиви'),
(8, 8, 'Рядки'),
(9, 9, 'Арифметика'),
(10, 10, 'Динаміка'),
(11, 11, 'Рекурсія'),
(12, 12, 'Моделювання'),
(13, 13, 'Теорія графів'),
(14, 14, 'Множини'),
(15, 15, 'Комбінаторика'),
(16, 16, 'Списки'),
(17, 17, 'Процедури та функції'),
(18, 18, 'Цікава інформатика'),
(19, 19, 'Занимательная физика'),
(20, 20, 'Обов\'язковий мінімум'),
(21, 21, 'Олімпіади, турніри');

-- --------------------------------------------------------

--
-- Структура таблицы `spm_problems_tests`
--
-- Создание: Апр 22 2018 г., 07:02
--

DROP TABLE IF EXISTS `spm_problems_tests`;
CREATE TABLE IF NOT EXISTS `spm_problems_tests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `problemId` bigint(20) UNSIGNED NOT NULL,
  `input` longblob,
  `output` longblob,
  `memoryLimit` bigint(20) UNSIGNED NOT NULL DEFAULT '20971520',
  `timeLimit` smallint(5) UNSIGNED NOT NULL DEFAULT '200',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `spm_problems_tests`
--

INSERT INTO `spm_problems_tests` (`id`, `problemId`, `input`, `output`, `memoryLimit`, `timeLimit`) VALUES
(1, 1001, '', 0x48656c6c6f2c20776f726c6421, 20971520, 200),
(2, 1002, 0x3332303030203332303030, 0x3634303030, 20971520, 150),
(3, 1002, 0x302030, 0x30, 20971520, 150),
(4, 1002, 0x2d3332303030202d3332303030, 0x2d3634303030, 20971520, 150),
(5, 1002, 0x323730372031313034, 0x33383131, 20971520, 150),
(6, 1002, 0x312030, 0x31, 20971520, 200),
(7, 1002, 0x302031, 0x31, 20971520, 200),
(8, 1002, 0x35203137, 0x3232, 20971520, 200),
(9, 1002, 0x352035, 0x3130, 20971520, 200),
(10, 1002, 0x323030302032303030, 0x34303030, 20971520, 200),
(11, 1002, 0x3330303030202d3330303030, 0x30, 20971520, 200),
(12, 1002, 0x3120353030, 0x353031, 20971520, 200),
(13, 1002, 0x3530302031, 0x353031, 20971520, 200),
(14, 1002, 0x3732302032, 0x373232, 20971520, 200),
(15, 1002, 0x38303020383030, 0x31363030, 20971520, 200),
(16, 1003, 0x302030, 0x302e303030, 30971520, 300),
(17, 1003, 0x2d312e33323520312e333235, 0x302e303030, 30971520, 300),
(18, 1003, 0x2d33303030302e3120302e31, 0x2d33303030302e303030, 30971520, 300),
(19, 1003, 0x352e323320372e313235, 0x31322e333535, 30971520, 300);

-- --------------------------------------------------------

--
-- Структура таблицы `spm_submissions`
--
-- Создание: Апр 22 2018 г., 07:08
--

DROP TABLE IF EXISTS `spm_submissions`;
CREATE TABLE IF NOT EXISTS `spm_submissions` (
  `submissionId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `classworkId` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `olympId` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `codeLang` tinytext NOT NULL,
  `userId` bigint(20) NOT NULL,
  `problemId` bigint(20) UNSIGNED NOT NULL,
  `testType` enum('unset','syntax','debug','release') NOT NULL DEFAULT 'unset',
  `judge` tinytext NOT NULL,
  `problemCode` mediumblob NOT NULL,
  `customTest` blob,
  `status` enum('waiting','processing','ready') NOT NULL DEFAULT 'waiting',
  `hasError` tinyint(1) NOT NULL DEFAULT '0',
  `errorOutput` mediumtext,
  `output` blob,
  `exitcodes` mediumtext,
  `usedProcTime` text,
  `usedMemory` text,
  `compiler_text` blob,
  `tests_result` text,
  `b` double UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`submissionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Problem submissions';

-- --------------------------------------------------------

--
-- Структура таблицы `spm_teacherid`
--
-- Создание: Мар 29 2018 г., 18:12
--

DROP TABLE IF EXISTS `spm_teacherid`;
CREATE TABLE IF NOT EXISTS `spm_teacherid` (
  `userId` bigint(20) UNSIGNED NOT NULL,
  `teacherId` tinytext NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `newUserPermission` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`userId`),
  UNIQUE KEY `teacherId` (`teacherId`(15))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `spm_users`
--
-- Создание: Апр 16 2018 г., 11:50
--

DROP TABLE IF EXISTS `spm_users`;
CREATE TABLE IF NOT EXISTS `spm_users` (
  `id` bigint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sessionId` text CHARACTER SET utf8 COLLATE utf8_bin,
  `last_online` datetime NOT NULL DEFAULT '2001-07-27 10:30:00',
  `avatar` mediumblob,
  `password` tinytext NOT NULL,
  `firstname` tinytext NOT NULL,
  `secondname` tinytext NOT NULL,
  `thirdname` tinytext NOT NULL,
  `birthday_date` date NOT NULL DEFAULT '2000-01-01',
  `email` tinytext CHARACTER SET ascii NOT NULL,
  `teacherId` bigint(20) UNSIGNED NOT NULL,
  `permissions` smallint(5) UNSIGNED NOT NULL,
  `institution` tinytext,
  `groupid` int(10) UNSIGNED NOT NULL,
  `associated_olymp` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `spm_users_groups`
--
-- Создание: Мар 29 2018 г., 18:12
--

DROP TABLE IF EXISTS `spm_users_groups`;
CREATE TABLE IF NOT EXISTS `spm_users_groups` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `teacherId` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User groups';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
