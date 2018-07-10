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
 * vefification system for programming tasks "SimplePM".
 *
 * Copyright 2018 Yurij Kadirov
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Visit website for more details: https://spm.sirkadirov.com/
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
                <a href="<?=_SPM_?>index.php/problems/archive" class="btn btn-primary"><?=_("Перейти в розділ")?></a>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?=_("Рейтинг користувачів")?></h5>
                <p class="card-text"><?=_("Перегляньте рейтингову таблицю користувачів системи та знайдіть у ній себе!")?></p>
                <a href="<?=_SPM_?>index.php/problems/rating" class="btn btn-primary"><?=_("Перейти в розділ")?></a>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?=_("Змагання")?></h5>
                <p class="card-text"><?=_("Беріть участь у змаганнях з алгоритмічного та спортивного програмування та отримуйте сертифікати!")?></p>
                <a href="<?=_SPM_?>index.php/olympiads/join" class="btn btn-primary"><?=_("Перейти в розділ")?></a>
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