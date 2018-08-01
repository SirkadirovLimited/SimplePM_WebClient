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

// Запрашиваем доступ к глобальным переменным
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

// Проверяем POST аргументы на существование
Security::CheckPostDataIssetAndNotNull(
    array(
        "institution"
    )
) or Security::ThrowError("input");

/*
 * Проверяем правильность заполнения
 * POST-параметров в форме, которая
 * перенаправила пользователя на
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

    // Проверяем POST аргументы на существование
    Security::CheckPostDataIssetAndNotNull(
        array(
            "groupid"
        )
    ) or Security::ThrowError("input");

    $_POST['groupid'] = abs((int)$_POST['groupid']);

    /*
     * Проверка на существование
     * указанной пользовательской
     * группы для обеспечения
     * безопасности работы системы.
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
 * на необходимый нам сервис системы.
 */

// Перезаписываем заголовок
header(
    'location: ' . _SPM_ . 'index.php/users/profile/?id=' . $_GET['id'],
    true
);

// Завершаем работу экземпляра веб-приложения
exit();