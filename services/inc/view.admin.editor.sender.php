<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__view.admin__") or die('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	global $_SPM_CONF;
	global $db;
	
	if (isset($_GET['edit']) && isset($_POST['pname']) && isset($_POST['pcontent'])){
		
		if(!$db_check = $db->query("SELECT * FROM `spm_pages` WHERE id = '" . htmlspecialchars(trim($_GET['edit'])) . "'"))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($db_check->num_rows === 0){
			die(header('location: index.php?service=error&err=404'));
		}else{
			if(!$db->query("UPDATE `spm_pages` SET `name` = '" . mysqli_real_escape_string($db, trim($_POST['pname'])) . "', `content` = '" . mysqli_real_escape_string($db, trim($_POST['pcontent'])) . "' WHERE `id` = " . mysqli_real_escape_string($db, trim($_GET['edit'])) . ";"))
				die(header('location: index.php?service=error&err=db_error'));
			else{
				$link = "index.php?service=view&id=" . (int)$_GET['edit'];
				_spm_view_msg("Зміни було записано. <a href='$link'>Перейти на сторінку</a>", "success");
				unset($link);
			}
		}
		unset($db_check);
		
	}elseif (!isset($_GET['edit']) && isset($_POST['pname']) && isset($_POST['pcontent'])){
		
		if(!$db_result = $db->query("INSERT INTO `spm_pages` (`name`, `content`) VALUES ('" . mysqli_real_escape_string($db, trim($_POST['pname'])) . "', '" . mysqli_real_escape_string($db, trim($_POST['pcontent'])) . "');")){
			die(header('location: index.php?service=error&err=db_error'));
			exit;
		}else{
			$link = "index.php?service=view&id=" . $db->insert_id;
			_spm_view_msg("Ви успішно створили сторінку. Не бажаєте <a href='$link'>подивитись</a>, що з цього вийшло?", "success");
			unset($link);
		}
		unset($db_result);
		
	}
?>