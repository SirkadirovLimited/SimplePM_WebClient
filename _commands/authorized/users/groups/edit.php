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