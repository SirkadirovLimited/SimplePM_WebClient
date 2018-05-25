<?php

/*
 * ███████╗██╗███╗   ███╗██████╗ ██╗     ███████╗██████╗ ███╗   ███╗
 * ██╔════╝██║████╗ ████║██╔══██╗██║     ██╔════╝██╔══██╗████╗ ████║
 * ███████╗██║██╔████╔██║██████╔╝██║     █████╗  ██████╔╝██╔████╔██║
 * ╚════██║██║██║╚██╔╝██║██╔═══╝ ██║     ██╔══╝  ██╔═══╝ ██║╚██╔╝██║
 * ███████║██║██║ ╚═╝ ██║██║     ███████╗███████╗██║     ██║ ╚═╝ ██║
 * ╚══════╝╚═╝╚═╝     ╚═╝╚═╝     ╚══════╝╚══════╝╚═╝     ╚═╝     ╚═╝
 *
 * SimplePM WebApp
 * A part of SimplePM programming contests management system.
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

$associated_olymp = (int)(Security::getCurrentSession()['user_info']->getUserInfo()['associated_olymp']);
$is_olymp_mode = $associated_olymp > 0;

?>

<li class="nav-item dropdown">

    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
        <?=_("Привіт")?>, <?=Security::getCurrentSession()["user_info"]->getUserInfo()["firstname"]?>
    </a>

    <div class="dropdown-menu">

        <?php if (!$is_olymp_mode): ?>

            <a
                class="dropdown-item"
                href="<?=_SPM_?>index.php/users/profile/"
            ><?=_("Моя сторінка")?></a>

            <a
                class="dropdown-item"
                href="<?=_SPM_?>index.php/users/edit/"
            ><?=_("Редагувати сторінку")?></a>

            <div class="dropdown-divider"></div>

            <a
                class="dropdown-item disabled"
            ><?=_("Про систему")?></a>

            <div class="dropdown-divider"></div>

        <?php endif; ?>

        <?php if ($is_olymp_mode && Olymp::CheckUserCanExit($associated_olymp)): ?>

            <a
                class="dropdown-item"
                href="<?=_SPM_?>index.php?cmd=olympiads/exit"
            ><?=_("Вийти зі змагання")?></a>

        <?php endif; ?>

        <a
            class="dropdown-item"
            href="<?=_SPM_?>index.php?cmd=logout"
        ><?=_("Вийти з системи")?></a>

    </div>

</li>