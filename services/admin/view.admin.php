<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	define("__view.admin__", 1);
	
	global $db;
	global $_SPM_CONF;
	
	
	//CONTENT STARTS HERE
	if (isset($_GET['edit'])){
		SPM_header("Сторінки сайту", "Редагування");
		include_once(_S_VIEW_ . "view/view.admin.editor.php");
		SPM_footer();
	}elseif (isset($_GET['create'])){
		SPM_header("Сторінки сайту", "Створення");
		include_once(_S_VIEW_ . "view/view.admin.editor.php");
		SPM_footer();
	}elseif (isset($_GET['del'])){
		SPM_header("Сторінки сайту", "Список");
		include_once(_S_SERV_INC_ . "view.admin.del.php");
		include_once(_S_VIEW_ . "view/view.admin.list.php");
		SPM_footer();
	}else{
		SPM_header("Сторінки сайту", "Список");
		include_once(_S_VIEW_ . "view/view.admin.list.php");
		SPM_footer();
	}
?>