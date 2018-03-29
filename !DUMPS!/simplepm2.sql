-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Мар 29 2018 г., 23:22
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
-- Процедуры
--
DROP PROCEDURE IF EXISTS `updateBCount`$$
CREATE DEFINER=`*`@`localhost` PROCEDURE `updateBCount` (IN `uId` BIGINT UNSIGNED)  SQL SECURITY INVOKER
begin
DECLARE sumVal FLOAT DEFAULT 0;
SELECT SUM(`b`) INTO sumVal FROM `spm_submissions` WHERE (`userId` = uId AND `b` > 0 AND `classworkId` = 0 AND `olympId` = 0);
UPDATE `spm_users` SET `bcount` = sumVal WHERE `id` = uId LIMIT 1;
end$$

DROP PROCEDURE IF EXISTS `updateRating`$$
CREATE DEFINER=`*`@`localhost` PROCEDURE `updateRating` (IN `urId` BIGINT UNSIGNED)  SQL SECURITY INVOKER
begin

DECLARE sumVal FLOAT DEFAULT 0;
DECLARE rProblemsCount TINYINT DEFAULT 0;

SELECT SUM(`b`) INTO `sumVal` FROM `spm_submissions` WHERE (`userId` = urId AND `b` >= 0 AND `classworkId` = 0 AND `olympId` = 0) ORDER BY `b` DESC LIMIT 30;

SELECT COUNT(`submissionId`) INTO `rProblemsCount` FROM `spm_submissions` WHERE (`userId` = urId AND `b` >= 0 AND `classworkId` = 0 AND `olympId` = 0) ORDER BY `b` DESC LIMIT 30;

UPDATE `spm_users` SET `rating` = (sumVal / rProblemsCount) WHERE `id` = urId LIMIT 1;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `spm_classworks`
--

DROP TABLE IF EXISTS `spm_classworks`;
CREATE TABLE IF NOT EXISTS `spm_classworks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `description` text NOT NULL,
  `startTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `endTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `teacherId` bigint(20) UNSIGNED NOT NULL,
  `studentsGroup` bigint(20) UNSIGNED NOT NULL,
  `ratingSystem` tinyint(3) UNSIGNED NOT NULL,
  `problemslist` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Classworks';

-- --------------------------------------------------------

--
-- Структура таблицы `spm_olympiads`
--

DROP TABLE IF EXISTS `spm_olympiads`;
CREATE TABLE IF NOT EXISTS `spm_olympiads` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `description` text,
  `startTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `endTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `teacherId` bigint(20) UNSIGNED NOT NULL,
  `type` enum('Private','Public') NOT NULL,
  `testingType` enum('Full','ByTestsCount') NOT NULL DEFAULT 'Full',
  `problemslist` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `spm_problems`
--

DROP TABLE IF EXISTS `spm_problems`;
CREATE TABLE IF NOT EXISTS `spm_problems` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `difficulty` tinyint(3) UNSIGNED NOT NULL,
  `catId` smallint(5) UNSIGNED NOT NULL,
  `name` tinytext NOT NULL,
  `description` mediumtext NOT NULL,
  `input` text,
  `output` text,
  `authorSolution` mediumblob,
  `authorSolutionLanguage` tinytext,
  `testsInformation` longblob,
  `adaptProgramOutput` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `spm_problems_categories`
--

DROP TABLE IF EXISTS `spm_problems_categories`;
CREATE TABLE IF NOT EXISTS `spm_problems_categories` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort` smallint(5) UNSIGNED DEFAULT NULL,
  `name` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `spm_problems_tests`
--

DROP TABLE IF EXISTS `spm_problems_tests`;
CREATE TABLE IF NOT EXISTS `spm_problems_tests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `problemId` bigint(20) UNSIGNED NOT NULL,
  `input` mediumblob,
  `output` mediumblob,
  `memoryLimit` bigint(20) UNSIGNED NOT NULL DEFAULT '20971520',
  `timeLimit` smallint(5) UNSIGNED NOT NULL DEFAULT '200',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `spm_submissions`
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

DROP TABLE IF EXISTS `spm_teacherid`;
CREATE TABLE IF NOT EXISTS `spm_teacherid` (
  `userId` int(11) NOT NULL,
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

DROP TABLE IF EXISTS `spm_users`;
CREATE TABLE IF NOT EXISTS `spm_users` (
  `id` bigint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sessionId` text CHARACTER SET utf8 COLLATE utf8_bin,
  `last_online` datetime NOT NULL DEFAULT '2001-07-27 10:30:00',
  `username` tinytext NOT NULL,
  `avatar` mediumblob,
  `password` tinytext NOT NULL,
  `firstname` tinytext NOT NULL,
  `secondname` tinytext NOT NULL,
  `thirdname` tinytext NOT NULL,
  `birthday_date` date NOT NULL DEFAULT '2000-01-01',
  `email` tinytext NOT NULL,
  `teacherId` bigint(20) UNSIGNED NOT NULL,
  `permissions` smallint(5) UNSIGNED NOT NULL,
  `country` tinytext,
  `city` tinytext,
  `school` tinytext,
  `groupid` int(10) UNSIGNED NOT NULL,
  `banned` tinyint(4) NOT NULL DEFAULT '0',
  `associated_olymp` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`(85)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `spm_users_groups`
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
