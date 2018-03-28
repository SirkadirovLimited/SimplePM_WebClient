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

abstract class PERMISSION {

    const ANONYMOUS = 0;

    const STUDENT = 2;

    const TEACHER = 4;
    const TEACHER_OLYMPIAD = 8;
    const TEACHER_MANAGE_PROBLEMS = 16;

    const ADMINISTRATOR = 256;

}

?>