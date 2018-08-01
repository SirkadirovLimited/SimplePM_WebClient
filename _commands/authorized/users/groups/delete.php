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
 * Проверяем уровень доступа
 * текущего пользователя.
 */

Security::CheckAccessPermissions(
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