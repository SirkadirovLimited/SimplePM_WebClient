<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	if ( isset( $_SESSION["uid"] ) && (int)$_SESSION["uid"] > 0){
		
		if (!$db_result = $db->query("SELECT `sessionId`, `banned`, `online` FROM `spm_users` WHERE `id` = '" . mysqli_real_escape_string($db, $_SESSION['uid']) . "' LIMIT 1;"))
			die('<strong>Произошла ошибка при попытке подключения к базе данных! Пожалуйста, посетите сайт позже!</strong>');
		
		if ($db_result->num_rows == 0):
			unset($_SESSION);
			header('location: index.php');
			die;
		endif;
		
		$userInfo = $db_result->fetch_assoc();
		$db_result->free();
		unset($db_result);
		
		//Check if user banned
		if ($userInfo['banned'] == 1):
			
			if (!$db->query("UPDATE `spm_users` SET `online` = 0 WHERE `id` = '" . mysqli_real_escape_string($db, $_SESSION['uid']) . "' LIMIT 1;"))
				die('<strong>Произошла ошибка при попытке подключения к базе данных! Пожалуйста, посетите сайт позже!</strong>');
			
			unset($_SESSION);
			header('location: index.php');
			die;
		endif;
		//Check if another user logged in in the same account
		if ($userInfo['sessionId'] != session_id()):
			unset($_SESSION['uid']);
			header('location: index.php');
			die;
		endif;
	}
?>