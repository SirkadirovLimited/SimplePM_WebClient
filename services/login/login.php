<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	if (isset($_SESSION['uid'])) {
		SPM_header("Ошибка 403");
		include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["access_denied"]);
		SPM_footer();
		exit();
	}
	
	require_once(_S_SERV_INC_ . "pre-login/login.php");
	
	if (isset($_POST['login']) && isset($_POST['password'])){
		
		if ($_SPM_CONF["SECURITY"]["require_captcha"]){
			if( !( ( isset($_POST['captcha']) && isset($_SESSION["captcha_code"]) ) && ( $_POST['captcha'] == $_SESSION["captcha_code"] ) ) ){
				header("Location: index.php?service=login&err=badcaptcha");
				die();
			}
		}
		
		$login = htmlspecialchars(strip_tags(trim($_POST['login'])));
		$pass = htmlspecialchars(strip_tags(trim($_POST['password'])));
		$password = md5(md5(md5($pass)));
		
		if (!((strlen($login)>=3) && (strlen($login)<=100))){
			header("Location: index.php?err=badlogin");
			die();
		}
		
		if (!(strlen($pass)>=$_SPM_CONF["PASSWD"]["minlength"]) && !(strlen($pass)<=$_SPM_CONF["PASSWD"]["maxlength"])){
			header("Location: index.php?service=login&err=badpass");
			die();
		}
		
		if (!$db_result = $db->query("SELECT * FROM `spm_users` WHERE `username` = '$login' AND `password` = '$password' LIMIT 1;")){
			header("Location: index.php?service=login&err=db");
		}
		
		if ($db_result->num_rows == 0){
			header("Location: index.php?service=login&err=nouser");
			die();
		}
		
		$user = $db_result->fetch_assoc();
		
		$db_result->free();
		unset($db_result);
		
		if ($user['id']>0 && $user['banned'] == 1){
			header("Location: " . $_SPM_CONF["BASE"]["SITE_URL"] . "index.php?service=login&err=banned");
			die();
		}
		
		if (!$db_result = $db->query("UPDATE `spm_users` SET `online` = '1', `sessionId` = '" . session_id() . "' WHERE `id` = " . $user['id'] . " LIMIT 1;")){
			header("Location: " . $_SPM_CONF["BASE"]["SITE_URL"] . "index.php?service=login&err=db");
			die();
		}
		
		$_SESSION['uid'] = $user['id'];
		$_SESSION['username'] = $user['username'];
		$_SESSION['full_name'] = $user['secondname'] . " " . $user['firstname'];
		$_SESSION['permissions'] = $user['permissions'];
		$_SESSION['banned'] = $user['banned'];
		
		header("Location: index.php?service=" . $_SPM_CONF["SERVICE"]["_AUTOSTART_SERVICE_"]);
	}else{
		include_once(_S_TPL_ . "pre-login/header.php");
?>
<p class="login-box-msg">Войдите в систему чтобы продолжить.</p>
<?php spm_login_error_view(); ?>
<form action="index.php?service=login" method="post">
	<div class="form-group has-feedback">
		<input type="text" class="form-control" placeholder="Имя пользователя" name="login" minlength="3" maxlength="100" required>
		<span class="glyphicon glyphicon-user form-control-feedback"></span>
	</div>
	<div class="form-group has-feedback">
		<input type="password" class="form-control" placeholder="Пароль" name="password" minlength="<?php print($_SPM_CONF["PASSWD"]["minlength"]); ?>" maxlength="<?php print($_SPM_CONF["PASSWD"]["maxlength"]); ?>" required>
		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
	</div>
	
<?php
	if ($_SPM_CONF["SECURITY"]["require_captcha"]){
?>
	<div class="row" style="margin-bottom: 15px;">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<img src="captcha.php" width="100%" height="auto" />
			<input type="text" class="form-control" width="100%" placeholder="Код безопасности" name="captcha" minlength="4" maxlength="4" autocomplete="off" required>
		</div>
		<div class="col-md-2"></div>
	</div>
<?php
	}
?>
	
	<button type="submit" class="btn btn-primary btn-block btn-flat">Войти</button>
	<br/>
	<a href="index.php?service=agreement">Лицензионное соглашение</a><br/>
	<a href="index.php?service=forgot">Забыли логин/пароль?</a><br/>
	<a href="index.php?service=register" class="text-center">Регистрация</a>
</form>

<?php
		include_once(_S_TPL_ . "pre-login/footer.php");
	}
?>