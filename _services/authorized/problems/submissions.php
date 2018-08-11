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
 * Идентификатор пользователя.
 */

isset($_GET['id'])
	or $_GET['id'] = Security::getCurrentSession()['user_info']->getUserId();

$_GET['id'] = abs((int)$_GET['id']);

/*
 * Идентификатор интересующего
 * нас соревнования.
 */

isset($_GET['oid'])
	or $_GET['oid'] = 0;

$_GET['oid'] = abs((int)$_GET['oid']);

/*
 * Проверка на существование
 * указанного   пользователя
 * системы.
 */

UserInfo::UserExists($_GET['id'])
	or Security::ThrowError("404");

/*
 * Проверяем,  имеет ли право текущий
 * пользователь системы просматривать
 * данный сервис с данными об указан-
 * ном  пользователе  или  нет, после
 * чего   предпринимаем   необходимые
 * действия в его адрес.
 */

Security::CheckAccessPermissionsForEdit($_GET['id'])
	or Security::ThrowError("403");

/*
 * Устанавливаем название и Layout сервиса
 */

define("__PAGE_TITLE__", _("Спроби"));
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к необходимым
 * глобальным переменным.
 */

global $database;

/*
 * Получаем список отложенных
 * задач для указанного поль-
 * зователя   и  обрабатываем
 * эту информацию.
 */

// Формируем запрос на выборку данных из БД
$query_str = sprintf("
    SELECT
      `spm_submissions`.`submissionId`,
      `spm_submissions`.`problemId`,
      `spm_submissions`.`time`,
      `spm_submissions`.`b`,
      
      `spm_problems`.`name`,
      `spm_problems`.`difficulty`
    FROM
      `spm_submissions`
    LEFT JOIN
      `spm_problems`
    ON
      `spm_submissions`.`problemId` = `spm_problems`.`id`
    WHERE
      `spm_submissions`.`olympId` = '%s'
    AND
      `spm_submissions`.`userId` = '%s'
    ORDER BY
      `spm_submissions`.`time` DESC,
      `spm_submissions`.`problemId` ASC
    ;
",
	$_GET['oid'],
	$_GET['id']
);

// Выполняем запрос и обрабатываем результаты
$submissions_list = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

?>

<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs">

			<li class="nav-item">
				<a class="nav-link" href="<?=_SPM_?>index.php/users/profile/?id=<?=$_GET['id']?>"><?=_("Профіль")?></a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="<?=_SPM_?>index.php/users/edit/?id=<?=$_GET['id']?>"><?=_("Редагувати сторінку")?></a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="<?=_SPM_?>index.php/problems/difficult/?id=<?=$_GET['id']?>"><?=_("Відкладені завдання")?></a>
			</li>

			<li class="nav-item">
				<a class="nav-link active" href="<?=_SPM_?>index.php/problems/submissions/?id=<?=$_GET['id']?>"><?=_("Спроби")?></a>
			</li>

			<?php if ($_GET['oid'] > 0): ?>

				<li class="nav-item">

					<a
							class="nav-link"
							href="<?=_SPM_?>index.php/olympiads/olympiad/?id=<?=$_GET['oid']?>"
					><strong class="text-danger"><?=_("Повернутися до змагання")?></strong></a>

				</li>

			<?php endif; ?>

		</ul>
	</div>
    
	<div class="card-body table-responsive" style="padding: 0;">

		<?php if (sizeof($submissions_list) > 0): ?>

			<table class="table" style="margin: 0;">

				<thead>

				<tr>

					<th><?=_("ID")?></th>
					<th><?=_("Назва завдання")?></th>
					<th><?=_("Дата та час відправки")?></th>
					<th><?=_("Спроба")?></th>
					<th><?=_("Points")?></th>

				</tr>

				</thead>

				<tbody>

				<?php foreach ($submissions_list as $problem_info): ?>

					<?php

					if ($problem_info['b'] == 0)
						$highlight = "text-danger";
					elseif ($problem_info['b'] < $problem_info['difficulty'])
						$highlight = "text-warning";
					elseif ($problem_info['b'] >= $problem_info['difficulty'])
						$highlight = "text-success";
					else
						$highlight = "text-info";

					?>

					<tr>

						<td><?=$problem_info['problemId']?></td>

						<td>
							<a
								style="color: #212121 !important;"
								href="<?=_SPM_?>index.php/problems/result/?id=<?=$problem_info['submissionId']?>"
							><?=$problem_info['name']?></a>
						</td>

						<td><?=$problem_info['time']?></td>

						<td>
							<a
								style="color: #212121 !important;"
								href="<?=_SPM_?>index.php/problems/result/?id=<?=$problem_info['submissionId']?>"
							><?=$problem_info['submissionId']?></a>
						</td>

						<td class="<?=$highlight?>">
							<?=$problem_info['b']?> / <?=$problem_info['difficulty']?>
						</td>

					</tr>

				<?php endforeach; ?>

				</tbody>

			</table>

		<?php else: ?>

			<p class="lead text-danger text-center" style="margin: 50px;">
				<?=_("Користувач ще не відправляв своїх рішень!")?>
			</p>

		<?php endif; ?>

	</div>
</div>