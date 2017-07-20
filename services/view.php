<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	function view_page($id){
		
		global $_SPM_CONF;
		global $db;
		global $LANG;
		
		if (!$db_result = $db->query("SELECT * FROM `spm_pages` WHERE id = '$id' LIMIT 1;"))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($db_result->num_rows == 0)
			die(header('location: index.php?service=error&err=404'));
		else
		{
			
			$page_info = $db_result->fetch_assoc();
			
			$db_result->free();
			unset($db_result);
			
			SPM_header($page_info['name'], "Сторінка");
			print(htmlspecialchars_decode($page_info['content']));
			
			if (permission_check($_SESSION['permissions'], PERMISSION::administrator))
				print("<div align='right'><a href='index.php?service=view.admin&edit=" . $page_info['id'] . "' class='btn btn-default btn-xs'>Редагувати</a></div>");
			
			SPM_footer();
			
		}
		
	}
	
	if (!isset($_GET['id']) || (int)$_GET['id'] <= 0)
		die(header('location: index.php?service=error&err=404'));
	else{
		view_page((int)$_GET['id']);
	}
?>