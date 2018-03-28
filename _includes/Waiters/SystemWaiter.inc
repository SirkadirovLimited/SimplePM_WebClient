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

class SystemWaiter {

    use Waiter;

    private $enabled_commands_list;

    public function __construct()
    {

        global $_CONFIG;

        $commands_list = $_CONFIG->getCommandsConfig();

        foreach ($commands_list as $item)
            if ($item["enabled"])
                $this->enabled_commands_list[] = $item["path"];

    }

    /**
     * Функция возвращает название коммандного сервиса,
     * который должен быть отображён в дапнном случае.
     * @return string Полное наименование комманды
     */

    public function GetRequestedCommandName() : string {

        /*
         * В зависимости от того, указан путь к
         * требуемому сервису или нет, выполняем
         * соответствующие действия.
         */

        if (isset($_GET['cmd']) && (int)strlen($_GET['cmd']) > 0) {

            /*
             * Вызываем специально созданную функцию
             * из трейта Waiter, которая приберёт за
             * недобросовестными    пользователями и
             * предоставит реальный  путь к сервису.
             */

            return $this->GetClearServicePath($_GET['cmd']);

        }
        else
        {

            return "";

        }

    }

    /**
     * Функция передаёт выполнение скрипту, который
     * является реализацией указанного в аргументах
     * коммандного сервиса.
     * @param string $command_name Название комманды
     * @return void Ничего интересного
     */

    public function RunCommand(string $command_name) : void {

        /*
         * Запрашиваем доступ к глобальным переменным
         */

        global $_CONFIG;

        /*
         * Выполняем необходимые действия
         * лишь  в  случае  существования
         * искомого коммандного сервиса.
         */

        if ($this->CommandExists($command_name)) {

            /*
             * Производим включение требуемого
             * файла исходного кода.
             */

            include_once $this->GenerateCommandPath($command_name);

            /*
             * Дабы избавиться от POST данных,
             * указываем, что необходимо пере-
             * адресовать   пользователя    на
             * главную страницу.
             *
             * Это будет выполнено  лишь в том
             * случае, если в скрипте не будет
             * определено     предпочтительное
             * действие данного характера.
             */

            header("location: " . _SPM_, true);

            /*
             * Завершаем работу скрипта для применения
             * указанных выше заголовков ответа.
             */

            exit;

        }

    }

    /**
     * Функция возвращает ответ логического типа,
     * который указывает, существует ли указанный
     * сервис или нет.
     * @param string $command_name Название комманды
     * @return bool
     */

    public function CommandExists(string $command_name) : bool {

        return preg_match
            (
                "/([A-Za-z-_])\w+/",
                $command_name
            ) && in_array
            (
                $command_name,
                $this->enabled_commands_list,
                true
            ) && file_exists
            (
                $this->GenerateCommandPath($command_name)
            );

    }

    /**
     * Функция возвращает относительный путь к php
     * файлу указанного в параметрах сервиса.
     * @param string $command_name Название комманды
     * @return string
     */

    public function GenerateCommandPath(string $command_name) : string {

        return _SPM_commands_ . $command_name . ".php";

    }

    /**
     * Функция возвращает массив доступных
     * на данный момент сервисов.
     * @return array
     */

    public function getEnabledCommandsList() : array
    {
        return $this->enabled_commands_list;
    }

}