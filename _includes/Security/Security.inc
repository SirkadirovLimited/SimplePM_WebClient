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

class Security {

    private $mysqli_connection;

    /**
     * Security constructor.
     * @param mysqli $mysqli_connection
     */

    public function __construct(mysqli $mysqli_connection)
    {

        $this->mysqli_connection = $mysqli_connection;

    }

    /**
     * @param string $param
     * @return string
     */

    public function ClearVisibleParam(string $param) : string
    {

        return htmlspecialchars(
            strip_tags(
                trim(
                    $param
                )
            )
        );

    }

    /**
     * @return void
     */

    public function ClearGET() : void
    {

        foreach ($_GET as &$param) {

            $param = $this->ClearVisibleParam($param);

        }

    }

    /**
     * @return void
     */

    public function ClearPOST() : void
    {

        foreach ($_POST as &$param) {

            $param = mysqli_real_escape_string(
                $this->mysqli_connection,
                trim(
                    $param
                )
            );

        }

    }

    /**
     * @return mixed
     */

    public static function &getCurrentSession()
    {

        global $_CONFIG;

        return $_SESSION[$_CONFIG->getWebappConfig()["site_session_id"]];

    }

    /**
     * @param int $user_permissions
     * @param int $requested_permission
     * @param bool $die_if_access_denied
     * @return bool
     */

    public static function CheckAccessPermissions(int $user_permissions, int $requested_permission, bool $die_if_access_denied = false) : bool
    {

        $result = $user_permissions & $requested_permission;

        if (!$die_if_access_denied || $result)
            return $result;
        else {

            self::ThrowError("403");

        }

    }

    /**
     * @param string $errorId
     * @return void
     */

    public static function ThrowError(string $errorId) : void
    {

        switch (strtolower($errorId))
        {

            case "403":
                $error_message = "403 Forbidden: You don't have access to this page.";
                break;
            case "404":
                $error_message = "404 File Not Found: Requested service or page is not found on this server.";
                break;
            case "db":
                $error_message = "Database error: An error catched while trying to connect to database!";
                break;
            case "input":
                $error_message = "Input error: Something wrong with data you entered!";
                break;
            default:
                $error_message = (string)$errorId;
                break;

        }

        exit(
            '<h1>' .
            $error_message .
            '</h1>' .
            '<meta http-equiv="refresh" content="5;URL=' . _SPM_ . '" />'
        );

    }

    /**
     * @param array $post_variables
     * @return bool
     */

    public static function CheckPostDataIssetAndNotNull(array $post_variables) : bool
    {

        // Обнуляем счётчик существующих ключей
        $success_count = 0;

        /*
         * В цикле вычисляем количество
         * существующих  переменных  из
         * переданного нам списка.
         */

        foreach ($post_variables as $post_variable) {

            $success_count += (int)(
                isset($_POST[$post_variable]) &&
                strlen($_POST[$post_variable]) > 0
            );

        }

        /*
         * Проверяем, все ли переменные
         * существуют или нет и выводим
         * соответствующий результат.
         */

        return $success_count >= count($post_variables);

    }

}