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

trait SessionChecker {

    public static function CheckUserSession() {

        /*
         * Если    пользователь     авторизирован,
         * а   идентификаторы   сессий   в   БД  и
         * на   стороне   сервера   веб-приложения
         * не   совпадают,  удаляем   все   данные
         * этой сессии  и  уведомляем пользователя
         * о том, что его сессия была перехвачена.
         */

        if (UserInfo::IsAuthUser() && Security::getCurrentSession()["user_info"]->getUserInfo()["sessionId"] != session_id()) {

            // Удаляем все данные сессии
            session_destroy();

            // Информируем об этом пользователя
            Security::ThrowError(_("Одночасний вхід в один аккаунт з декількох пристроїв заборонений!"));

        }

    }

}