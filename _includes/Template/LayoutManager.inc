<?php

/*
 * ███████╗██╗███╗   ███╗██████╗ ██╗     ███████╗██████╗ ███╗   ███╗
 * ██╔════╝██║████╗ ████║██╔══██╗██║     ██╔════╝██╔══██╗████╗ ████║
 * ███████╗██║██╔████╔██║██████╔╝██║     █████╗  ██████╔╝██╔████╔██║
 * ╚════██║██║██║╚██╔╝██║██╔═══╝ ██║     ██╔══╝  ██╔═══╝ ██║╚██╔╝██║
 * ███████║██║██║ ╚═╝ ██║██║     ███████╗███████╗██║     ██║ ╚═╝ ██║
 * ╚══════╝╚═╝╚═╝     ╚═╝╚═╝     ╚══════╝╚══════╝╚═╝     ╚═╝     ╚═╝
 *
 * SimplePM WebApp
 * A part of SimplePM programming contests management system.
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