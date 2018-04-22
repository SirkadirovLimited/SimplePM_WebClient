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
 * Осуществляем проверку  на  наличие доступа
 * у текущего пользователя для редактирования
 * указанной задачи.
 */

Security::CheckAccessPermissions(
	Security::getCurrentSession()['user_info']->getUserInfo()['permissions'],
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
		"id",
		"name",
		"category_id",
		"difficulty",
		"description",
		"authorSolution",
		"authorSolutionLanguage"
	)
) or Security::ThrowError("input");

/*
 * Получаем информацию о
 * ыбранных чекбоксах.
 */

$_POST['enabled'] = isset($_POST['enabled']);
$_POST['adaptProgramOutput'] = isset($_POST['adaptProgramOutput']);

/*
 * В случае незаполнения указываем
 * значения  необязательных  полей
 * данной формы, с помощью которой
 * был проведён текщий POST-запрос
 */

isset($_POST['input_description']) or $_POST['input_description'] = null;
isset($_POST['output_description']) or $_POST['output_description'] = null;

