<?php
	DEFINED("_SPM_register_") OR DIE('403 ACCESS DENIED');
	
	$errors_col=0;
	
	/*
	 * ПЕРВЫЙ ШАГ ОТСЕИВАНИЯ ДУШ
	*/
	//email
	if ( !isset($_POST['email']) || trim(strip_tags($_POST['email'])) == "")
		$errors_col++;
	//login
	if ( !isset($_POST['login']) || trim(strip_tags($_POST['login'])) == "")
		$errors_col++;
	//password
	if ( !isset($_POST['password']) || trim(strip_tags($_POST['password'])) == "")
		$errors_col++;
	//1name
	if ( !isset($_POST['1name']) || trim(strip_tags($_POST['1name'])) == "")
		$errors_col++;
	//2name
	if ( !isset($_POST['2name']) || trim(strip_tags($_POST['2name'])) == "")
		$errors_col++;
	//3name
	if ( !isset($_POST['3name']) || trim(strip_tags($_POST['3name'])) == "")
		$errors_col++;
	//bday
	if ( !isset($_POST['bday']) || trim(strip_tags($_POST['bday'])) == "")
		$errors_col++;
	//teacherId
	if ( !isset($_POST['teacherId']) || trim(strip_tags($_POST['teacherId'])) == "")
		$errors_col++;
	
	if ($errors_col > 0){
		print("<strong>Пожалуйста, заполните форму регистрации корректно!</strong>");
		print("<meta http-equiv='refresh' content='3;URL=index.php?service=register' />");
		exit;
	}
	
	/*
	 * ВТОРОЙ ШАГ ОТСЕИВАНИЯ ДУШ
	*/
	$_POST['email'] = trim(strip_tags($_POST['email']));
	$_POST['login'] = htmlspecialchars(trim(strip_tags($_POST['login'])));
	$_POST['password'] = htmlspecialchars(trim(strip_tags($_POST['password'])));
	$_POST['1name'] = htmlspecialchars(trim(strip_tags($_POST['1name'])));
	$_POST['2name'] = htmlspecialchars(trim(strip_tags($_POST['2name'])));
	$_POST['3name'] = htmlspecialchars(trim(strip_tags($_POST['3name'])));
	$_POST['bday'] = htmlspecialchars(trim(strip_tags($_POST['bday'])));
	$_POST['teacherId'] = htmlspecialchars(trim(strip_tags($_POST['teacherId'])));
	
	/*
	 * ТРЕТИЙ ШАГ ОТСЕИВАНИЯ ДУШ
	*/
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		$errors_col++;
	
	if (!strlen($_POST['login']) >= 3 && !strlen($_POST['login'])<=100)
		$errors_col++;
	if (!strlen($_POST['password']) >= 6 && !strlen($_POST['password'])<=25)
		$errors_col++;
	
	if (!strlen($_POST['1name']) >= 3 && !strlen($_POST['1name'])<=255)
		$errors_col++;
	if (!strlen($_POST['2name']) >= 3 && !strlen($_POST['2name'])<=255)
		$errors_col++;
	if (!strlen($_POST['3name']) >= 3 && !strlen($_POST['3name'])<=255)
		$errors_col++;
	
	if (strlen($_POST['teacherId']) != $_SPM_CONF["TEACHERID"]["length"])
		$errors_col++;
	
	if ($errors_col > 0){
		print("<strong>Пожалуйста, заполните форму регистрации корректно!</strong>");
		print("<meta http-equiv='refresh' content='3;URL=index.php?service=register' />");
		exit;
	}
	
	/*
	 * ЧЕТВЁРТЫЙ ШАГ ОТСЕИВАНИЯ ДУШ, ФИНАЛЬНЫЙ
	 * TeacherID
	*/
	if (!$db_result = $db->query("SELECT * FROM `spm_teacherId` WHERE teacherId = '" . $_POST['teacherId'] . "' AND `enabled` = true LIMIT 1;")){
		print("<strong>Произошла ошибка при попытке подключения к базе данных. Повторите попытку позже!</strong>");
		print("<meta http-equiv='refresh' content='3;URL=index.php?service=register' />");
		exit;
	}
	if ($db_result->num_rows == 0){
		print("<strong>TeacherID введён некорректно или был временно отключён.</strong>");
		print("<meta http-equiv='refresh' content='3;URL=index.php?service=register' />");
		exit;
	}
	
	$TeacherID = $db_result->fetch_assoc();
	
	$db_result->free();
	unset($db_result);
	
	/*
	 * ВВОД ДАННЫХ В БАЗУ ДАННЫХ
	*/
	unset($teacherId);
	
	$email = $_POST['email'];
	$login = $_POST['login'];
	$password = md5(md5(md5($_POST['password'])));
	$name1 = $_POST['1name'];
	$name2 = $_POST['2name'];
	$name3 = $_POST['3name'];
	$bday = $_POST['bday'];
	$teacherId = $TeacherID['userId'];
	$permissions = $TeacherID['newUserPermission'];
	
	$db_query = "INSERT INTO 
					`spm_users` 
				SET 
					`username` = '$login', 
					`password` = '$password', 
					`firstname` = '$name1', 
					`secondname` = '$name2', 
					`thirdname` = '$name3', 
					`bdate` = '$bday', 
					`email` = '$email', 
					`teacherId` = '$teacherId', 
					`permissions` = '$permissions', 
					`group` = '0'
				";

	if(!$db->query($db_query)){
		print("<strong>Форма заполнена не корректно!</strong>");
		print("<meta http-equiv='refresh' content='3;URL=index.php?service=register' />");
		exit;
	}
	
	header('location: index.php?service=login');
?>