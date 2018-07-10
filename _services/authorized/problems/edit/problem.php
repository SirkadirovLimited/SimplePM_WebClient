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
 * Осуществляем проверку  на  наличие доступа
 * у текущего пользователя для редактирования
 * указанной задачи.
 */

Security::CheckAccessPermissions(
		PERMISSION::ADMINISTRATOR | PERMISSION::TEACHER_MANAGE_PROBLEMS,
		true
);

/*
 * Осуществляем различные проверки
 * безопасности, а  также  очищаем
 * данные от возможного  вредонос-
 * ного содержимого.
 */

isset($_GET['id']) or $_GET['id'] = 0;
$_GET['id'] = abs((int)$_GET['id']);

/*
 * Осуществляем необходимые define-ы.
 */

define("__PAGE_TITLE__", _("Редагування задачі"));
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;
global $_CONFIG;

/*
 * Если идентификатор редактируемой
 * задачи  больше  нуля, производим
 * опрос базы данных системы о нали
 * чии информации о данной задаче.
 */

if ($_GET['id'] > 0)
{

	// Формируем запрос на выборку из БД
	$query_str = "
		SELECT
		  `enabled`,
		  `difficulty`,
		  `category_id`,
		  `name`,
		  `description`,
		  `input_description`,
		  `output_description`,
		  `authorSolution`,
		  `authorSolutionLanguage`,
		  `adaptProgramOutput`
		FROM
		  `spm_problems`
		WHERE
		  `id` = '" . $_GET['id'] . "'
		LIMIT
		  1
		;
	";

	// Производим запрос на выборку из БД
	$query = $database->query($query_str);

	/*
	 * Если соответствующие данные найдены,
	 * извлекаем их из временной таблицы.
	 *
	 * В другом случае  будем  считать, что
	 * данные есть, но они null.
	 */

	if ($query->num_rows > 0)
		$problem_info = $query->fetch_assoc();
	else
		$problem_info = null;

}

?>

<script src="<?=_SPM_assets_?>_plugins/trumbowyg/trumbowyg.min.js"></script>
<link rel="stylesheet" href="<?=_SPM_assets_?>_plugins/trumbowyg/ui/trumbowyg.min.css">
<script>

	document.addEventListener('DOMContentLoaded', function() {

		$('.editor').trumbowyg();

	});

</script>

<form action="<?=_SPM_?>index.php?cmd=problems/edit/problem" method="post" style="margin-top: 20px;">

    <!-- System information -->

    <div class="form-group">

        <label><strong><?=_("Ідентифікатор задачі")?></strong></label>

        <input
				type="text"
				name="id"
				class="form-control disabled"

				value="<?=@$_GET['id']?>"

				readonly
				required
		>

        <small class="form-text text-muted">
            <?=_("Ідентифікатор задачі, яку потрібно відредагувати. Заповнюється автоматично.")?>
        </small>

    </div>

    <!-- Base information -->

    <div class="form-group">

        <label><strong><?=_("Назва задачі")?></strong></label>

        <input
				type="text"
				name="name"
				class="form-control"

				value="<?=@$problem_info['name']?>"

				maxlength="255"
				required
		>

        <small class="form-text text-muted">
            <?=_("Вкажіть назву задачі. Вона повинна бути короткою, але в той самий час передавати основну ідею задачі.")?>
        </small>

    </div>

    <!-- Additional information -->

    <div class="form-group">

        <label><strong><?=_("Категорія задачі")?></strong></label>

        <select name="category_id" class="form-control" required>

			<?php

			$query_str = "
				SELECT
				  `id`,
				  `name`
				FROM
				  `spm_problems_categories`
				ORDER BY
				  `sort` ASC,
				  `id` ASC
				;
			";

			$problems_categories = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

			?>

            <option><?=_("Виберіть категорію задачі")?></option>

			<?php foreach ($problems_categories as $problem_category): ?>

				<option
					value="<?=$problem_category['id']?>"
					<?=($problem_category['id'] == @$problem_info['category_id'] ? "selected" : "")?>
				><?=$problem_category['name']?></option>

			<?php endforeach; unset($problems_categories); ?>

        </select>

        <small class="form-text text-muted">
            <?=_("Вкажіть категорію, в яку буде додана ця задача.")?>
        </small>

    </div>

    <div class="form-group">

        <label><strong><?=_("Складність задачі")?></strong></label>

        <input
            type="number"
            name="difficulty"
            class="form-control"

            min="1"
            max="255"
            value="<?=@$problem_info['difficulty']?>"

            required
        >

        <small class="form-text text-muted">
            <?=_("Вкажіть кількість балів, що будуть надаватись за повне вирішення цієї задачі.")?>
        </small>

    </div>

    <!-- Allow/deny actions -->

    <div class="form-group">

        <div class="custom-control custom-checkbox">
            <input
                    type="checkbox"
                    name="enabled"
                    id="enabled"
                    class="custom-control-input"

                    <?=(@$problem_info['enabled'] ? "checked" : "")?>
            >
            <label
                    class="custom-control-label"
                    for="enabled"
            ><?=_("Задача доступна для перегляду та вирішення")?></label>
        </div>

        <small class="form-text text-muted">
            <?=_("Зверніть увагу на те, що цей параметр не блокує доступ до задачі адміністраторам системи.")?>
        </small>

    </div>

    <div class="form-group">

        <div class="custom-control custom-checkbox">
            <input
                    type="checkbox"
                    name="adaptProgramOutput"
                    id="adaptProgramOutput"
                    class="custom-control-input"

                <?=(@$problem_info['adaptProgramOutput'] ? "checked" : "")?>
            >
            <label
                    class="custom-control-label"
                    for="adaptProgramOutput"
            ><?=_("Порівнювати очищені від зайвих пробілів вхідні потоки")?></label>
        </div>

        <small class="form-text text-muted">
            <?=_("Увімкніть для не суворої перевірки вихідних даних, вимкніть для суворої.")?>
        </small>

    </div>

    <!-- Description -->

    <div class="form-group">

		<label><strong><?=_("Детальні умови задачі")?></strong></label>

		<textarea
				name="description"
				class="form-control editor"

				maxlength="65535"
				required
		><?=@htmlspecialchars($problem_info['description'])?></textarea>

        <small class="form-text text-muted">
            <?=_("Надайте детальні умови задачі, включаючи всі вимоги, а також обмеження.")?>
        </small>

    </div>

    <!-- Input and output description  -->

    <div class="row">

        <div class="col-md-6 col-sm-12">

            <div class="form-group">

				<label><strong><?=_("Опис вхідних даних")?></strong></label>

                <textarea
						name="input_description"
						class="form-control editor"
				><?=@htmlspecialchars($problem_info['input_description'])?></textarea>

                <small class="form-text text-muted">
                    <?=_("Надайте детальний опис вхідних даних для користувацької програми.")?>
                </small>

            </div>

        </div>

        <div class="col-md-6 col-sm-12">

            <div class="form-group">

                <label><strong><?=_("Опис вихідних даних")?></strong></label>

                <textarea
						name="output_description"
						class="form-control editor"
				><?=@htmlspecialchars($problem_info['output_description'])?></textarea>

                <small class="form-text text-muted">
                    <?=_("Надайте детальний опис вихідних даних користувацької програми.")?>
                </small>

            </div>

        </div>

    </div>

    <!-- Author solution & its configuration -->

    <div class="form-group">

		<label><strong><?=_("Початковий код авторського рішення")?></strong></label>

		<textarea
            name="authorSolution"
            class="form-control"

			style="min-height: 400px;"

            required
        ><?=@htmlspecialchars($problem_info['authorSolution'])?></textarea>

        <small class="form-text text-muted">
            <?=_("Авторське рішення використовується для роботи debug-режиму тестування користувацьких рішень.")?>
        </small>

    </div>

    <div class="form-group">

		<label><strong><?=_("Мова програмування авторського рішення")?></strong></label>

        <select name="authorSolutionLanguage" class="form-control" required>

			<option value><?=_("Виберіть мову програмування")?></option>

			<?php foreach ($_CONFIG->getCompilersConfig() as $compiler): if ($compiler['enabled']): ?>

				<option
						value="<?=$compiler['language_name']?>"
						<?=(
						$compiler['language_name'] == @$problem_info['authorSolutionLanguage']
							? "selected"
							: ""
						)?>
				><?=$compiler['display_name']?> (<?=$compiler['language_name']?>)</option>

			<?php endif; endforeach; ?>

        </select>

        <small class="form-text text-muted">
            <?=_("Оберіть мову програмування, на якій було написано авторське рішення задачі.")?>
        </small>

    </div>

    <div align="right">

        <button type="reset" class="btn btn-outline-secondary"><?=_("Відмінити зміни")?></button>

	    <?php if ($_GET['id'] > 0): ?>

			<a
					href="<?=_SPM_?>index.php/problems/edit/tests/"
					class="btn btn-dark"
			><?=_("Редагувати тести до задачі")?></a>

	    <?php endif; ?>

        <button type="submit" class="btn btn-primary"><?=_("Зберегти зміни")?></button>

    </div>

</form>