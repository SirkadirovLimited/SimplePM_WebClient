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