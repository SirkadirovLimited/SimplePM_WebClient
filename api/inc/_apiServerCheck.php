<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	(isset($_SERVER["REMOTE_ADDR"]) && !empty($_SERVER["REMOTE_ADDR"])) or die("REMOTE_ADDR not set or empty!");
	
	$_remoteServerIP = htmlspecialchars(strip_tags(trim($_SERVER["REMOTE_ADDR"])));
	
	if (!$db_result = $db->query("SELECT `id` FROM `spm_servers` WHERE `address` = '" . $_remoteServerIP . "' LIMIT 1;"))
		die('Database connection error!');
	
	($db_result->num_rows > 0) or die('403 Access denied!');
	
	unset($db_result, $_remoteServerIP);
?>