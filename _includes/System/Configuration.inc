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

class Configuration {

    // Конфигурация базы данных
    private $database_config;

    // Конфигурация веб-приложения
    private $webapp_config;

    /*
     * Список поддерживаемых системой
     * языков программирования.
     */

    private $compilers_config;

    // Список сервисов веб-приложения
    private $services_config;

    // Список коммандных сервисов веб-приложения
    private $commands_config;

    public function __construct() {

        /*
         * Производим чтение конфигурационных файлов,
         * после чего парсим их и записываем полученные
         * данные в соответствующие переменные.
         */

        $this->InitConfigVariable(
            $this->database_config,
            "database"
        );

        $this->InitConfigVariable(
            $this->webapp_config,
            "webapp"
        );

        $this->InitConfigVariable(
            $this->services_config,
            "services"
        );

        $this->InitConfigVariable(
            $this->commands_config,
            "commands"
        );

        /*
         * Производим дополнительные присвоения
         */

        if ($this->webapp_config["site_base_url"] == "HTTP_HOST")
            $this->webapp_config["site_base_url"] = "//" . $_SERVER['HTTP_HOST'] . "/";

    }

    /**
     * Функция занимается инициализацией переменных,
     * которые хранят конфигурации различных частей
     * данного веб-приложения.
     * @param array $config_var Ссылка на переменную
     * @param string $config_file_name Название файла конфигурации
     */

    private function InitConfigVariable(&$config_var, string $config_file_name) : void {

        $config_var = json_decode(
            (string)file_get_contents(
                _SPM_configuration_ . $config_file_name . ".json"
            ),
            true
        );

    }

    /*
     * Раздел объявления геттеров и сеттеров
     * для правтных переменных.
     */

    /* Для конфигурации сервисов */

    public function &getServicesConfig() : array {
        return $this->services_config;
    }

    /* Для конфигурации коммандных сервисов */

    public function &getCommandsConfig() : array {
        return $this->commands_config;
    }

    /* Для конфигурации веб-приложения */

    public function &getWebappConfig() : array {
        return $this->webapp_config;
    }

    /* Для коннфигурации подключения к базе данных */

    public function &getDatabaseConfig() : array {
        return $this->database_config;
    }

}