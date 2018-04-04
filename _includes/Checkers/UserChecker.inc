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

trait UserChecker {

    public static function CheckUserExists() {

        /*
         * Выполняем  проверки  лишь в том случае,
         * если текущий пользователь авторизирован
         * в данном веб-приложении.
         */

        if (UserInfo::IsAuthUser())
        {

            /*
             * Если пользователь удалён из базы данных,
             * принудительно выводим его из клиентской
             * части веб-приложения.
             */

            if (!UserInfo::UserExists(Security::getCurrentSession()["user_info"]->getUserId()))
                SessionDestroyer::DestroySession(_("Ваш аккаунт було видлено!"));

            /*
             * Если текущий пользователь забанен,
             * производим принудительный выход из
             * данного веб-приложения.
             */

            if ((bool)Security::getCurrentSession()["user_info"]->getUserInfo()["banned"])
                SessionDestroyer::DestroySession(_("Ваш аккаунт було заблоковано!"));

        }

    }

}