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

/*
 * Класс описывает методы для работы
 * с соревновательным режимом в веб-
 * приложении системы проверки реше-
 * ний SimplePM, а также методы, нео
 * бходимые  для  интеграции данного
 * режима работы в данное веб-прило-
 * жение.
 */

class Olymp
{

    /**
     * Функция определяет,  содержится ли указанная
     * задача в списке задач, доступных для решения
     * при проведении указанного соревнования.
     *
     * Если указанная задача не найдена в указанном
     * списке, то мы производим принудительную пере
     * адресацию пользователя на страницу описания
     * возникшего исключения.
     *
     * Если  указанная  задача  найдена, то  работа
     * родительского скрипта продолжается.
     *
     * @param int $olymp_id Идентификатор соревнования
     * @param int $problem_id Идентификатор задачи
     * @return void
     */

    public static function CheckProblemInList(int $olymp_id, int $problem_id) : void
    {

        /*
         * Проверяем, проходит ли в данный
         * момент  указанное  соревнование
         * или нет.
         */

        if ($olymp_id > 0)
        {

            // Формируем запрос на выборку из БД
            $query_str = "
                SELECT
                  count(`id`)
                FROM
                  `spm_olympiads`
                WHERE
                  `id` = '" . $olymp_id . "'
                AND
                  `problems_list` LIKE '%" . $problem_id . ",%'
                ;
            ";

            // Проверяем результат проверки и выполняем необходимые действия
            if ((int)($database->query($query_str)->fetch_array()[0]) != 1)
                Security::ThrowError(_("Вказане завдання не присутнє у поточному змаганні!"));

        }

    }

}