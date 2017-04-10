<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__view.admin__") or die('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	if (isset($_GET['edit']) && isset($_POST['pname']) && isset($_POST['pcontent'])){
		
		if(!$db_check = $db->query("SELECT * FROM `spm_news` WHERE id = '" . htmlspecialchars(trim($_GET['edit'])) . "'"))
			die('Произошла неизвестная ошибка при обращении к базе данных!');
		
		if ($db_check->num_rows === 0){
			_spm_view_msg("Страница которую вы пытались изменить не существует или была перенесена на новый адрес.", "danger");
			exit;
		}else{
			if(!$db->query("UPDATE `spm_news` SET `title` = '" . htmlspecialchars(trim($_POST['pname'])) . "', `content` = '" . htmlspecialchars(trim($_POST['pcontent'])) . "' WHERE `id` = " . htmlspecialchars(trim($_GET['edit'])) . ";"))
				die('<b>Произошла неизвестная ошибка при обращении к базе данных!</b>');
			else{
				_spm_view_msg("Внесённые вами изменения были успешно сохранены.", "success");
				unset($link);
			}
		}
		unset($db_check);
		
	}elseif (!isset($_GET['edit']) && isset($_POST['pname']) && isset($_POST['pcontent'])){
		
		if(!$db->query("INSERT INTO `spm_news` (`authorID`,`date`,`title`,`content`) VALUES ('" . $_SESSION['uid'] . "', '" . date("Y-m-d") . "', '" . htmlspecialchars(trim($_POST['pname'])) . "', '" . htmlspecialchars(trim($_POST['pcontent'])) . "');")){
			_spm_view_msg("Произошла непредвиденная ошибка при попытке подключения к базе данных. Обратитесь к системному администратору.", "danger");
			exit;
		}else{
			_spm_view_msg("Новость успешно опубликована на сайте!", "success");
			unset($link);
		}
		
	}
?>