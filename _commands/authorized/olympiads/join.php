<?php

/*
 * Copyright (C) 2018, Yurij Kadirov.
 * All rights are reserved.
 * Licensed under Apache License 2.0 with additional restrictions.
 *
 * @Author: Yurij Kadirov
 * @Website: https://sirkadirov.com/
 * @Email: admin@sirkadirov.com
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