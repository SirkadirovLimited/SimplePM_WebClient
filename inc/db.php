<?php
	//DATABASE CONFIGURATION FILE
	$db = new mysqli($_SPM_CONF["DB"]["host"], $_SPM_CONF["DB"]["user"], $_SPM_CONF["DB"]["pass"], $_SPM_CONF["DB"]["name"]); 
	if ($db->connect_errno){
		die('<h1>Database connection error. Sorry!</h1>');
	}
	$db->set_charset("utf8");
?>