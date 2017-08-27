<?php
	defined("__spm.user.edit__") or die('403 ACCESS DENIED');
	
	global $user_info;
	
	//SQL Injection cleaner
	$user_id = (int)mysqli_real_escape_string($db, trim(strip_tags($_GET['id'])));
	$old_passwd = mysqli_real_escape_string($db, trim(strip_tags($_POST['old-password'])));
	$passwd =mysqli_real_escape_string($db, trim(strip_tags($_POST['password'])));
	$passwd_retype = mysqli_real_escape_string($db, trim(strip_tags($_POST['password2'])));
	
	//Errors count variable
	$errors_count = 0;
	
	/*
	 * ПЕРВЫЙ ШАГ ОТСЕИВАНИЯ ДУШ
	 * "Испытание стрингленом"
	 */
	
	if ($user_id <= 0)
		$errors_count++;
	
	//Old passworld check
	if (!permission_check($_SESSION["permissions"], PERMISSION::administrator) && $teacherId != $_SESSION["uid"])
		if (!(strlen($old_passwd) >= $_SPM_CONF["PASSWD"]["minlength"]) || !(strlen($old_passwd) <= $_SPM_CONF["PASSWD"]["maxlength"]))
			$errors_count++;
	
	//New password check
	if (!(strlen($passwd) >= $_SPM_CONF["PASSWD"]["minlength"]) || !(strlen($passwd) <= $_SPM_CONF["PASSWD"]["maxlength"]))
		$errors_count++;
	
	//Retype new password check
	if (!(strlen($passwd_retype) >= $_SPM_CONF["PASSWD"]["minlength"]) || !(strlen($passwd_retype) <= $_SPM_CONF["PASSWD"]["maxlength"]))
		$errors_count++;
	
	//New password second check
	if ($passwd != $passwd_retype)
		$errors_count++;
	
	//Errors count check
	if ($errors_count > 0)
		die(header('location: index.php?service=error&err=input'));
	
	/*
	 * ИЗМЕНЕНИЕ ПАРОЛЯ
	 */
	
	$query_str = "
		SELECT
			`teacherId`,
			`password`
		FROM
			`spm_users`
		WHERE
			`id` = '" . $user_id . "'
		LIMIT
			1
		;
	";
	
	if (!$db_query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($db_query->num_rows == 0)
		die(header('location: index.php?service=error&err=404'));
	
	$usr_ans = $db_query->fetch_assoc();
	
	$old_passwd = md5(md5(md5($old_passwd)));
	$passwd = md5(md5(md5($passwd)));
	
	if (!permission_check($_SESSION["permissions"], PERMISSION::administrator) && $usr_ans['teacherId'] != $_SESSION["uid"])
		if ($usr_ans['password'] != $old_passwd)
			die(header('location: index.php?service=error&err=input'));
	
	if (!$db->query("UPDATE `spm_users` SET `password` = '" . $passwd . "' WHERE `id`='" . $user_id . "' LIMIT 1;"))
		die(header('location: index.php?service=error&err=db_error'));
	
	exit(header('location: index.php?service=user.edit&id=' . $user_id));
?>