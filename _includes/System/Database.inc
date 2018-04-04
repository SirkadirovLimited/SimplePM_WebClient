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
 * Инициализируем соединение с базой данных
 * и получаем его дескриптор.
 */

$database = new mysqli(
    $_CONFIG->getDatabaseConfig()["hostname"],
    $_CONFIG->getDatabaseConfig()["username"],
    $_CONFIG->getDatabaseConfig()["password"],
    $_CONFIG->getDatabaseConfig()["basename"],
    $_CONFIG->getDatabaseConfig()["portname"]
);

/*
 * Производим проверку на наличие ошибок
 * при попытке подключения к удалённой
 * базе данных, и, в случае их нахождения,
 * выбрасываем исключение.
 */

if ($database->connect_errno)
    die("<h1>Oops, database connection error! Please, try again later!</h1>");

$database->set_charset("utf8");

?>