<?php
	include_once(_S_SERV_INC_ . "pre-login/login.php");
	include_once(_S_TPL_ . "pre-login/header.php");
?>
<p class="login-box-msg">Для продовження увійдіть до системи.</p>

<?php spm_login_error_view(); ?>

<form action="index.php?service=login" method="post">
	
	<div class="form-group has-feedback">
		
		<input
			type="text"
			class="form-control"
			placeholder="Ім'я користувача"
			name="login"
			minlength="3"
			maxlength="100"
			required
		>
		
		<span class="glyphicon glyphicon-user form-control-feedback"></span>
		
	</div>
	
	<div class="form-group has-feedback">
		
		<input
			type="password"
			class="form-control"
			placeholder="Пароль"
			name="password"
			minlength="<?=$_SPM_CONF["PASSWD"]["minlength"]?>"
			maxlength="<?=$_SPM_CONF["PASSWD"]["maxlength"]?>"
			required
		>
		
		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
		
	</div>
	
<?php if ($_SPM_CONF["SECURITY"]["require_captcha"]):
?>
	<div class="row" style="margin-bottom: 15px;">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			
			<img src="captcha.php" width="100%" height="auto" />
			
			<input
				type="text"
				class="form-control"
				width="100%"
				placeholder="Код безпеки"
				name="captcha"
				minlength="4"
				maxlength="4"
				autocomplete="off"
				required
			>
			
		</div>
		<div class="col-md-2"></div>
	</div>
<?php endif; ?>
	
	<button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-sign-in"></i> Увійти до системи</button>
	
	<div style="margin-top: 10px;">
		<a href="index.php?service=forgot">Відновлення доступу</a><br/>
		<a href="index.php?service=register">Реєстрація</a>
	</div>
</form>

<?php include_once(_S_TPL_ . "pre-login/footer.php"); ?>