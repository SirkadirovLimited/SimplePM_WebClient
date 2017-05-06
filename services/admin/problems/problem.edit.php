<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	isset($_GET['id']) && (int)$_GET['id']>=0 or $_GET['id'] = 0;
	
	SPM_header("Задача " . (int)$_GET['id'], "Редактирование задачи", "Управление задачами");
?>

<script src="<?=_S_TPL_?>js/tinymce/tinymce.min.js"></script>
<script>
	tinymce.init({
		selector: '.editor',
		height: 300,
		theme: 'modern',
		plugins: [
			'image imagetools'
		]
	});
</script>

<div class="panel panel-primary" style="border-radius: 0; margin: 0;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Основная информация</h3>
	</div>
	<div class="panel-body" style="padding: 0;">
		
		<div class="table-responsive" style="border-radius: 0; margin: 0;">
			<table class="table table-bordered" style="margin: 0; min-width: 500px;">
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
							<input type="text" class="form-control" placeholder="Hello, world!" maxlength="255" required>
						</td>
					</tr>
					<tr>
						<td>
							Задача доступна
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
							<textarea class="form-control editor" style="resize: none;" rows="10" placeholder="" maxlength="65535" required></textarea>
						</td>
					</tr>
					<tr>
						<th>
							Описание потоков
						</th>
						<td style="padding: 0;">
							<!--STREAM DESCRIPTION-->
							<table class="table" style="width: 100%; height: 100%; margin: 0;">
								<thead>
									<th>Описание входного потока</th>
									<th>Описание выходного потока</th>
								</thead>
								<tbody>
									<tr>
										<td style="padding: 0;"><textarea class="form-control" style="resize: none;" rows="5" placeholder="Во входном потоке..." maxlength="65535" required></textarea></td>
										<td style="padding: 0;"><textarea class="form-control" style="resize: none;" rows="5" placeholder="В выходном потоке..." maxlength="65535" required></textarea></td>
									</tr>
								</tbody>
							</table>
							<!--/STREAM DESCRIPTION-->
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="row-fluid">
			<div class="col-md-3" style="padding: 0;">
				<input type="reset" class="btn btn-danger btn-flat btn-block" style="margin: 0; padding: 10px;" value="Отменить изменения">
			</div>
			<div class="col-md-6" style="padding: 0;">
				<?php if($_GET['id'] > 0): ?>
				<a href=""
				   class="btn btn-warning btn-flat btn-block"
				   style="padding: 10px; margin: 0;"
				>
					<span class="glyphicon glyphicon-exclamation-sign"></span> Управление тестами
				</a>
				<?php endif; ?>
			</div>
			<div class="col-md-3" style="padding: 0;">
				<input type="submit" class="btn btn-success btn-flat btn-block" style="margin: 0; padding: 10px;" value="Сохранить изменения">
			</div>
		</div>
	</div>
</div>

<?php
	SPM_footer();
?>