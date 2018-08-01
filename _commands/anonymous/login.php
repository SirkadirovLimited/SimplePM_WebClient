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
 * Запрашиваем  доступ  к
 * глобальным переменным.
 */

global $database;

/*
 * Производим проверку на существование
 * и не пустоту требуемых полей запроса
 * на вход в систему.
 */

Security::CheckPostDataIssetAndNotNull(
    array(
        "email",
        "password"
    )
) or Security::ThrowError("input");

// Проверяем e-mail на правильность
filter_var(
	$_POST['email'],
	FILTER_VALIDATE_EMAIL
) or Security::ThrowError("input");

/*
 * Производим выборку найденных по
 * указанному логину пользователей
 * (точнее единственного).
 */

// Создаём шаблон запроса
$query_str = "
    SELECT
      `id`,
      `password`
    FROM
      `spm_users`
    WHERE
      `email` = '" . $_POST['email'] . "'
    LIMIT
      1
    ;
";

// Выполняем запрос по шаблону
$query = $database->query($query_str);

/*
 * Проверяем искомого пользователя
 * на существование по его логину.
 */

if ($query->num_rows == 0)
    Security::ThrowError(_("Користувача з вказаним логіном не знайдено!"));

/*
 * Получаем объект, содержащий
 * необходимые данные о найденном
 * пользователе и присваиваем
 * ссылку на него  специально
 * созданной переменной.
 */

$user_obj = $query->fetch_object();
/*
 * Проверяем введённый пароль на
 * соответствие с паролем найденного
 * пользователя.
 */

if (!password_verify($_POST['password'], $user_obj->password))
    Security::ThrowError(_("Пароль введено не вірно!"));

/*
 * Задаём текущую  пользовательскую сессию.
 * В связке с дополнительными скриптами,
 * это запретит возможность одновременного
 * входа в один аккаунт с нескольких
 * устройств, что обезопасит систему от
 * попыток атак на неё.
 *
 * Это ограничение не касается пользователей,
 * которые используют оффициальные приложения
 * SimplePM на своих устройствах.
 */

// Формируем запрос на внесение изменений
$query_str = "
    UPDATE
      `spm_users`
    SET
      `sessionId` = '" . $database->real_escape_string(session_id()) . "'
    WHERE
      `id` = '" . $user_obj->id . "'
    LIMIT
      1
    ;
";

// Выполняем запрос на внесение изменений
$database->query($query_str);

/*
 * Записываем в сессию информацию о
 * текущем пользователе, так как
 * вход был проделан ним успешно.
 */

Security::getCurrentSession()["user_info"] = new SessionUser($user_obj->id);