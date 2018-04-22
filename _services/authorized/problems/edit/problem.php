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
 * Осуществляем проверку  на  наличие доступа
 * у текущего пользователя для редактирования
 * указанной задачи.
 */

Security::CheckAccessPermissions(
		Security::getCurrentSession()['user_info']->getUserInfo()['permissions'],
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
		  `id`,
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

				required
				disabled
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

        <div class="form-check">

			<input
					type="checkbox"
					name="enabled"
					id="enabled"
					class="form-check-input"

					<?=(@$problem_info['enabled'] ? "checked" : "")?>
			>

            <label for="enabled" class="form-check-label"><?=_("Задача доступна для перегляду та вирішення")?></label>

        </div>

        <small class="form-text text-muted">
            <?=_("Зверніть увагу на те, що цей параметр не блокує доступ до задачі адміністраторам системи.")?>
        </small>
    </div>

    <div class="form-group">
        <div class="form-check">

            <input
					type="checkbox"
					name="adaptProgramOutput"
					id="adaptProgramOutput"
					class="form-check-input"

					<?=(@$problem_info['adaptProgramOutput'] ? "checked" : "")?>
			>

            <label for="adaptProgramOutput" class="form-check-label">
				<?=_("Порівнювати очищені від зайвих пробілів вхідні потоки")?>
			</label>

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
		><?=@$problem_info['description']?></textarea>

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
				><?=@$problem_info['input_description']?></textarea>

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
				><?=@$problem_info['output_description']?></textarea>

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
        ><?=@$problem_info['authorSolution']?></textarea>

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

		<a
				href="<?=_SPM_?>index.php/problems/edit/tests/"
				class="btn btn-dark"
		><?=_("Редагувати тести до задачі")?></a>

        <button type="submit" class="btn btn-primary"><?=_("Зберегти зміни")?></button>

    </div>

</form>