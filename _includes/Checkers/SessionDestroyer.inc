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

trait SessionDestroyer {

    /**
     * @param string $errorId
     * @return void
     */

    public static function DestroySession(string $errorId) : void
    {

        // Удаляем все данные сессии
        session_destroy();

        // Информируем об этом пользователя
        Security::ThrowError($errorId);

    }

}