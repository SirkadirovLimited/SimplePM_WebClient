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

define("__PAGE_TITLE__", _("Профіль користвувача"));
define("__PAGE_LAYOUT__", "default");

/*
 * Защита от различных видов атак
 */

isset($_GET['id']) or $_GET['id'] = Security::getCurrentSession()["user_info"]->getUserId();
$_GET['id'] = abs((int)$_GET['id']);

UserInfo::UserExists(
    $_GET['id']
) or Security::ThrowError(_("Користувача з вказаним ідентифікатором не знайдено!"));

/*
 * Получаем информацию о пользователе
 */

$user_info = UserInfo::getUserInfo($_GET['id']);

?>

<div class="card text-center">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link active" href=""><?=_("Профіль")?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><?=_("Редагувати профіль")?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?=_SPM_?>"><?=_("Спроби")?></a>
            </li>
        </ul>
    </div>
    <div class="card-body">

        <img
            src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20200%20200%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_16277f0cf54%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_16277f0cf54%22%3E%3Crect%20width%3D%22200%22%20height%3D%22200%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2274.4296875%22%20y%3D%22104.5%22%3E200x200%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E"
            class="rounded mx-auto d-block"
            style="min-width: 200px; min-height: 100px; margin: 20px;"
        >
        <h3 class="card-title" style="margin: 0;">
            <?=$user_info["secondname"]?> <?=$user_info["firstname"]?> <?=$user_info["thirdname"]?>
        </h3>

        <div class="row" style="margin-top: 30px;">

            <!-- INFORMATION ABOUT USER STARTS -->

            <div class="col-md-6 col-sm-12 text-left">

                <div class="list-group">

                    <a class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><?=_("Інформація про користувача")?></h6>
                        </div>
                    </a>

                    <a class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><?=_("Ім'я")?></h6>
                        </div>
                        <p class="mb-1"><?=$user_info["firstname"]?></p>
                    </a>

                    <a class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><?=_("Прізвище")?></h6>
                        </div>
                        <p class="mb-1"><?=$user_info["secondname"]?></p>
                    </a>

                    <a class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><?=_("По-батькові")?></h6>
                        </div>
                        <p class="mb-1"><?=$user_info["thirdname"]?></p>
                    </a>

                    <a class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><?=_("Дата народження")?></h6>
                        </div>
                        <p class="mb-1"><?=$user_info["birthday_date"]?></p>
                    </a>

                </div>

            </div>

            <!-- INFORMATION ABOUT USER ENDS -->

        </div>

    </div>
</div>