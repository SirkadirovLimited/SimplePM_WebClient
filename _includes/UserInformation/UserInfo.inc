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