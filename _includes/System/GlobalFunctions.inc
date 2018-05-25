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