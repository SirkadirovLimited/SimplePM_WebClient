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

trait UserChecker {

    public static function CheckUserExists() {

    	/*
    	 * Запрашиваем доступ к глобальным
    	 * переменным  для  дальнейшего их
    	 * использования.
    	 */

    	global $database;

        /*
         * Выполняем  проверки  лишь в том случае,
         * если текущий пользователь авторизирован
         * в данном веб-приложении.
         */

        if (UserInfo::IsAuthUser())
        {

            /*
             * Если пользователь удалён из базы данных,
             * принудительно выводим его из клиентской
             * части веб-приложения.
             */

            if (!UserInfo::UserExists(Security::getCurrentSession()["user_info"]->getUserId()))
                SessionDestroyer::DestroySession(_("Ваш аккаунт було видлено!"));

            /*
             * Если текущий пользователь забанен,
             * производим принудительный выход из
             * данного веб-приложения.
             */

            if ((int)(Security::getCurrentSession()["user_info"]->getUserInfo()["groupid"]) <= 0)
                SessionDestroyer::DestroySession(_("Ваш аккаунт ще не активовано Вашим куратором!"));

            /*
             * Устанавливаем дату и время последней
             * активности пользователя на сайте для
             * дальшейшего использования в собствен
             * ных нуждах системы SimplePM.
             */

            // Формируем запрос на обновление данных
            $query_str = "
            	UPDATE
            	  `spm_users`
            	SET
            	  `last_online` = CURRENT_TIMESTAMP
            	WHERE
            	  `id` = '" . Security::getCurrentSession()["user_info"]->getUserId() . "'
            	LIMIT
            	  1
            	;
            ";

			// Выполняем запрос к БД
			@$database->query($query_str);

        }

    }

}