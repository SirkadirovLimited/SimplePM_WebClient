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
 * указанной задачи.
 */

Security::CheckAccessPermissions(
	Security::getCurrentSession()['user_info']->getUserInfo()['permissions'],
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