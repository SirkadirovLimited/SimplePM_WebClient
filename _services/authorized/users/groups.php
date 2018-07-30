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

// Получаем подробную информацию о текущем пользователе
$user_info = Security::getCurrentSession()['user_info']->getUserInfo();

// Производим проверку на наличие соответствующих разрешений
Security::CheckAccessPermissions(
	PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR,
	true
);

// Запрашиваем доступ к глобальным переменным
global $database;

/*
 * Производим запрос на выборку
 * подчинённых данному пользова
 * телю системы пользователей.
 */

// Формируем запрос к БД
$query_str = "
	SELECT
	  `id`,
	  `name`
	FROM
	  `spm_users_groups`
	WHERE
	  `teacherId` = '" . $user_info['id'] . "'
	;
";

// Выпоняем запрос и получаем данные
$groups_list = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

/*
 * Устанавливаем название и Layout сервиса
 */

define("__PAGE_TITLE__", _("Групи користувачів"));
define("__PAGE_LAYOUT__", "default");

?>

<!-- Edit group dialog -->

<form action="<?=_SPM_?>index.php?cmd=users/groups/edit" method="post">
	<div class="modal fade" id="edit_group_modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><?=_("Редагування користувацької групи")?></h5>
					<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				</div>
				<div class="modal-body">

					<label><strong><?=_("Ідентифікатор групи")?></strong></label>
					<input
							type="text"
							class="form-control"
							readonly
							name="id"
							id="edit_group_id"
							value=""

							required

							minlength="1"
							maxlength="255"
					>

					<label><strong><?=_("Назва групи")?></strong></label>
					<input
							type="text"
							class="form-control"
							name="name"
							id="edit_group_name"
							value=""

							required

							minlength="1"
							maxlength="255"
					>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_("Закрити")?></button>
					<button type="submit" class="btn btn-primary"><?=_("Зберегти зміни")?></button>
				</div>
			</div>
		</div>
	</div>
</form>

<script>

	document.addEventListener('DOMContentLoaded', function() {

		$('#edit_group_modal').on('show.bs.modal', function (event) {

			var button = $(event.relatedTarget);

			$('#edit_group_id').val(button.data('groupid'));
			$('#edit_group_name').val(button.data('groupname'));

		})

	});

</script>

<!-- /Edit group dialog -->

<div align="right" style="margin-top: 10px; margin-bottom: 40px;">

	<button
			class="btn btn-outline-success"

			data-groupid="NULL"
			data-groupname=""

			data-toggle="modal"
			data-target="#edit_group_modal"
	><?=_("Створити групу")?></button>

</div>

<?php if (sizeof($groups_list) > 0): ?>

	<div class="row">

		<?php foreach ($groups_list as $group_info): ?>

			<div class="col-md-4 col-sm-12">

				<div class="card" style="margin-bottom: 30px;">
					<div class="card-body">

						<h5 class="card-title"><?=$group_info['name']?></h5>

						<a
                                class="btn btn-outline-secondary btn-sm"

								href="<?=_SPM_?>index.php/problems/rating/?group=<?=$group_info['id']?>"
						><?=_("Користувачі")?></a>

						<a
                                class="btn btn-outline-secondary btn-sm"

								data-groupid="<?=$group_info['id']?>"
								data-groupname="<?=$group_info['name']?>"

								data-toggle="modal"
								data-target="#edit_group_modal"

                                href="#"
						><?=_("Редагувати")?></a>

						<a
                                class="btn btn-outline-danger btn-sm"

								onclick="return confirm('<?=_("Ви впевнені?")?>');"
                                href="<?=_SPM_?>index.php?cmd=users/groups/delete&group=<?=$group_info['id']?>"
						><?=_("Видалити")?></a>

					</div>
				</div>

			</div>

		<?php endforeach; ?>

	</div>

<?php else: ?>

	<p class="lead text-danger text-center" style="margin-top: 40px !important; margin-bottom: 50px !important;"><?=_("Ні однієї користувацької групи ще не створено.")?></p>

<?php endif; ?>