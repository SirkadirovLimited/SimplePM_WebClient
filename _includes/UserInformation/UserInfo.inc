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

class UserInfo {

    /**
     * С помощью этой функции можно узнать,
     * произведён ли вход в систему текущим
     * пользователем или нет.
     * @return bool
     */

    public static function IsAuthUser() : bool {

        return isset(Security::getCurrentSession()["user_info"]);

    }

    /**
     * Функция отвечает на самый заветный и сложный вопрос во всём мире.
     * @param int $userId Идентификатор пользователя
     * @return bool Существует ли указанный пользователь
     */

    public static function UserExists(int $userId) : bool
    {

        /*
         * Запрашиваем  доступ  к
         * глобальным переменным.
         */

        global $database;

        /*
         * Формируем запрос на выборку
         * данных из базы данных.
         */

        $query_str = "
            SELECT
              count(`id`)
            FROM
              `spm_users`
            WHERE
              `id` = '" . abs($userId) . "'
            ;
        ";

        /*
         * Отвечаем на вопрос как 42
         */

        return (int)($database->query($query_str)->fetch_array()[0]) > 0;

    }

    /**
     * @param int $groupId
     * @return string
     */

    public static function GetGroupName(int $groupId) : string
    {

        /*
         * Запрашиваем  доступ  к
         * глобальным переменным.
         */

        global $database;

        /*
         * Формируем запрос на выборку
         * данных из базы данных.
         */

        $query_str = "
            SELECT
              `name`
            FROM
              `spm_users_groups`
            WHERE
              `id` = '" . abs($groupId) . "'
            LIMIT
              1
            ;
        ";

        /*
         * Выполняем SQL запрос на выборку данных
         */

        $query = $database->query($query_str);

        /*
         * Возвращаем результат работы функции
         */

        if ($query->num_rows == 0)
            return _("Невідома група");
        else
            return $query->fetch_object()->name;

    }

    /**
     * @param int $userId Текущий пользователь
     * @return array Информация о пользователе
     */
    public static function getUserInfo(int $userId): array
    {

        /*
         * Запрашиваем  доступ  к
         * глобальным переменным.
         */

        global $database;

        /*
         * Формируем запрос на выборку
         * данных из базы данных.
         */

        $query_str = "
            SELECT
              `id`,
              `sessionId`,
              `last_online`,
              `firstname`,
              `secondname`,
              `thirdname`,
              `birthday_date`,
              `email`,
              `teacherId`,
              `permissions`,
              `institution`,
              `groupid`,
              `RatingBase`(`id`) AS rating,
              `RatingCount`(`id`, 0) AS rating_count,
              `associated_olymp`
            FROM
              `spm_users`
            WHERE
              `id` = '$userId'
            LIMIT
              1
            ;
        ";

        /*
         * Возвращаем ассоциативны массив,
         * содержащий информацию о текущем
         * пользователе.
         */

        return $database->query($query_str)->fetch_assoc();

    }

}