<?php

/*
 * Copyright (C) 2018, Yurij Kadirov.
 * All rights are reserved.
 * Licensed under Apache License 2.0 with additional restrictions.
 *
 * @Author: Yurij Kadirov
 * @Website: https://sirkadirov.com/
 * @Email: admin@sirkadirov.com
 * @Repo: https://github.com/SirkadirovTeam/SimplePM_Server
 */

class Security {

    private $mysqli_connection;

    public function __construct(mysqli $mysqli_connection)
    {

        $this->mysqli_connection = $mysqli_connection;

    }

    public function ClearVisibleParam(string $param) : string {

        return htmlspecialchars(
            strip_tags(
                trim(
                    $param
                )
            )
        );

    }

    public function ClearGET() : void {

        foreach ($_GET as &$param) {

            $param = $this->ClearVisibleParam($param);

        }

    }

    public function ClearPOST() : void {

        foreach ($_POST as &$param) {

            $param = mysqli_real_escape_string(
                $this->mysqli_connection,
                strip_tags(
                    trim(
                        $param
                    )
                )
            );

        }

    }

    public static function &getCurrentSession() {

        global $_CONFIG;

        return $_SESSION[$_CONFIG->getWebappConfig()["site_session_id"]];

    }

    public function CheckAccessPermissions(int $user_permissions, int $requested_permission, bool $die_if_access_denied = false) : bool {

        $result = $user_permissions & $requested_permission;

        if (!$die_if_access_denied || $result)
            return $result;
        else {

            header("HTTP/1.0 403 Forbidden", true, 403);
            print("<h1>403 Forbidden: You don't have access to this page.</h1>");
            exit;

        }

    }

}

?>