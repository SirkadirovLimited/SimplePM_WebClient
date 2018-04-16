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

define("__PAGE_TITLE__", _("Редагування профілю"));
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

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
 * Получаем информацию о пользователе
 */

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
				<a class="nav-link" href="<?=_SPM_?>index.php/problems/submissions/?id=<?=$_GET['id']?>"><?=_("Спроби")?></a>
			</li>

		</ul>
	</div>
	<div class="card-body">

		<h3><?=_("Дані для входу")?></h3>

		<form method="post" action="<?=_SPM_?>index.php?cmd=users/edit/authinfo&id=<?=$_GET['id']?>">

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
                >

                <small class="form-text text-muted">
                    <?=_("Вказана адреса email буде використовуватись при вході в систему та для зворотнього зв'язку.")?>
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

		<h3><?=_("Особиста інформація")?></h3>

		<form method="post" action="<?=_SPM_?>index.php?cmd=users/edit/personal&id=<?=$_GET['id']?>">

            <div class="form-group">

                <label><?=_("Ім'я")?></label>

                <input
                        type="text"
                        class="form-control"

                        name=""
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

                        name=""
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

                        name=""
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

                        name="institution"
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

            if (Security::CheckAccessPermissionsForEdit($user_info['id'])): ?>

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

            <?php endif; unset($f_check); ?>

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

	</div>
</div>