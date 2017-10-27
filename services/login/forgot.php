<?php
	include_once(_S_TPL_ . "pre-login/header.php");
?>

<div style="text-align: justify;">
	<p class="login-box-msg"><strong>Відновлення доступу</strong></p>
	<form>
		<p>Для відновлення доступу до вашого аккаунту, зв'яжіться з адміністратором системи за e-mail:</p>
		<pre style="border-radius: 0;"><?=str_replace("@", " [ собака ] ", $_SPM_CONF["BASE"]["ADMIN_MAIL"])?></pre>
		<p>чи сповістіть вашого вчителя або куратора про це.</p>
		<br/>
		<a class="btn btn-flat btn-block btn-primary" href="index.php?service=login">Вхід до системи</a>
		<a class="btn btn-flat btn-block btn-default" href="index.php?service=register">Реєстрація</a>
	</form>
</div>

<?php
	include_once(_S_TPL_ . "pre-login/footer.php");
?>