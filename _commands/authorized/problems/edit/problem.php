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
 * Осуществляем проверку  на  наличие доступа
 * у текущего пользователя для редактирования
 * указанной задачи.
 */

Security::CheckAccessPermissions(
	PERMISSION::ADMINISTRATOR | PERMISSION::TEACHER_MANAGE_PROBLEMS,
	true
);

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;
global $_CONFIG;

/*
 * Проверяем на наличие необходимые
 * POST параметры, переданные нам.
 */

Security::CheckPostDataIssetAndNotNull(
	array(
		"name",
		"description",
		"authorSolution",
		"authorSolutionLanguage"
	)
) or Security::ThrowError("input1");

/*
 * Получаем информацию о
 * выбранных  чекбоксах.
 */

$_POST['enabled'] = isset($_POST['enabled']);
$_POST['adaptProgramOutput'] = isset($_POST['adaptProgramOutput']);

/*
 * Производи очистку числовых данных
 * от возможного вредоносного кода.
 */

$_POST['id'] = abs(
	(int)(isset($_POST['id']) ? $_POST['id'] : 0)
);

$_POST['category_id'] = abs(
	(int)(isset($_POST['category_id']) ? $_POST['category_id'] : 0)
);

$_POST['difficulty'] = abs(
	(int)(isset($_POST['difficulty']) ? $_POST['difficulty'] : 1)
);

/*
 * Проверяем размеры переданных
 * данных на соответствие с ран
 * ее установленными ограничени
 * ями в базе данных системы.
 */

strlen_check_post_param('name', 1, 255);
strlen_check_post_param('description', 1, 65535);

strlen_check_post_param('authorSolution', 1, 16777215);
strlen_check_post_param('authorSolutionLanguage', 1, 255);

/*
 * Выполняем запрос на добавление
 * или изменение данных в базе да
 * нных системы  проверки решений
 * SimplePM.
 *
 * Соответствующий вид запроса вы
 * бирается автоматически  исходя
 * из существования задачи с указ
 * анным идентификатором в соотве
 * тственной таблице в базе данны
 * х системы SimplePM. О как!
 */

// Формируем частицу запроса на вставку в БД
$query_str_param = sprintf(
	"
		`enabled` = '%s',
		  
		`category_id` = '%s',
		`difficulty` = '%s',
		  
		`name` = '%s',
		`description` = '%s',
		  
		`input_description` = '%s',
		`output_description` = '%s',
		  
		`authorSolution` = '%s',
		`authorSolutionLanguage` = '%s',
		  
		`adaptProgramOutput` = '%s'
	",
	$_POST['enabled'] ? "1" : "0",

	$_POST['category_id'],
	$_POST['difficulty'],

	$_POST['name'],
	$_POST['description'],

	$_POST['input_description'],
	$_POST['output_description'],

	$_POST['authorSolution'],
	$_POST['authorSolutionLanguage'],

	$_POST['adaptProgramOutput'] ? "1" : "0"
);

// Формируем запрос на вставку в БД
$query_str = sprintf(
	"
		INSERT INTO
		  `spm_problems`
		SET
		  `id` = " . ($_POST['id'] > 0 ? $_POST['id'] : "NULL") . ",
		  
		  " . $query_str_param . "
		  
		ON DUPLICATE KEY UPDATE
		
		  " . $query_str_param . "
		  
		;
	"
);

// Выполняем запрос на вставку в БД и отлавливаем исключения
$database->query($query_str) or die($database->error);//Security::ThrowError("input");

/*
 * Если вставка в базу данных
 * была  произведена успешно,
 * переадресуем текущего поль
 * зователя снова на ту же са
 * мую станицу редактирования
 * задачи с указанным иденти-
 * фикатором.
 */

// Устанавливаем заголовок
header(
	'location: ' . _SPM_ . 'index.php/problems/edit/problem/?id=' . $_POST['id'],
	true
);

// Завершаем работу экземпляра веб-приложения
exit;