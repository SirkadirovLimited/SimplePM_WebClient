<?php

/*
 * ███████╗██╗███╗   ███╗██████╗ ██╗     ███████╗██████╗ ███╗   ███╗
 * ██╔════╝██║████╗ ████║██╔══██╗██║     ██╔════╝██╔══██╗████╗ ████║
 * ███████╗██║██╔████╔██║██████╔╝██║     █████╗  ██████╔╝██╔████╔██║
 * ╚════██║██║██║╚██╔╝██║██╔═══╝ ██║     ██╔══╝  ██╔═══╝ ██║╚██╔╝██║
 * ███████║██║██║ ╚═╝ ██║██║     ███████╗███████╗██║     ██║ ╚═╝ ██║
 * ╚══════╝╚═╝╚═╝     ╚═╝╚═╝     ╚══════╝╚══════╝╚═╝     ╚═╝     ╚═╝
 *
 * SimplePM WebApp
 * A part of SimplePM programming contests management system.
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
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Выполняем различные проверки безопасности
 */

isset($_GET['id']) or Security::ThrowError("input");
$_GET['id'] = abs((int)$_GET['id']);

UserInfo::UserExists($_GET['id'])
    or Security::ThrowError("404");

Security::CheckAccessPermissionsForEdit($_GET['id'], true)
    or Security::ThrowError("403");

/*
 * Проверяем POST аргументы
 * на существование.
 */

Security::CheckPostDataIssetAndNotNull(
    array(
        "institution"
    )
) or Security::ThrowError("input");

/*
 * Проверяем правильность заполнения
 * POST-параметров в форме,  которая
 * перенаправила   пользователя   на
 * данный командный сервис.
 */

strlen_check_post_param("institution", 1, 255);

/*
 * Обновляем пользовательские данные
 */

// Формируем запрос на обновление информации
$query_str = "
    UPDATE
      `spm_users`
    SET
      `institution` = '" . $_POST['institution'] . "'
    WHERE
      `id` = '" . $_GET['id'] . "'
    LIMIT
      1
    ;
";

// Выполняем запрос на обновление информации
$database->query($query_str);

/*
 * РАЗДЕЛ ПРЕДОСТАВЛЕНИЯ ВОЗМОЖНОСТЕЙ ДЛЯ
 * ИЗМЕНЕНИЯ ТЕКУЩЕЙ ГРУППЫ ПОЛЬЗОВАТЕЛЯ.
 */

if (Security::CheckAccessPermissionsForEdit($_GET['id'], false))
{

    /*
     * Проверяем POST аргументы
     * на существование.
     */

    Security::CheckPostDataIssetAndNotNull(
        array(
            "groupid"
        )
    ) or Security::ThrowError("input");

    $_POST['groupid'] = abs((int)$_POST['groupid']);

    /*
     * Проверка  на  существование
     * указанной  пользовательской
     * группы для обеспечения безо
     * пасности работы системы.
     */

    // Формируем запрос на выборку из БД
    $query_str = "
        SELECT
          count(`id`)
        FROM
          `spm_users_groups`
        WHERE
          `id` = '" . $_POST['groupid'] . "'
        AND
          `teacherId` = '" . UserInfo::getUserInfo($_GET['id'])['teacherId'] . "'
        ;
    ";

    // Выполняем запрос и обрабатываем результат
    (int)($database->query($query_str)->fetch_array()[0]) > 0
        or Security::ThrowError("403");

    /*
     * Обновляем информацию о пользователе
     * в базе данных системы.
     */

    // Формируем запрос на обновление данных
    $query_str = "
        UPDATE
          `spm_users`
        SET
          `groupid` = '" . $_POST['groupid'] . "'
        WHERE
          `id` = '" . $_GET['id'] . "'
        LIMIT
          1
        ;
    ";

    // Выполняем запрос на обновление данных
    $database->query($query_str);

}

/*
 * Перенаправляем текущего пользователя
 * на необходимый нам  сервис  системы.
 */

// Перезаписываем заголовок
header(
    'location: ' . _SPM_ . 'index.php/users/profile/?id=' . $_GET['id'],
    true
);

// Завершаем работу экземпляра веб-приложения
exit();