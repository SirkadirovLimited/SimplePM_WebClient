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