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
 * Получаем подробную информацию
 * о текущем пользователе.
 */

$user_info = Security::getCurrentSession()['user_info']->getUserInfo();

/*
 * Производим проверку на
 * наличие соответствующи
 * х разрешений.
 */

Security::CheckAccessPermissions(
	$user_info['permissions'],
	PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR,
	true
);

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Производим запрос на выборку
 * подчинённых данному пользова
 * телю системы пользователей.
 */

// Формируем запрос к БД
$query_str = "
	SELECT
	  `id`,
	  `name`
	FROM
	  `spm_users_groups`
	WHERE
	  `teacherId` = '" . $user_info['id'] . "'
	;
";

// Выпоняем запрос и получаем данные
$groups_list = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

/*
 * Устанавливаем название и Layout сервиса
 */

define("__PAGE_TITLE__", _("Групи користувачів"));
define("__PAGE_LAYOUT__", "default");