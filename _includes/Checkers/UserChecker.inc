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

trait UserChecker {

    public static function CheckUserExists() {

    	/*
    	 * Запрашиваем доступ к глобальным
    	 * переменным для дальнейшего их
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
                SessionDestroyer::DestroySession(_("Ваш аккаунт було видалено!"));

            /*
             * Если текущий пользователь забанен,
             * производим принудительный выход из
             * данного веб-приложения.
             */

            if ((int)(Security::getCurrentSession()["user_info"]->getUserInfo()["groupid"]) <= 0)
                SessionDestroyer::DestroySession(
                    _("Ваш аккаунт ще не активовано або було заблоковано! За подробицями зверніться до Вашого куратора.")
                );

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