<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__spm.user.edit__") or die('403 ACCESS DENIED');
	
	global $user_info;
	
	$user_id = (int)trim(strip_tags($_GET['id']));
	$old_passwd = htmlspecialchars(trim(strip_tags($_POST['old-password'])));
	$passwd = htmlspecialchars(trim(strip_tags($_POST['password'])));
	$passwd_retype = htmlspecialchars(trim(strip_tags($_POST['password2'])));
	
	$errors_count = 0;
	
	/*
	 * ПЕРВЫЙ ШАГ ОТСЕИВАНИЯ ДУШ
	 * "Испытание стрингленом"
	*/
	
	if ($user_id <= 0)
		$errors_count++;
	
	
	
	if (!(strlen($old_passwd) >= $_SPM_CONF["PASSWD"]["minlength"]) || !(strlen($old_passwd) <= $_SPM_CONF["PASSWD"]["maxlength"]))
		die(1);
	
	if (!(strlen($passwd) >= $_SPM_CONF["PASSWD"]["minlength"]) || !(strlen($passwd) <= $_SPM_CONF["PASSWD"]["maxlength"]))
		$errors_count++;
	
	if (!(strlen($passwd_retype) >= $_SPM_CONF["PASSWD"]["minlength"]) || !(strlen($passwd_retype) <= $_SPM_CONF["PASSWD"]["maxlength"]))
		$errors_count++;
	
	if ($passwd != $passwd_retype)
		$errors_count++;
	
	if ($errors_count > 0){
		print("<strong>Вы некорректно заполнили форму изменения пароля! Возможные причины: Пароль не соответствует требованиям (от 5 до 25 символов); пароли не одинаковы; одно из полей не указано.</strong>");
		print("<meta http-equiv='refresh' content='3;URL=index.php?service=user.edit&id=$user_id' />");
		die();
	}
	
	/*
	 * ИЗМЕНЕНИЕ ПАРОЛЯ
	*/
	
	$old_passwd = md5(md5(md5($old_passwd)));
	$passwd = md5(md5(md5($passwd)));
	
	if (!$db_pass = $db->query("SELECT * FROM `spm_users` WHERE (`id`='" . $user_id . "' AND `password` = '" . $old_passwd . "') LIMIT 1;"))
		die("<strong>Возникла ошибка при попытке подключения к базе данных! Пожалуйста, посетите сайт позже!</strong>");
	
	if ($db_pass->num_rows === 0){
		print("<strong>Вы некорректно заполнили форму изменения пароля!</strong>");
		print("<meta http-equiv='refresh' content='3;URL=index.php?service=user.edit&id=$user_id' />");
		die();
	}
	$db_pass->free();
	unset($db_pass);
	
	if (!$db->query("UPDATE `spm_users` SET `password` = '" . $passwd . "' WHERE `id`='" . $user_id . "'"))
		die("<strong>Возникла ошибка при попытке подключения к базе данных / пользователь не найден / введены неверные данные!</strong>");
?>