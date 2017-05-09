<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::teacher | PERMISSION::administrator);
	
	//Include password generator file
	include_once(_S_INC_FUNC_ . "password_gen.php");
	
	function insertOrUpdateTeacherID(){
		global $_SPM_CONF;
		global $db;
		
		//Generate new TeacherID
		$teacherId = spm_generate_password($_SPM_CONF["TEACHERID"]["length"]);
		$teacherId_enabled = true;
		
		//New user permission set
		if (permission_check($_SESSION['permissions'], PERMISSION::administrator))
			$newUserPermission = 6;
		else
			$newUserPermission = 2;
		
		$tmp_query = "INSERT INTO
							`spm_teacherid` 
						SET
							`userId` = '" . $_SESSION['uid'] . "', 
							`teacherId` = '" . $teacherId . "', 
							`newUserPermission` = '" . $newUserPermission . "' 
						ON DUPLICATE KEY UPDATE 
							`teacherId` = '" . $teacherId . "'
						;";
		if (!$db->query($tmp_query))
			die(header('location: index.php?service=error&err=db_error'));
		
		unset($tmp_query);
		
		header('location: index.php?service=teacherID');
		exit;
	}
	
	if (isset($_POST['turnOn'])):
		if (!$db->query("UPDATE `spm_teacherid` SET `enabled` = true WHERE `userId` = '" . $_SESSION['uid'] . "' LIMIT 1;"))
			die('<strong>Произошла ошибка при выполнении запроса к базе данных! Пожалуйста, обновите страницу!</strong>');
		
		header('location: index.php?service=teacherID');
		exit;
	elseif (isset($_POST['turnOff'])):
		if (!$db->query("UPDATE `spm_teacherid` SET `enabled` = false WHERE `userId` = '" . $_SESSION['uid'] . "' LIMIT 1;"))
			die('<strong>Произошла ошибка при выполнении запроса к базе данных! Пожалуйста, обновите страницу!</strong>');
		
		header('location: index.php?service=teacherID');
		exit;
	elseif (isset($_POST['regenerate'])):
		insertOrUpdateTeacherID();
		
		header('location: index.php?service=teacherID');
		exit;
	endif;
	
	if (!$db_query = $db->query("SELECT `teacherId`,`enabled` FROM `spm_teacherid` WHERE `userId` = '" . $_SESSION['uid'] . "' LIMIT 1;"))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($db_query->num_rows == 0):
		
		insertOrUpdateTeacherID();
	else:
		
		$tmp = $db_query->fetch_assoc();
		
		$teacherId = $tmp["teacherId"];
		$teacherId_enabled = $tmp["enabled"];
		
		unset($tmp);
	endif;
	
	$db_query->free();
	unset($db_query);
	
	if ($teacherId_enabled)
		$teacherId_enabled = "success";
	else
		$teacherId_enabled = "danger";
	
	SPM_header("TeacherID");
?>
<div class="alert alert-<?=$teacherId_enabled?>" style="border-radius: 0;" align="center">
	<h1><?=$teacherId?></h1>
	<p><b>ВНИМАНИЕ!</b> Не передавайте этот код никому кроме ваших учеников, позаботьтесь о безопасности!</p>
</div>
<p class="lead">
	<form action="index.php?service=teacherID" method="post">
		<div class="row">
			<div class="col-md-4">
				<input type="submit" name="turnOn" value="Включить" class="btn btn-success btn-block btn-lg btn-flat">
			</div>
			<div class="col-md-4">
				<input type="submit" name="turnOff" value="Выключить" class="btn btn-danger btn-block btn-lg btn-flat">
			</div>
			<div class="col-md-4">
				<input type="submit" name="regenerate" value="Сгенерировать новый" class="btn btn-warning btn-block btn-lg btn-flat">
			</div>
		</div>
	</form>
</p>
<div class="alert alert-info" style="border-radius: 0;">
	<p class="lead"><strong>TeacherID</strong> - это ваш персональный идентификатор, с помощью которого ваши студенты или подчинённые могут регистрироваться в системе.</p>
</div>
<?php SPM_footer(); ?>