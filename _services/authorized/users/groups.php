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