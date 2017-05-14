<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	include_once(_S_TPL_ . "pre-login/header.php");
?>

<p class="login-box-msg"><strong>Восстановление доступа</strong></p>
<form>
	<p>Для восстановления доступа к аккаунту обратитесь к администратору системы по e-mail:</p>
	<pre><?=str_replace("@", " [ собака ] ", $_SPM_CONF["BASE"]["ADMIN_MAIL"])?></pre>
	<p>либо оповестите об утере аккаунта вашего учителя / куратора.</p>
	<br/>
	<a href="index.php?service=agreement" class="text-center">Лицензионное соглашение</a><br/>
	<a href="index.php?service=login" class="text-center">Вход в систему</a><br/>
	<a href="index.php?service=register" class="text-center">Регистрация</a>
</form>
<?php
	include_once(_S_TPL_ . "pre-login/footer.php");
?>