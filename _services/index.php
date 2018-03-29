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

define("__PAGE_TITLE__", _("Головна сторінка"));
define("__PAGE_LAYOUT__", "default");

?>
<style>
    h3.welcome {
        text-align: center;
        margin-top: 60px;
        margin-bottom: 60px;
    }
</style>

<h3 class="welcome"><?=_("Вітаємо Вас на головній сторінці веб-додатку SimplePM!")?></h3>

<div class="row">

    <div class="col-md-3">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?=_("Архів задач")?></h5>
                <p class="card-text"><?=_("Розпочніть вирішення завдань з алгоритмічного та спортивного програмування зараз!")?></p>
                <a href="#" class="btn btn-primary"><?=_("Перейти в розділ")?></a>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?=_("Рейтинг користувачів")?></h5>
                <p class="card-text"><?=_("Перегляньте рейтингову таблицю користувачів системи та знайдіть у ній себе!")?></p>
                <a href="#" class="btn btn-primary"><?=_("Перейти в розділ")?></a>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?=_("Змагання")?></h5>
                <p class="card-text"><?=_("Беріть участь у змаганнях з алгоритмічного та спортивного програмування та отримуйте сертифікати!")?></p>
                <a href="#" class="btn btn-primary"><?=_("Перейти в розділ")?></a>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?=_("Про систему")?></h5>
                <p class="card-text"><?=_("Дізнавшись більше про цю систему, Ви отримаєте доступ до її безмежного функціоналу.")?></p>
                <a href="#" class="btn btn-primary"><?=_("Перейти в розділ")?></a>
            </div>
        </div>

    </div>

</div>