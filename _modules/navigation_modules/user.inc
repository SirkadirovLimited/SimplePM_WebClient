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