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
 * Производим   включение   используемых
 * и  необходимых файлов исходного кода.
 */

include_once _SPM_includes_ . "ServiceHelpers/Olymp.inc";

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $_CONFIG;
global $database;
global $supported_programming_languages;

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
foreach ($supported_programming_languages->getSupportedLanguages() as $language)
{

    // Если мы нашли то, что искали...
    if ($_POST['submission_language'] == $language['name'])
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
$query_str = sprintf("
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
      `id` = '%s'
    ;
",
	$_POST['problem_id']
);

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
$query_str = sprintf("
    SELECT
      count(`id`)
    FROM
      `spm_problems_tests`
    WHERE
      `problemId` = '%s'
    ;
",
	$_POST['problem_id']
);

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
        $query_str = sprintf("
            SELECT
              `input`
            FROM
              `spm_problems_tests`
            WHERE
              `problemId` = '%s'
            ORDER BY
              `id` ASC
            LIMIT
              1
            ;
        ",
	        $_POST['problem_id']
        );

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

	$query_str = sprintf("
		SELECT
		  `judge`
		FROM
		  `spm_olympiads`
		WHERE
		  `id` = '%s'
		LIMIT
		  1
		;
	",
		$_olymp_id
	);

	$_POST['judge'] = $database->query($query_str)->fetch_array()[0];

}
else
	$_POST['judge'] = $_CONFIG->getWebappConfig()['default_judge'];

/*
 * Производим подсчёт предыдущих попы
 * ток release-отправки решений задач
 * и данным пользователем.
 */

// Формируем запрос на выборку данных из БД
$query_str = sprintf("
	SELECT
	  SUM(`previous_count`)
	FROM
	  `spm_submissions`
	WHERE
	  `userId` = '%s'
	AND
	  `problemId` = '%s'
	AND
	  `olympId` = '%s'
	;
",
	Security::getCurrentSession()["user_info"]->getUserId(),
	$_POST['problem_id'],
	$_olymp_id
);

// Выполняем запрос и производим выборку данных из БД
$previous_count = (int)(@$database->query($query_str)->fetch_array()[0]);

/*
 * Выборочно удаляем  все  предыдущие
 * попытки пользователя решить задачу
 * (кроме release-отправок  во  время
 * проходящего соревнования).
 */

// Формируем запрос на удаление данных из БД
$query_str = sprintf("
    DELETE FROM
      `spm_submissions`
    WHERE
      `userId` = '%s'
    AND
      `problemId` = '%s'
    AND
      `olympId` = '%s'
    ;
",
	Security::getCurrentSession()["user_info"]->getUserId(),
	$_POST['problem_id'],
	$_olymp_id
);

// Выполняем удаление данных из БД
$database->query($query_str);

/*
 * Добавляем новый запрос на тестирование в БД
 */

// Формируем запрос на добавление данных в БД
$query_str = sprintf("
    INSERT INTO
      `spm_submissions`
    SET
      `olympId` = '%s',
      
      `userId` = '%s',
      
      `previous_count` = '%s',
      
      `problemId` = '%s',
      `codeLang` = '%s',
      `problemCode` = '%s',
      
      `testType` = '%s',
      `judge` = '%s',
      `customTest` = '%s'
    ;
",
	$_olymp_id,
	Security::getCurrentSession()["user_info"]->getUserId(),
	($previous_count + (int)($_POST['submission_type'] === "release")),
	$_POST['problem_id'],
	$_POST['submission_language'],
	$_POST['code'],
	$_POST['submission_type'],
	$_POST['judge'],
	$_POST['custom_test']
);

// Только для тестирования
//for ($i = 0; $i < 100000; $i++)

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