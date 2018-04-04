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

define("__PAGE_TITLE__", _("Авторизація"));
define("__PAGE_LAYOUT__", "skeleton");

?>
<link href="<?=_SPM_assets_?>css/auth_page.css" rel="stylesheet">
<form class="form-signin" method="post" action="<?=_SPM_?>index.php?cmd=login">

    <h1 class="h1 mb-3 font-weight-normal text-center"><strong>Simple</strong>PM</h1>

    <input
            name="username"
            maxlength="255"
            type="text"
            class="form-control"
            placeholder="<?=_("Ім'я користувача")?>"
            pattern="[a-zA-Z0-9._]\w+"
            required
            autofocus
    >
    <input
            name="password"
            maxlength="255"
            type="password"
            class="form-control"
            placeholder="<?=_("Пароль")?>"
            required
    >

    <button
            type="submit"
            class="btn btn-lg btn-primary btn-block"
            style="margin: 0;"
    ><?=_("Увійти")?></button>

    <button
            type="button"
            class="btn btn-outline-primary btn-sm btn-block"
            style="margin: 0;"
            data-toggle="modal"
            data-target="#registrationModal"
    ><?=_("Зареєструватись")?></button>

</form>

<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-lg" role="document">

        <form method="post" action="<?=_SPM_?>index.php?cmd=registration" enctype="text/plain">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><?=_("Реєстрація в системі")?></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <h5 class="h5 text-center" style="margin-bottom: 20px;"><?=_("Дані для авторизації в системі")?></h5>

                    <div class="form-group">
                        <label><?=_("Лоін")?></label>
                        <input
                                type="text"
                                class="form-control"
                                maxlength="255"
                                pattern="[a-zA-Z0-9._]\w+"
                                required
                        >
                        <small class="form-text text-muted">
                            <?=_("Логін буде використовуватись Вами під час авторизації.")?>
                            <?=_("Використовуйте лише латинські букви, цифри та символ '_'.")?>
                        </small>
                    </div>

                    <div class="form-group">
                        <label><?=_("Пароль")?></label>
                        <input
                                type="password"
                                class="form-control"
                                maxlength="255"
                                required
                        >
                        <small class="form-text text-muted">
                            <?=_("Використовуйте лише букви латинського алфавіту, цифри та символи, пробіли використовувати заборонено!")?>
                        </small>
                    </div>

                    <h5 class="h5 text-center" style="margin: 20px;"><?=_("Контактні дані")?></h5>

                    <div class="form-group">
                        <label><?=_("Email адреса")?></label>
                        <input
                                type="email"
                                class="form-control"
                                maxlength="255"
                                required
                        >
                        <small class="form-text text-muted"><?=_("На вказану скриньку будуть надходити важливі сповіщення.")?></small>
                    </div>

                    <h5 class="h5 text-center" style="margin: 20px;"><?=_("Особиста інформація")?></h5>

                    <div class="row">

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label><?=_("Ім'я")?></label>
                                <input
                                        type="text"
                                        class="form-control"
                                        maxlength="255"
                                        required
                                >
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label><?=_("Прізвище")?></label>
                                <input
                                        type="text"
                                        class="form-control"
                                        maxlength="255"
                                        required
                                >
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label><?=_("По-батькові")?></label>
                                <input
                                        type="text"
                                        class="form-control"
                                        maxlength="255"
                                        required
                                >
                            </div>
                        </div>

                    </div>

                    <h5 class="h5 text-center" style="margin: 20px;"><?=_("Захист від несанкціонованого доступу")?></h5>

                    <div class="form-group">
                        <label><?=_("TeacherID")?></label>
                        <input
                                type="text"
                                class="form-control"
                                maxlength="255"
                                required
                        >
                        <small class="form-text text-muted">
                            <?=_("Введіть ключ реєстрації, що надав Вам викладач або куратор.")?>
                        </small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_("Закрити")?></button>
                    <button type="submit" class="btn btn-primary"><?=_("Зареєструватись")?></button>
                </div>

            </div>

        </form>

    </div>

</div>