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
 * указанной задачи.
 */

Security::CheckAccessPermissions(
	PERMISSION::ADMINISTRATOR,
	true
);

/*
 * Проверяем необходимые GET-параметры на существование
 */

isset($_GET['id']) or Security::ThrowError("input");
$_GET['id'] = abs((int)$_GET['id']);

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Удаляем условия задачи.
 */

// Формируем запрос на удаление из БД
$query_str = "
	DELETE FROM
	  `spm_problems`
	WHERE
	  `id` = '" . $_GET['id'] . "'
	LIMIT
	  1
	;
";

// Выполняем запрос на удаление из БД
$database->query($query_str);

/*
 * Удаляем тесты задачи.
 */

// Формируем запрос на удаление из БД
$query_str = "
	DELETE FROM
	  `spm_problems_tests`
	WHERE
	  `problemId` = '" . $_GET['id'] . "'
	;
";

// Выполняем запрос на удаление из БД
$database->query($query_str);

/*
 * Удаляем запросы на тестирование по задаче.
 */

// Формируем запрос на удаление из БД
$query_str = "
	DELETE FROM
	  `spm_submissions`
	WHERE
	  `problemId` = '" . $_GET['id'] . "'
	;
";

// Выполняем запрос на удаление из БД
$database->query($query_str);

/*
 * Если всё прошло успешно,
 * перенаправляем пользоват
 * еля на "Архив задач".
 */

// Устанавливаем заголовок
header(
	'location: ' . _SPM_ . 'index.php/problems/archive/',
	true
);

// Завершаем работу экземпляра веб-приложения
exit;