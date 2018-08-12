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

// Запрашиваем доступ к глобальным переменным
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

        <?php

        switch ($olymp_info['visibility'])
        {

            case "Private":
                $visibility_info_additional_class = "badge-warning";
                break;

            case "Public":
                $visibility_info_additional_class = "badge-success";
                break;

            default:
                $visibility_info_additional_class = "badge-light";
                break;

        }

        ?>

        <h1><?=$olymp_info['name']?> <span class="badge <?=$visibility_info_additional_class?>"><?=$olymp_info['visibility']?></span></h1>

		<p class="lead"><?=$olymp_info['description']?></p>

	</div>

</div>

<div class="table-responsive" style="margin: 0;">

	<table class="table table-bordered" style="margin: 0;">

		<tbody>

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Куратор змагання")?>
			</td>

			<td>

				<?php $curator_info = UserInfo::getUserInfo($olymp_info['teacherId']); ?>

				<a href="<?=_SPM_?>index.php/users/profile/?id=<?=$curator_info['id']?>" style="color: #212121;">
					<?=$curator_info['secondname']?> <?=$curator_info['firstname']?> <?=$curator_info['thirdname']?>
				</a>

			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Час проведення")?>
			</td>

			<td>
                <span class="badge badge-light"><?=$olymp_info['startTime']?></span> <strong>-</strong> <span class="badge badge-light"><?=$olymp_info['endTime']?></span>
                (<?=((new DateTime($olymp_info['endTime']))->diff(new DateTime($olymp_info['startTime'])))->format("%a " . _("днів") . " %H " . _("годин") . " %I " . _("хвилин"))?>)
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
	  SEC_TO_TIME(
	    sum(
	      TIME_TO_SEC(
	        TIMEDIFF(`spm_submissions`.`time`, '%s')
	      )
	    ) +
	    (
	      sum(
	        `spm_submissions`.`previous_count`
	      ) * 60 * 20
	    )
	  ) AS penalty,
	  
      `spm_users`.`id`,
      
      `spm_users`.`firstname`,
      `spm_users`.`secondname`,
      `spm_users`.`thirdname`,
      `spm_users`.`email`,
      `spm_users`.`groupid`,
      `spm_users`.`institution`,
      
      sum(`b`) AS points
    FROM
      `spm_submissions`
    LEFT JOIN
      `spm_users`
    ON
	  `spm_submissions`.`userId` = `spm_users`.`id`
	WHERE
	  `spm_submissions`.`olympId` = '%s'
	AND
	  `spm_submissions`.`testType` = 'release'
	AND
	  `spm_submissions`.`b` > 0
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

		<table class="table table-bordered table-hover">

			<thead>

			<tr>

				<th><?=_("№")?></th>
				<th><?=_("Повне ім'я")?></th>
                <th><?=_("Навчальний заклад")?></th>
				<th><?=_("Група")?></th>
				<th><?=_("Набрані бали")?></th>

			</tr>

			</thead>

			<tbody>

				<?php foreach ($rating_table as $user_rating): ?>

					<tr>

						<th><?=@++$i?></th>

						<td>

							<a href="<?=_SPM_?>index.php/problems/submissions/?id=<?=$user_rating['id']?>&oid=<?=$_GET['id']?>">
								<?=$user_rating['secondname']?> <?=$user_rating['firstname']?> <?=$user_rating['thirdname']?>
							</a>

						</td>

                        <td><?=$user_rating['institution']?></td>

						<td><?=UserInfo::GetGroupName($user_rating['groupid'])?></td>

						<td><?=number_format($user_rating['points'], 2)?></td>

					</tr>

				<?php endforeach; ?>

			</tbody>

			<tfoot>

			<tr>

				<th><?=_("№")?></th>
				<th><?=_("Повне ім'я")?></th>
                <th><?=_("Навчальний заклад")?></th>
				<th><?=_("Група")?></th>
				<th><?=_("Набрані бали")?></th>

			</tr>

			</tfoot>

		</table>

	</div>

<?php else: ?>

	<p class="lead text-center" style="margin-top: 100px; margin-bottom: 100px;">
		<?=_("Не знайдено користувачів, що вирішили хоча б одну задачу зі списку!")?>
	</p>

<?php endif; ?>