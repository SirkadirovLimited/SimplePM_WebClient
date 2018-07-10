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
 * vefification system for programming tasks "SimplePM".
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
			  `type`,
			  `userCanExit`,
			  `judge`,
			  `problems_list`,
			  
			  `requiredRating`,
			  `citedScore`
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