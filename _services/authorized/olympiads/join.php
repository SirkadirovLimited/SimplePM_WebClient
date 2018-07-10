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
 * Проверяем пава пользователя
 * на  использование   данного
 * сервиса.
 */

Security::CheckAccessPermissions(
	Security::getCurrentSession()['user_info']->getUserInfo()['permissions'],
	PERMISSION::STUDENT,
	true
);

/*
 * Устанавливаем название и Layout сервиса
 */

define("__PAGE_TITLE__", _("Приєднатися до змагання"));
define("__PAGE_LAYOUT__", "default");

/*
 * Получаем идентификатор куратора
 * текущего пользователя системы.
 */

$teacherId_of_current_user = Security::getCurrentSession()['user_info']->getUserInfo()['teacherId'];

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Производим выборку всех соревнований
 * доступных текущему пользователю.
 */

//Формируем запрос на выборку данных из БД
$query_str = sprintf("
	SELECT
	  `id`,
	  `name`,
	  `description`,
	  `type`,
	  `teacherId`,
	  `startTime`,
	  `endTime`
	FROM
	  `spm_olympiads`
	WHERE
	  (
	    `type` = 'Public'
	  OR
		(
		  `teacherId` = '%s'
		AND
		  `type` = 'Private'
		)
	  )
	AND
	  (
	  	`startTime` <= NOW()
	  AND
	  	`endTime` >= NOW()
	  )
	ORDER BY
	  `endTime` ASC
	;
", $teacherId_of_current_user);

// Выполняем запрос и производим выборку всех необходимых данных из БД
$olympiads_list = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

?>

<style>

	#jumbotron-head {
		display: none;
	}

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
		<h1><?=_("Приєднатись до змагання")?></h1>
		<p class="lead"><?=_("Для того, щоб приєднатись до існуючого змагання оберіть його у списку нижче. Вдачі!")?></p>
	</div>
</div>

<div class="row" style="margin-top: 2rem;">

	<?php foreach ($olympiads_list as $olympiad_info): ?>

		<div class="col-md-6 col-sm-12" style="margin-bottom: 2rem;">

			<a
				class="card"
				style="text-decoration: none !important;"
				onclick="return confirm('<?=_("Розпочати участь у змаганні?")?>');"
				href="<?=_SPM_?>index.php?cmd=olympiads/join&id=<?=$olympiad_info['id']?>"
			>

				<div class="card-body">

					<strong><?=$olympiad_info['name']?></strong>

					<p class="card-text">

						<span><?=$olympiad_info['description']?></span><br>

						<i style="padding: 5px; display: block;">

							<span><strong><?=_("Початок")?>:</strong> <?=$olympiad_info['startTime']?></span><br>
							<span><strong><?=_("Закінчення")?>:</strong> <?=$olympiad_info['endTime']?></span>

						</i>

						<span class="badge badge-info"><?=$olympiad_info['type']?></span>

						<?php if ($olympiad_info['teacherId'] == $teacherId_of_current_user): ?>

							<span class="badge badge-success"><?=_("Від вашого куратора")?></span>

						<?php endif; ?>

					</p>

				</div>

			</a>

		</div>

	<?php endforeach; ?>

</div>