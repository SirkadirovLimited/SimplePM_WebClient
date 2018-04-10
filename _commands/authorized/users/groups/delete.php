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
 * Проверяем уровень доступа
 * текущего пользователя.
 */

Security::CheckAccessPermissions(
	Security::getCurrentSession()['user_info']->getUserInfo()["permissions"],
	PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR,
	true
);

/*
 * Производим различные проверки
 * для обеспечения безопасности,
 * а также очищаем входные данны
 * е от возможных инъекций.
 */

isset($_POST['group']) or Security::ThrowError("input");
$_POST['group'] = abs((int)$_POST['group']);

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Удаляем указанную пользовательскую
 * группу  из  базы  данных, учитывая
 * при этом некоторые директивы безоп
 * асности.
 */

// Формируем запрос на удаление
$query_str = "
	DELETE FROM
	  `spm_users_groups`
	WHERE
	  `teacherId` = '" . Security::getCurrentSession()['user_info']->getUserId() . "'
	AND
	  `id` = '" . $_POST['group'] . "'
	LIMIT
	  1
	;
";

// Выполняем запрос на удаление
if (!$database->query($query_str))
	Security::ThrowError("input");

/*
 * Перенаправляем пользователя
 * на необходимую страницу.
 */

// Устанавливаем и перезаписываем заголовок
header(
	'location: ' . _SPM_ . 'index.php/users/groups/',
	true
);

// Завершаем работу веб-приложения
exit;