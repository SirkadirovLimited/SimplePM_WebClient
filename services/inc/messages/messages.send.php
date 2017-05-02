<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__spm.messages.send_as_post__") or die('403 ACCESS DENIED');
	
	global $spm_sendmsg_user;
	
	isset($_POST["msg_title"]) && strlen(strip_tags(trim($_POST["msg_title"]) > 0)) or $_POST["msg_title"] = "Ничего важного:)";
	
	(strlen($_POST["msg_title"])>=1 && strlen($_POST["msg_title"])<=255) or die("<strong>Введённая вами тема не соответствует требованиям!</strong>");
	
	isset($_POST["msg_message"]) && ( strlen(strip_tags(trim($_POST["msg_message"])))>=2 && strlen($_POST["msg_message"])<=30000 ) or die("<strong>Введённое вами сообщение не соотвествует требованиям!</strong>");
	
	$_POST["msg_title"] = htmlspecialchars(strip_tags(trim($_POST["msg_title"])));
	$_POST["msg_message"] = htmlspecialchars(strip_tags($_POST["msg_message"]));
	
	if (!$db->query("INSERT INTO `spm_messages` (`date`,`from`,`to`,`title`,`message`) VALUES ('" . date("Y-m-d H:i:s") . "','" . $_SESSION["uid"] . "', '" . $spm_sendmsg_user["id"] . "','" . $_POST["msg_title"] . "','" . $_POST["msg_message"] . "');"))
		die("<strong>Произошла ошибка при попытке подключения к базе данных. Пожалуйста, повторите попытку позже!</strong>");
	
	header("location: index.php?service=messages.list");
?>