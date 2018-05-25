<?php

/*
 * ███████╗██╗███╗   ███╗██████╗ ██╗     ███████╗██████╗ ███╗   ███╗
 * ██╔════╝██║████╗ ████║██╔══██╗██║     ██╔════╝██╔══██╗████╗ ████║
 * ███████╗██║██╔████╔██║██████╔╝██║     █████╗  ██████╔╝██╔████╔██║
 * ╚════██║██║██║╚██╔╝██║██╔═══╝ ██║     ██╔══╝  ██╔═══╝ ██║╚██╔╝██║
 * ███████║██║██║ ╚═╝ ██║██║     ███████╗███████╗██║     ██║ ╚═╝ ██║
 * ╚══════╝╚═╝╚═╝     ╚═╝╚═╝     ╚══════╝╚══════╝╚═╝     ╚═╝     ╚═╝
 *
 * SimplePM WebApp
 * A part of SimplePM programming contests management system.
 *
 * Copyright 2018 Yurij Kadirov
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Visit website for more details: https://spm.sirkadirov.com/
 */

/*
 * Проверяем пава пользователя
 * на  использование   данного
 * сервиса.
 */

Security::CheckAccessPermissions(
	Security::getCurrentSession()['user_info']->getUserInfo()['permissions'],
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