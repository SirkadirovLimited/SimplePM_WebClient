<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	if (!$db_res = $db->query("SELECT * FROM `spm_messages` WHERE `id` = '" . (int)$_GET['del'] . "' AND (`to` = '" . $_SESSION["uid"] . "' or `from` = '" . $_SESSION["uid"] . "') LIMIT 1;"))
		die("<strong>Произошла ошибка при попытке доступа к базе данных! Перезагрузите страницу.</strong>");
	
	if ($db_res->num_rows === 1){
		$message = $db_res->fetch_assoc();
		
		if (($message["from"] == $_SESSION["uid"]) && ($message["to"] == $_SESSION["uid"])) {
			
			if (!$db->query("DELETE FROM `spm_messages` WHERE `id`='" . (int)$_GET['del'] . "';"))
				die('<strong>Данное сообщение удалить не возможно!</strong>');
			
		} elseif ($message["from"] == $_SESSION["uid"]) {
			
			if (!$db->query("UPDATE `spm_messages` SET `delFrom` = '1' WHERE `id`='" . (int)$_GET['del'] . "' LIMIT 1;"))
				die('<strong>Данное сообщение удалить не возможно!</strong>');
			if ($message["delTo"] == 1){
				if (!$db->query("DELETE FROM `spm_messages` WHERE `id`='" . (int)$_GET['del'] . "' LIMIT 1;"))
					die('<strong>Данное сообщение удалить не возможно!</strong>');
			}
			
		} elseif ($message["to"] == $_SESSION["uid"]) {
			
			if (!$db->query("UPDATE `spm_messages` SET `delTo` = '1' WHERE `id`='" . (int)$_GET['del'] . "' LIMIT 1;"))
				die('<strong>Данное сообщение удалить не возможно!</strong>');
			if ($message["delFrom"] == 1){
				if (!$db->query("DELETE FROM `spm_messages` WHERE `id`='" . (int)$_GET['del'] . "' LIMIT 1;"))
					die('<strong>Данное сообщение удалить не возможно!</strong>');
			}
			
		}
		
		header('location: index.php?service=messages.list');
	}
	elseif ($db_res->num_rows == 0)
		die("<strong>Сообщение с указанным ID не найдено, пустота удалена успешно!</strong>");
	
	$db_res->free();
	unset($db_res);
?>