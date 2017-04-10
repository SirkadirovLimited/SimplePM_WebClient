<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__view.admin__") or die('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	global $_SPM_CONF;
	global $db;
	
	if (isset($_GET['edit']) && isset($_POST['pname']) && isset($_POST['pcontent'])){
		
		if(!$db_check = $db->query("SELECT * FROM `spm_pages` WHERE id = '" . htmlspecialchars(trim($_GET['edit'])) . "'"))
			die('Произошла неизвестная ошибка при обращении к базе данных!');
		
		if ($db_check->num_rows === 0){
			_spm_view_msg("Страница которую вы пытались изменить не существует или была перенесена на новый адрес.", "danger");
			exit;
		}else{
			if(!$db->query("UPDATE `spm_pages` SET `name` = '" . htmlspecialchars(trim($_POST['pname'])) . "', `content` = '" . htmlspecialchars(trim($_POST['pcontent'])) . "' WHERE `id` = " . htmlspecialchars(trim($_GET['edit'])) . ";"))
				die('<b>Произошла неизвестная ошибка при обращении к базе данных!</b>');
			else{
				$link = $_SPM_CONF["BASE"]["SITE_URL"] . "index.php?service=view&id=" . htmlspecialchars(trim($_GET['edit']));
				_spm_view_msg("Внесённые вами изменения были успешно сохранены. Не желаете ли вы <a href='$link'>просмотреть</a> их?", "success");
				unset($link);
			}
		}
		unset($db_check);
		
	}elseif (!isset($_GET['edit']) && isset($_POST['pname']) && isset($_POST['pcontent'])){
		
		if(!$db_result = $db->query("INSERT INTO `spm_pages` (`name`, `content`) VALUES ('" . htmlspecialchars(trim($_POST['pname'])) . "', '" . htmlspecialchars(trim($_POST['pcontent'])) . "');")){
			_spm_view_msg("Произошла непредвиденная ошибка при попытке подключения к базе данных. Обратитесь к системному администратору.", "danger");
			exit;
		}else{
			$link = $_SPM_CONF["BASE"]["SITE_URL"] . "index.php?service=view&id=" . $db->insert_id;
			_spm_view_msg("Страница успешно создана. Не желаете ли вы <a href='$link'>просмотреть</a> её?", "success");
			unset($link);
		}
		unset($db_result);
		
	}
?>