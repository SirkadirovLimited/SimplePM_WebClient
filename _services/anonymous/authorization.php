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

define("__PAGE_TITLE__", _("Авторизація"));
define("__PAGE_LAYOUT__", "skeleton");

?>
<link href="<?=_SPM_assets_?>css/auth_page.css" rel="stylesheet">
<form class="form-signin" method="post">
    <!--h1 class="h3 mb-3 font-weight-normal text-center"><?=_("Вхід в систему")?></h1-->
    <h1 class="h1 mb-3 font-weight-normal text-center"><strong>Simple</strong>PM</h1>
    <input type="text" id="inputEmail" class="form-control" placeholder="<?=_("Ім'я користувача")?>" required autofocus>
    <input type="password" id="inputPassword" class="form-control" placeholder="<?=_("Пароль")?>" required>

    <button type="submit" class="btn btn-lg btn-primary btn-block" style="margin: 0;"><?=_("Увійти")?></button>

    <div class="row">
        <div class="col">
            <button
                    type="button"
                    class="btn btn-outline-primary btn-sm btn-block"
                    style="margin: 0;"
                    data-toggle="modal"
                    data-target="#registrationModal"
            ><?=_("Зареєструватись")?></button>
        </div>
    </div>

</form>

<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-lg" role="document">

        <form method="post" action="">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Реєстрація в системі</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label for="regEmail"><?=_("Email адреса")?></label>
                        <input
                                type="email"
                                class="form-control"
                                id="regEmail"
                                maxlength="255"
                                required
                        >
                        <small class="form-text text-muted"><?=_("На вказану скриньку будуть надходити важливі сповіщення.")?></small>
                    </div>

                    <div class="form-group">
                        <label for="regLogin"><?=_("Лоін")?></label>
                        <input
                                type="text"
                                class="form-control"
                                id="regLogin"
                                maxlength="255"
                                required
                        >
                        <small class="form-text text-muted">
                            <?=_("Логін буде використовуватись Вами під час авторизації.")?>
                            <?=_("Використовуйте лише латинські букви, цифри та символи '-' і '_'.")?>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="regPassword"><?=_("Пароль")?></label>
                        <input
                                type="text"
                                class="form-control"
                                id="regPassword"
                                maxlength="255"
                                required
                        >
                        <small class="form-text text-muted">
                            <?=_("Використовуйте лише букви латинського алфавіту, цифри та символи, пробіли використовувати заборонено!")?>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="regFirstName"><?=_("Ім'я")?></label>
                        <input
                                type="text"
                                class="form-control"
                                id="regLogin"
                                maxlength="255"
                                required
                        >
                        <small class="form-text text-muted"><?=_("Логін буде використовуватись Вами під час авторизації.")?></small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_("Закрити")?></button>
                    <button type="button" class="btn btn-primary"><?=_("Зареєструватись")?></button>
                </div>

            </div>

        </form>

    </div>

</div>