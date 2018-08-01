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

/*
 * Трейт содержит  общие  для  всех Waiter-ов
 * функции  и  поля,   что  даёт  возможность
 * сократить количество кода в обоих классах.
 *
 * Почему  именно  трейт?  Да просто, надо же
 * как-то использовать весь функционал  столь
 * великого скриптового языка как PHP ;)
 */

trait Waiter {

    /**
     * Специально      созданная      функция
     * из трейта Waiter, которая приберает за
     * недобросовестными   пользователями   и
     * предоставляет реальный  путь к сервису
     * @param string $requested_service
     * @return string
     */

    public function GetClearServicePath(string &$requested_service) : string
    {

        /*
         * Если  строка  запроса  не  пустая,
         * очищаем её от возможных неприятных
         * моментов, иначе ничего не делаем.
         */

        if (strlen($requested_service) > 0)
        {

            /*
             * В случае, если псоледний символ
             * запроса - слеш, удаляем его.
             */

            if ($requested_service[strlen($requested_service) - 1] == "/")
                $requested_service = @str_lreplace("/", "", $requested_service);

            /*
             * В случае, если первый символ запроса - слеш,
             * удаляем его.
             */

            if (strlen($requested_service) > 0 && $requested_service[0] == "/")
                $requested_service = @substr($requested_service, 1);

            /*
             * Стандартизируем всё всё всё
             */

            $requested_service = strtolower($requested_service);

            /*
             * Если пользователь не авторизирован,
             * предоставляем ему доступ лишь к сервисам
             * обслуживания не авторизованых пользователей.
             */

            if (UserInfo::IsAuthUser())
				$requested_service = "authorized/" . $requested_service;
            else
                $requested_service = "anonymous/" . $requested_service;


        }

        /*
         * Возвращаем результат работы функции
         */

        return $requested_service;

    }

}