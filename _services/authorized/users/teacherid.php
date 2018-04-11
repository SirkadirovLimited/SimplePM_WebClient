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
 * Производим проверку наличия доступа
 * для использования данного сервиса.
 */

Security::CheckAccessPermissions(
	Security::getCurrentSession()['user_info']->getUserInfo()['permissions'],
	PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR,
	true
);

/*
 * Глобальные константы
 */

define("__PAGE_TITLE__", _("TeacherID"));
define("__PAGE_LAYOUT__", "default");

/*
 * Объявляем и инициализируем важные
 * и часто используемые переменные.
 */

$_current_user_id = Security::getCurrentSession()['user_info']->getUserId();

/**
 * Функция переадресовывает текущего
 * пользователя на нужный нам сервис
 */

function redirect() : void
{

	// Перезаписываем заголовок
	header(
		'location: ' . _SPM_ . 'index.php/users/TeacherID',
		true
	);

	// Завершаем работу веб-приложения
	exit;

}

/**
 * Функция занимается перегенерацией TeacherID
 * указанного пользователя системы.
 * @param int $userId Идентификатор пользователя
 */

function teacherId_regenerate(int $userId) : void
{

	/*
	 * Запрашиваем доступ к глобальным переменным
	 */

	global $database;

	/*
	 * Получаем  текущий  уровень
	 * доступа указанного доступа
	 */

	$this_user_permissions = UserInfo::getUserInfo($userId)["permissions"];

	/*
	 * Устанавливаем уровень доступа для
	 * пользователей,  которые будут рег
	 * истрироваться с помощью  текущего
	 * сгенерированного кода TeacherID.
	 */

	if (Security::CheckAccessPermissions($this_user_permissions, PERMISSION::TEACHER, false))
		$user_permission = PERMISSION::STUDENT;
	elseif (Security::CheckAccessPermissions($this_user_permissions, PERMISSION::ADMINISTRATOR, false))
		$user_permission = PERMISSION::TEACHER;
	else
		$user_permission = PERMISSION::ANONYMOUS;

	/*
	 * Генерируем TeacherID
	 */

	$teacherId = Security::GenerateKeyCode(10);

	/*
	 * Производим необхордимые запросы
	 * к базе данных.
	 */

	// Формируем запрос на вставку к БД
	$query_str = "
		INSERT INTO
		  `spm_teacherid`
		SET
		  `userId` = '" . $userId . "',
		  `teacherId` = '" . $teacherId . "',
		  `newUserPermission` = '" . $user_permission . "'
		ON DUPLICATE KEY UPDATE
		  `teacherId` = '" . $teacherId . "',
		  `newUserPermission` = '" . $user_permission . "'
		;
	";

	// Выполняем запрос и отлавливаем ошибки
	if (!$database->query($query_str))
		Security::ThrowError("input");

	/*
	 * Переадресовываем пользователя
	 * на необходимый нам сервис.
	 */

	redirect();

}

/**
 * Функция позволяет изменить статус
 * пользовательского   TeacherID  на
 * указанный.
 * @param int $userId Идентификатор пользователя
 * @param bool $enable Установить в положение
 */

function teacherId_toggle(int $userId, bool $enable) : void
{

	/*
	 * Запрашиваем доступ к глобальным переменным
	 */

	global $database;

	/*
	 * Производим необходимые запросы к базе данных
	 */

	// Формируем запрос на обновление данных
	$query_str = "
		UPDATE
		  `spm_teacherid`
		SET
		  `enabled` = " . (int)$enable . "
		WHERE
		  `userId` = " . $userId . "
		LIMIT
		  1
		;
	";

	// Выполняем запрос и обрабатываем ошибки
	if (!$database->query($query_str))
		Security::ThrowError("input");

	/*
	 * Перенаправляем пользователя
	 * на необходимый нам сервис.
	 */
	redirect();

}

/**
 * Функция позволяет получить текущий код TeacherID
 * указанного пользователя системы SimplePM.
 * @param int $userId Идентификатор пользователя
 * @return string TeacherID указанного пользователя
 */

function teacherId_get(int $userId) : string
{

	/*
	 * Запрашиваем доступ к глобальным переменным
	 */

	global $database;

	/*
	 * Производим необходимые запросы к БД
	 */

	$query_str = "
		SELECT
	  	  `teacherId`
		FROM
		  `spm_teacherid`
		WHERE
		  `userId` = '" . $userId . "'
		LIMIT
		  1
		;
	";

	/*
	 * Возвращаем результат работы функции
	 */

	return (string)$database->query($query_str)->fetch_array()[0];

}

/**
 * Функция позволяет определить, активирован ли
 * уникальній код TeacherID, привязанный к указ
 * анному идентификатору пользователя системы.
 * @param int $userId Идентификатор пользователя
 * @return bool Статус кода
 */

function teacherId_enabled(int $userId) : bool
{

	/*
	 * Запрашиваем доступ к глобальным переменным
	 */

	global $database;

	/*
	 * Производим запрос на выборку
	 * необходимых нам данных из БД
	 */

	// Формируем запрос на выборку
	$query_str = "
		SELECT
		  `enabled`
		FROM
		  `spm_teacherid`
		WHERE
		  `userId` = " . $userId . "
		LIMIT
		  1
		;
	";

	/*
	 * Выполняем запрос, возвращаем
	 * и форматируем результат выпо
	 * лнения данного запроса.
	 */

	return (bool)(
		(int)(
			$database->query($query_str)->fetch_array()[0]
		)
	);

}

/**
 * Функция  позволяет  определить, существует  ли
 * уникальный код TeacherID, который ассоциирован
 * с указанным пользователем системы SimplePM.
 * @param int $userId Идентификатор пользователя
 * @return bool Существует или нет
 */

function teacherId_exists(int $userId) : bool
{

	/*
	 * Запрашиваем доступ к глобальным переменным
	 */

	global $database;

	/*
	 * Производим запрос к базе данных
	 */

	// ФОрмируем запрос на выборку из БД
	$query_str = "
		SELECT
		  count(`teacherId`)
		FROM
		  `spm_teacherid`
		WHERE
		  `userId` = " . $userId . "
		;
	";

	/*
	 * Выполняем запрос и возвращаем
	 * преформатированный результат.
	 */

	return (int)(
		$database->query($query_str)->fetch_array()[0]
		) > 0;

}

if (isset($_GET['t_action']))
{

	switch ($_GET['t_action'])
	{

		case "new":
			teacherId_regenerate($_current_user_id);
			break;

		case "enable":
			teacherId_toggle($_current_user_id, true);
			break;
		case "disable":
			teacherId_toggle($_current_user_id, false);
			break;

	}

}

/*
 * Если  TeacherID   для   текущего  пользователя
 * ещё не создан, запускаем скрипт его генерации.
 */

if (!teacherId_exists($_current_user_id))
	teacherId_regenerate($_current_user_id);

?>

<div class="card">
	<div class="card-body" align="center">

		<h2
				class="text-center <?=(teacherId_enabled($_current_user_id) ? "text-success" : "text-danger")?>"
				style="padding-bottom: 10px;"
		><?=teacherId_get($_current_user_id)?></h2>

		<a
				href="<?=_SPM_?>index.php/users/TeacherID/?t_action=new"
				class="btn btn-outline-dark"
		><?=_("Згенерувати новий")?></a>

		<a
			href="<?=_SPM_?>index.php/users/TeacherID/?t_action=enable"
			class="btn btn-outline-success"
		><?=_("Ввімкнути")?></a>

		<a
			href="<?=_SPM_?>index.php/users/TeacherID/?t_action=disable"
			class="btn btn-outline-danger"
		><?=_("Вимкнути")?></a>

	</div>
</div>

<div class="card">
	<div class="card-body text-justify">

		<p class="lead" style="margin: 0;"><strong>TeacherID</strong> - <?=_("це унікальний пароль, що дозволяє іншим реєструватися в системі та автоматично пов'язуватися зі своїм куратором.")?></p>

	</div>
</div>