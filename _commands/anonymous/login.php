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
        "username",
        "password"
    )
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
      `username` = '" . $_POST['username'] . "'
    AND
      `banned` = '0'
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
 * Получаем   объект,  содержащий
 * необходимые данные о найденном
 * пользователе   и   присваиваем
 * ссылку   на   него  специально
 * созданной переменной.
 */

$user_obj = $query->fetch_object();
/*
 * Проверяем  введённый  пароль   на
 * соответствие с паролем найденного
 * пользователя.
 */

if (!password_verify($_POST['password'], $user_obj->password))
    Security::ThrowError(_("Пароль введено не вірно!"));

/*
 * Задаём  текущую  пользовательскую сессию.
 * В  связке  с  дополнительными  скриптами,
 * это  запретит  возможность одновременного
 * входа   в   один аккаунт   с   нескольких
 * устройств,  что  обезопасит  систему   от
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
 * текущем  пользователе,  так  как
 * вход был проделан ним успешно.
 */

Security::getCurrentSession()["user_info"] = new SessionUser($user_obj->id);