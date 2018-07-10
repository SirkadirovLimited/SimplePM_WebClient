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
 * Производим проверку наличия доступа
 * для использования данного сервиса.
 */

Security::CheckAccessPermissions(
	Security::getCurrentSession()['user_info']->getUserInfo()['permissions'],
	PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR,
	true
);

/*
 * Глобальные константы
 */

define("__PAGE_TITLE__", _("Змагання"));
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Получаем полный список соревнований,
 * курируемых текущим пользователем.
 */

// Формируем запрос на выборку данных из БД
$query_str = sprintf("
	SELECT
	  `id`,
	  `name`,
	  `startTime`,
	  `endTime`
	FROM
	  `spm_olympiads`
	WHERE
	  `teacherId` = '%s'
	ORDER BY
	  `startTime` DESC,
	  `endTime` DESC,
	  `id` DESC
	;
", Security::getCurrentSession()['user_info']->getUserId());

// Выполняем запрос к БД и производим выборку данных
$olympiads_list = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

?>

<div class="card text-center">

	<div class="card-header">

		<ul class="nav nav-tabs card-header-tabs">

            <li class="nav-item">
                <a class="nav-link active" href="<?=_SPM_?>index.php/olympiads/list/"><?=_("Список змагань")?></a>
            </li>

			<li class="nav-item">
                <a class="nav-link" href="<?=_SPM_?>index.php/olympiads/edit/"><?=_("Створити змагання")?></a>
            </li>

        </ul>

    </div>

    <div class="card-body" style="padding: 0;">

		<?php if (sizeof($olympiads_list) <= 0): ?>

			<p class="lead text-center" style="margin-top: 100px; margin-bottom: 100px;"><?=_("Ви ще не створили ні одного змагання!")?></p>

		<?php else: ?>

			<div class="table-responsive" style="margin: 0;">

				<table class="table" style="margin: 0;">

					<thead>

					<tr>

						<th><?=_("ID")?></th>
						<th><?=_("Назва")?></th>
						<th><?=_("Початок")?></th>
						<th><?=_("Завершення")?></th>
						<th><?=_("Операції")?></th>

					</tr>

					</thead>

					<tbody>

					<?php foreach ($olympiads_list as $olympiad_info): ?>

						<tr>

							<td><?=$olympiad_info['id']?></td>
							<td>
								<a href="<?=_SPM_?>index.php/olympiads/olympiad/?id=<?=$olympiad_info['id']?>">
									<?=$olympiad_info['name']?>
								</a>
							</td>
							<td><?=$olympiad_info['startTime']?></td>
							<td><?=$olympiad_info['endTime']?></td>
							<td>

								<a href="" class="text-dark disabled"><?=_("Редагувати")?></a>
								<a href="" class="text-danger disabled"><?=_("Видалити")?></a>

							</td>

						</tr>

					<?php endforeach; ?>

					</tbody>

				</table>

			</div>

		<?php endif; ?>

	</div>

</div>
