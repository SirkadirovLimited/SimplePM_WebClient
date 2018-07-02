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