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
	  `name`
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
	  `username`,
	  `permissions`,
	  `RatingBase`(`id`) AS rating,
	  `RatingCount`(`id`) AS points
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

// Проверка на ненахождение пользователей
if ($query->num_rows == 0)
	Security::ThrowError("404");

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

<div class="card">

	<div class="card-body table-responsive" style="padding: 0;">

		<table class="table table-bordered table-hover" style="margin: 0;">

			<thead>

			<tr>

				<th><?=_("ID")?></th>
				<th><?=_("Нікнейм")?></th>
				<th><?=_("Повне ім'я")?></th>
				<th><?=_("Points")?></th>
				<th><?=_("Рейтинг")?></th>

			</tr>

			</thead>

			<tbody>

			<?php foreach ($users_list as $listed_user): ?>

				<tr>

					<td><?=$listed_user['id']?></td>
					<td><?=$listed_user['username']?></td>
					<td>
						<?=$listed_user['secondname']?>
						<?=$listed_user['firstname']?>
						<?=$listed_user['thirdname']?>
					</td>
					<td><?=(int)$listed_user['points']?></td>
					<td><?=$listed_user['rating']?></td>

				</tr>

			<?php endforeach; ?>

			</tbody>

		</table>

	</div>

</div>