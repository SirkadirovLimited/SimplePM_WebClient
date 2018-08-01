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

        // Запрашиваем доступ к глобальным переменным.
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

        // Отвечаем на вопрос как 42
        return (int)($database->query($query_str)->fetch_array()[0]) > 0;

    }

    /**
     * @param int $groupId
     * @return string
     */

    public static function GetGroupName(int $groupId) : string
    {

        // Запрашиваем доступ к глобальным переменным
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

        // Выполняем SQL запрос на выборку данных
        $query = $database->query($query_str);

        // Возвращаем результат работы функции
        return $query->num_rows == 0 ? _("Невідома група") : $query->fetch_object()->name;

    }

    /**
     * @param int $userId Текущий пользователь
     * @return array Информация о пользователе
     */
    public static function getUserInfo(int $userId): array
    {

        // Запрашиваем доступ к глобальным переменным
        global $database;

        /*
         * Формируем запрос на выборку
         * данных из базы данных.
         */

        $query_str = "
            SELECT
              `id`,
              `sessionId`,
              
              IF(ROUND(time_to_sec(TIMEDIFF(NOW(), `last_online`)) / 60) <= 10, TRUE, FALSE) AS `is_online`,
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