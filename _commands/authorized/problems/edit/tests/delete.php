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
 * Осуществляем проверку  на  наличие доступа
 * у текущего пользователя для редактирования
 * тестов указанной задачи.
 */

Security::CheckAccessPermissions(
	Security::getCurrentSession()['user_info']->getUserInfo()['permissions'],
	PERMISSION::ADMINISTRATOR | PERMISSION::TEACHER_MANAGE_PROBLEMS,
	true
);

/*
 * Осуществляем различные проверки
 * безопасности, а  также  очищаем
 * данные от возможного  вредонос-
 * ного содержимого.
 */

isset($_GET['id']) or Security::ThrowError("404");
isset($_GET['pid']) or Security::ThrowError("input");

$_GET['id'] = abs((int)$_GET['id']);
$_GET['pid'] = abs((int)$_GET['pid']);

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Производим удаление указанного теста
 */

// Формируем запрос на удаление данных из БД
$query_str = sprintf("
	DELETE FROM
	  `spm_problems_tests`
	WHERE
	  `id` = '%s'
	LIMIT
	  1
	;
", $_GET['id']);

// Выполняем запрос
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