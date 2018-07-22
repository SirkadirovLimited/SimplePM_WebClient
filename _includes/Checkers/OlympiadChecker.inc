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
             *
             * Существует - значит проводится
             * в данный момент времени.
             */

            if (!Olymp::OlympiadExistsNow($associated_olymp)){

                /*
                 * Производим принудительный выход
                 * из участия в соревновании.
                 */

                Olymp::ExitOlymp();

            }

        }

    }

}