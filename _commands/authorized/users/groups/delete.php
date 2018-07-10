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
 * vefification system for programming tasks "SimplePM".
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

isset($_GET['group']) or Security::ThrowError("input");
$_GET['group'] = abs((int)$_GET['group']);

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Выполняем деактивацию всех
 * пользователей, которые нах
 * одятся в удаляемой нами гр
 * уппе.
 *
 * Это деобходимо для упрощен
 * ия жизни разработчику сист
 * емы (если кто не понял).
 */

$query_str = "
	DELETE FROM
	  `spm_users`
	WHERE
	  `teacherId` = '" . Security::getCurrentSession()['user_info']->getUserId() . "'
	AND
	  `groupid` = '" . $_GET['group'] . "'
	;
";

// Выполняем запрос на удаление
if (!$database->query($query_str))
	Security::ThrowError("input");

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
	  `id` = '" . $_GET['group'] . "'
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