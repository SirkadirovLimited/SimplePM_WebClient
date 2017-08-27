<?php
	deniedOrAllowed(PERMISSION::administrator);
	define("__spm_admin_problems_edit__", 1);
	
	isset($_GET['id']) && (int)$_GET['id']>=0 or $_GET['id'] = null;
	
	if (isset($_POST['save']))
		include_once(_S_SERV_INC_ . "admin/problem.edit.sender.php");
	
	if ($_GET['id'] != null)
	{
		
		if (!$query_ptr = $db->query("SELECT * FROM `spm_problems` WHERE `id` = '" . (int)$_GET['id'] . "' LIMIT 1;"))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query_ptr->num_rows == 0)
			die(header('location: index.php?service=error&err=no_rows'));
		
		$problem_info = $query_ptr->fetch_assoc();
		
	}
	else
	{
		
		$problem_info['id'] = null;
		$problem_info['enabled'] = 1;
		$problem_info['difficulty'] = 1;
		$problem_info['catId'] = 0;
		$problem_info['name'] = "";
		$problem_info['description'] = "";
		$problem_info['debugTimeLimit'] = 200;
		$problem_info['debugMemoryLimit'] = 20971520;
		$problem_info['input'] = "";
		$problem_info['output'] = "";
		
	}
	
	SPM_header("Задача " . $problem_info['id'], "Редагування задачі", "Управління задачами");
?>

<?php
	!isset($_GET['success']) or _spm_view_msg("Умови задачі було успішно змінено! Просимо зазначити, що рейтинг користувачів при цьому не зміниться.", "success");
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

<form action="index.php?service=problem.edit&id=<?=$problem_info['id']?>" method="post">
	<div class="panel panel-primary" style="border-radius: 0; margin: 0;">
		<div class="panel-heading" style="border-radius: 0;">
			<h3 class="panel-title">Базова інформація</h3>
		</div>
		<div class="panel-body" style="padding: 0;">
			
			<div class="table-responsive" style="border-radius: 0; margin: 0;">
				<table class="table table-bordered" style="margin: 0; min-width: 500px;">
					<tbody>
						
						<tr>
							<td>
								<strong>Назва задачі</strong>
							</td>
							<td style="padding: 0;">
								<input type="text" name="name" class="form-control" placeholder="Hello, world!" maxlength="255" value="<?=$problem_info['name']?>" required>
							</td>
						</tr>
						
						<tr>
							<td>
								<strong>Задача доступна для подачі рішень</strong><br/>
								(відноситься лише до вільного режиму)
							</td>
							<td>
								<input type="checkbox" name="enabled" value="1" <?=($problem_info['enabled']) ? 'checked' : ''?>>
							</td>
						</tr>
						
						<tr>
							<td>
								<strong>Категорія задачі</strong>
							</td>
							<td style="padding: 0;">
								<?php
									if (!$db_cat_result = $db->query("SELECT * FROM `spm_problems_categories`;"))
										die(header('location: index.php?service=error&err=db_error'));
								?>
								<select name="catId" class="form-control" required>
									<option>Не выбрана</option>
									
									<?php while ($problem_category = $db_cat_result->fetch_assoc()): ?>
									<option value="<?=$problem_category['id']?>" <?=($problem_category['id'] == $problem_info['catId']) ? 'selected' : ''?>><?=$problem_category['name']?></option>
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
								<strong>Складність задачі</strong>
							</td>
							<td style="padding: 0;">
								<input type="number" name="difficulty" class="form-control" min="1" max="100" value="<?=$problem_info['difficulty']?>" required>
							</td>
						</tr>
						
						<tr>
							<td>
								<strong>Текст задачі</strong>
							</td>
							<td style="padding: 0;">
								<textarea name="description" class="form-control editor" style="resize: none;" rows="10" maxlength="65535"><?=$problem_info['description']?></textarea>
							</td>
						</tr>
						
						<tr>
							<th>
								<strong>Опис потоків</strong>
							</th>
							<td style="padding: 0;">
								<!--STREAM DESCRIPTION-->
								<table class="table" style="width: 100%; height: 100%; margin: 0;">
									<thead>
										<th>Опис вхідного потоку</th>
										<th>Опис вихідного потоку</th>
									</thead>
									<tbody>
										<tr>
											<td style="padding: 0;">
												<textarea
													name="input"
													class="form-control"
													style="resize: none;"
													rows="5"
													placeholder="У вхідному потоці..."
													maxlength="65535"
												><?=$problem_info['input']?></textarea>
											</td>
											<td style="padding: 0;">
												<textarea
													name="output"
													class="form-control"
													style="resize: none;"
													rows="5"
													placeholder="У виходному потоці..."
													maxlength="65535"
												><?=$problem_info['output']?></textarea>
											</td>
										</tr>
									</tbody>
								</table>
								<!--/STREAM DESCRIPTION-->
							</td>
						</tr>
						
						<tr>
							<td>
								<strong>Ліміт процессорного часу (debug)</strong>
							</td>
							<td style="padding: 0;">
								<div class="input-group">
									<input
										type="number"
										name="debugTimeLimit"
										class="form-control"
										value="<?=$problem_info['debugTimeLimit']?>"
										required
									>
									<span class="input-group-addon">мс</span>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Ліміт пам'яті (debug)</strong>
							</td>
							<td style="padding: 0;">
								<div class="input-group">
									<input
										type="number"
										name="debugMemoryLimit"
										class="form-control"
										value="<?=$problem_info['debugMemoryLimit']?>"
										required
									>
									<span class="input-group-addon">Байт</span>
								</div>
							</td>
						</tr>
						
					</tbody>
				</table>
			</div>
			<div class="row-fluid">
				<div class="col-md-3" style="padding: 0;">
					<button type="reset" class="btn btn-danger btn-flat btn-block" style="margin: 0; padding: 10px;">
						Відмінити зміни
					</button>
				</div>
				<div class="col-md-6" style="padding: 0;">
					<?php if($problem_info['id'] != null): ?>
					<a href="index.php?service=problem.edit.tests&id=<?=$problem_info['id']?>"
					   class="btn btn-warning btn-flat btn-block"
					   style="padding: 10px; margin: 0;"
					>
						Управління тестами
					</a>
					<?php endif; ?>
				</div>
				<div class="col-md-3" style="padding: 0;">
					<button type="submit" class="btn btn-success btn-flat btn-block" style="margin: 0; padding: 10px;" name="save">
						Зберегти зміни
					</button>
				</div>
			</div>
		</div>
	</div>
</form>

<?php
	SPM_footer();
?>