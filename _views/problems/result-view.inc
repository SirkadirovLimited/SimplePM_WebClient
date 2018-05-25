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
		<pre class="card-text" style="margin: 0;"><?=$submission_info['compiler_text']?></pre>
		<p class="card-text text-center" style="margin: 0; padding: 5px !important;">
			<strong><?=(!$submission_info['hasError'] ? _("Компіляція проведена успішно!") : _("Компіляція зазнала невдачі!"))?></strong>
		</p>
	</div>

</div>

<?php if (!$submission_info['hasError'] && $submission_info['testType'] != "syntax"): ?>

	<?php

	$submission_info['usedMemory'] = explode(
			"|",
			substr(
					$submission_info['usedMemory'],
					0,
					strlen(
							$submission_info['usedMemory']
					) - 1
			)
	);

	$submission_info['usedProcTime'] = explode(
			"|",
			substr(
				$submission_info['usedProcTime'],
				0,
				strlen(
					$submission_info['usedProcTime']
				) - 1
			)
	);

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
					<th><?=_("Пам'ять, байт")?></th>
					<th><?=_("Процесорний час, мс")?></th>
					<th><?=_("Код виходу")?></th>
					<th><?=_("Результат")?></th>

				</tr>

				</thead>

				<tbody>

				<?php $i = 0; foreach ($submission_info['tests_result'] as $test_result): ?>

					<tr class="<?=($test_result == '+' ? "" : "bg-danger text-white")?>">

						<td><?=($i + 1)?></td>
						<td><?=@$submission_info['usedMemory'][$i]?></td>
						<td><?=@$submission_info['usedProcTime'][$i]?></td>
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
