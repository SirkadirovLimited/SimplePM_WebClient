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
 * Запрашиваем доступ к используемым
 * глобальным переменным.
 */

global $database;

/**
 * Функция занимается проверкой длины
 * единого POST параметра с указанным
 * именем.
 * @param string $post_param Имя POST параметра
 * @param int $min_length Минимальная длина
 * @param int $max_length Максимальная длина
 */

function strlen_check_post_param(string $post_param, int $min_length, int $max_length) : void
{

	(strlen($_POST[$post_param]) >= $min_length && strlen($_POST[$post_param]) <= $max_length)
		or Security::ThrowError("input1");

}

/*
 * Проверка на существование
 * и  непустоту  необходимых
 * POST параметров запроса.
 */
//HzEO4zZzmz
Security::CheckPostDataIssetAndNotNull(
	array(
		"username",
		"password",

		"email",

		"firstname",
		"secondname",
		"thirdname",

		"teacherid"
	)
) or Security::ThrowError("input2");

/*
 * Производим различные проверки
 * вводимых пользователем данных
 * перед тем, как продолжить про
 * цесс регистрации нового польз
 * ователя в системе SimplePM.
 */

// Проверяем длину имени пользователя (ника)
strlen_check_post_param("username", 3, 100);

// Проверяем длину пользовательского пароля
strlen_check_post_param("password", 8, 255);

$_POST['password'] = $database->real_escape_string(
	password_hash(
		$_POST['password'],
		PASSWORD_DEFAULT
	)
);

// Проверяем вводимый e-mail по его формату
filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) or Security::ThrowError("input");

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
$query = $database->query($query_str) or Security::ThrowError("input3");

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
	  `username` = '" . $_POST['username'] . "',
	  `password` = '" . $_POST['password'] . "',
	  
	  `email` = '" . $_POST['email'] . "',
	  
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
	Security::ThrowError("input4");

/*
 * Информируем пользователя об успешности
 * регистрации  его  профиля  в  системе.
 */

Security::ThrowError(_("Вітаємо! Вас було успішно зареєстровано в системі!"));