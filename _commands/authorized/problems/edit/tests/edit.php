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
 * тестов указанной задачи.
 */

Security::CheckAccessPermissions(
	PERMISSION::ADMINISTRATOR | PERMISSION::TEACHER_MANAGE_PROBLEMS,
	true
);

/*
 * Осуществляем различные проверки
 * безопасности, а  также  очищаем
 * данные от возможного  вредонос-
 * ного содержимого.
 */

isset($_GET['pid']) or Security::ThrowError("input");
$_GET['pid'] = abs((int)$_GET['pid']);

/*
 * Проверяем переданные нам POST
 * данные на полноту  выполнения
 * некоторых требований.
 */

Security::CheckPostDataIssetAndNotNull(
	array(
		'testId',

		'input',
		'output',

		'timeLimit',
		'memoryLimit'
	)
) or Security::ThrowError("input");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Осуществляем полный перебор всех тестов
 * и заносим все изменения, сделанные поль
 * зователем в базу данных системы.
 */

$tests_iterator = 0;

foreach ($_POST['testId'] as $testId)
{

	// Формируем запрос на обновление данных в БД
	$query_str = sprintf("
		UPDATE
		  `spm_problems_tests`
		SET
		  `input` = '%s',
		  `output` = '%s',
		  
		  `timeLimit` = '%s',
		  `memoryLimit` = '%s'
		WHERE
		  `problemId` = '%s'
		AND
		  `id` = '%s'
		LIMIT
		  1
		;
	",
		$_POST['input'][$tests_iterator],
		$_POST['output'][$tests_iterator],
		$_POST['timeLimit'][$tests_iterator],
		$_POST['memoryLimit'][$tests_iterator],
		$_GET['pid'],
		$testId
	);

	// Выполняем сформированный запрос
	$database->query($query_str);

	// Переходим к следующему тесту
	$tests_iterator++;

}

/*
 * Перенаправляем пользователя  на  страницу
 * редактирования тестов к указанной задаче.
 */

// Перезаписываем заголовок
header(
	'location: ' . _SPM_ . 'index.php/problems/edit/tests/?id=' . $_GET['pid'],
	true
);

// Завершаем работу экземпляра веб-приложения
exit;