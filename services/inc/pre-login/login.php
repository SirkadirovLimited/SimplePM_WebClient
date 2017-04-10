<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
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
?>