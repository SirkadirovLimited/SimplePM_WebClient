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