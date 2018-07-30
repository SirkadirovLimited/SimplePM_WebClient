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

define("__PAGE_TITLE__", _("Редагування профілю"));
define("__PAGE_LAYOUT__", "default");

// Запрашиваем доступ к глобальным переменным
global $database;

/*
 * Защита от различных видов атак
 */

isset($_GET['id']) or $_GET['id'] = Security::getCurrentSession()["user_info"]->getUserId();
$_GET['id'] = abs((int)$_GET['id']);

// Если идентификатор 0 и ниже, отображаем страницу текущего пользователя
if ($_GET['id'] <= 0)
	$_GET['id'] = Security::getCurrentSession()["user_info"]->getUserId();

// Проверка пользователя с указанным идентификатором на существование
UserInfo::UserExists(
	$_GET['id']
) or Security::ThrowError(_("Користувача з вказаним ідентифікатором не знайдено!"));

/*
 * Проверка на наличие соответствующих
 * разрешений на внесение  изменений в
 * профиль указанного пользователя.
 */

Security::CheckAccessPermissionsForEdit(
        $_GET['id'],
        true
) or Security::ThrowError("403");

// Получаем информацию о пользователе
$user_info = UserInfo::getUserInfo($_GET['id']);

?>

<style>

    .card-body form {

        margin-bottom: 20px;

    }

</style>

<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs">

			<li class="nav-item">
				<a class="nav-link" href="<?=_SPM_?>index.php/users/profile/?id=<?=$_GET['id']?>"><?=_("Профіль")?></a>
			</li>

			<li class="nav-item">
				<a class="nav-link active" href="<?=_SPM_?>index.php/users/edit/?id=<?=$_GET['id']?>"><?=_("Редагувати сторінку")?></a>
			</li>

            <li class="nav-item">
                <a class="nav-link" href="<?=_SPM_?>index.php/problems/difficult/?id=<?=$_GET['id']?>"><?=_("Відкладені задачі")?></a>
            </li>

			<li class="nav-item">
				<a class="nav-link" href="<?=_SPM_?>index.php/problems/submissions/?id=<?=$_GET['id']?>"><?=_("Спроби")?></a>
			</li>

		</ul>
	</div>
	<div class="card-body">

		<h3><?=_("Дані для входу")?></h3>

		<form method="post" action="#">

            <div class="form-group">

                <label><?=_("Email адреса")?></label>

                <input
                        type="email"
                        class="form-control"

                        name=""
                        value="<?=$user_info['email']?>"

                        minlength="1"
                        maxlength="255"

                        required
                        disabled
                >

                <small class="form-text text-muted">
                    <?=_("Вказана адреса email буде використовуватись при вході в систему та для зворотнього зв'язку.")?>
                </small>

            </div>

            <div align="right">

                <button
                        type="reset"
                        class="btn btn-outline-secondary"
                        disabled
                ><?=_("Відмінити зміни")?></button>

                <button
                        type="submit"
                        class="btn btn-primary"
                        disabled
                ><?=_("Зберегти зміни")?></button>

            </div>

		</form>

		<h3><?=_("Особиста інформація")?></h3>

		<form method="post" action="<?=_SPM_?>index.php?cmd=users/edit/personal&id=<?=$_GET['id']?>">

            <div class="form-group">

                <label><?=_("Ім'я")?></label>

                <input
                        type="text"
                        class="form-control"

                        name="firstname"
                        value="<?=$user_info['firstname']?>"

                        minlength="1"
                        maxlength="255"

                        required
                >

                <small class="form-text text-muted">
                    <?=_("Ця інформація відображається у профайлі користувача.")?>
                </small>

            </div>

            <div class="form-group">

                <label><?=_("Фамілія")?></label>

                <input
                        type="text"
                        class="form-control"

                        name="secondname"
                        value="<?=$user_info['secondname']?>"

                        minlength="1"
                        maxlength="255"

                        required
                >

                <small class="form-text text-muted">
                    <?=_("Ця інформація відображається у профайлі користувача.")?>
                </small>

            </div>

            <div class="form-group">

                <label><?=_("По-батькові")?></label>

                <input
                        type="text"
                        class="form-control"

                        name="thirdname"
                        value="<?=$user_info['thirdname']?>"

                        minlength="1"
                        maxlength="255"

                        required
                >

                <small class="form-text text-muted">
                    <?=_("Ця інформація відображається у профайлі користувача.")?>
                </small>

            </div>

            <div class="form-group">

                <label><?=_("Дата народження")?></label>

                <input
                        type="date"
                        class="form-control"

                        name="birthday_date"
                        value="<?=$user_info['birthday_date']?>"

                        required
                >

                <small class="form-text text-muted">
                    <?=_("Використовується для заповнення блоку \"Дні народження у поточному місяці\".")?>
                </small>

            </div>

            <div align="right">

                <button
                        type="reset"
                        class="btn btn-outline-secondary"
                ><?=_("Відмінити зміни")?></button>

                <button
                        type="submit"
                        class="btn btn-primary"
                ><?=_("Зберегти зміни")?></button>

            </div>

		</form>

		<h3><?=_("Класифікація користувача")?></h3>

		<form method="post" action="<?=_SPM_?>index.php?cmd=users/edit/eduinfo&id=<?=$_GET['id']?>">

            <div class="form-group">

                <label><?=_("Навчальний заклад / Організація")?></label>

                <input
                        type="text"
                        class="form-control"

                        name="institution"
                        value="<?=$user_info['institution']?>"

                        minlength="1"
                        maxlength="255"

                        required
                >

                <small class="form-text text-muted">
                    <?=_("Ця інформація відображається у профайлі користувача.")?>
                </small>

            </div>

            <?php

            if (Security::CheckAccessPermissionsForEdit($user_info['id'], false)): ?>

                <div class="form-group">

                    <label><?=_("Клас / Група")?></label>

                    <select
                            class="form-control"
                            name="groupid"
                            required
                    >

                        <?php

                        $query_str = "
                            SELECT
                              `id`,
                              `name`
                            FROM
                              `spm_users_groups`
                            WHERE
                              `teacherId` = '" . $user_info['teacherId'] . "'
                            ORDER BY
                              `id` ASC
                            ;
                        ";

                        $groups_info = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

                        ?>

                        <option value><?=_("Виберіть групу чи клас")?></option>

                        <?php foreach ($groups_info as $group_info): ?>
                            <option
                                    value="<?=$group_info['id']?>"
                                <?=($user_info['groupid'] == $group_info['id'] ? "selected" : "")?>
                            ><?=$group_info['name']?> (gid<?=$group_info['id']?>)</option>
                        <?php endforeach; ?>

                    </select>

                    <small class="form-text text-muted">
                        <?=_("Користувача буде активовано лише у тому випадку, якщо він буде асоційований з існуючою групою.")?>
                    </small>

                </div>

            <?php endif; ?>

            <!--div class="form-group">

                <label><?=_("")?></label>

                <small class="form-text text-muted"><?=_("")?></small>

            </div-->

            <div align="right">

                <button
                        type="reset"
                        class="btn btn-outline-secondary"
                ><?=_("Відмінити зміни")?></button>

                <button
                        type="submit"
                        class="btn btn-primary"
                ><?=_("Зберегти зміни")?></button>

            </div>

		</form>

        <!--!!! USER BLOCKING AND REMOVAL SECTION !!!-->

        <?php if (($user_info['teacherId'] == Security::getCurrentSession()['user_info']->getUserId() ||
                Security::CheckAccessPermissions(PERMISSION::ADMINISTRATOR)) &&
                $user_info['id'] != Security::getCurrentSession()['user_info']->getUserId()): ?>

            <h3 class="text-danger"><?=_("Зона підвищеної небезпеки")?></h3>

            <p>
                <strong><?=_("Видалення користувача")?></strong> - <?=_("незворотній процес, при виконанні якого також видаляються всі пов'язані з цим користувачем дані.")?>
                <?=_("Більше того, буде видалена інформація про його запити на тестування, участь у змаганнях та всі його нагороди.")?>
            </p>

            <p>
                <strong><?=_("Блокування користувача")?></strong> - <?=_("зворотній процес, після якого користувач потрапляє у список непідтверджених користувачів та не може входити в систему.")?>
                <?=_("Для розблокування заблокованного користувача достатньо у сервісі \"Управління TeacherID\" обрати його та пов'язати з будь-якою користувацькою групою.")?>
            </p>

            <p>
                <?=_("Перед виконанням будь-яких операцій, будь-ласка, зверніться до офіційних настанов з адміністрування та використання SimplePM.")?>
            </p>

            <a
                    href="<?=_SPM_?>index.php?cmd=users/edit/delete&id=<?=$user_info['id']?>"
                    class="btn btn-outline-danger"

                    onclick="return confirm('<?=_("Ви впевнені в тому, що хочете зробити?")?>');"
            ><?=_("Видалити цього користувача")?></a>

            <a
                    href="<?=_SPM_?>index.php?cmd=users/edit/ban&id=<?=$user_info['id']?>"
                    class="btn btn-outline-warning"

                    onclick="return confirm('<?=_("Ви впевнені в тому, що хочете зробити?")?>');"
            ><?=_("Заблокувати цього користувача")?></a>

        <?php endif; ?>

        <!--!!! /USER BLOCKING AND REMOVAL SECTION !!!-->

	</div>
</div>