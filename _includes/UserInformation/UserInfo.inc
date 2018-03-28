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
     * @param int $userId
     * @return array
     */
    public static function getUserInfo(int $userId): array
    {

        global $database;

        $query_str = "
            SELECT
              `id`,
              `sessionId`,
              `lastOnline`,
              `username`,
              `firstname`,
              `secondname`,
              `thirdname`,
              `bdate`,
              `email`,
              `teacherId`,
              `permissions`,
              `country`,
              `city`,
              `school`,
              `phone`,
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

        return $database->query($query_str)->fetch_assoc();

    }

}