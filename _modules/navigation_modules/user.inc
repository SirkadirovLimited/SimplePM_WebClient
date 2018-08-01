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
 * verification system for programming tasks "SimplePM".
 *
 * Copyright (C) 2016-2018 Yurij Kadirov
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * GNU Affero General Public License applied only to source code of
 * this program. More licensing information hosted on project's website.
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