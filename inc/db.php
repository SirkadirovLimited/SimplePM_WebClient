<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	//DATABASE CONFIGURATION FILE
	$db = new mysqli($_SPM_CONF["DB"]["host"], $_SPM_CONF["DB"]["user"], $_SPM_CONF["DB"]["pass"], $_SPM_CONF["DB"]["name"]); 
	if ($db->connect_errno){
		die('При попытке подключения к базе данных возникла непредвиденная ошибка. Пожалуйста, посетите сайт позже!');
	}
	$db->set_charset("utf8");
?>