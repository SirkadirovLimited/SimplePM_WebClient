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
 * Запрашиваем доступ к используемым
 * глобальным переменным.
 */

global $database;

/*
 * Проверка на существование
 * и  непустоту  необходимых
 * POST параметров запроса.
 */
//HzEO4zZzmz
Security::CheckPostDataIssetAndNotNull(
	array(
		"email",
		"password",

		"firstname",
		"secondname",
		"thirdname",

		"teacherid"
	)
) or Security::ThrowError("input");

/*
 * Производим различные проверки
 * вводимых пользователем данных
 * перед тем, как продолжить про
 * цесс регистрации нового польз
 * ователя в системе SimplePM.
 */

// Проверяем вводимый e-mail по его формату
filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) or Security::ThrowError("input");

// Проверяем длину пользовательского пароля
strlen_check_post_param("password", 8, 255);

$_POST['password'] = $database->real_escape_string(
	password_hash(
		$_POST['password'],
		PASSWORD_DEFAULT
	)
);

// Проверяем длину имени
strlen_check_post_param("firstname", 1, 255);

// Проверяем длину фамилии
strlen_check_post_param("secondname", 1, 255);

// Проверяем длину отчества
strlen_check_post_param("thirdname", 1, 255);

/*
 * Запрашиваем  развёрнутую  информацию
 * о введённом пользователем TeacherID.
 */

// Формируем запрос на выборку данных из БД
$query_str = "
	SELECT
	  `userId` AS teacher,
	  `newUserPermission` AS granted_permissions
	FROM
	  `spm_teacherid`
	WHERE
	  `teacherId` = '" . $_POST['teacherid'] . "'
	AND
	  `enabled` = TRUE
	LIMIT
	  1
	;
";

// Выполняем запрос на выборку данных
$query = $database->query($query_str) or Security::ThrowError("input");

// Проверяем запрос на успешность
if ($query->num_rows <= 0)
	Security::ThrowError(_("Введений TeacherID не знайдено! Можливо його було вимкнено чи видалено. Зверніться до свого куратора!"));

// Получаем предоставленную информацию
$teacherId_info = $query->fetch_assoc();

/*
 * Освобождаем более не используемые
 * переменные от страданий.
 */

$query->free();
unset($query);

/*
 * Добавляем нового пользователя
 * в базу данных системы.
 */

// Формируем запрос на добавление в БД
$query_str = "
	INSERT INTO
	  `spm_users`
	SET
	  `email` = '" . $_POST['email'] . "',
	  `password` = '" . $_POST['password'] . "',
	  
	  `firstname` = '" . $_POST['firstname'] . "',
	  `secondname` = '" . $_POST['secondname'] . "',
	  `thirdname` = '" . $_POST['thirdname'] . "',
	  
	  `teacherId` = '" . $teacherId_info['teacher'] . "',
	  `permissions` = '" . $teacherId_info['granted_permissions'] . "',
	  
	  `groupid` = '0'
	;
";

// Выполняем сформированный запрос
if (!$database->query($query_str))
	Security::ThrowError("input");

/*
 * Информируем пользователя об успешности
 * регистрации  его  профиля  в  системе.
 */

Security::ThrowError(_("Вітаємо! Вас було успішно зареєстровано в системі!"));