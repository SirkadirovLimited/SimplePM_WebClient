<?php

/*
 * ███████╗██╗███╗   ███╗██████╗ ██╗     ███████╗██████╗ ███╗   ███╗
 * ██╔════╝██║████╗ ████║██╔══██╗██║     ██╔════╝██╔══██╗████╗ ████║
 * ███████╗██║██╔████╔██║██████╔╝██║     █████╗  ██████╔╝██╔████╔██║
 * ╚════██║██║██║╚██╔╝██║██╔═══╝ ██║     ██╔══╝  ██╔═══╝ ██║╚██╔╝██║
 * ███████║██║██║ ╚═╝ ██║██║     ███████╗███████╗██║     ██║ ╚═╝ ██║
 * ╚══════╝╚═╝╚═╝     ╚═╝╚═╝     ╚══════╝╚══════╝╚═╝     ╚═╝     ╚═╝
 *
 * SimplePM WebApp
 * A part of SimplePM programming contests management system.
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

		<form method="post" action="">

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

				<label><?=_("Унікальний ідентифікатор модуля судді")?></label>

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
					<?=_("Вкажіть модуль судді, за допомогою якого під час змагання його учасники будуть отримувати рейтинг.")?>
				</small>

			</div>

			<h4 class="text-center" style="margin-top: 10px; margin-bottom: 10px;"><?=_("Виставлення оцінок")?></h4>

			<div class="form-group">

				<div class="custom-control custom-checkbox">
					<input
							type="checkbox"
							name="enableCitedScore"
							id="enableCitedScore"
							class="custom-control-input"
					>
					<label
							class="custom-control-label"
							for="enableCitedScore"
					><?=_("Зведене оцінювання користувацьких рішень")?></label>
				</div>

				<small class="form-text text-muted">
					<?=_("Увімкніть для розрахунку персональної оцінки для кожного учасника змагання.")?>
				</small>

			</div>

			<div class="row">

				<div class="col-md-6 col-sm-12">

					<div class="form-group">

						<label><?=_("Обов'язковий бал")?></label>

						<input
								type="number"
								class="form-control"

								name="requiredRating"
								value="1"

								min="1"
								max="16777215"

								required
						>

						<small class="form-text text-muted">
							<?=_("Вкажіть суму балів, за здобуття якої під час змагання користувач отримає найвищий бал, який вказано у полі \"Зведений бал\".")?>
						</small>

					</div>

				</div>

				<div class="col-md-6 col-sm-12">

					<div class="form-group">

						<label><?=_("Зведений бал")?></label>

						<input
								type="number"
								class="form-control"

								name="citedScore"
								value="12"

								minlength="1"
								maxlength="65535"
								required
						>

						<small class="form-text text-muted">
							<?=_("Вкажіть максимальний бал, що може отримати учасник змагання за вирішення задач, сума балів яких більше або дорівнює значенню поля \"Обов'язковий бал\".")?>
						</small>

					</div>

				</div>

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
							name="enablePenalty"
							id="enablePenalty"
							class="custom-control-input"
					>
					<label
							class="custom-control-label"
							for="enablePenalty"
					><?=_("Обчислювати персональне пенальті учасників змагання")?></label>
				</div>

				<small class="form-text text-muted">
					<?=_("Персональне пенальті використовується при проведенні більшості змагань зі спортивного програмування за правилами АСМ.")?>
					<?=_("Детальну інформацію про цей вид уточнення користувацького рейтингу Ви можете отримати на сторінках офіційних настанов з використання системи.")?>
				</small>

			</div>

			<div class="form-group">

				<div class="custom-control custom-checkbox">
					<input
							type="checkbox"
							name="redIfNoRequiredRating"
							id="redIfNoRequiredRating"
							class="custom-control-input"
					>
					<label
							class="custom-control-label"
							for="redIfNoRequiredRating"
					><?=_("Підкрашувати учасників змагання, які не набрали обов'язковий бал")?></label>
				</div>

				<small class="form-text text-muted">
					<?=_("Це не вплине на обчислення рейтингу, але надасть Вам можливість швидше з'ясувати, які з учасників змагання не встигли виконати вказану Вами квоту.")?>
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

			<div align="right">

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