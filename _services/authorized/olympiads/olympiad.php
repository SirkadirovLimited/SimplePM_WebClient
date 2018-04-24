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
 * Получаем идентификатор ассоциированого соревнования
 */

$associated_olymp = (int)(Security::getCurrentSession()["user_info"]->getUserInfo()["associated_olymp"]);

/*
 * Различные проверки безопасности
 */

if ($associated_olymp > 0)
	$_GET['id'] = $associated_olymp;

isset($_GET['id']) or Security::ThrowError("input");
$_GET['id'] = abs((int)$_GET['id']);

/*
 * Получаем  информацию  о  необходимом
 * нам  соревновании.  Если  ничего  не
 * найдено - отображаем страницу ошибки
 */

// Получаем полную информацию о соревновании
$olymp_info = Olymp::GetOlympiadInfo($_GET['id']);

// Дополнительная защита
if (!is_array($olymp_info))
	Security::ThrowError("404");

/*
 * Глобальные константы
 */

define("__PAGE_TITLE__", _("Інформація про змагання №") . $_GET['id']);
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

?>

<style>

	.jumbotron-header {

		position: relative;

		background-color: #343a40 !important;
		color: white !important;

		padding-top: 20px;
		padding-bottom: 20px;

	}

	.jumbotron-header p.lead {
		margin-bottom: 0;
	}

	a.card {
		color: #343a40;
	}

</style>

<div class="jumbotron jumbotron-fluid jumbotron-header">
	<div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; background-color: transparent; opacity: 0.1; z-index: 1;"></div>
	<div class="container" style="z-index: 2;">
		<h1><?=$olymp_info['name']?></h1>
		<p class="lead"><?=$olymp_info['description']?></p>
	</div>
</div>

<div class="table-responsive" style="margin: 0;">

	<table class="table table-bordered table-hover" style="margin: 0;">

		<tbody>

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Куратор змагання")?>
			</td>

			<td>

				<?php $curator_info = UserInfo::getUserInfo($olymp_info['teacherId']); ?>

				<a href="<?=_SPM_?>index.php/users/profile/?id=<?=$curator_info['id']?>">
					<?=$curator_info['secondname']?> <?=$curator_info['firstname']?> <?=$curator_info['thirdname']?>
				</a>

			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Тип змагання")?>
			</td>

			<td>
				<?=$olymp_info['type']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Дата та час початку")?>
			</td>

			<td>
				<?=$olymp_info['startTime']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Дата та час закінчення")?>
			</td>

			<td>
				<?=$olymp_info['endTime']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Тип оцінювання")?>
			</td>

			<td>
				<?=$olymp_info['judge']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Треба набрати балів")?>
			</td>

			<td>
				<?=$olymp_info['requiredRating']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Нормалізована оцінка")?>
			</td>

			<td>
				<?=$olymp_info['citedScore']?>
			</td>


		</tr>
		<!-- /PARAM -->

		</tbody>

	</table>

</div>

<?php

/*
 * Производим  формирование  рейтинговой
 * таблицы пользователей, учавствовавших
 * в данном соревновании.
 */

// Формируем запрос на выборку данных из БД
$query_str = sprintf("
	SELECT
	  SEC_TO_TIME(sum(TIME_TO_SEC(TIMEDIFF(`spm_submissions`.`time`, '%s')))) AS penalty,
	  
      `spm_users`.`id`,
      
      `spm_users`.`firstname`,
      `spm_users`.`secondname`,
      `spm_users`.`thirdname`,
      `spm_users`.`email`,
      `spm_users`.`groupid`,
      
      sum(`b`) AS points
    FROM
      `spm_submissions`
    LEFT JOIN
      `spm_users`
    ON
	  `spm_submissions`.`userId` = `spm_users`.`id`
	WHERE
	  `spm_submissions`.`olympId` = '%s'
	GROUP BY
	  `spm_submissions`.`userId`
	ORDER BY
	  points DESC,
	  penalty ASC,
	  `id` ASC
	;
",
	$olymp_info['startTime'],
	$_GET['id']
);

// Выполняем запрос и производим выборку всех полученных данных из БД
$rating_table = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

?>

<?php if (sizeof($rating_table) > 0): ?>

	<div class="jumbotron text-white bg-dark">
		<h4 class="lead"><?=_("Рейтинг учасників")?></h4>
	</div>

	<div class="table-responsive">

		<table class="table">

			<thead>

			<tr>

				<th><?=_("ID")?></th>
				<th><?=_("Email")?></th>
				<th><?=_("Повне ім'я")?></th>
				<th><?=_("Група")?></th>
				<th><?=_("Пенальті")?></th>
				<th><?=_("Набрані бали")?></th>
				<th><?=_("Оцінка")?></th>

			</tr>

			</thead>

			<tbody>

				<?php foreach ($rating_table as $user_rating): ?>

					<tr>

						<td><?=$user_rating['id']?></td>
						<td><?=$user_rating['email']?></td>
						<td><?=$user_rating['secondname']?> <?=$user_rating['firstname']?> <?=$user_rating['thirdname']?></td>
						<td><?=UserInfo::GetGroupName($user_rating['groupid'])?></td>
						<td><?=$user_rating['penalty']?></td>
						<td><?=number_format($user_rating['points'], 2)?></td>
						<td></td>

					</tr>

				<?php endforeach; ?>

			</tbody>

			<tfoot>

			<tr>

				<th><?=_("ID")?></th>
				<th><?=_("Email")?></th>
				<th><?=_("Повне ім'я")?></th>
				<th><?=_("Група")?></th>
				<th><?=_("Пенальті")?></th>
				<th><?=_("Набрані бали")?></th>
				<th><?=_("Оцінка")?></th>

			</tr>

			</tfoot>

		</table>

	</div>

<?php else: ?>

	<p class="lead text-center" style="margin-top: 100px; margin-bottom: 100px;">
		<?=_("Не знайдено користувачів, що вирішили хоча б одну задачу зі списку!")?>
	</p>

<?php endif; ?>