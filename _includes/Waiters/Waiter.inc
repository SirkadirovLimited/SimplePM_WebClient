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