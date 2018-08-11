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
                <a class="nav-link" href="<?=_SPM_?>index.php/problems/difficult/?id=<?=$_GET['id']?>"><?=_("Відкладені завдання")?></a>
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

                <label><?=_("Прізвище")?></label>

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

            <p class="text-justify">
                <strong><?=_("Видалення користувача")?></strong> - <?=_("незворотній процес, при виконанні якого також видаляються всі пов'язані з цим користувачем дані.")?>
                <?=_("Більше того, буде видалена інформація про його запити на тестування, участь у змаганнях та всі його нагороди.")?>
            </p>

            <p class="text-justify">
                <strong><?=_("Блокування користувача")?></strong> - <?=_("зворотній процес, після якого користувач потрапляє у список непідтверджених користувачів та не може входити в систему.")?>
                <?=_("Для розблокування заблокованного користувача достатньо у сервісі \"Управління TeacherID\" обрати його та пов'язати з будь-якою користувацькою групою.")?>
            </p>

            <p class="text-justify">
                <?=_("Перед виконанням будь-яких операцій, будь-ласка, зверніться до офіційних настанов з адміністрування та використання SimplePM.")?>
            </p>

            <?php if (Security::getCurrentSession()['user_info']->getUserId() == 1): ?>

                <form
                        action="<?=_SPM_?>index.php?cmd=users/edit/permissions&id=<?=$user_info['id']?>"
                        method="post"
                >

                    <div class="form-group">

                        <label><?=_("Зміна прав користувача")?></label>

                        <div class="input-group">

                            <input
                                    type="number"
                                    name="new_permissions"

                                    class="form-control"

                                    min="0"

                                    placeholder="<?=$user_info['permissions']?>"
                                    value="<?=$user_info['permissions']?>"

                                    required
                            >

                            <div class="input-group-append">

                                <button
                                        class="btn btn-outline-secondary"
                                        type="reset"
                                ><?=_("Відмінити")?></button>

                                <button
                                        class="btn btn-outline-danger"
                                        type="submit"
                                ><?=_("Змінити")?></button>

                            </div>

                        </div>

                        <small class="form-text text-muted">
                            <?=_("Подробиці про цей функціонал можна дізнатися в офіційних настановах з адміністрування та використання SimplePM.")?>
                        </small>

                    </div>

                </form>

            <?php endif; ?>

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