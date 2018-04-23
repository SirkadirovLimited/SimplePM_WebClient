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

trait OlympiadChecker {

    public static function CheckOlympiadExists() {

        /*
         * Если пользователь авторизирован,
         * а соревнование с указанным идент
         * ификатором не существует,  то мы
         * осуществляем принудительный выхо
         * д пользователя  из  указанного в
         * его профиле соревнования.
         */

        if (UserInfo::IsAuthUser() && Olymp::IsAssociatedWithOlymp())
        {

            /*
             * Получаем уникальный идентификатор
             * ассоциированного соревнования.
             */

            $associated_olymp = Security::getCurrentSession()['user_info']->getUserInfo()['associated_olymp'];

            /*
             * Если соревнование с полученным
             * нами ранее  идентификатором не
             * существует, проделываем необхо
             * димые действия.
             */

            if (!Olymp::OlympiadExists($associated_olymp)){

                /*
                 * Производим принудительный выход
                 * из участия в соревновании.
                 */

                Olymp::ExitOlymp();

            }

        }

    }

}