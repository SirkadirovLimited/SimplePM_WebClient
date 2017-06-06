<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::teacher | PERMISSION::administrator);
	
	/////////////////////////////////////
	//        REQUIRED INCLUDES        //
	/////////////////////////////////////
	
	include_once(_S_INC_FUNC_ . "password_gen.php");
	
	/////////////////////////////////////
	//    TEACHERID INSERT FUNCTION    //
	/////////////////////////////////////
	
	function insertOrUpdateTeacherID($userId = null){
		
		global $_SPM_CONF;
		global $db;
		
		if ($userId == null)
			$userId = $_SESSION['uid'];
		
		//Generate new TeacherID
		$teacherId = spm_generate_password($_SPM_CONF["TEACHERID"]["length"]);
		
		$query_str = "
			SELECT
				`permissions`
			FROM
				`spm_users`
			WHERE
				`id` = '" . (int)$userId . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		$userPermissions = $query->fetch_array()[0];
		$query->free();
		
		//New user permission set
		if (permission_check($userPermissions, PERMISSION::administrator))
			$newUserPermission = 6;
		else
			$newUserPermission = 2;
		
		$query_str = "
			INSERT INTO
				`spm_teacherid` 
			SET
				`userId` = '" . $userId . "', 
				`teacherId` = '" . $teacherId . "', 
				`newUserPermission` = '" . $newUserPermission . "' 
			ON DUPLICATE KEY UPDATE 
				`teacherId` = '" . $teacherId . "'
			;
		";
		
		if (!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		exit(header('location: ' . $_SERVER["REQUEST_URI"]));
		
	}
	
	/////////////////////////////////////
	//             COMMANDS            //
	/////////////////////////////////////
	
	/* Turn TeacherID on */
	if (isset($_POST['turnOn'])):
		
		$query_str = "
			UPDATE
				`spm_teacherid`
			SET
				`enabled` = true
			WHERE
				`userId` = '" . $_SESSION['uid'] . "'
			LIMIT
				1
			;
		";
		
		if (!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		exit(header('location: index.php?service=teacherID'));
		
	/* Turn TeacherID off */
	elseif (isset($_POST['turnOff'])):
		
		$query_str = "
			UPDATE
				`spm_teacherid`
			SET
				`enabled` = false
			WHERE
				`userId` = '" . $_SESSION['uid'] . "'
			LIMIT
				1
			;
		";
		
		if (!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		exit(header('location: index.php?service=teacherID'));
		
	/* Generate new TeacherID */
	elseif (isset($_POST['regenerate'])):
		
		insertOrUpdateTeacherID();
		
		exit(header('location: index.php?service=teacherID'));
		
	/* Generate new TeacherIDs for all teachers and admins */
	elseif (isset($_POST['fullRegenerate'])):
		
		$query_str = "
			SELECT
				`userId`
			FROM
				`spm_teacherid`
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows > 0)
			while ($usr = $query->fetch_assoc())
				insertOrUpdateTeacherID($usr['userId']);
		
		$query->free();
		
	endif;
	
	/////////////////////////////////////
	//       TEACHERID SELECTOR        //
	/////////////////////////////////////
	
	$query_str ="
		SELECT
			`teacherId`,
			`enabled`
		FROM
			`spm_teacherid`
		WHERE
			`userId` = '" . $_SESSION['uid'] . "'
		LIMIT
			1
		;
	";
	
	if (!$db_query = $db->query($query_str))
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
	
	/////////////////////////////////////
	//   VARIABLES REINITIALIZATION    //
	/////////////////////////////////////
	
	$teacherId_enabled = ($teacherId_enabled ? "success" : "danger");
	
	/////////////////////////////////////
	//           LOAD HEADER           //
	/////////////////////////////////////
	
	SPM_header("TeacherID", "Управление");
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

<?php if (permission_check($_SESSION["permissions"], PERMISSION::administrator)): ?>
<div align="right">
	<form action="<?=$_SERVER["REQUEST_URI"]?>" method="post">
		<button
			type="submit"
			class="btn btn-danger btn-xs btn-flat"
			name="fullRegenerate"
			onclick="return confirm('ВНИМАНИЕ! Данное действие на слабом сервере и при большом количестве учителей и администраторов временно может вывести Web-сервер из работоспособного состояния! ВЫ УВЕРЕНЫ?');"
		>
			Сгенерировать новые TeacherID для всех
		</button>
	</form>
</div>
<?php endif; ?>

<?php SPM_footer(); ?>