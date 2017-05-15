<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::olymp);
	
	isset($_GET['id']) && (int)$_GET['id'] > 0 or $_GET['id'] = 0;
	$_GET['id'] = (int)$_GET['id'];
	
	if (isset($_GET['id']) && (int)$_GET['id'] > 0){
		
		if (!permission_check($_SESSION['permissions'], PERMISSION::administrator))
			$query_addition = "WHERE `teacherId` = '" . $_SESSION['uid'] . "'";
		else
			$query_addition = "";
		
		$query_str = "SELECT * FROM `spm_olympiads` WHERE `id` = '" . (int)$_GET['id'] . "' " . $query_addition . " LIMIT 1;";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows == 0)
			die('<strong>Соревнование с таким ID не найдено!</strong>');
		
		$olymp_info = $query->fetch_assoc();
	} else {
		$olymp_info['id'] = "AUTO INCREMENT";
	}
	
	if (isset($_POST['sender']))
		include_once(_S_SERV_INC_ . "olympiads/edit.sender.php");
	
	SPM_header("Олимпиадный режим", "Редактирование олимпиады");
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
							<td><input type="text" class="form-control" value="<?=@$olymp_info['id']?>" disabled></td>
						</tr>
						<tr>
							<td>Тип соревнования<span style="color: red;" data-toggle="tooltip" data-placement="right" title="Поле обязательно для заполнения">*</span></td>
							<td>
								<select class="form-control" name="olympType" required>
									<option value="olymp" selected>Олимпиада / соревнование</option>
									<option value="classwork">Урок</option>
									<option value="testing">Контрольная работа</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Название олимпиады<span style="color: red;" data-toggle="tooltip" data-placement="right" title="Поле обязательно для заполнения">*</span></td>
							<td><input type="text" class="form-control" name="name" value="<?=@$olymp_info['name']?>" reqired></td>
						</tr>
						<tr>
							<td>Описание олимпиады<span style="color: red;" data-toggle="tooltip" data-placement="right" title="Поле обязательно для заполнения">*</span></td>
							<td><textarea class="form-control" style="resize: none;" name="description" rows="5" reqired><?=@$olymp_info['description']?></textarea></td>
						</tr>
						<tr>
							<td>Дата и время начала олимпиады<span style="color: red;" data-toggle="tooltip" data-placement="right" title="Поле обязательно для заполнения">*</span></td>
							<td>
								<input type="text" class="form-control" name="startTime" placeholder="ГГГГ-ММ-ДД ЧЧ:ММ:СС" value="<?=@$olymp_info['startTime']?>" reqired>
							</td>
						</tr>
						<tr>
							<td>Дата и время окончания олимпиады<span style="color: red;" data-toggle="tooltip" data-placement="right" title="Поле обязательно для заполнения">*</span></td>
							<td>
								<input type="text" class="form-control" name="endTime" placeholder="ГГГГ-ММ-ДД ЧЧ:ММ:СС" value="<?=@$olymp_info['endTime']?>" reqired>
							</td>
						</tr>
						<tr>
							<td>Минимальный балл<span style="color: red;" data-toggle="tooltip" data-placement="right" title="Поле обязательно для заполнения">*</span></td>
							<td>
								<input type="number" class="form-control" name="minb" placeholder="0" value="<?=@$olymp_info['minb']?>" reqired>
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
				<p>Список доступных для решения задач после создания олимпиады изменить нельзя!</p>
			</div>
			
		</div>
	</div>
	<div class="box box-danger box-solid" style="border-radius: 0;">
		<div class="box-header with-border" style="border-radius: 0;">
			<h3 class="box-title">Участники соревнования</h3>
		</div>
		<div class="box-body" style="padding: 0; margin: 0;">
			
			<div class="table-responsive" style="border-radius: 0; margin: 0;">
				<table class="table table-bordered" style="margin: 0;">
					<thead>
						<th width="30%">Название поля</th>
						<th width="70%">Значение поля</th>
					</thead>
					<tbody>
						<?php if (permission_check($_SESSION["permissions"], PERMISSION::administrator)): ?>
						<tr>
							<?php
								if(!$db_teacher_query = $db->query("SELECT `userId` FROM `spm_teacherid` WHERE `newUserPermission` = '2';"))
									die(header('location: index.php?service=error&err=db_error'));
							?>
							<td>Добавление участников по учителям<br/>(выбрать необходимые)</td>
							<td>
								<select class="form-control" size="5" name="users-teachers[]" multiple>
									<option selected>НЕТ</option>
									<?php
										while ($teacher_id = $db_teacher_query->fetch_assoc()):
										$teacher_id = $teacher_id["userId"];
										if (!$teacher_query = $db->query("SELECT `firstname`, `secondname`, `thirdname` FROM `spm_users` WHERE `id` = '" . $teacher_id . "' LIMIT 1;"))
											die(header('location: index.php?service=error&err=db_error'));
										$teacherInfo = $teacher_query->fetch_assoc();
									?>
									<option value="<?=$teacher_id?>">
										<?=$teacherInfo["secondname"] . " " . 
										   $teacherInfo["firstname"] . " " . 
										   $teacherInfo["thirdname"] . ", id" . 
										   $teacher_id
										?>
									</option>
									<?php endwhile; ?>
								</select>
							</td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			
			<div class="callout callout-warning" style="border-radius: 0;  margin: 0;">
				<p>Список участников олимпиады после создания олимпиады изменить нельзя!</p>
			</div>
			
		</div>
	</div>
<?php endif; ?>

	<div align="right">
		<a class="btn btn-danger btn-flat" href="index.php?service=olympiads">Отменить</a>
		<input type="reset" class="btn btn-warning btn-flat" value="Сбросить изменения">
		<input type="submit" class="btn btn-success btn-flat" name="sender" value="Применить">
	</div>
</form>

<?php
	SPM_footer();
?>