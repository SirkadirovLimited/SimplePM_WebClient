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
 * Получаем идентификатор текущего соревнования
 * для возможного ограничения доступного списка
 * задач.
 */

$associated_olymp = (int)(Security::getCurrentSession()["user_info"]->getUserInfo()["associated_olymp"]);

/*
 * Получаем информацию о запрошенном
 * запросе (о как!) на тестирование.
 */

// Формируем запрос на выборку данных
$query_str = "
    SELECT
      `submissionId`,
      `status`,
      `time`,
      
      `olympId`,
      `userId`,
      `problemId`,
      
      `seen`,
      
      `problemCode`,
      `codeLang`,
      
      `testType`,
      `judge`,
      `customTest`,
      
      `hasError`,
      
      `errorOutput`,
      `output`,
      
      `exitcodes`,
      
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

if ($submission_info->num_rows <= 0)
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

Security::CheckAccessPermissionsForEdit($submission_info['userId'])
    or Security::ThrowError("403");

/*
 * Проверяем,  может   ли   текущий
 * пользователь во время возможного
 * текущего соревнования просматрив
 * ать результат этого запроса.
 */

if (
	$submission_info['userId'] == Security::getCurrentSession()['user_info']->getUserId() &&
	$associated_olymp > 0 && $submission_info['olympId'] != $associated_olymp
)
	Security::ThrowError("403");

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