<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::teacher);
	
	/////////////////////////////////////
	
	isset($_GET['id']) && (int)$_GET['id'] > 0 or $_GET['id'] = 0;
	$_GET['id'] = (int)$_GET['id'];
	
	/////////////////////////////////////
	
	if ($_GET['id'] > 0){
		
		$query_str = "
			SELECT
				*
			FROM
				`spm_classworks`
			WHERE
				`id` = '" . (int)$_GET['id'] . "'
			AND
				`teacherId` = '" . $_SESSION["uid"] . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows == 0)
			die('<strong>Урок с таким идентификатором не найден!</strong>');
		
		$cwork_info = $query->fetch_assoc();
		
	} else {
		$cwork_info['id'] = "AUTO INCREMENT";
		$cwork_info['minb'] = 0;
	}
	
	/////////////////////////////////////
	
	if (isset($_POST["sender"])){
		
		////////
		
		$_GET['id'] > 0 or $_GET['id'] = "null";
		
		////////
		
		isset($_POST["name"]) or die(header('location: ' . $_SERVER['REQUEST_URI']));
		isset($_POST["description"]) or die(header('location: ' . $_SERVER['REQUEST_URI']));
		
		isset($_POST["startTime"]) or die(header('location: ' . $_SERVER['REQUEST_URI']));
		isset($_POST["endTime"]) or die(header('location: ' . $_SERVER['REQUEST_URI']));
		
		isset($_POST["studentsGroup"]) or die(header('location: ' . $_SERVER['REQUEST_URI']));
		
		////////
		
		$_POST["name"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["name"])));
		$_POST["description"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["description"])));
		
		$_POST["startTime"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["startTime"])));
		$_POST["endTime"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["endTime"])));
		
		$_POST["studentsGroup"] = (int)$_POST["studentsGroup"];
		
		////////
		
		(strlen($_POST["name"]) > 0 && strlen($_POST["name"]) <= 255) or die(header('location: ' . $_SERVER['REQUEST_URI']));
		(strlen($_POST["description"]) > 0 && strlen($_POST["description"]) <= 65535) or die(header('location: ' . $_SERVER['REQUEST_URI']));
		
		(strlen($_POST["startTime"]) == 19) or die(header('location: ' . $_SERVER['REQUEST_URI']));
		(strlen($_POST["endTime"]) == 19) or die(header('location: ' . $_SERVER['REQUEST_URI']));
		
		$_POST["studentsGroup"] > 0 or die(header('location: ' . $_SERVER['REQUEST_URI']));
		
		////////
		
		$query_substr = "
				`name` = '" . $_POST["name"] . "',
				`description` = '" . $_POST["description"] . "',
				
				`startTime` = '" . $_POST["startTime"] . "',
				`endTime` = '" . $_POST["endTime"] . "',
				
				`studentsGroup` = '" . $_POST["studentsGroup"] . "',
				
				`teacherId` = '" . $_SESSION["uid"] . "'
		";
		$query_str = "
			INSERT INTO
				`spm_classworks`
			SET
				`id` = " . $_GET["id"] . ",
				
				" . $query_substr . "
			ON DUPLICATE KEY UPDATE
				" . $query_substr . "
			;
		";
		
		if (!$db->query($query_str))
			die(mysqli_error($db));//die(header('location: index.php?service=error&err=db_error'));
		
		////////
		
		//Получаем идентификатор урока
		$_classworkId = $db->insert_id;
		
		if (isset($_POST['problems-by-id'])){
		
			$problems = explode("\n", $_POST['problems-by-id']);
			
			foreach ($problems as $problem){
				$problemId = (int)mysqli_real_escape_string($db, strip_tags(trim($problem)));
				
				$query_str = "
							INSERT INTO
								`spm_classworks_problems`
							SET
								`classworkId` = '" . $_classworkId . "',
								`problemId` = '" . $problemId . "'
							;
				";
				
				@$db->query($query_str);
			}
			
		}
		
		////////
		
		exit(header('location: ' . $_SERVER['REQUEST_URI']));
	}
	
	SPM_header("Подсистема уроков", "Редактирование урока");
?>

<form action="<?=$_SERVER['REQUEST_URI']?>" method="post">
	
	<div class="box box-primary box-solid" style="border-radius: 0;">
		<div class="box-header with-border" style="border-radius: 0;">
			<h3 class="box-title">Основные настройки</h3>
		</div>
		<div class="box-body" style="padding: 0; margin: 0;">
			
			<div class="table-responsive" style="border-radius: 0; margin: 0;">
				<table class="table table-bordered" style="margin: 0;">
					<thead>
						<th width="30%">Название поля</th>
						<th width="70%">Значение поля</th>
					</thead>
					<tbody>
						<tr>
							<td>ID</td>
							<td><input type="text" class="form-control" value="<?=$cwork_info['id']?>" disabled></td>
						</tr>
						<tr>
							<td>Название урока<span style="color: red;" data-toggle="tooltip" data-placement="right" title="Поле обязательно для заполнения">*</span></td>
							<td><input type="text" class="form-control" name="name" value="<?=@$cwork_info['name']?>" reqired></td>
						</tr>
						<tr>
							<td>Описание урока<span style="color: red;" data-toggle="tooltip" data-placement="right" title="Поле обязательно для заполнения">*</span></td>
							<td><textarea class="form-control" style="resize: none;" name="description" rows="5" reqired><?=@$cwork_info['description']?></textarea></td>
						</tr>
						<tr>
							<td>Дата и время начала урока<span style="color: red;" data-toggle="tooltip" data-placement="right" title="Поле обязательно для заполнения">*</span></td>
							<td>
								<input type="text" class="form-control" name="startTime" placeholder="ГГГГ-ММ-ДД ЧЧ:ММ:СС" value="<?=@$cwork_info['startTime']?>" reqired>
							</td>
						</tr>
						<tr>
							<td>Дата и время окончания урока<span style="color: red;" data-toggle="tooltip" data-placement="right" title="Поле обязательно для заполнения">*</span></td>
							<td>
								<input type="text" class="form-control" name="endTime" placeholder="ГГГГ-ММ-ДД ЧЧ:ММ:СС" value="<?=@$cwork_info['endTime']?>" reqired>
							</td>
						</tr>
						
						<tr>
							<td>
								Группа учащихся<span style="color: red;" data-toggle="tooltip" data-placement="right" title="Поле обязательно для заполнения">*</span>
							</td>
							<td>
								<select name="studentsGroup" class="form-control">
<?php
	$query_str = "
		SELECT
			`id`,
			`name`
		FROM
			`spm_users_groups`
		WHERE
			`teacherId` = '" . $_SESSION["uid"] . "'
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	while ($group = $query->fetch_assoc()):
		if ($user_info['group'] == $group['id'])
			$selectedGroup = " selected";
		else
			$selectedGroup = "";
?>
					<option value="<?=$group['id']?>"<?=$selectedGroup?>><?=$group['name']?></option>
<?php endwhile; ?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>
	
<?php if ($_GET['id'] == 0): ?>
	<div class="box box-danger box-solid" style="border-radius: 0;">
		<div class="box-header with-border" style="border-radius: 0;">
			<h3 class="box-title">Список доступных задач</h3>
		</div>
		<div class="box-body" style="padding: 0; margin: 0;">
			
			<div class="table-responsive" style="border-radius: 0; margin: 0;">
				<table class="table table-bordered" style="margin: 0;">
					<thead>
						<th width="30%">Название поля</th>
						<th width="70%">Значение поля</th>
					</thead>
					<tbody>
						<tr>
							<td>Добавление задач по номерам<br/>(1 строка - 1 номер)</td>
							<td><textarea class="form-control" style="resize: none;" rows="5" name="problems-by-id"></textarea></td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="callout callout-warning" style="border-radius: 0;  margin: 0;">
				<p>Список доступных для решения задач после создания урока изменить нельзя!</p>
			</div>
			
		</div>
	</div>
<?php endif; ?>

	<div align="right">
		<a class="btn btn-danger btn-flat" href="index.php?service=classworks">Отменить</a>
		<input type="reset" class="btn btn-warning btn-flat" value="Сбросить изменения">
		<input type="submit" class="btn btn-success btn-flat" name="sender" value="Применить">
	</div>
</form>

<?php
	SPM_footer();
?>