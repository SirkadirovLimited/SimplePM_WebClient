<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	spm_setUserOnline($_SESSION["uid"], false);
	
	unset($_SESSION);
	
	session_destroy();
	
	header("location: index.php");
?>