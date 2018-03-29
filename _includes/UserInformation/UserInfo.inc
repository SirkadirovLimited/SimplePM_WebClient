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
              `username`,
              `firstname`,
              `secondname`,
              `thirdname`,
              `birthday_date`,
              `email`,
              `teacherId`,
              `permissions`,
              `country`,
              `city`,
              `school`,
              `groupid`,
              `banned`
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