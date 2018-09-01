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

/*
 * Производим проверку наличия доступа
 * для использования данного сервиса.
 */

Security::CheckAccessPermissions(
	PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR,
	true
);

/*
 * Глобальные константы
 */

define("__PAGE_TITLE__", _("TeacherID"));
define("__PAGE_LAYOUT__", "default");

/*
 * Объявляем и инициализируем важные
 * и часто используемые переменные.
 */

$_current_user_id = Security::getCurrentSession()['user_info']->getUserId();

/**
 * Функция переадресовывает текущего
 * пользователя на нужный нам сервис
 */

function redirect() : void
{

	// Перезаписываем заголовок
	header(
		'location: ' . _SPM_ . 'index.php/users/TeacherID',
		true
	);

	// Завершаем работу веб-приложения
	exit;

}

/**
 * Функция занимается перегенерацией TeacherID
 * указанного пользователя системы.
 * @param int $userId Идентификатор пользователя
 */

function teacherId_regenerate(int $userId) : void
{

	// Запрашиваем доступ к глобальным переменным
	global $database;

	/*
	 * Устанавливаем уровень доступа для пользователей, которые
	 * будут регистрироваться с помощью текущего кода TeacherID.
	 */

	if (Security::CheckAccessPermissions(PERMISSION::TEACHER, false))
		$user_permission = PERMISSION::STUDENT;
	elseif (Security::CheckAccessPermissions(PERMISSION::ADMINISTRATOR, false))
		$user_permission = PERMISSION::TEACHER;
	else
		$user_permission = PERMISSION::ANONYMOUS;

	// Генерируем TeacherID
	$teacherId = Security::GenerateKeyCode(10);

	/*
	 * Производим необхордимые запросы
	 * к базе данных.
	 */

	// Формируем запрос на вставку к БД
	$query_str = sprintf("
		INSERT INTO
		  `spm_teacherid`
		SET
		  `userId` = '%s',
		  `teacherId` = '%s',
		  `newUserPermission` = '%s'
		ON DUPLICATE KEY UPDATE
		  `teacherId` = '%s',
		  `newUserPermission` = '%s'
		;
	",
        $userId,
        $teacherId,
        $user_permission,
        $teacherId,
        $user_permission
    );

	// Выполняем запрос и отлавливаем ошибки
	if (!$database->query($query_str))
		Security::ThrowError("input");

	// Переадресовываем пользователя на необходимый нам сервис
	redirect();

}

/**
 * Функция позволяет изменить статус
 * пользовательского TeacherID на
 * указанный.
 * @param int $userId Идентификатор пользователя
 * @param bool $enable Установить в положение
 */

function teacherId_toggle(int $userId, bool $enable) : void
{

	global $database;

	$query_str = sprintf("
		UPDATE
		  `spm_teacherid`
		SET
		  `enabled` = '%s'
		WHERE
		  `userId` = '%s'
		LIMIT
		  1
		;
	",
        (int)$enable,
        $userId
    );

	if (!$database->query($query_str))
		Security::ThrowError("input");

	redirect();

}

/**
 * Функция позволяет получить текущий код TeacherID
 * указанного пользователя системы SimplePM.
 * @param int $userId Идентификатор пользователя
 * @return string TeacherID указанного пользователя
 */

function teacherId_get(int $userId) : string
{

	global $database;

	$query_str = sprintf("
		SELECT
	  	  `teacherId`
		FROM
		  `spm_teacherid`
		WHERE
		  `userId` = '%s'
		LIMIT
		  1
		;
	", $userId);

	return (string)$database->query($query_str)->fetch_array()[0];

}

/**
 * Функция позволяет определить, активирован ли
 * уникальній код TeacherID, привязанный к указанному
 * идентификатору пользователя системы.
 * @param int $userId Идентификатор пользователя
 * @return bool Статус кода
 */

function teacherId_enabled(int $userId) : bool
{

	global $database;

	$query_str = sprintf("
		SELECT
		  `enabled`
		FROM
		  `spm_teacherid`
		WHERE
		  `userId` = '%s'
		LIMIT
		  1
		;
	", $userId);

	return (bool)(
		(int)(
			$database->query($query_str)->fetch_array()[0]
		)
	);

}

/**
 * Функция позволяет определить, существует ли
 * уникальный код TeacherID, который ассоциирован
 * с указанным пользователем системы SimplePM.
 * @param int $userId Идентификатор пользователя
 * @return bool Существует или нет
 */

function teacherId_exists(int $userId) : bool
{

	/*
	 * Запрашиваем доступ к глобальным переменным
	 */

	global $database;

	/*
	 * Производим запрос к базе данных
	 */

	// ФОрмируем запрос на выборку из БД
	$query_str = sprintf("
		SELECT
		  count(`teacherId`)
		FROM
		  `spm_teacherid`
		WHERE
		  `userId` = '%s'
		;
	", $userId);

	// Выполняем запрос и возвращаем преформатированный результат
	return (int)(
		$database->query($query_str)->fetch_array()[0]
	) > 0;

}

if (isset($_GET['t_action']))
{

	switch ($_GET['t_action'])
	{

		case "new":
			teacherId_regenerate($_current_user_id);
			break;

		case "enable":
			teacherId_toggle($_current_user_id, true);
			break;
		case "disable":
			teacherId_toggle($_current_user_id, false);
			break;

	}

}

/*
 * Если  TeacherID   для   текущего  пользователя
 * ещё не создан, запускаем скрипт его генерации.
 */

if (!teacherId_exists($_current_user_id))
	teacherId_regenerate($_current_user_id);

?>

<div class="row">

    <div class="col-md-4 col-sm-12">

        <div class="card card-teacherid">

            <div class="card-header <?=(teacherId_enabled($_current_user_id) ? "text-success" : "text-danger")?>"
            ><?=teacherId_get($_current_user_id)?></div>

            <div class="card-body">

                <p class="card-text text-justify">
                    <strong><?=_("TeacherID")?></strong> - <?=_("це унікальний код доступу в SimplePM, що дозволяє новим користувачам реєструватися в системі та автоматично пов'язуватися зі своїм куратором.")?>
                </p>

            </div>

            <ul class="list-group list-group-flush">

                <li class="list-group-item">

                    <!--p><?=sprintf(_("Нові користувачі будуть автоматично додані у групу %s"), "11-Б")?></p-->

                    <form action="" method="get">

                        <div class="input-group">

                            <select class="form-control">

                                <option value="0"><?=_("Не підтверджувати автоматично")?></option>

                            </select>

                            <div class="input-group-append">

                                <button
                                        class="btn btn-outline-success"
                                        type="submit"
                                ><i class="fas fa-check"></i></button>

                            </div>

                        </div>

                    </form>

                </li>

                <li class="list-group-item">
                    <a class="text-dark" href="<?=_SPM_?>index.php/users/TeacherID/?t_action=new"
                    ><?=_("Згенерувати новий код")?></a>
                </li>

                <?php if (!teacherId_enabled($_current_user_id)): ?>

                    <li class="list-group-item">
                        <a class="text-success" href="<?=_SPM_?>index.php/users/TeacherID/?t_action=enable"
                        ><?=_("Ввімкнути код доступу")?></a>
                    </li>

                <?php else: ?>

                    <li class="list-group-item">
                        <a class="text-danger" href="<?=_SPM_?>index.php/users/TeacherID/?t_action=disable"
                        ><?=_("Вимкнути код доступу")?></a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>

    <?php

    // Запрашиваем доступ к глобальным переменным
    global $database;

    $query_str = sprintf("
        SELECT
          `id`,
          `email`,
          `firstname`,
          `secondname`,
          `thirdname`
        FROM
          `spm_users`
        WHERE
          `teacherId` = '%s'
        AND
          `groupid` = '0'
        ORDER BY
          `last_online` ASC
        ;
    ", Security::getCurrentSession()['user_info']->getUserId());

    $deactivated_users = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

    ?>

    <?php if (sizeof($deactivated_users) > 0): ?>

        <div class="col-md-8 col-sm-12">

            <div class="card">

                <div class="card-body table-responsive">

                    <h3 class="text-center" style="margin-bottom: 10px;"><?=_("Черга активації користувачів")?></h3>

                    <p class="lead text-center" style="margin: 0; margin-bottom: 20px;">
                        <?=_("Для активації вказаного у списку користувача потрібно приєднати його до існуючої користувацької групи.")?>
                    </p>

                    <table class="table table-borderless table-hover" style="margin: 0;">

                        <?php foreach ($deactivated_users as $deactivated_user): ?>

                            <tr>

                                <td>

                                    <a href="<?=_SPM_?>index.php/users/edit/?id=<?=$deactivated_user['id']?>">

                                        <i class="fas fa-user-edit"></i>
                                        <?=$deactivated_user['secondname']?>
                                        <?=$deactivated_user['firstname']?>
                                        <?=$deactivated_user['thirdname']?>

                                    </a>

                                </td>

                                <td>

                                    <a href="mailto:<?=$deactivated_user['email']?>">
                                        <i class="fas fa-envelope"></i> <?=$deactivated_user['email']?>
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </table>

                </div>

            </div>

        </div>

    <?php endif; ?>

</div>

<style>

    div.row {
        margin-top: 5em;
        margin-bottom: 5em;
    }

    div.card.card-teacherid .card-header {
        text-align: center;
        font-size: 20pt;
    }

    a {
        color: #212121 !important;
    }

</style>