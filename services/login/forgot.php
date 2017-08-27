<?php
	include_once(_S_TPL_ . "pre-login/header.php");
?>

<p class="login-box-msg"><strong>Відновлення доступу</strong></p>
<form>
	<p>Для відновлення доступу до вашого аккаунту, зв'яжіться з адміністратором системи за e-mail:</p>
	<pre><?=str_replace("@", " [ собака ] ", $_SPM_CONF["BASE"]["ADMIN_MAIL"])?></pre>
	<p>чи сповістіть вашого вчителя або куратора про це.</p>
	<br/>
	<a href="index.php?service=login" class="text-center">Вхід до системи</a><br/>
	<a href="index.php?service=register" class="text-center">Реєстрація</a>
</form>
<?php
	include_once(_S_TPL_ . "pre-login/footer.php");
?>