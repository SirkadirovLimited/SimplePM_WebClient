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
 * Проверяем пава пользователя
 * на  использование   данного
 * сервиса.
 */

Security::CheckAccessPermissions(
	PERMISSION::STUDENT,
	true
);

/*
 * Осуществляем проверку на наличие
 * необходимых нам  для  работы GET
 * параметров.
 */

isset($_GET['id']) or Security::ThrowError("input");
$_GET['id'] = abs((int)$_GET['id']);

/*
 * Получаем идентификатор куратора
 * текущего пользователя системы.
 */

$teacherId_of_current_user = Security::getCurrentSession()['user_info']->getUserInfo()['teacherId'];

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Производим выборку всех соревнований
 * доступных текущему пользователю.
 */

//Формируем запрос на выборку данных из БД
$query_str = sprintf("
	SELECT
	  count(`id`)
	FROM
	  `spm_olympiads`
	WHERE
	  `id` = '%s'
	AND
	  (
	    `type` = 'Public'
	  OR
		(
		  `teacherId` = '%s'
		AND
		  `type` = 'Private'
		)
	  )
	AND
	  (
	  	`startTime` <= NOW()
	  AND
	  	`endTime` >= NOW()
	  )
	LIMIT
	  1
	;
",
	$_GET['id'],
	$teacherId_of_current_user
);

// Выполняем запрос и выполняем необходимые проверки
if ((int)($database->query($query_str)->fetch_array()[0]) <= 0)
	Security::ThrowError("404");

/*
 * Приказываем   пользователю    принимать
 * участие в необходимом ему соревновании.
 */

// Формируем запрос на обновление данных в БД
$query_str = sprintf("
	UPDATE
	  `spm_users`
	SET
	  `associated_olymp` = '%s'
	WHERE
	  `id` = '%s'
	LIMIT
	  1
	;
",
	$_GET['id'],
	Security::getCurrentSession()['user_info']->getUserId()
);

// Выполняем запрос
$database->query($query_str);

/*
 * Используем стандартное перенаправление
 * на главную страницу системы.
 */