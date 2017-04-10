<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	function view_page($id){
		
		global $_SPM_CONF;
		global $db;
		
		if (!$db_result = $db->query("SELECT * FROM `spm_pages` WHERE id = '$id'")){
			SPM_header("DB error");
			print("<strong>Произошла ошибка при отправке запроса к базе данных. Посетите данную страницу позже.</strong>");
			SPM_footer();
			exit;
		}
		
		if ($db_result->num_rows === 0){
			SPM_Header("Ошибка 404");
			include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["404"]);
			SPM_footer();
		}else{
			
			$page_info = $db_result->fetch_assoc();
			
			$db_result->free();
			unset($db_result);
			
			SPM_header($page_info['name'], "Страница");
			print(htmlspecialchars_decode($page_info['content']));
			if (permission_check($_SESSION['permissions'], PERMISSION::administrator)){
				print("<div align='right'><a href='index.php?service=view.admin&edit=" . $page_info['id'] . "' class='btn btn-default btn-xs'>Редактировать</a></div>");
			}
			SPM_footer();
			
			unset($page_info);
		}
		
	}
	
	if (!isset($_GET['id']) || strlen(trim($_GET['id'])) == 0){
		SPM_header("404");
		include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["404"]);
		SPM_footer();
	}else{
		$id = intval( htmlspecialchars( trim( $_GET['id'] ) ), 0 ); //Stay safe
		view_page($id);
	}
?>