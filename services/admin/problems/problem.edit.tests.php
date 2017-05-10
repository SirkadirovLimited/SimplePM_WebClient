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
	
	if (isset($_POST['save'])){
		//Проверяем на правильность заполнения всех полей формы
		(isset($_POST['timeLimit']) && $_POST['timeLimit']>0)
			or die('<strong>Проверьте правильность заполнения полей формы!</strong>');
		(isset($_POST['memoryLimit']) && $_POST['memoryLimit']>0)
			or die('<strong>Проверьте правильность заполнения полей формы!</strong>');
		//Очищаем строки от возможных опасностей
		//id
		$_POST['id'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['id'])));
		//io
		@$_POST['input'] = mysqli_real_escape_string($db, $_POST['input']);
		@$_POST['output'] = mysqli_real_escape_string($db, $_POST['output']);
		//limits...
		$_POST['timeLimit'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['timeLimit'])));
		$_POST['memoryLimit'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['memoryLimit'])));
		
		(isset($_POST['input']) && strlen($_POST['input'])>0) or $_POST['input'] = "";
		(isset($_POST['output']) && strlen($_POST['output'])>0) or $_POST['output'] = "";
		
		//Сохраняем и применяем изменения
		//в базу данных SimplePM_WebClient
		$query_string = "UPDATE 
							`spm_problems_tests` 
						SET
							`input` = '" . $_POST['input'] . "',
							`output` = '" . $_POST['output'] . "',
							`timeLimit` = '" . $_POST['timeLimit'] . "',
							`memoryLimit` = '" . $_POST['memoryLimit'] . "'
						WHERE 
							`id` = '" . $_POST['id'] . "' 
						LIMIT 1;";
		if (!$db->query($query_string))
			die(header('location: index.php?service=error&err=db_error'));
		
		//Перекидываем пользователя на ту же страницу
		//на всякий случай.
		exit(header('location: '. $_SERVER["REQUEST_URI"]));
	} elseif (isset($_POST['del'])){
		//Очищаем строки от возможных опасностей
		$_POST['id'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['id'])));
		
		if (!$db->query("DELETE FROM `spm_problems_tests` WHERE `id` = '" . $_POST['id'] . "' LIMIT 1;"))
			die(header('location: index.php?service=error&err=db_error'));
		
		//Перекидываем пользователя на ту же страницу
		//на всякий случай.
		exit(header('location: '. $_SERVER["REQUEST_URI"]));
	} elseif (isset($_POST['addTest'])){
		//Добавляем новый пустой тест
		//для заданной задачи в базу данных
		if (!$db->query("INSERT INTO `spm_problems_tests` SET `problemId` = '" . (int)$_GET['id'] . "';"))
			die(header('location: index.php?service=error&err=db_error'));
		
		//Перекидываем пользователя на ту же страницу
		//на всякий случай.
		exit(header('location: '. $_SERVER["REQUEST_URI"]));
	}
	
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