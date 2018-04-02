<?php

/*
 * Copyright (C) 2018, Yurij Kadirov.
 * All rights are reserved.
 * Licensed under Apache License 2.0 with additional restrictions.
 *
 * @Author: Yurij Kadirov
 * @Website: https://sirkadirov.com/
 * @Email: admin@sirkadirov.com
 * @Repo: https://github.com/SirkadirovTeam/SimplePM_Server
 */

global $_CONFIG;
global $database;

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
 * Проверяем язык программирования на существование
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

// Очищаем данные от возможного мусора
$_POST['problem_id'] = abs((int)$_POST['problem_id']);

// Получаем идентификатор текущей олимпиады
$_olymp_id = Security::getCurrentSession()["user_info"]->getUserInfo()["associated_olymp"];

/*
 * Проверяем задачу на существование
 * в текущем архиве задач.
 */

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

if ((int)($database->query($query_str)->fetch_array()[0] != 1))
    Security::ThrowError("404");

/*
 * Проверяем, содержится ли задача в списке
 * заданий  к  выполнению  в  информации  о
 * текущем соревновании.
 */

if ($_olymp_id > 0)
{

    // Формируем запрос на выборку из БД
    $query_str = "
        SELECT
          count(`id`)
        FROM
          `spm_olympiads`
        WHERE
          `id` = '" . $_olymp_id . "'
        AND
          `problems_list` LIKE '%" . $_POST['problem_id'] . ",%'
        ;
    ";

    // Проверяем результат проверки и выполняем необходимые действия
    if ((int)($database->query($query_str)->fetch_array()[0]) != 1)
        Security::ThrowError(_("Вказане завдання не присутнє у поточному змаганні!"));

}

/*
 * Продолжаем только в том случае,
 * если данная задача имеет тесты.
 */

// TODO

/*
 * Проверка на наличие пользовательского
 * теста, в случае  не  нахождения будем
 * использовать  первый  попавшийся тест
 * данной задачи, взятый из БД.
 */

if (!isset($_POST['custom_test']) || (int)strlen($_POST['custom_test']) <= 0)
{

    // TODO: записывать входной поток первого теста в поле кастомного теста

}

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
    AND
      (
        `testType` = 'syntax'
      OR
        `testType` = 'debug'
      OR
        (
          `testType` = 'release'
        AND
          `b` = '0'
        )
      )
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
      `codeLang` = '" . $_POST['submission_language'] . "',
      `userId` = '" . Security::getCurrentSession()["user_info"]->getUserId() . "',
      `problemId` = '" . $_POST['problem_id'] . "',
      `testType` = '" . $_POST['submission_type'] . "',
      `problemCode` = '" . $_POST['code'] . "',
      `customTest` = '" . $_POST[''] . "'
    ;
";

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