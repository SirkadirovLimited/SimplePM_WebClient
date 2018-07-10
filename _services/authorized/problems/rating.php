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
 * vefification system for programming tasks "SimplePM".
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

/*
 * Устанавливаем название и Layout сервиса
 */

define("__PAGE_TITLE__", _("Користувачі системи"));
define("__PAGE_LAYOUT__", "default");

/*
 * Получаем информацию о текущем пользователе
 */

$user_info = Security::getCurrentSession()["user_info"]->getUserInfo();

/*
 * Различные проверки безопасности
 */

// Отображаемая группа пользователей по-умолчанию
isset($_GET['group']) or
	$_GET['group'] = $user_info['groupid'];

// Очистка от возможных ошибок
$_GET['group'] = abs((int)$_GET['group']);

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Производим выборку пользовательских
 * групп учителя текущего пользователя
 * из базы данных.
 */

// Формируем запрос на выборку данных
$query_str = "
	SELECT
	  `id`,
	  `name`,
	  `teacherId`
	FROM
	  `spm_users_groups`
	WHERE
	  `teacherId` = '" . $user_info['teacherId'] . "'
	OR
	  `teacherId` = '" . $user_info['id'] . "'
	;
";

// Выполняем запрос и получаем данные
$groups_list = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

/*
 * Производим выборку пользователей
 * в составе указанной группы из БД
 */

// Формируем запрос на выборку данных
$query_str = "
	SELECT
	  `id`,
	  `firstname`,
	  `secondname`,
	  `thirdname`,
	  `permissions`,
	  `RatingBase`(`id`) AS rating,
	  `RatingCount`(`id`, 0) AS points
	FROM
	  `spm_users`
	WHERE
	  `groupid` = '" . $_GET['group'] . "'
	ORDER BY
	  rating DESC,
	  points DESC,
	  `permissions` DESC,
	  `id` ASC
	;
";

// Выполняем сформированный запрос
$query = $database->query($query_str);

// Получаем предоставленные данные
$users_list = $query->fetch_all(MYSQLI_ASSOC);

// Удаляем более не используемую переменную
unset($query);

?>

<div class="card" style="margin-bottom: 10px;">
	<div class="card-body">

		<form action="<?=_SPM_?>index.php/problems/rating/" method="get">

			<div class="input-group">

				<div class="input-group-prepend">
					<span class="input-group-text"><?=_("Група користувачів")?></span>
				</div>

				<select
					class="form-control"
					name="group"
				>

					<?php foreach ($groups_list as $group_info): ?>

						<option
								value="<?=$group_info['id']?>"
								<?=($group_info['id'] == $_GET['group'] ? "selected" : "")?>
								class="<?=($group_info['teacherId'] == $user_info['id'] ? "bg-success text-white" : "")?>"
						><?=$group_info['name']?></option>

					<?php endforeach; ?>

				</select>

				<div class="input-group-append">
					<button
						type="submit"
						class="btn btn-primary"
					><?=_("Відобразити")?></button>
				</div>

			</div>

		</form>

	</div>
</div>

<?php if (Security::CheckAccessPermissions(
		PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR,
        false,
        $user_info['permissions']
)): ?>

	<ul class="nav nav-pills justify-content-end" style="margin-top: 0; margin-bottom: 10px;">

		<li class="nav-item">
			<a
					class="nav-link"
					href="<?=_SPM_?>index.php/users/groups/"
			><?=_("Управління групами")?></a>
		</li>

		<li class="nav-item">
			<a
					class="nav-link"
					href="<?=_SPM_?>index.php/users/TeacherID/"
			><?=_("TeacherID")?></a>
		</li>

	</ul>

<?php endif; ?>

<div class="card">

	<div class="card-body table-responsive" style="padding: 0;">

		<table class="table table-bordered table-hover" style="margin: 0;">

			<thead>

			<tr>

				<th><?=_("ID")?></th>
				<th><?=_("Повне ім'я")?></th>
				<th><?=_("Points")?></th>
				<th><?=_("Рейтинг")?></th>

			</tr>

			</thead>

			<tbody>

			<?php foreach ($users_list as $listed_user): ?>

				<tr>

					<td><?=$listed_user['id']?></td>

					<td>
						<a href="<?=_SPM_?>index.php/users/profile/?id=<?=$listed_user['id']?>">

							<?=$listed_user['secondname']?>
							<?=$listed_user['firstname']?>
							<?=$listed_user['thirdname']?>

						</a>
					</td>

					<td><?=(int)$listed_user['points']?></td>

					<td><?=number_format((float)$listed_user['rating'], 2)?></td>

				</tr>

			<?php endforeach; ?>

			</tbody>

		</table>

	</div>

</div>