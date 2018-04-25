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
				<?=@$submission_info['submissionId']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Ідентифікатор завдання")?>
			</td>

			<td>
				<?=@$submission_info['problemId']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Дата та час запиту")?>
			</td>

			<td>
				<?=@$submission_info['time']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Тип тестування")?>
			</td>

			<td>
				<?=@$submission_info['testType']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Тип оцінювання")?>
			</td>

			<td>
				<?=@$submission_info['judge']?>
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
				<?=@$submission_info['status']?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Пов'язане змагання")?>
			</td>

			<td>
				<?=@$submission_info['olympId'] > 0 ? "№" . @$submission_info['olympId'] : _("зв'язок відсутній")?>
			</td>


		</tr>
		<!-- /PARAM -->

		<!-- PARAM -->
		<tr>

			<td>
				<?=_("Початковий код")?>
			</td>

			<td>

				<!-- Button trigger modal -->
				<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#SourceCodeModal">
					<?=_("Показати")?>
				</button>

				<!-- Modal -->
				<div class="modal fade" id="SourceCodeModal" tabindex="-1" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"><?=_("Початковий код запиту")?> (<?=$submission_info['codeLang']?>)</h5>
								<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
							</div>
							<pre class="modal-body"><?=htmlspecialchars($submission_info['problemCode'])?></pre>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити вікно</button>
							</div>
						</div>
					</div>
				</div>

			</td>


		</tr>
		<!-- /PARAM -->

		</tbody>

	</table>

</div>