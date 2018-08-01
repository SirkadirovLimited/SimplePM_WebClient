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
        "firstname",
        "secondname",
        "thirdname",

        "birthday_date"
    )
) or Security::ThrowError("input");

/*
 * Проверяем правильность заполнения
 * POST-параметров в форме,  которая
 * перенаправила   пользователя   на
 * данный командный сервис.
 */

strlen_check_post_param("firstname", 1, 255);
strlen_check_post_param("secondname", 1, 255);
strlen_check_post_param("thirdname", 1, 255);

strlen_check_post_param("birthday_date", 10, 10);

/*
 * Обновляем информацию о пользователе
 * в  базе  данных  автоматизированной
 * системы  проверки  решений задач по
 * программированию "SimplePM".
 */

// Формируем запрос на обновление данных
$query_str = sprintf(
    "
        UPDATE
          `spm_users`
        SET
          `firstname` = '%s',
          `secondname` = '%s',
          `thirdname` = '%s',
          
          `birthday_date` = '%s'
        WHERE
          `id` = '%d'
        LIMIT
          1
        ;
    ",

    $_POST['firstname'],
    $_POST['secondname'],
    $_POST['thirdname'],

    $_POST['birthday_date'],

    $_GET['id']
);

// Выполяем запрос на обновление данных
$database->query($query_str);

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