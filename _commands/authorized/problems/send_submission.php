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
 * Производим   включение   используемых
 * и  необходимых файлов исходного кода.
 */

include_once _SPM_includes_ . "ServiceHelpers/Olymp.inc";

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $_CONFIG;
global $database;

/*
 * Осуществляем  различные  проверки,
 * призванные  защитить  систему   от
 * преднамеренных и не преднамеренных
 * атак и злополучных действий.
 */

(
    Security::CheckPostDataIssetAndNotNull
    (
        array
        (
            "problem_id",
            "code",
            "submission_language",
            "submission_type"
        )
    )
    &&
    (
        $_POST['submission_type'] == "syntax"
        || $_POST['submission_type'] == "debug"
        || $_POST['submission_type'] == "release"
    )
)
or Security::ThrowError(_("Форму відправки рішення заповнено з помилками!"));

/*
 * Проверяем указанный пользователем язык
 * программирования на существование.
 */

// Инициализируем флаг
$lang_found = false;

// Производим перебор всех компиляторов в цикле
foreach ($_CONFIG->getCompilersConfig() as $compiler_info)
{

    // Мы ищем лишь доступные компиляторы и ЯП
    if (!$compiler_info['enabled'])
        continue;

    // Если мы нашли то, что искали...
    if ($_POST['submission_language'] == $compiler_info['language_name'])
    {

        // Устанавливаем флаг в соответствующее состояние
        $lang_found = true;

        // Преждевременно выходим из цикла
        break;

    }

}

// Если указанный ЯП не найден, выдаём ошибку
if (!$lang_found)
    Security::ThrowError(_("Вказана мова програмування не підтримується!"));

// Уничтожаем временную переменную
unset($lang_found);

/*
 * Инициализируем и очищаем от
 * мусора необходимые и исполь
 * зуемые переменные.
 */

// Очищаем данные от возможного мусора
$_POST['problem_id'] = abs((int)$_POST['problem_id']);

// Получаем идентификатор текущей олимпиады
$_olymp_id = Security::getCurrentSession()["user_info"]->getUserInfo()["associated_olymp"];

/*
 * Проверяем задачу на существование
 * в текущем архиве задач.
 */

// Формируем запрос на выборку
$query_str = "
    SELECT
      count(`id`)
    FROM
      `spm_problems`
    WHERE
      `enabled` = TRUE
    AND
      `authorSolution` IS NOT NULL
    AND
      `authorSolutionLanguage` IS NOT NULL
    AND
      `id` = '" . $_POST['problem_id'] . "'
    ;
";

// Выполняем запрос и обрабатываем результаты
if ((int)($database->query($query_str)->fetch_array()[0] != 1))
    Security::ThrowError("404");

/*
 * Проверяем,  содержится ли текущая
 * задача  в  списке  доступных  для
 * решения задач во время проведения
 * текущего соревнования или урока.
 */

Olymp::CheckProblemInList($_olymp_id, $_POST['problem_id']);

/*
 * Продолжаем только в том случае,
 * если данная задача имеет тесты.
 */

// Формируем запрос на выборку из БД
$query_str = "
    SELECT
      count(`id`)
    FROM
      `spm_problems_tests`
    WHERE
      `problemId` = '" . $_POST['problem_id'] . "'
    ;
";

// Производим запрос и проверку
(int)($database->query($query_str)->fetch_array()[0]) > 0
    or Security::ThrowError(_("Вирішена задача не має тестів! Зв'яжіться з адміністратором системи!"));

/*
 * Проверка на наличие пользовательского
 * теста, в случае  не  нахождения будем
 * использовать  первый  попавшийся тест
 * данной задачи, взятый из БД.
 */

if (!isset($_POST['custom_test']) || (int)strlen($_POST['custom_test']) <= 0)
{

    /*
     * Выборку из базы данных производим
     * лишь  в  том  случае,  если типом
     * тестирования выбран  Debug-режим,
     * в   остальных  случаях  заполняем
     * поле  так  называемым  "мусором",
     * дабы  соответствовать требованиям
     * таблицы  запросов на тестирование
     * в базе данных.
     */

    if ($_POST['submission_type'] == "debug")
    {

        // Формируем запрос на выборку из БД
        $query_str = "
            SELECT
              `input`
            FROM
              `spm_problems_tests`
            WHERE
              `problemId` = '" . $_POST['problem_id'] . "'
            ORDER BY
              `id` ASC
            LIMIT
              1
            ;
        ";

        // Получаем входные данные для теста
        $_POST['custom_test'] = $database->query($query_str)->fetch_array()[0];

    }
    else
    {

        // Делаем всё, чтобы не NULL
        $_POST['custom_test'] = "CUSTOM_TEST_NONE";

    }

}

/*
 * Определяем тип судьи, которым
 * будет оцениваться пользовател
 * ьское   решение  поставленной
 * задачи.
 */

if ($_olymp_id > 0)
{

	$query_str = "
		SELECT
		  `judge`
		FROM
		  `spm_olympiads`
		WHERE
		  `id` = '" . $_olymp_id . "'
		LIMIT
		  1
		;
	";

	$_POST['judge'] = $database->query($query_str)->fetch_array()[0];

}
else
	$_POST['judge'] = $_CONFIG->getWebappConfig()['default_judge'];

/*
 * Выборочно удаляем  все  предыдущие
 * попытки пользователя решить задачу
 * (кроме release-отправок  во  время
 * проходящего соревнования).
 */

// Формируем запрос на удаление данных из БД
$query_str = "
    DELETE FROM
      `spm_submissions`
    WHERE
      `userId` = '" . Security::getCurrentSession()["user_info"]->getUserId() . "'
    AND
      `problemId` = '" . $_POST['problem_id'] . "'
    AND
      `olympId` = '" . $_olymp_id . "'
    ;
";

// Выполняем удаление данных из БД
$database->query($query_str);

/*
 * Добавляем новый запрос на тестирование в БД
 */

// Формируем запрос на добавление данных в БД
$query_str = "
    INSERT INTO
      `spm_submissions`
    SET
      `olympId` = '" . $_olymp_id . "',
      
      `userId` = '" . Security::getCurrentSession()["user_info"]->getUserId() . "',
      
      `problemId` = '" . $_POST['problem_id'] . "',
      `codeLang` = '" . $_POST['submission_language'] . "',
      `problemCode` = '" . $_POST['code'] . "',
      
      `testType` = '" . $_POST['submission_type'] . "',
      `judge` = '" . $_POST['judge'] . "',
      `customTest` = '" . $_POST['custom_test'] . "'
    ;
";

// Только для тестирования
//for ($i = 0; $i < 1000; $i++)

// Выполняем запрос и обрабатываем ошибки
if (!$database->query($query_str))
    Security::ThrowError(_("Форма відправки запиту на тестування заповнена не правильно!"));

/*
 * Выполняем принудительную переадресацию
 * пользователя на страницу информации  о
 * результате  проверки  польовательского
 * решения поставленной задачи.
 */

// Перезаписываем заголовок
header(
    'location: ' . _SPM_ . 'index.php/problems/result/?id=' . $database->insert_id,
    true
);

// Завершаем работу скрипта
exit();