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
 * тестов указанной задачи.
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

isset($_GET['id']) or Security::ThrowError("404");
$_GET['id'] = abs((int)$_GET['id']);

/*
 * Осуществляем необходимые define-ы.
 */

define("__PAGE_TITLE__", _("Редагування тестів до задачі"));
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Проверяем,  существует   ли   запрошенная
 * задача или нет, после чего делаем выводы.
 */

// Формируем запрос на выборку данных из БД
$query_str = sprintf("
	SELECT
	  count(`id`)
	FROM
	  `spm_problems`
	WHERE
	  `id` = '%s'
	;
", $_GET['id']);

// Выполняем запрос и производим проверки
if ((int)($database->query($query_str)->fetch_array()[0]) <= 0)
	Security::ThrowError("404");

/*
 * Производим выборку всех тестов
 * данной задачи из базы данных.
 */

// Формируем запрос на выборку данных из БД
$query_str = sprintf("
	SELECT
	  `id`,
	  `input`,
	  `output`,
	  `memoryLimit`,
	  `timeLimit`
	FROM
	  `spm_problems_tests`
	WHERE
  	  `problemId` = '%s'
  	ORDER BY
  	  `id` ASC
  	;
", $_GET['id']);

// Выполняем запрос и производим выборку всех данных
$tests_list = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

?>

<div class="card">
	<div class="card-body">

		<a
			href="<?=_SPM_?>index.php/problems/problem/?id=<?=$_GET['id']?>"
			class="btn btn-primary"
		><?=_("Повернутися до задачі")?></a>

		<a
			href="<?=_SPM_?>index.php?cmd=problems/edit/tests/add&pid=<?=$_GET['id']?>"
			class="btn btn-outline-dark"
		><?=_("Створити новий тест")?></a>

	</div>
</div>

<?php if (sizeof($tests_list) > 0): ?>

	<form
		class="table-responsive"

		method="post"
		enctype="multipart/form-data"
		action="<?=_SPM_?>index.php?cmd=problems/edit/tests/edit&pid=<?=$_GET['id']?>"
	>

		<table class="table">

			<thead>

			<tr>

				<th><?=_("ID")?></th>
				<th><?=_("Вхідні дані")?></th>
				<th><?=_("Вихідні дані")?></th>
				<th><?=_("Обмеження")?></th>

			</tr>

			</thead>

			<tbody>

			<?php foreach($tests_list as $test_info): ?>

				<tr>

					<td>

						#<?=$test_info['id']?><br>

						<input
							type="hidden"
							name="testId[]"
							value="<?=$test_info['id']?>"
						>

						<a
							href="<?=_SPM_?>index.php?cmd=problems/edit/tests/delete&id=<?=$test_info['id']?>&pid=<?=$_GET['id']?>"
							class="text-danger"
							onclick="return confirm('<?=_("Ви впевнені? Видалений тест не можна повернути!")?>');"
						><?=_("Видалити")?></a>

					</td>

					<td>

						<textarea
							class="form-control"
							style="height: 200px; min-height: 200px; margin: 0;"
							name="input[]"
						><?=htmlspecialchars($test_info['input'])?></textarea>

					</td>

					<td>

						<textarea
							class="form-control"
							style="height: 200px; min-height: 200px; margin: 0;"
							name="output[]"
						><?=htmlspecialchars($test_info['output'])?></textarea>

					</td>

					<td>

						<div class="form-group" style="margin-top: 0;">

							<label for="time<?=$test_info['id']?>"><?=_("Time limit")?></label>

							<input
								type="number"
								class="form-control"
								id="time<?=$test_info['id']?>"

								name="timeLimit[]"

								min="1"
								value="<?=$test_info['timeLimit']?>"
							>

							<small class="form-text text-muted">
								<?=_("Ліміт використаного процесорного часу в міллісекундах")?>
							</small>

						</div>

						<div class="form-group" style="margin-bottom: 0;">

							<label for="mem<?=$test_info['id']?>"><?=_("Memory limit")?></label>

							<input
								type="number"
								class="form-control"
								id="mem<?=$test_info['id']?>"

								name="memoryLimit[]"

								min="1"
								value="<?=$test_info['memoryLimit']?>"
							>

							<small class="form-text text-muted">
								<?=_("Ліміт використаної пам'яті в байтах")?>
							</small>

						</div>

					</td>

				</tr>

			<?php endforeach; ?>

			</tbody>

			<tfoot>

			<tr>

				<th><?=_("ID")?></th>
				<th><?=_("Вхідні дані")?></th>
				<th><?=_("Вихідні дані")?></th>
				<th><?=_("Обмеження")?></th>

			</tr>

			</tfoot>

		</table>

		<div align="right">

			<button
				type="reset"
				class="btn btn-danger"
			><?=_("Відмінити зміни")?></button>

			<button
				type="submit"
				class="btn btn-success"
			><?=_("Зберегти зміни")?></button>

		</div>

	</form>

<?php else: ?>

	<p class="lead text-center text-danger" style="margin-top: 100px; margin-bottom: 100px;">
		<?=_("Задача ще не має тестів, тому відправка рішень до неї заблокована!")?>
	</p>

<?php endif; ?>