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

// Запрашиваем доступ к глобальным переменным
global $database;

?>

<style>

	.jumbotron-header {

		position: relative;

		background-color: #343a40 !important;
		color: white !important;

	}

	.jumbotron-header a {
		color: #fff !important;
	}

	.card {
		margin-bottom: 10px;
	}

	pre {

		background-color: #f8f9fa;
		padding: 10px;

	}

</style>

<div class="jumbotron jumbotron-fluid jumbotron-header">
	<div class="container" style="z-index: 2;">
		<h1><?=_("Результат тестування")?> №<?=$submission_info['submissionId']?></h1>
		<p class="lead">

			<a href="<?=_SPM_?>index.php/problems/problem?id=<?=$submission_info['problemId']?>"
			><?=_("Задача")?> №<?=$submission_info['problemId']?></a>&nbsp;/

			<?=_("Тип тестування")?>: <?=$submission_info['testType']?>&nbsp;/

			<a href="<?=_SPM_?>index.php/users/profile?id=<?=$submission_info['userId']?>"
			>Профіль користувача</a>

		</p>
	</div>
</div>

<?php include(_SPM_views_ . "problems/result-info-view.inc"); ?>

<div class="card">

	<div class="card-header"><?=_("Вихідний потік компілятора")?></div>

	<div class="card-body" style="padding: 0;">
		<pre class="card-text" style="margin: 0;"><?=trim($submission_info['compiler_text'])?></pre>
		<p class="card-text text-center" style="margin: 0; padding: 5px !important;">
			<strong><?=(!$submission_info['hasError'] ? _("Компіляція проведена успішно!") : _("Компіляція зазнала невдачі!"))?></strong>
		</p>
	</div>

</div>

<?php if (!$submission_info['hasError'] && $submission_info['testType'] != "syntax"): ?>

	<?php

    /*
     * Получаем информацию о всех
     * тестах к данной  здаче для
     * последующего вывода информ
     * ации о них в пользовательс
     * ком интерфейсе веб-приложе
     * ния.
     */

    // Формируем запрос на выборку данных из БД
    $query_str = sprintf("
        SELECT
          `id`,
          `input`,
          `timeLimit`,
          `memoryLimit`
        FROM
          `spm_problems_tests`
        WHERE
          `problemId` = '%s'
        ORDER BY
          `id` ASC
        ;
    ",
        $submission_info['problemId']
    );

    // Выполняем запрос и производим выборку всех данных из БД
    $tests_info = $database->query($query_str)->fetch_all(MYSQLI_BOTH);

    /*
     * Получаем информацию о кодах выхода
     * пользовательского решения.
     */

	$submission_info['exitcodes'] = explode(
			"|",
			substr(
				$submission_info['exitcodes'],
				0,
				strlen(
					$submission_info['exitcodes']
				) - 1
			)
	);

	/*
	 * Получаем информацию о результатах
	 * пользовательской   программы   по
	 * каждому тесту в отдельности.
	 */

	$submission_info['tests_result'] = explode(
			"|",
			substr(
				$submission_info['tests_result'],
				0,
				strlen(
					$submission_info['tests_result']
				) - 1
			)
	);

	?>

	<div class="card">

		<div class="card-header"><?=_("Результати тестування")?></div>

		<div class="card-body table-responsive" style="padding: 0;">

			<table class="table" style="margin: 0;">

				<thead>

				<tr>

					<th><?=_("ID тесту")?></th>
                    <th><?=_("Ліміт за часом CPU")?>, <abbr title="<?=_("Максимальна кількість використаного процесорного часу в міллісекундах")?>"><?=_("ms")?></abbr></th>
                    <th><?=_("Ліміт за пам'яттю")?>, <abbr title="<?=_("Максимальний розмір використаної пам'яті в байтах")?>"><?=_("bytes")?></abbr></th>
					<th><?=_("Код виходу")?></th>
					<th><?=_("Результат")?></th>

				</tr>

				</thead>

				<tbody>

				<?php $i = 0; foreach ($submission_info['tests_result'] as $test_result): ?>

					<tr class="<?=($test_result == '+' ? "" : "bg-danger text-white")?>">

						<td>

                            <?php if (Security::CheckAccessPermissions(PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR)): ?>

                            <abbr title="<?=$tests_info[$i]['input']?>"><?=$tests_info[$i]['id']?></abbr>

                            <?php else: ?>
                            <span><?=$tests_info[$i]['id']?></span>
                            <?php endif; ?>

                        </td>

                        <td><?=$tests_info[$i]['timeLimit']?></td>
                        <td><?=$tests_info[$i]['memoryLimit']?></td>

						<td><?=@$submission_info['exitcodes'][$i]?></td>
						<td><strong><?=$test_result?></strong></td>

					</tr>

				<?php $i++; endforeach; unset($i); ?>

				</tbody>

			</table>

		</div>

	</div>

<?php endif; ?>

<?php if (@$submission_info['testType'] == "debug"): ?>

	<div class="row">

		<div class="col-md-6 col-sm-12">

			<div class="card">

				<div class="card-header"><?=_("Вхідні дані (input.dat)")?></div>

				<div class="card-body" style="padding: 0;">
					<pre class="card-text" style="height: 5rem; overflow-y: scroll;"><?=
						(
						strlen(
							$submission_info['customTest']
						) > 0
							? $submission_info['customTest']
							: _("Вхідні дані не надані.")
						)
						?></pre>
				</div>

			</div>

		</div>

		<div class="col-md-6 col-sm-12">

			<div class="card">

				<div class="card-header"><?=_("Вихідні дані (output.dat)")?></div>

				<div class="card-body" style="padding: 0;">
					<pre class="card-text" style="height: 5rem; overflow-y: scroll;"><?=
						(
						strlen(
							$submission_info['output']
						) > 0
							? $submission_info['output']
							: _("Вихідні дані не надані.")
						)
						?></pre>
				</div>

			</div>

		</div>

	</div>

<?php endif; ?>

<?php if (strlen($submission_info['errorOutput']) > 0): ?>

	<div class="card text-white bg-danger">

		<div class="card-header"><?=_("Вихідний потік помилок")?></div>

		<div class="card-body" style="padding: 0;">
			<pre class="card-text" style="color: #fff; background-color: transparent;"><?=$submission_info['errorOutput']?></pre>
		</div>

	</div>

<?php endif; ?>

<div class="card">
	<div class="card-body text-center">
		<strong><?=sprintf(_("За вирішення цієї задачі отримано %s балів!"), number_format($submission_info['b'], 2))?></strong>
	</div>
</div>
