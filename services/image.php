<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	global $_SPM_CONF;
	global $db;
	
	header("Content-type: image/*");
	
	if (isset($_GET['uid']) && ((int)$_GET['uid'] > 0)) {
		
		/*
		 * ФУНКЦИОНАЛ АВАТАРОВ ПОЛЬЗОВАТЕЛЕЙ
		 */
		
		$img_id = (int)$_GET['uid'];
		
		$db_result = $db->query("SELECT `avatar` FROM `spm_users` WHERE `id` = '" . $img_id . "' LIMIT 1;");
		if ($db_result->num_rows === 1) {
			$image = $db_result->fetch_assoc();
			
			if ($image['avatar'] != null)
				echo($image['avatar']);
			else {
				print(file_get_contents(_S_MEDIA_IMG_ . "no-avatar.png"));
			}
		}else{
			print(file_get_contents(_S_MEDIA_IMG_ . "no-avatar.png"));
		}
		
	}elseif (isset($_GET['id']) && ((int)$_GET['id'] > 0)) {
		
		/*
		 * ФУНКЦИОНАЛ ИЗОБРАЖЕНИЙ
		 */
		
		$img_id = (int)$_GET['id'];
		
		$db_result = $db->query("SELECT `content` FROM `spm_images` WHERE `id` = '" . $img_id . "' LIMIT 1;");
		if ($db_result->num_rows === 1) {
			$image = $db_result->fetch_assoc();
			
			print($image['content']);
		}else{
			print(file_get_contents(_S_MEDIA_IMG_ . "no-img.png"));
		}
		
	}
	else {
		print(file_get_contents(_S_MEDIA_IMG_ . "no-img.png"));
	}
	
	exit();
?>