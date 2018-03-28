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

?>