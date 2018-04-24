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