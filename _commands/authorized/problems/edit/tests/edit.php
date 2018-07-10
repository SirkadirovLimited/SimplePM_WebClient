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
 * Осуществляем проверку  на  наличие доступа
 * у текущего пользователя для редактирования
 * тестов указанной задачи.
 */

Security::CheckAccessPermissions(
	Security::getCurrentSession()['user_info']->getUserInfo()['permissions'],
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