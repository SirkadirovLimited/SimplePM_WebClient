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

				<a
                        href="<?=_SPM_?>index.php/problems/problem?id=<?=@$submission_info['problemId']?>"
                ><?=@$submission_info['problemId']?></a>

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

				<?php if ($submission_info['olympId'] > 0): ?>

					<a href="<?=_SPM_?>index.php/olympiads/olympiad/?id=<?=@$submission_info['olympId']?>" target="_blank">
						<?=_("№ ") . @$submission_info['olympId']?>
					</a>

				<?php else: ?>

					<?=_("зв'язок відсутній")?>

				<?php endif; ?>

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
							<pre class="modal-body" style="margin-bottom: 0;"><?=htmlspecialchars($submission_info['problemCode'])?></pre>
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