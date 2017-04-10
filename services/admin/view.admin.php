<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	define("__view.admin__", 1);
	
	global $db;
	global $_SPM_CONF;
	
	
	//CONTENT STARTS HERE
	if (isset($_GET['edit'])){
		SPM_header("Редактирование страницы");
		include_once(_S_SERV_INC_ . "view.admin.editor.php");
		SPM_footer();
	}elseif (isset($_GET['create'])){
		SPM_header("Создание страницы");
		include_once(_S_SERV_INC_ . "view.admin.editor.php");
		SPM_footer();
	}elseif (isset($_GET['del'])){
		SPM_header("Страницы сайта");
		include_once(_S_SERV_INC_ . "view.admin.del.php");
		include_once(_S_SERV_INC_ . "view.admin.list.php");
		SPM_footer();
	}else{
		SPM_header("Страницы сайта");
		include_once(_S_SERV_INC_ . "view.admin.list.php");
		SPM_footer();
	}
?>