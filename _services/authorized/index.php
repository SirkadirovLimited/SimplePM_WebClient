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

    div.row div.col-md-3 div.card {
        margin: 10px;
    }
    div.row div.col-md-3 div.card:hover {
        transform: scale(1.01, 1.01);
        border-color: #343a40;
    }

    #simplepm-name:hover {
        background-color: #343a40;
        color: #ffffff;
    }
</style>

<h3 class="welcome">
    <?=sprintf(_("Вітаємо Вас на головній сторінці веб-додатку %s!"), "<span id=\"simplepm-name\">SimplePM</span>")?>
</h3>

