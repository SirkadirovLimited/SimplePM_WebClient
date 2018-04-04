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

class Configuration {

    /*
     * Конфигурация базы данных
     */

    private $database_config;

    /*
     * Конфигурация веб-приложения
     */

    private $webapp_config;

    /*
     * Список поддерживаемых системой
     * языков программирования.
     */

    private $compilers_config;

    /*
     * Список сервисов веб-приложения
     */

    private $services_config;

    /*
     * Список коммандных сервисов веб-приложения
     */

    private $commands_config;

    public function __construct() {

        /*
         * Производим  чтение конфигурационных  файлов,
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
            $this->compilers_config,
            "compilers"
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

    public function setServicesConfig(string $config_file_name) : void {
        $this->InitConfigVariable($this->services_config, $config_file_name);
    }

    /* Для конфигурации коммандных сервисов */

    public function &getCommandsConfig() : array {
        return $this->commands_config;
    }

    public function setCommandsConfig(string $config_file_name) : void {
        $this->InitConfigVariable($this->commands_config, $config_file_name);
    }

    /* Для конфигурации веб-приложения */

    public function &getWebappConfig() : array {
        return $this->webapp_config;
    }

    /* Для коннфигурации подключения к базе данных */

    public function &getDatabaseConfig() : array {
        return $this->database_config;
    }

    /* Для коннфигурации поддерживаемых компиляторов */

    public function &getCompilersConfig() : array {
        return $this->compilers_config;
    }

}