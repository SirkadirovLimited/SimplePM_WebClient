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

isset($_POST['id']) && $_POST['id'] != "NULL" or $_POST['id'] = 0;
$_POST['id'] = abs((int)$_POST['id']);

Security::CheckPostDataIssetAndNotNull(
	array(
		"name"
	)
) or Security::ThrowError("input");;

/*
 * Запрашиваем доступ к используемы
 * м глобальным переменным.
 */

global $database;

/*
 * Если производится редактирование
 * ранее существующей группы, прове
 * ряем, может ли текущий пользоват
 * ель вносить изменения в информац
 * ию о текущей пользовательской гр
 * уппе.
 */

if ($_POST['id'] > 0)
{

	// Формируем запрос на выборку из БД
	$query_str = "
		SELECT
		  count(`id`)
		FROM
		  `spm_users_groups`
		WHERE
		  `id` = '" . $_POST['id'] . "'
		AND
		  `teacherId` = '" . Security::getCurrentSession()['user_info']->getUserId() . "'
		;
	";

	// Выполняем запрос и производим проверки
	if ((int)($database->query($query_str)->fetch_array()[0]) <= 0)
		Security::ThrowError("403");

}

/*
 * Записываем новые или обновлённые
 * даные  в  соответствующую ячейку
 * хранения в базе данных системы.
 */

// Формируем запрос на запись в БД
$query_str = "
	INSERT INTO
	  `spm_users_groups`
	SET
	  `id` = " . ($_POST['id'] > 0 ? $_POST['id'] : "NULL") . ",
	  `name` = '" . $_POST['name'] . "',
	  `teacherId` = '" . Security::getCurrentSession()['user_info']->getUserId() . "'
	ON DUPLICATE KEY UPDATE
	  `name` = '" . $_POST['name'] . "',
	  `teacherId` = '" . Security::getCurrentSession()['user_info']->getUserId() . "'
	;
";

// Выполняем запрос и обрабатываем ошибки
if (!$database->query($query_str))
	Security::ThrowError("input");

/*
 * Переадресовываем пользователя
 * на необходимый нам сервис.
 */

// Посылаем заголовки
header(
	'location: ' . _SPM_ . 'index.php/users/groups/',
	true
);

// Завершаем работу веб-приложения
exit;