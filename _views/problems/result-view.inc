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

</style>

<div class="jumbotron jumbotron-fluid jumbotron-header">
	<div class="container" style="z-index: 2;">
		<h1><?=_("Результат тестування")?> №<?=$submission_info['submissionId']?></h1>
		<p class="lead">

			<a href="<?=_SPM_?>index.php/problems/problem?id=<?=$submission_info['problemId']?>">
				<?=_("Задача")?> №<?=$submission_info['problemId']?>
			</a> /

			<?=_("Тип тестування")?>: <?=$submission_info['testType']?> /

			<a href="">
				Профіль користувача
			</a>

		</p>
	</div>
</div>

