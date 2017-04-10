<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	if (isset($_SESSION['uid'])) {
		SPM_header("Ошибка 403");
		include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["access_denied"]);
		SPM_footer();
		exit();
	}
	
	if (isset($_POST['submit'])){
		
		define("_SPM_register_", 1);
		include_once(_S_SERV_INC_ . "register.php");
		
	}else{
		include_once(_S_TPL_ . "pre-login/header.php");
?>
<p class="login-box-msg">Все поля обязательны для заполнения.</p>

<form action="index.php?service=register" method="post">
	<div>
		<!--email-->
		<div class="form-group has-feedback">
			<input type="email" class="form-control" placeholder="Email" name="email" minlength="5" maxlength="100" required>
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		</div>
		<!--login-->
		<div class="form-group has-feedback">
			<input type="text" class="form-control" placeholder="Имя пользователя" name="login" minlength="3" maxlength="100" required>
			<span class="glyphicon glyphicon-user form-control-feedback"></span>
		</div>
		<!--password-->
		<div class="form-group has-feedback">
			<input type="password" class="form-control" placeholder="Пароль" name="password" minlength="6" maxlength="25" required>
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
		</div>
	</div>
	<div style="margin-top: 20px;">
		<!--secondname-->
		<div class="form-group has-feedback">
			<label for="secondname">Фамилия</label>
			<input type="text" class="form-control" id="secondname" placeholder="Кадиров" name="2name" minlength="3" maxlength="255" required>
		</div>
		<!--firstname-->
		<div class="form-group has-feedback">
			<label for="firstname">Имя</label>
			<input type="text" class="form-control" id="firstname" placeholder="Юрий" name="1name" minlength="3" maxlength="255" required>
		</div>
		<!--thirdname-->
		<div class="form-group has-feedback">
			<label for="thirdname">Отчество</label>
			<input type="text" class="form-control" id="thirdname" placeholder="Викторович" name="3name" minlength="3" maxlength="255" required>
		</div>
		<!--bday-->
		<div class="form-group has-feedback">
			<label for="bday">Дата рождения</label>
			<input type="date" class="form-control" id="bday" placeholder="Дата рождения" name="bday" required>
			<span class="help-block">Ваша дата рождения в Unix формате (ГГГГ-ММ-ДД)</span>
		</div>
	</div>
	<div style="margin-top: 20px;">
		<!--teacherId-->
		<div class="form-group has-feedback has-error">
			<label for="teacherId">TeacherID</label>
			<input type="text" class="form-control" id="teacherId" placeholder="ExAmPlEtEaChErID-12345" name="teacherId" required>
			<span class="help-block">Код приглашения выданный учителем / куратором / администратором.</span>
		</div>
	</div>
	
	<p>Регистрируясь на данном сайте вы автоматически принимаете условия <a href="index.php?service=agreement">Лицензионного соглашения</a></p>
	
	<button type="submit" class="btn btn-primary btn-block btn-flat" name="submit">Зарегистрироваться</button>
	<br/>
	<a href="index.php?service=forgot">Забыли логин/пароль?</a><br/>
	<a href="index.php?service=login" class="text-center">Уже есть аккаунт?</a>
</form>
<?php
		include_once(_S_TPL_ . "pre-login/footer.php");
	}
?>