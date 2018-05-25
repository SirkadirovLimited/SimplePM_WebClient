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