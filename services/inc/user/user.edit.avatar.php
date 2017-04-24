<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__spm.user.edit__") or die('403 ACCESS DENIED');
	
	global $user_info;
	
	require_once(_S_INC_CLASS_ . "SimpleImage.php");
	
	if (!permission_check($_SESSION["permissions"], PERMISSION::administrator) && ($user_info["teacherId"] != $_SESSION["uid"]) && ($user_info["id"] != $_SESSION["uid"])) {
		SPM_header("Ошибка 403");
		include(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["access_denied"]);
		SPM_footer();
		die();
	}
	
	if(!empty($_FILES['avatarFile']['name'])) {
		if ( $_FILES['avatarFile']['error'] == 0 ) {
			if( substr($_FILES['avatarFile']['type'], 0, 5)=='image' ) {
				
				$imgc = new SimpleImage();
				$imgc->load($_FILES['avatarFile']['tmp_name']);
				$imgc->resizeToWidth(400);
				$imgc->save($_FILES['avatarFile']['tmp_name'], IMAGETYPE_JPEG, 100);
				
				
				$image = file_get_contents( $_FILES['avatarFile']['tmp_name'] );
				$image = $db->real_escape_string($image);
				if(!$db->query("UPDATE `spm_users` SET `avatar` = '" . $image . "' WHERE `id` = '" . $user_info["id"] . "';"))
					die('<strong>Произошла ошибка при попытке записи информации в базу данных! Пожалуйста, повторите ваш запрос позже!</strong>');
				header('location: index.php?service=user.edit&id=' . $user_info["id"]);
			}else{
				die('<strong>Произошла ошибка при загрузке файла: файл не является изображением!</strong>');
			}
		}else{
			die('<strong>Произошла ошибка при загрузке файла: непредвиденная ошибка (файл имеет ошибки)!</strong>');
		}
	}else{
		die('<strong>Произошла ошибка при загрузке файла: файл не выбран!</strong>');
	}
?>