<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	if (!$db_result = $db->query("UPDATE `spm_users` SET `online` = '0' WHERE `id` = " . $_SESSION['uid'] . ";"))
			die(header('location: index.php?service=error&err=db_error'));
	
	unset($_SESSION);
	
	session_destroy();
	
	header("location: index.php");
?>