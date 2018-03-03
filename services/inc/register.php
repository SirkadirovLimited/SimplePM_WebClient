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
		print("<strong>Будь ласка, перевірте правильність заповнення форми реєстрації!</strong>");
		print("<meta http-equiv='refresh' content='3;URL=index.php?service=register' />");
		exit;
	}
	
	/*
	 * ВТОРОЙ ШАГ ОТСЕИВАНИЯ ДУШ
	*/
	$_POST['email'] = mysqli_real_escape_string($db, trim(strip_tags($_POST['email'])));
	$_POST['login'] = mysqli_real_escape_string($db, trim(strip_tags($_POST['login'])));
	$_POST['password'] = mysqli_real_escape_string($db, trim(strip_tags($_POST['password'])));
	$_POST['1name'] = mysqli_real_escape_string($db, trim(strip_tags($_POST['1name'])));
	$_POST['2name'] = mysqli_real_escape_string($db, trim(strip_tags($_POST['2name'])));
	$_POST['3name'] = mysqli_real_escape_string($db, trim(strip_tags($_POST['3name'])));
	$_POST['bday'] = mysqli_real_escape_string($db, trim(strip_tags($_POST['bday'])));
	$_POST['teacherId'] = mysqli_real_escape_string($db, trim(strip_tags($_POST['teacherId'])));
	
	/*
	 * ТРЕТИЙ ШАГ ОТСЕИВАНИЯ ДУШ
	*/
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		$errors_col++;
	
	if (!strlen($_POST['login']) >= 3 && !strlen($_POST['login']) <= 100)
		$errors_col++;
	
	if (!strlen($_POST['password']) >= $_SPM_CONF["PASSWD"]["minlength"] && !strlen($_POST['password']) <= $_SPM_CONF["PASSWD"]["maxlength"])
		$errors_col++;
	
	if (!strlen($_POST['1name']) >= 3 && !strlen($_POST['1name']) <= 255)
		$errors_col++;
	
	if (!strlen($_POST['2name']) >= 3 && !strlen($_POST['2name']) <= 255)
		$errors_col++;
	
	if (!strlen($_POST['3name']) >= 3 && !strlen($_POST['3name']) <= 255)
		$errors_col++;
	
	if (strlen($_POST['teacherId']) != $_SPM_CONF["TEACHERID"]["length"])
		$errors_col++;
	
	if ($errors_col > 0) {
		print("<strong>Будь ласка, правильно заповніть форму реєстрації!</strong>");
		print("<meta http-equiv='refresh' content='3;URL=index.php?service=register' />");
		exit;
	}
	
	/*
	 * ЧЕТВЁРТЫЙ ШАГ ОТСЕИВАНИЯ ДУШ, ФИНАЛЬНЫЙ
	 * TeacherID
	*/
	
	$query_str = "
		SELECT
			*
		FROM
			`spm_teacherId`
		WHERE
			`teacherId` = '" . $_POST['teacherId'] . "'
		AND
			`enabled` = true
		LIMIT
			1
		;
	";
	
	if (!$db_result = $db->query($query_str)) {
		print("<strong>Виникла помилка бази даних! Будь ласка, зазерніть на сайт пізніше!</strong>");
		print("<meta http-equiv='refresh' content='3;URL=index.php?service=register' />");
		exit;
	}
	if ($db_result->num_rows == 0) {
		print("<strong>TeacherID деактивований чи не існує.</strong>");
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
	
	$db_query = "
		INSERT INTO 
			`spm_users` 
		SET 
			`username` = '" . $_POST['login'] . "', 
			`password` = '" . md5(md5(md5($_POST['password']))) . "', 
			`firstname` = '" . $_POST['1name'] . "', 
			`secondname` = '" . $_POST['2name'] . "', 
			`thirdname` = '" . $_POST['3name'] . "', 
			`bdate` = '" . $_POST['bday'] . "', 
			`email` = '" . $_POST['email'] . "', 
			`teacherId` = '" . $TeacherID['userId'] . "', 
			`permissions` = '" . $TeacherID['newUserPermission'] . "', 
			`country` = 'UA', 
			`city` = 'UNSET', 
			`school` = 'UNSET', 
			`groupid` = '0'
		;
	";
	
	if(!$db->query($db_query)) {
		
		print("<strong>Форму заповнено не за правилами!</strong>");
		print("<meta http-equiv='refresh' content='3;URL=index.php?service=register' />");
		exit;
		
	}
	
	header('location: index.php?service=login');
	
?>