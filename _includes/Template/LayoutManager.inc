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

class LayoutManager {

    /*
     * Название стиля отображения
     */

    private $layout_name;

    public function __construct(string $layout_name)
    {

        /*
         * Присваиваем локальной переменной
         * название требуемого стиля.
         */

        $this->layout_name = $layout_name;

        /*
         * Проверяем наличие указанного
         * стиля   отображения  по  его
         * названию и местонахождению.
         */

        if (!self::CheckLayoutExists($this->layout_name)) {

            /*
             * Запрашиваем доступ к требуемым
             * глобальным переменным.
             */

            global $_CONFIG;

            /*
             * Если требуемый стиль не найден,
             * используем стандартный.
             */

            $this->layout_name = $_CONFIG->getWebappConfig()["default_layout_name"];

        }

    }

    /**
     * Функция отображает указанный контент с указанным стилем вывода.
     * @param string $content Контент отображаемой страницы
     * @param string $title Название страницы
     * @param bool $exit_on_print Сигнализирует о том, что после отображения необходимо выйти
     */

    public function DisplayLayout(string $content, string $title = "SimplePM", bool $exit_on_print = false) : void
    {

        /*
         * Запускаем буферизацию вывода
         */

        ob_start();

        /*
         * Производим включение файла стиля отображения
         */

        include self::GetLayoutPath($this->layout_name);

        /*
         * Получаем перехваченный вывод скрипта
         */

        $layout = ob_get_clean();

        /*
         * Производим замену временных переменных в стиле
         * на их постоянные значения.
         */

        $layout = str_replace("$(title)", $title, $layout);
        $layout = str_replace("$(content)", $content, $layout);

        /*
         * Производим  запись данных в
         * стандартный выходной поток.
         */

        print $layout;

        /*
         * В случае крайней необходимости
         * завершаем выполнение скриптов.
         */

        if ($exit_on_print)
            exit;

    }

    /**
     * Функция определяет, существует ли
     * запрашиваемый  стиль  отображения
     * или нет.
     * @param string $layout_name Имя стиля отображения
     * @return bool Результат проверки
     */

    public static function CheckLayoutExists(string $layout_name) : bool
    {
        return file_exists(
            self::GetLayoutPath(
                $layout_name
            )
        );
    }

    /**
     * Возвращает возможный путь к файлу
     * указанного стиля отображения.
     * @param string $layout_name Имя стиля отображения
     * @return string Возможный путь к файлу
     */

    public static function GetLayoutPath(string $layout_name) : string
    {
        return _SPM_template_ . "Layouts/" . $layout_name . ".inc";
    }

    /**
     * Возвращает название установленного
     * стиля отображения.
     * @return string Название стиля отображения
     */

    public function getLayoutName() : string
    {
        return $this->layout_name;
    }

}