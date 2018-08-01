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

/**
 * Функция производит поиск и замену последнего
 * вхождения указанной подстроки в указанную строку
 * на указанную подстроку.
 * @param string $search что ищем
 * @param string $replace на что хотим заменить
 * @param string $subject над чем выполняем операцию
 * @return string результат выполнения операции
 */

function str_lreplace(string $search, string $replace, string $subject) : string
{

    /*
     * Производим поиск позиции последнего
     * вхождения указанной подстроки в указанную
     * подстроку.
     */

    $pos = strrpos($subject, $search);

    /*
     * Производим действия лишь в том случае,
     * если искомая подстрока найдена в указанной
     * в качестве аргумента строке.
     */

    if($pos !== false)
    {

        /*
         * Заменяем последнее вхождение подстроки
         * в указанную строку на указанную строку
         */

        $subject = substr_replace($subject, $replace, $pos, strlen($search));

    }

    /*
     * Возвращаем результат віполнения операции
     */

    return $subject;

}

/**
 * Функция занимается проверкой длины
 * единого POST параметра с указанным
 * именем.
 * @param string $post_param Имя POST параметра
 * @param int $min_length Минимальная длина
 * @param int $max_length Максимальная длина
 */

function strlen_check_post_param(string $post_param, int $min_length, int $max_length) : void
{

    (strlen($_POST[$post_param]) >= $min_length && strlen($_POST[$post_param]) <= $max_length)
    or Security::ThrowError("input");

}

?>