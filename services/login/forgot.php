<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	include_once(_S_TPL_ . "pre-login/header.php");
?>
<div class="alert alert-warning alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
	<strong><span class="glyphicon glyphicon-warning-sign"></span> ВНИМАНИЕ!</strong> Сервис не предоставляется учителям, кураторам и администраторам! Если вы потеряли доступ к аккаунту, обратитесь к вашему куратору.
</div>

<p class="login-box-msg"><strong>Забыли пароль?</strong></p>
<form action="index.php?service=login" method="post">
	<div class="form-group has-feedback">
		<input type="email" class="form-control" placeholder="Email" name="email" minlength="5" maxlength="255" required>
		<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		<span class="help-block">Ваш email, указанный в учётной записи, доступ к которой вы потеряли</span>
	</div>
	<div class="form-group has-feedback">
		<input type="date" class="form-control" placeholder="Дата рождения" name="bdate" required>
		<span class="glyphicon glyphicon-gift form-control-feedback"></span>
		<span class="help-block">Ваша дата рождения в Unix формате (ГГГГ-ММ-ДД)</span>
	</div>
	
	<button type="submit" class="btn btn-primary btn-block btn-flat">Восстановить пароль</button>
	<br/>
	<a href="index.php?service=agreement" class="text-center">Лицензионное соглашение</a><br/>
	<a href="index.php?service=login" class="text-center">Вход в систему</a><br/>
	<a href="index.php?service=register" class="text-center">Регистрация</a>
</form>
<?php
	include_once(_S_TPL_ . "pre-login/footer.php");
?>