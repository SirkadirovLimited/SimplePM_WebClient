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

?>

<div class="table-responsive" style="margin: 0;">

	<table class="table table-bordered table-hover">

		<tbody>

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Ідентифікатор запиту")?>
			</td>

			<td>
				<?=$submission_info['submissionId']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Ідентифікатор завдання")?>
			</td>

			<td>
				<?=$submission_info['problemId']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Дата та час запиту")?>
			</td>

			<td>
				<?=$submission_info['time']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Тип тестування")?>
			</td>

			<td>
				<?=$submission_info['testType']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Мова програмування")?>
			</td>

			<td>
				<?=$submission_info['codeLang']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Статус")?>
			</td>

			<td id="testing_status_info">
				<?=$submission_info['status']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Пов'язане змагання")?>
			</td>

			<td id="testing_status_info">
				<?=$submission_info['olympId'] > 0 ? "№" . $submission_info['olympId'] : _("зв'язок відсутній")?>
			</td>


		</tr>
		<!-- /PARAM -->

		</tbody>

	</table>

</div>