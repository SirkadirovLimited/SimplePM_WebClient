<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	/*
	 * Запрещаем доступ уже авторизированным пользователям
	 */
	if (isset($_SESSION['uid'])) {
		SPM_header("Ошибка 403");
		include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["access_denied"]);
		SPM_footer();
		exit;
	}
	
	function spm_login_error_view(){
		if(isset($_GET['err'])){
			switch ($_GET['err']){
				case "badlogin":
					_spm_view_msg("Введённый логин не соответствует требованиям!","danger");
					break;
				case "badpass":
					_spm_view_msg("Введённый пароль не соответствует требованиям!","danger");
					break;
				case "badcaptcha":
					_spm_view_msg("CAPTCHA введена не верно!","danger");
					break;
				case "db":
					_spm_view_msg("Возникла ошибка при совершении запроса к базе данных! Возможно вы используете недопустимые символы!","danger");
					break;
				case "nouser":
					_spm_view_msg("Вы ввели неверный логин и/или пароль или же пользователя с таким логином не существует!","danger");
					break;
				case "banned":
					_spm_view_msg("Вы забанены в системе! Обратитесь к своему учителю, куратору или администратору!","danger");
					break;
			}
		}
	}
	
	/*
	 * Основной скрипт входа пользователей в систему
	 */
	if (isset($_POST['login']) && isset($_POST['password'])){
		
		if ($_SPM_CONF["SECURITY"]["require_captcha"])
			if (!(isset($_POST['captcha']) && isset($_SESSION["captcha_code"]) && $_POST['captcha'] == $_SESSION["captcha_code"]))
				exit(header("location: index.php?service=login&err=badcaptcha"));
		
		$login = mysqli_real_escape_string($db, strip_tags(trim($_POST['login'])));
		$pass = mysqli_real_escape_string($db, strip_tags(trim($_POST['password'])));
		$password = md5(md5(md5($pass)));
		
		if (!((strlen($login)>=3) && (strlen($login)<=100)))
			exit(header("location: index.php?err=badlogin"));
		
		if (!(strlen($pass)>=$_SPM_CONF["PASSWD"]["minlength"]) && !(strlen($pass)<=$_SPM_CONF["PASSWD"]["maxlength"]))
			exit(header("location: index.php?service=login&err=badpass"));
		
		if (!$db_result = $db->query("SELECT * FROM `spm_users` WHERE `username` = '$login' AND `password` = '$password' LIMIT 1;"))
			exit(header("location: index.php?service=login&err=db"));
		
		if ($db_result->num_rows == 0)
			exit(header("location: index.php?service=login&err=nouser"));
		
		$user = $db_result->fetch_assoc();
		
		$db_result->free();
		unset($db_result);
		
		if ($user['id']>0 && $user['banned'] == 1)
			exit(header("location: index.php?service=login&err=banned"));
		
		if (!$db_result = $db->query("UPDATE `spm_users` SET `online` = '1', `sessionId` = '" . session_id() . "' WHERE `id` = " . $user['id'] . " LIMIT 1;"))
			exit(header("location: index.php?service=login&err=db"));
		
		$_SESSION['uid'] = $user['id'];
		$_SESSION['username'] = $user['username'];
		$_SESSION['permissions'] = $user['permissions'];
		$_SESSION['teacherId'] = $user['teacherId'];
		
		exit(header("Location: index.php?service=" . $_SPM_CONF["SERVICES"]["_AUTOSTART_SERVICE_"]));
	}
?>