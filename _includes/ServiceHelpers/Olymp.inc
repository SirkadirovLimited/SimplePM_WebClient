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
     * Данная статическая функция проверяет,
     * разрешён ли выход пользователям, кото
     * рые принимают участие в указанном со-
     * ревновании из него  (досрочный выход)
     * или нет, и возвращает соответственный
     * результат своей работы.
     * @param int $olymp_id Идентификатор соревнования
     * @return bool Возможен или не возможен
     */

    public static function CheckUserCanExit(int $olymp_id) : bool
    {

        /*
         * Запрашиваем доступ к глобальным переменным.
         */

        global $database;

        /*
         * Если идентификатор олимпиады
         * меньше или равен нулю, то на
         * самом деле никакого соревно-
         * вания не существует!
         */

        if ($olymp_id <= 0)
            return true;

        /*
         * Формируем запрос на выборку
         * информации   о  возможности
         * выходить из соревнования из
         * базы данных системы.
         */

        $query_str = sprintf("
            SELECT
              `userCanExit`
            FROM
              `spm_olympiads`
            WHERE
              `id` = '%s'
            LIMIT
              1
            ;
        ", $olymp_id);

        /*
         * Выполняем запрос на выборку
         * данных из базы данных.
         */

        $query = $database->query($query_str);

        /*
         * Если необходимого нам соревнования не найдено,
         * значит выйти из него мы не можем!
         */

        if ($query->num_rows <= 0)
            return true;

        /*
         * Производим выборку данных
         * из базы данных системы и
         * проделываем соответствен-
         * ные проверки, после чего
         * возвращаем результат рабо
         * ты данного метода.
         */

        return (int)($query->fetch_array()[0]) > 0;

    }

    public static function ExitOlymp(int $user_id = 0) : void
    {

        // Мелкое упрощение жизни разработчику
        $user_id > 0
            or $user_id = Security::getCurrentSession()['user_info']->getUserId();

        /*
         * Запрашиваем доступ к глобальным переменным.
         */

        global $database;

        /*
         * Устанавливаем привязанность
         * к нулевому соревнованию.
         */

        // Формируем запрос на обновление данных в БД
        $query_str = sprintf("
            UPDATE
              `spm_users`
            SET
              `associated_olymp` = '0'
            WHERE
              `id` = '%s'
            LIMIT
              1
            ;
        ", $user_id);

        // Выполняем запрос на обновление данных в БД
        $database->query($query_str);

    }

    public static function OlympiadExistsNow(int $olymp_id) : bool
    {

        /*
         * Запрашиваем доступ к глобальным переменным.
         */

        global $database;

        /*
         * Формируем запрос на
         * выборку данных из
         * базы данных SimplePM.
         */

        $query_str = sprintf("
            SELECT
              count(`id`)
            FROM
              `spm_olympiads`
            WHERE
              `id` = '%s'
            AND
              `startTime` <= NOW()
            AND
              `endTime` >= NOW()
            ;
        ", $olymp_id);

        /*
         * Выполняем запрос на выборку
         * данных из базы данных системы
         * и путём не сложных логических
         * размышлений мы получаем ответ
         * на главный вопрос, который
         * становится перед этим методом,
         * после чего возвращаем полученный
         * результат обыкновенным return-ом.
         */

        return (int)($database->query($query_str)->fetch_array()[0]) > 0;

    }

    public static function IsAssociatedWithOlymp(int $user_id = 0) : bool
    {

        // Мелкое упрощение жизни разработчику
        $user_id > 0
            or $user_id = Security::getCurrentSession()['user_info']->getUserId();

        /*
         * Получаем необходимую информацию
         * и возвращаем результат работы
         * функции.
         */

        return @(int)(UserInfo::getUserInfo($user_id)['associated_olymp']) > 0;

    }

    public static function GetOlympiadInfo(int $olymp_id)
	{

		/*
         * Запрашиваем доступ к глобальным переменным.
         */

		global $database;

		/*
		 * Формируем запрос на выборку
		 * данных о соревновании из БД
		 */

		$query_str = sprintf("
			SELECT
			  `id`,
			  `name`,
			  `description`,
			  `startTime`,
			  `endTime`,
			  `teacherId`,
			  `visibility`,
			  `userCanExit`,
			  `judge`,
			  `problems_list`
			FROM
			  `spm_olympiads`
			WHERE
			  `id` = '%s'
			LIMIT
			  1
			;
		", $olymp_id);

		/*
		 * Выполняем запрос на выборку
		 * данных из базы данных.
		 */

		$query = $database->query($query_str);

		/*
		 * Если необходимого соревнования
		 * не найдено - возвращаем null.
		 */

		if ($query->num_rows <= 0)
			return null;

		/*
		 * Производим выборку данных из
		 * базы данных системы и возвра
		 * щаем  результаты  выполнения
		 * функции с помощью return-а.
		 */

		return $query->fetch_assoc();

	}

    /**
     * Функция определяет, содержится ли указанная
     * задача в списке задач, доступных для решения
     * при проведении указанного соревнования.
     *
     * Если указанная задача не найдена в указанном
     * списке, то мы производим принудительную пере
     * адресацию пользователя на страницу описания
     * возникшего исключения.
     *
     * Если указанная задача найдена, то  работа
     * родительского скрипта продолжается.
     *
     * @param int $olymp_id Идентификатор соревнования
     * @param int $problem_id Идентификатор задачи
     * @return void
     */

    public static function CheckProblemInList(int $olymp_id, int $problem_id) : void
    {

        /*
         * Запрашиваем доступ к глобальным переменным.
         */

        global $database;

        /*
         * Проверяем, проходит ли в данный момент
         * указанное соревнование или нет.
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
                  (
                  `problems_list` LIKE '" . $problem_id . ",%'
                OR
                  `problems_list` LIKE '%," . $problem_id . ",%'
                OR
                  `problems_list` LIKE '%," . $problem_id . "'
                  )
                ;
            ";

            // Проверяем результат проверки и выполняем необходимые действия
            if ((int)($database->query($query_str)->fetch_array()[0]) != 1)
                Security::ThrowError(_("Вказане завдання не присутнє у поточному змаганні!"));

        }

    }

}