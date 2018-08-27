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
 * Обеспечиваем дополнительный
 * уровень безопасности.
 */

isset($_GET['id']) or $_GET['id'] = 0;
$_GET['id'] = abs((int)$_GET['id']);

/*
 * Глобальные константы
 */

define("__PAGE_TITLE__", ($_GET['id'] > 0 ? _("Редагування інформації про змагання №") . $_GET['id'] : _("Створення нового змагання")));
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * В случае, если на данный момент
 * происходит  редактирование  уже
 * существующего соревнования, про
 * изводим выборку всей информации
 * о нём из базы данных системы.
 */

if ($_GET['id'] > 0)
{

    /*
     * Получаем информацию об указанном соревновании
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
        AND
          `id` = '%s'
        ORDER BY
          `startTime` DESC,
          `endTime` DESC,
          `id` DESC
        ;
    ",
        Security::getCurrentSession()['user_info']->getUserId(),
        $_GET['id']
    );

    // Выполняем запрос к БД и производим выборку данных
    $olympiad_info = $database->query($query_str)->fetch_assoc();

}

?>

<div class="card">

    <div class="card-header">

        <ul class="nav nav-tabs card-header-tabs">

            <li class="nav-item">
                <a class="nav-link" href="<?=_SPM_?>index.php/olympiads/list/"><?=_("Список змагань")?></a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?=$_GET['id'] == 0 ? "active" : ""?>" href="<?=_SPM_?>index.php/olympiads/edit/"><?=_("Створити змагання")?></a>
            </li>

			<?php if ($_GET['id'] > 0): ?>

				<li class="nav-item">
					<a class="nav-link active" href="<?=_SPM_?>index.php/olympiads/edit/?id=<?=$_GET['id']?>"><?=_("Редагування змагання")?></a>
				</li>

			<?php endif; ?>

        </ul>

    </div>

    <div class="card-body">

		<form method="post" action="" style="margin: 0;">

			<h4 class="text-center" style="margin-top: 10px; margin-bottom: 10px;"><?=_("Основна інформація")?></h4>

			<input
					type="hidden"

					name="id"
					value="<?=$_GET['id']?>"
			>

			<div class="form-group">

				<label><?=_("Назва змагання")?></label>

				<input
						type="text"
						class="form-control"

						name="name"
						value=""

						minlength="1"
						maxlength="255"
						required
				>

				<small class="form-text text-muted">
					<?=_("Вкажіть назву змагання, що буде використовуватися для візуальної ідентифікації цього змагання. Назва не є унікальною та доступна для перегляду всім користувачам системи.")?>
				</small>

			</div>

			<div class="form-group">

				<label><?=_("Опис змагання")?></label>

				<textarea
						class="form-control"
						style="resize: none; height: 100px;"

						name="description"

						maxlength="1000"

				></textarea>

				<small class="form-text text-muted">
					<?=_("Опис змагання не є обов'язковим для заповнення, але є бажаним для надання важливої інформації щодо змагання.")?>
				</small>

			</div>

			<div class="row">

				<div class="col-md-6 col-sm-12">

					<div class="form-group">

						<label><?=_("Розпочинається")?></label>

						<input
								type="datetime-local"
								class="form-control"

								name="startTime"
								value=""

								required
						>

						<small class="form-text text-muted">
							<?=_("Вкажіть дату та час початку змагання.")?>
						</small>

					</div>

				</div>

				<div class="col-md-6 col-sm-12">

					<div class="form-group">

						<label><?=_("Завершується")?></label>

						<input
								type="datetime-local"
								class="form-control"

								name="endTime"
								value=""

								required
						>

						<small class="form-text text-muted">
							<?=_("Вкажіть дату та час завершення змагання.")?>
						</small>

					</div>

				</div>

			</div>

			<div class="form-group">

				<label><?=_("Включені задачі")?></label>

				<textarea
						class="form-control"
						style="resize: none; height: 100px;"

						name="problems_list"

						minlength="1"
						maxlength="16777215"

						required
				></textarea>

				<small class="form-text text-muted">
					<?=_("Вкажіть через кому задачі, які учасники змагання повинні будуть вирішити.")?>
				</small>

			</div>

			<h4 class="text-center" style="margin-top: 10px; margin-bottom: 10px;"><?=_("Оцінювання")?></h4>

			<div class="form-group">

				<label><?=_("Унікальний ідентифікатор плагіну судді")?></label>

				<input
						type="text"
						class="form-control"

						name="judge"
						value=""

						minlength="1"
						maxlength="255"
						required
				>

				<small class="form-text text-muted">
					<?=_("Вкажіть унікальний ідентифікатор плагіну судді, за допомогою якого під час змагання його учасники будуть отримувати рейтинг.")?>
				</small>

			</div>
			
			<div class="form-group">

				<label><?=_("Унікальний ідентифікатор плагіна формування рейтингової таблиці")?></label>

				<input
						type="text"
						class="form-control"

						name="ratingGenerator"
						value=""

						minlength="1"
						maxlength="255"
						required
				>

				<small class="form-text text-muted">
					<?=_("Вкажіть унікальний ідентифікатор плагіну, що буде нести відповідальність за формування рейтингової таблиці змагання.")?>
				</small>

			</div>

			<h4 class="text-center" style="margin-top: 10px; margin-bottom: 10px;"><?=_("Додаткові параметри")?></h4>

			<div class="form-group">

				<div class="custom-control custom-checkbox">
					<input
							type="checkbox"
							name="userCanExit"
							id="userCanExit"
							class="custom-control-input"
					>
					<label
							class="custom-control-label"
							for="userCanExit"
					><?=_("Учасник може виходити зі змагання та повертатися до нього")?></label>
				</div>

				<small class="form-text text-muted">
					<?=_("Якщо Ви бажаєте, щоб після виконання завдань у цьому змаганні його учасники перейшли до виконання інших завдань у системі, увімкніть цей чекбокс.")?>
				</small>

			</div>
			
			<div class="form-group">

				<div class="custom-control custom-checkbox">
					<input
							type="checkbox"
							name="disableCopyPaste"
							id="disableCopyPaste"
							class="custom-control-input"
					>
					<label
							class="custom-control-label"
							for="disableCopyPaste"
					><?=_("Заборонити копіювання та вставку")?></label>
				</div>

				<small class="form-text text-muted">
					<?=_("Відімкнути можливість копіювання та вставки, а також використання інших подібних функцій в системі для учасників змагання.")?>
				</small>

			</div>

			<div class="saving-buttons-group">

				<button
						type="reset"
						class="btn btn-outline-secondary"
				><?=_("Відмінити зміни")?></button>

				<button
						type="submit"
						class="btn btn-primary"
				><?=_("Зберегти зміни")?></button>

			</div>

		</form>

    </div>

</div>