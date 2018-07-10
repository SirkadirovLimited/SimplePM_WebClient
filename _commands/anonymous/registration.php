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