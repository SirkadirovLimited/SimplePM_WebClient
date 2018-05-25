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

define("__PAGE_TITLE__", _("Авторизація"));
define("__PAGE_LAYOUT__", "skeleton");

?>
<link href="<?=_SPM_assets_?>css/auth_page.css" rel="stylesheet">
<form class="form-signin" method="post" action="<?=_SPM_?>index.php?cmd=login">

    <h1 class="h1 mb-3 font-weight-normal text-center"><strong>Simple</strong>PM</h1>

    <input
            name="email"
            maxlength="255"
            type="email"
            class="form-control"
            placeholder="<?=_("E-mail адреса")?>"
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

        <form method="post" action="<?=_SPM_?>index.php?cmd=registration">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><?=_("Реєстрація в системі")?></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">

                    <h5 class="h5 text-center" style="margin-bottom: 20px;"><?=_("Дані для авторизації в системі")?></h5>

					<div class="form-group">
						<label><?=_("Email адреса")?></label>
						<input
								name="email"

								type="email"
								class="form-control"
								maxlength="255"
								required
						>
						<small class="form-text text-muted"><?=_("Вказаний e-mail буде використовуватися для входу в систему.")?></small>
					</div>

                    <div class="form-group">
                        <label><?=_("Пароль")?></label>
                        <input
								name="password"

                                type="password"
                                class="form-control"
								minlength="8"
                                maxlength="255"
                                required
                        >
                        <small class="form-text text-muted">
                            <?=_("Використовуйте лише букви латинського алфавіту, цифри та символи, пробіли використовувати заборонено!")?>
                        </small>
                    </div>

                    <h5 class="h5 text-center" style="margin: 20px;"><?=_("Особиста інформація")?></h5>

                    <div class="row">

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label><?=_("Ім'я")?></label>
                                <input
										name="firstname"

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
										name="secondname"

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
										name="thirdname"

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
								name="teacherid"

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