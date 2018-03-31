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

class ClientWaiter {

    use Waiter;

    private $enabled_services_list;

    public function __construct()
    {

        global $_CONFIG;

        $services_list = $_CONFIG->getServicesConfig();

        foreach ($services_list as $item)
            if ($item["enabled"])
                $this->enabled_services_list[] = $item["path"];

    }

    /**
     * Функция возвращает название сервиса, который
     * должен быть отображён в дапнном случае.
     * @return string Полное наименование сервиса
     */

    public function GetRequestedServiceName() : string {

        /*
         * Определяем используемые глобальные переменные.
         */

        global $_CONFIG;

        /*
         * В зависимости от того, указан путь к
         * требуемому сервису или нет, выполняем
         * соответствующие действия.
         */

        if (isset($_SERVER['PATH_INFO']) && (int)strlen($_SERVER['PATH_INFO']) > 0) {

            /*
             * Вызываем специально созданную функцию
             * из трейта Waiter, которая приберёт за
             * недобросовестными    пользователями и
             * предоставит реальный  путь к сервису.
             */

            $req_service =  $this->GetClearServicePath($_SERVER['PATH_INFO']);

            /*
             * Возвращаем результат работы функции
             */

            return strlen($req_service) > 0
                ? $req_service
                : $_CONFIG->getWebappConfig()["site_default_service"];

        }
        else
        {

            /*
             * Если пользователь авторизован, указываем
             * отображаемый сервис по-умолчанию, иначе
             * отправляем пользователя на страницу входа
             * в систему. Поделом ему.
             */

            return (
            UserInfo::IsAuthUser()
                ? $_CONFIG->getWebappConfig()["site_default_service"]
                : "anonymous/" . $_CONFIG->getWebappConfig()["site_default_service_anonymous"]
            );

        }

    }

    /**
     * Функция передаёт выполнение скрипту, который
     * является реализацией указанного в аргументах
     * сервиса, после чего указывает успешность за-
     * пуска этого скрипта.
     * @param string $service_name Название сервиса
     * @return bool
     */

    public function RunService(string $service_name) : bool {

        /*
         * Если сервис существует, запускаем его
         * на выполнение, иначе просто ничего не
         * делаем.
         */

        if ($this->ServiceExists($service_name)) {

            /*
             * Запускаем буферизацию вывода
             */

            ob_start();

            /*
             * Производим включение файла исходного
             * кода сервиса.
             */

            include_once $this->GenerateServicePath($service_name);

            /*
             * Создаём новый объект типа LayoutManager
             * для форматирования вывода сервисов.
             */

            $layout_manager = new LayoutManager(__PAGE_LAYOUT__);

            /*
             * Производим вывод буферизированных
             * данных с указанным стилем.
             */

            $layout_manager->DisplayLayout(
                ob_get_clean(),
                __PAGE_TITLE__
            );

            /*
             * Указываем на то, что всё прошло хорошо
             */

            return true;

        }
        else
            return false;

    }

    /**
     * Функция возвращает ответ логического типа,
     * который указывает, существует ли указанный
     * сервис или нет.
     * @param string $service_name Название сервиса
     * @return bool
     */

    public function ServiceExists(string $service_name) : bool {

        /*
         * Производим различные проверки, в том числе:
         * - Соблюдение регулярного выражения
         * - Присутствие в списке включённых сервисов
         * - Существование файла сервиса
         */

        return preg_match
            (
            "/([A-Za-z-_])\w+/",
            $service_name
            ) && in_array
            (
            $service_name,
            $this->enabled_services_list,
            true
            ) && file_exists
            (
                $this->GenerateServicePath($service_name)
            );

    }

    /**
     * Функция возвращает относительный путь к php
     * файлу указанного в параметрах сервиса.
     * @param string $service_name Название сервиса
     * @return string
     */

    public function GenerateServicePath(string $service_name) : string {

        return _SPM_services_ . $service_name . ".php";

    }

    /**
     * Функция возвращает массив доступных
     * на данный момент сервисов.
     * @return array
     */

    public function getEnabledServicesList() : array
    {
        return $this->enabled_services_list;
    }

}