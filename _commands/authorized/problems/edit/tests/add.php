<?php

/*
 * ███████╗██╗███╗   ███╗██████╗ ██╗     ███████╗██████╗ ███╗   ███╗
 * ██╔════╝██║████╗ ████║██╔══██╗██║     ██╔════╝██╔══██╗████╗ ████║
 * ███████╗██║██╔████╔██║██████╔╝██║     █████╗  ██████╔╝██╔████╔██║
 * ╚════██║██║██║╚██╔╝██║██╔═══╝ ██║     ██╔══╝  ██╔═══╝ ██║╚██╔╝██║
 * ███████║██║██║ ╚═╝ ██║██║     ███████╗███████╗██║     ██║ ╚═╝ ██║
 * ╚══════╝╚═╝╚═╝     ╚═╝╚═╝     ╚══════╝╚══════╝╚═╝     ╚═╝     ╚═╝
 *
 * SimplePM WebApp is a part of software product "Automated
 * verification system for programming tasks "SimplePM".
 *
 * Copyright (C) 2016-2018 Yurij Kadirov
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * GNU Affero General Public License applied only to source code of
 * this program. More licensing information hosted on project's website.
 *
 * Visit website for more details: https://spm.sirkadirov.com/
 */

/*
 * Осуществляем проверку  на  наличие доступа
 * у текущего пользователя для редактирования
 * тестов указанной задачи.
 */

Security::CheckAccessPermissions(
	PERMISSION::ADMINISTRATOR | PERMISSION::TEACHER_MANAGE_PROBLEMS,
	true
);

/*
 * Осуществляем различные проверки
 * безопасности, а  также  очищаем
 * данные от возможного  вредонос-
 * ного содержимого.
 */

isset($_GET['pid']) or Security::ThrowError("input");
$_GET['pid'] = abs((int)$_GET['pid']);

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Проверяем указанную задачу на
 * существование.
 */

// Формируем запрос на поиск по БД
$query_str = sprintf("
	SELECT
	  count(`id`)
	FROM
	  `spm_problems`
	WHERE
	  `id` = '%s'
	;
", $_GET['pid']);

// Выполняем запрос и различные проверки
if ((int)($database->query($query_str)->fetch_array()[0]) <= 0)
	Security::ThrowError("404");

/*
 * Добавляем новый пустой тест для
 * указанной задачи в базу данных.
 */

// Формируем запрос на добавление данных в БД
$query_str = sprintf("
	INSERT INTO
	  `spm_problems_tests`
	SET
	  `problemId` = '%s'
	;
", $_GET['pid']);

// Выполняем запрос на добавление данных в БД
$database->query($query_str);

/*
 * Перенаправляем пользователя  на  страницу
 * редактирования тестов к указанной задаче.
 */

// Перезаписываем заголовок
header(
	'location: ' . _SPM_ . 'index.php/problems/edit/tests/?id=' . $_GET['pid'],
	true
);

// Завершаем работу экземпляра веб-приложения
exit;