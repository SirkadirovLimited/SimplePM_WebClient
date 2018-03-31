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
             * Если пользователь не авторизирован,
             * предоставляем ему доступ лишь к сервисам
             * обслуживания не авторизованых пользователей.
             */

            if (!UserInfo::IsAuthUser())
                $requested_service = "anonymous/" . $requested_service;

        }

        /*
         * Возвращаем результат работы функции
         */

        return $requested_service;

    }

}