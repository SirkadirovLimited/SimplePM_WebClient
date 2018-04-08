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

			<a href="<?=_SPM_?>index.php/problems/problem?id=<?=$submission_info['problemId']?>">
				<?=_("Задача")?> №<?=$submission_info['problemId']?>
			</a> /

			<?=_("Тип тестування")?>: <?=$submission_info['testType']?> /

			<a href="<?=_SPM_?>index.php/users/profile?id=<?=$submission_info['userId']?>">
				Профіль користувача
			</a>

		</p>
	</div>
</div>

<?php include(_SPM_views_ . "problems/result-info-view.inc"); ?>

<?php if (strlen($submission_info['compiler_text']) > 0): ?>

	<div class="card">

		<div class="card-header">Вихідний потік компілятора</div>

		<div class="card-body" style="padding: 0;">
			<pre class="card-text"><?=$submission_info['compiler_text']?></pre>
		</div>

	</div>

<?php endif; ?>

<?php if (strlen($submission_info['errorOutput']) > 0): ?>

	<div class="card text-white bg-danger">

		<div class="card-header">Стандартний потік помилок</div>

		<div class="card-body" style="padding: 0;">
			<pre class="card-text" style="color: #fff; background-color: transparent;"><?=$submission_info['errorOutput']?></pre>
		</div>

	</div>

<?php endif; ?>
