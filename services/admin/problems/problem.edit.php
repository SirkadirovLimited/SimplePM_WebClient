<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	isset($_GET['id']) && (int)$_GET['id']>=0 or $_GET['id'] = 0;
	
	SPM_header("Задача " . (int)$_GET['id'], "Редактирование задачи", "Управление задачами");
?>
<div class="panel panel-primary" style="border-radius: 0;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Основная информация</h3>
	</div>
	<div class="panel-body" style="padding: 0;">
		
		<div class="table-responsive" style="border-radius: 0; margin: 0;">
			<table class="table table-bordered" style="margin: 0;">
				<thead>
					<th width="30%">Название поля</th>
					<th width="70%">Значение поля</th>
				</thead>
				<tbody>
					<tr>
						<td>
							Название задачи
						</td>
						<td style="padding: 0;">
							<input type="text" class="form-control" placeholder="Hello, world!" required>
						</td>
					</tr>
					<tr>
						<td>
							Задача доступна для решения пользователями
						</td>
						<td>
							<input type="checkbox" checked="1">
						</td>
					</tr>
					<tr>
						<td>
							Категория задачи
						</td>
						<td style="padding: 0;">
							<?php
								if (!$db_cat_result = $db->query("SELECT * FROM `spm_problems_categories`;"))
									die(header('location: index.php?service=error&err=db_error'));
							?>
							<select class="form-control" required>
								<option selected>Не выбрана</option>
								
								<?php while ($problem_category = $db_cat_result->fetch_assoc()): ?>
								<option value="<?=$problem_category['id']?>"><?=$problem_category['name']?></option>
								<?php endwhile; ?>
								
								<?php unset($problem_category); ?>
							</select>
							<?php
								$db_cat_result->free();
								unset($db_cat_result);
							?>
						</td>
					</tr>
					<tr>
						<td>
							Сложность задачи
						</td>
						<td style="padding: 0;">
							<input type="number" class="form-control" min="1" max="100" value="1" placeholder="Сложность задачи" required>
						</td>
					</tr>
					<tr>
						<td>
							Текст задачи
						</td>
						<td style="padding: 0;">
							<textarea class="form-control" style="resize: none;" rows="10" placeholder="" required></textarea>
						</td>
					</tr>
					<tr>
						<td>
							Описание потоков
						</td>
						<td style="padding: 0;">
							<!--STREAM DESCRIPTION-->
							<table class="table" style="width: 100%; height: 100%; margin: 0;">
								<thead>
									<th>Описание входного потока</th>
									<th>Описание выходного потока</th>
								</thead>
								<tbody>
									<tr>
										<td style="padding: 0;"><textarea class="form-control" style="resize: none;" rows="5" placeholder="" required></textarea></td>
										<td style="padding: 0;"><textarea class="form-control" style="resize: none;" rows="5" placeholder="" required></textarea></td>
									</tr>
								</tbody>
							</table>
							<!--/STREAM DESCRIPTION-->
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</div>
</div>
<?php if($_GET['id'] > 0): ?>
<div class="panel panel-primary" style="border-radius: 0;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Тесты</h3>
	</div>
	<div class="panel-body" style="padding: 0;">
		
		<div class="table-responsive" style="border-radius: 0; margin: 0;">
			<table class="table table-bordered" style="margin: 0;">
				<thead>
					<th width="10%">TEST_ID</th>
					<th width="45%">Входной поток</th>
					<th width="45%">Выходной поток</th>
				</thead>
				<tbody>
					<tr>
						<td><b>1</b></td>
						<td style="padding: 0;">
							<textarea class="form-control" rows="5" style="resize: none; margin: 0;" placeholder="Входной поток"></textarea>
						</td>
						<td style="padding: 0;">
							<textarea class="form-control" rows="5" style="resize: none; margin: 0;" placeholder="Выходной поток"></textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<a href="" class="btn btn-default btn-flat btn-block">Добавить тест...</a>
		
	</div>
</div>
<?php endif; ?>

<input type="submit" class="btn btn-success btn-flat btn-block" value="Сохранить изменения">
<input type="reset" class="btn btn-danger btn-flat btn-block" style="margin-top: 0;" value="Отменить изменения">
<?php
	SPM_footer();
?>