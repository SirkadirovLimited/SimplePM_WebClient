<?php
	deniedOrAllowed(PERMISSION::administrator);
	
	(isset($_GET['id']) && (int)$_GET['id'] > 0)
		or die(header('location: index.php?service=error&err=403'));
	
	$query_str = "
		SELECT
			count(`id`)
		FROM
			`spm_problems`
		WHERE
			`id` = '" . (int)$_GET['id'] . "'
		LIMIT
			1
		;
	";
	
	if (!$is_set = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($is_set->fetch_array()[0] <= 0)
		die(header('location: index.php?service=error&err=404'));
	
	$is_set->free();
	unset($is_set);
	
	include_once(_S_SERV_INC_ . "admin/problem.edit.tests.sender.php");
	
	/*
	 * ВЫБОРКА ТЕСТОВ УКАЗАННОЙ ЗАДАЧИ ИЗ БАЗЫ ДАННЫХ
	 */
	$query_str = "
		SELECT
			*
		FROM
			`spm_problems_tests`
		WHERE
			`problemID` = '" . (int)$_GET['id'] . "'
		;
	";
	
	if (!$tests_query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	SPM_header("Задача " . (int)$_GET['id'], "Управління тестами");
?>

<div align="left" style="margin-bottom: 10px;">
	<a href="index.php?service=problem.edit&id=<?=(int)$_GET['id']?>" class="btn btn-default btn-flat">
		<span class="glyphicon glyphicon-chevron-left"></span>
		&nbsp;Редагування задачі
	</a>
	<a href="index.php?service=problem.edit.tests&id=<?=(int)$_GET['id']?>" class="btn btn-default btn-flat">
		<span class="glyphicon glyphicon-upload"></span>
		&nbsp;Імпортувати тести
	</a>
</div>

<div class="alert alert-info alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<h4><i class="icon fa fa-info"></i> Зверніть увагу</h4>
	Редагування тестів за допомогою цього інтерфейсу вважається застарілим методом. Рекомендуємо вам використати 
	для цього SimplePM_TestsGenerator, що можна звантажити на <a href="https://spm.sirkadirov.com/" target="_blank">офіційному сайті SimplePM</a>.
</div>

<div class="table-responsive" style="margin: 0;">
	<table class="table table-bordered table-hover" style="background-color: white; margin: 0;">
		<thead>
			<th width="10%">ID тесту</th>
			<th width="30%">Вхідний потік</th>
			<th width="30%">Вихідний потік</th>
			<th width="10%" title="Ліміт процессорного часу">Time limit</th>
			<th width="15%" title="Ліміт пам'яті">Memory limit</th>
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
						><?=$testInfo['input']?></textarea>
					</td>
					<td style="padding: 0;">
						<textarea
							class="form-control"
							name="output"
							style="resize: none; height: 102px;"
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
						>Зберегти</button>
						<button
							type="reset"
							class="btn btn-warning btn-flat btn-block"
							style="margin: 0;"
						>Відміна</button>
						<button
							type="submit"
							class="btn btn-danger btn-flat btn-block"
							style="margin: 0;"
							name="del"
						>Видалити</button>
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
	>Додати новий тест</button>
</form>

<?php SPM_footer(); ?>