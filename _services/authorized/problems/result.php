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
 * Мелкие проверки для обеспечения
 * безопасности веб-приложения.
 */

isset($_GET['id']) or Security::ThrowError(_("Ідентифікатор рішення не вказано!"));
$_GET['id'] = abs((int)$_GET['id']);

/*
 * Указываем название и дизайнерскую
 * разметку данного сервиса.
 */

define("__PAGE_TITLE__", _("Рішення") . " №" . @$_GET['id']);
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Получаем информацию о запрошенном
 * запросе (о как!) на тестирование.
 */

// Формируем запрос на выборку данных
$query_str = "
    SELECT
      `submissionId`,
      `olympId`,
      `time`,
      `seen`,
      `codeLang`,
      `userId`,
      `problemId`,
      `testType`,
      `problemCode`,
      `customTest`,
      `status`,
      `hasError`,
      `errorOutput`,
      `output`,
      `exitcodes`,
      `usedProcTime`,
      `usedMemory`,
      `compiler_text`,
      `tests_result`,
      `b`
    FROM
      `spm_submissions`
    WHERE
      `submissionId` = '" . $_GET['id'] . "'
    LIMIT
      1
    ;
";

// Выполняем запрос на выборку данных
$submission_info = $database->query($query_str);

/*
 * Проверяем, существует ли запро
 * на  тестирование  с  указанным
 * идентификатором или нет, после
 * чего выполняем необходимые дей
 * ствия по обеспечению безопасно
 * сти веб-приложения системы.
 */

if ($submission_info->num_rows == 0)
    Security::ThrowError("404");

/*
 * Получаем развёрнутую информаци
 * ю об указанном запросе на тест
 * ирование в виде ассоциативного
 * массива, после чего будем её и
 * спользовать в корыстных целях.
 */

$submission_info = $submission_info->fetch_assoc();

/*
 * Получаем развёрнутую информаци
 * ю о текущем авторизированном п
 * ользователе  для  последующего
 * её использования.
 */

$current_user_info = Security::getCurrentSession()["user_info"]->getUserInfo();

/*
 * Проверяем, имеет ли текущий по
 * льзователь доступ к предоставл
 * яемой нами информации или нет,
 * а также выполняем соответствую
 * щие полученным фактам действия
 *
 * Предотсавляем доступ в случае,
 * если текущий пользователь:
 * - Администратор системы
 * - Преподаватель автора решения
 * - Автор решения
 */

(
    // Текущий пользователь - автор решения
    $current_user_info['id'] == $submission_info['userId'] ||

    // Текущий пользователь - администратор
    Security::CheckAccessPermissions(
        $current_user_info['permissions'],
        PERMISSION::ADMINISTRATOR,
        false
    ) ||

    // Текущий пользователь - преподаватель автора
	$current_user_info['id'] == UserInfo::getUserInfo($submission_info['userId'])['teacherId']
) or Security::ThrowError("403");

/*
 * Удаляем все временные переменные
 * для экономия оперативной памяти.
 */

unset($current_user_info);

/*
 * В зависимости от того, готовы ли
 * результаты проверки текущего реш
 * ения или нет, предоставляем поль
 * зователю актуальную информацию о
 * статусе проверки запрошенного за
 * проса на тестирование.
 */

if ($submission_info['status'] == "ready")
    include_once _SPM_views_ . "problems/result-view.inc";
else
    include_once _SPM_views_ . "problems/result-wait.inc";