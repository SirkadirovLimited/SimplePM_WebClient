<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	if (isset($_SESSION['uid']))
		die(header('location: index.php?service=error&err=403'));
	
	function spm_login_error_view()
	{
		if(isset($_GET['err']))
		{
			switch ($_GET['err'])
			{
				case "badlogin":
					_spm_view_msg("Логін не відповідає правилам!","danger");
					break;
				case "badpass":
					_spm_view_msg("Пароль не відповідає правилам!","danger");
					break;
				case "badcaptcha":
					_spm_view_msg("Тест CAPTCHA не пройдено!","danger");
					break;
				case "db":
					_spm_view_msg("Інформація введена в не правильному форматі!","danger");
					break;
				case "nouser":
					_spm_view_msg("За вказаними даними користувача не знайдено! Перевірте правильність логіну та паролю.","danger");
					break;
				case "banned":
					_spm_view_msg("Ваш аккаунт заблоковано!","danger");
					break;
			}
		}
	}
	
	/*
	 * Основной скрипт входа пользователей в систему
	 */
	if (isset($_POST['login']) && isset($_POST['password']))
	{
		
		if ($_SPM_CONF["SECURITY"]["require_captcha"])
			if (!(isset($_POST['captcha']) && isset($_SESSION["captcha_code"]) && $_POST['captcha'] == $_SESSION["captcha_code"]))
				exit(header("location: index.php?service=login&err=badcaptcha"));
		
		$login = mysqli_real_escape_string($db, strip_tags(trim($_POST['login'])));
		$pass = mysqli_real_escape_string($db, strip_tags(trim($_POST['password'])));
		$password = md5(md5(md5($pass)));
		
		if (!((strlen($login)>=3) && (strlen($login)<=100)))
			exit(header("location: index.php?err=badlogin"));
		
		$param1 = (strlen($pass)>=$_SPM_CONF["PASSWD"]["minlength"]);
		$param2 = (strlen($pass)<=$_SPM_CONF["PASSWD"]["maxlength"]);
		
		if (!$param1 && !$param2)
			exit(header("location: index.php?service=login&err=badpass"));
		
		$query_str = "
			SELECT
				*
			FROM
				`spm_users`
			WHERE
				`username` = '$login'
			AND
				`password` = '$password'
			LIMIT
				1
			;
		";
		
		if (!$db_result = $db->query($query_str))
			exit(header("location: index.php?service=login&err=db"));
		
		if ($db_result->num_rows == 0)
			exit(header("location: index.php?service=login&err=nouser"));
		
		$user = $db_result->fetch_assoc();
		
		$db_result->free();
		unset($db_result);
		
		if ($user['id'] > 1 && $user['banned'] == 1)
			exit(header("location: index.php?service=login&err=banned"));
		
		$query_str = "
			UPDATE
				`spm_users`
			SET
				`sessionId` = '" . session_id() . "'
			WHERE
				`id` = " . $user['id'] . "
			LIMIT
				1
			;
		";
		
		if (!$db_result = $db->query($query_str))
			exit(header("location: index.php?service=login&err=db"));
		
		$_SESSION['uid'] = $user['id'];
		$_SESSION['permissions'] = $user['permissions'];
		$_SESSION['teacherId'] = $user['teacherId'];
		
		exit(header("location: index.php?service=" . $_SPM_CONF["SERVICES"]["_AUTOSTART_SERVICE_"]));
	}
?>