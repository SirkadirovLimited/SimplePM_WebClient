<?php
	if (isset($_SESSION['uid']))
		die(header('location: index.php?service=error&err=403'));
	
	if (isset($_POST['submit'])){
		
		define("_SPM_register_", 1);
		include_once(_S_SERV_INC_ . "register.php");
		
	}
	
	include_once(_S_TPL_ . "pre-login/header.php");
?>

<style>
	.help-block {
		
		text-align: justify;
		
	}
</style>

<p class="login-box-msg">Усі поля обов'язкові для заповнення.</p>

<form action="index.php?service=register" method="post">
	<div>
		<!--email-->
		<div class="form-group has-feedback">
			
			<input
				type="email"
				class="form-control"
				placeholder="Email"
				name="email"
				minlength="5"
				maxlength="100"
				autocomplete="off"
				required
			>
			
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			
			<span class="help-block">Введіть свою справжню email адресу. На неї інколи будуть приходити важливі сповіщення.</span>
			
		</div>
		
		<!--login-->
		<div class="form-group has-feedback">
			
			<input
				type="text"
				class="form-control"
				placeholder="Ім'я користувача"
				name="login"
				minlength="3"
				maxlength="100"
				autocomplete="off"
				required
			>
			
			<span class="glyphicon glyphicon-user form-control-feedback"></span>
			
			<span class="help-block">Логін повинен складатися лише з латинських букв та цифр.</span>
			
		</div>
		<!--password-->
		<div class="form-group has-feedback">
			
			<input
				type="password"
				class="form-control"
				placeholder="Пароль"
				name="password"
				minlength="<?=$_SPM_CONF["PASSWD"]["minlength"]?>"
				maxlength="<?=$_SPM_CONF["PASSWD"]["maxlength"]?>"
				autocomplete="off"
				required
			>
			
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			
		</div>
	</div>
	<div style="margin-top: 20px;">
		<div class="row-fluid">
			<div class="col-md-4 col-xs-4" style="padding: 0;">
				<!--secondname-->
				<div class="form-group">
					
					<label for="secondname">Фамілія</label>
					
					<input
						type="text"
						class="form-control"
						id="secondname"
						name="2name"
						minlength="3"
						maxlength="255"
						autocomplete="off"
						required
					>
					
				</div>
			</div>
			<div class="col-md-4 col-xs-4" style="padding-left: 3px; padding-right: 3px;">
				<!--firstname-->
				<div class="form-group">
					
					<label for="firstname">Ім'я</label>
					
					<input
						type="text"
						class="form-control"
						id="firstname"
						name="1name"
						minlength="3"
						maxlength="255"
						autocomplete="off"
						required
					>
					
				</div>
			</div>
			<div class="col-md-4 col-xs-4" style="padding: 0;">
				<!--thirdname-->
				<div class="form-group">
					
					<label for="thirdname">По-батькові</label>
					
					<input
						type="text"
						class="form-control"
						id="thirdname"
						name="3name"
						minlength="3"
						maxlength="255"
						autocomplete="off"
						required
					>
					
				</div>
			</div>
		</div>
		<!--bday-->
		<div class="form-group">
			
			<label for="bday">Дата народження</label>
			
			<input
				type="date"
				class="form-control"
				id="bday"
				placeholder="РРРР-ММ-ДД"
				name="bday"
				autocomplete="off"
				required
			>
			
		</div>
	</div>
	<div style="margin-top: 20px;">
		<!--teacherId-->
		<div class="form-group">
			
			<label for="teacherId">TeacherID</label>
			
			<input
				type="text"
				class="form-control"
				id="teacherId"
				placeholder=""
				name="teacherId"
				autocomplete="off"
				required
			>
			
			<span class="help-block"><strong>TeacherID</strong> - це код безпеки, що дозволить зареєструватися в системі та автоматично вас прив'яже до вашого викладача чи куратора.</span>
			
		</div>
	</div>
	
	<button type="submit" class="btn btn-primary btn-block btn-flat" name="submit">Реєстрація</button>
	<a href="index.php?service=login" class="btn btn-default btn-block btn-flat">Вже є аккаунт?</a>
</form>
<?php
	include_once(_S_TPL_ . "pre-login/footer.php");
?>