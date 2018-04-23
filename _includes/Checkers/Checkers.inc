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

// Файл, содержащий рецепт по удалению сессии
include _SPM_includes_ . "Checkers/SessionDestroyer.inc";

// Файл, содержащий пользовательский чекер
include _SPM_includes_ . "Checkers/UserChecker.inc";

// Файл, содержащий чекер пользовательской сессии
include _SPM_includes_ . "Checkers/SessionChecker.inc";

// Файл, содержащий чекер связанного соревнования
include _SPM_includes_ . "Checkers/OlympiadChecker.inc";

class Checkers {

    use SessionDestroyer;

    use UserChecker;
    use SessionChecker;

    use OlympiadChecker;

}