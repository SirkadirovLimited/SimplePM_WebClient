<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	(isset($_GET['id']) && (int)$_GET['id'] > 0)
		or die('<strong>ID задачи не указан либо указан не верно!</strong>');
	
	if (!$is_set = $db->query("SELECT count(`id`) FROM `spm_problems` WHERE `id` = '" . (int)$_GET['id'] . "' LIMIT 1;"))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($is_set->fetch_array()[0] <= 0)
		die('<strong>Задача с указанным ID не существует!</strong>');
	
	$is_set->free();
	unset($is_set);
	
	include_once(_S_SERV_INC_ . "admin/problem.edit.tests.sender.php");
	
	/*
	 * ВЫБОРКА ТЕСТОВ УКАЗАННОЙ ЗАДАЧИ ИЗ БАЗЫ ДАННЫХ
	 */
	if (!$tests_query = $db->query("SELECT * FROM `spm_problems_tests` WHERE `problemID` = '" . (int)$_GET['id'] . "';"))
		die(header('location: index.php?service=error&err=db_error'));
	
	SPM_header("Задача " . (int)$_GET['id'], "Управление тестами");
?>

<div align="left" style="margin-bottom: 10px;">
	<a href="index.php?service=problem.edit&id=<?=(int)$_GET['id']?>" class="btn btn-default btn-flat">
		<span class="glyphicon glyphicon-chevron-left"></span>
		&nbsp;Редактирование задачи
	</a>
</div>

<div class="table-responsive" style="margin: 0;">
	<table class="table table-bordered table-hover" style="background-color: white; margin: 0;">
		<thead>
			<th width="10%">ID теста</th>
			<th width="30%">Входной поток</th>
			<th width="30%">Выходной поток</th>
			<th width="10%">Time limit</th>
			<th width="15%">Memory limit</th>
			<th width="5%">Действия</th>
		</thead>
		<tbody>
<?php while ($testInfo = $tests_query->fetch_assoc()): ?>
			<tr>
				<form action="<?=$_SERVER['REQUEST_URI']?>" method="post">
					<td>
						<?=$testInfo['id']?>
						<input type="hidden" name="id" value="<?=$testInfo['id']?>">
					</td>
					<td style="padding: 0;">
						<textarea
							class="form-control"
							name="input"
							style="resize: none; height: 102px;"
							placeholder="Во входном потоке..."
						><?=$testInfo['input']?></textarea>
					</td>
					<td style="padding: 0;">
						<textarea
							class="form-control"
							name="output"
							style="resize: none; height: 102px;"
							placeholder="В выходном потоке..."
						><?=$testInfo['output']?></textarea>
					</td>
					<td style="padding: 0;">
						<textarea
							class="form-control"
							name="timeLimit"
							style="resize: none; height: 102px;"
							required
						><?=$testInfo['timeLimit']?></textarea>
					</td>
					<td style="padding: 0;">
						<textarea
							class="form-control"
							name="memoryLimit"
							style="resize: none; height: 102px;"
							required
						><?=$testInfo['memoryLimit']?></textarea>
					</td>
					<td style="padding: 0;">
						<button
							type="submit"
							class="btn btn-success btn-flat btn-block"
							style="margin: 0;"
							name="save"
						>SAVE</button>
						<button
							type="reset"
							class="btn btn-warning btn-flat btn-block"
							style="margin: 0;"
						>CANCEL</button>
						<button
							type="submit"
							class="btn btn-danger btn-flat btn-block"
							style="margin: 0;"
							name="del"
						>DEL</button>
					</td>
				</form>
			</tr>
<?php endwhile; ?>
		</tbody>
	</table>
</div>
<form action="<?=$_SERVER["REQUEST_URI"]?>" method="post">
	<button
		class="btn btn-primary btn-flat btn-block"
		type="submit"
		name="addTest"
	>Добавить новый тест</button>
</form>

<?php SPM_footer(); ?>