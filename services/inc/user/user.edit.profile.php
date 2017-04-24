<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__spm.user.edit__") or die('403 ACCESS DENIED');
	
	$meta_refresh = "<meta http-equiv='refresh' content='5; url=index.php?service=user.edit&id=" . (int)$_GET['id'] . "'>";
	
	/*
	 * FUNCTIONS LIST
	 */
	
	function changeProfileParam($param_post_name, $param_db_name, $param_real_name, $param_min_length = 3, $param_max_length = 100, $is_email = false){
		
		global $db;
		
		if (isset($_POST[$param_post_name]) && strlen($_POST[$param_post_name]) >= $param_min_length && strlen($_POST[$param_post_name]) <= $param_max_length){
			
			if ($is_email)
				@(filter_var($_POST[$param_post_name], FILTER_VALIDATE_EMAIL) or die("Email указан в неверном формате!" . $meta_refresh));
			
			if (!$db->query("UPDATE `spm_users` SET `" . $param_db_name . "` = '" . $_POST[$param_post_name] . "' WHERE `id` = '" . (int)$_GET['id'] . "';"))
				die('<strong>Поле "' . $param_real_name . '" заполнено не верно! Возможные причины:</strong><br/>
					 <ul>
						<li>Значение поля должно быть уникальным (email, имя пользователя)</li>
						<li>Значение поля указано в неверном формате (относится ко всем полям)</li>
						<li>Значение поля не соответствует указанным размерам (относится ко всем полям)</li>
						<li>Дата указана в неверном формате (относится к дате рождения)</li>
						<li>Данная группа пользователей не существует или вы не имеете права ёё изменять (относится к группе)</li>
					 </ul>
					' . $meta_refresh);
			
		}
		
	}
	
	/*
	 * ПЕРВЫЙ ШАГ ОТСЕИВАНИЯ ДУШ
	 * Сжигание на костре
	 */
	 
	//username
	(!isset($_POST["username"])) or $_POST["username"] = mysqli_real_escape_string($db,strip_tags(trim($_POST["username"])));
	//email
	(!isset($_POST["email"])) or $_POST["email"] = mysqli_real_escape_string($db,strip_tags(trim($_POST["email"])));
	
	//secondname
	(!isset($_POST["secondname"])) or $_POST["secondname"] = mysqli_real_escape_string($db,strip_tags(trim($_POST["secondname"])));
	//secondname
	(!isset($_POST["firstname"])) or $_POST["firstname"] = mysqli_real_escape_string($db,strip_tags(trim($_POST["firstname"])));
	//secondname
	(!isset($_POST["thirdname"])) or $_POST["thirdname"] = mysqli_real_escape_string($db,strip_tags(trim($_POST["thirdname"])));
	
	//bday
	(!isset($_POST["bdate"])) or $_POST["bdate"] = mysqli_real_escape_string($db,trim($_POST["bdate"]));
	
	//country
	(!isset($_POST["country"])) or $_POST["country"] = mysqli_real_escape_string($db,strip_tags(trim($_POST["country"])));
	//city
	(!isset($_POST["city"])) or $_POST["city"] = mysqli_real_escape_string($db,strip_tags(trim($_POST["city"])));
	
	//school
	(!isset($_POST["school"])) or $_POST["school"] = mysqli_real_escape_string($db,strip_tags(trim($_POST["school"])));
	//group
	(!isset($_POST["group"])) or $_POST["group"] = mysqli_real_escape_string($db,strip_tags(trim($_POST["group"])));
	
	/*
	 * ВТОРОй ШАГ ОТСЕИВАНИЯ ДУШ
	 * Испытание пофигизмом
	 */
	
	//username
	changeProfileParam("username", "username", "Имя пользователя", 3, 100);
	//email
	changeProfileParam("email", "email", "Email", 6, 100, true);
	
	//secondname
	changeProfileParam("secondname", "secondname", "Фамилия", 3, 100);
	//firstname
	changeProfileParam("firstname", "firstname", "Имя", 3, 100);
	//thirdname
	changeProfileParam("thirdname", "thirdname", "Отчество", 3, 100);
	
	//bday
	changeProfileParam("bdate", "bdate", "Дата рождения", 10, 10);
	
	//country
	changeProfileParam("country", "country", "Страна", 1, 100);
	//city
	changeProfileParam("city", "city", "Город", 3, 100);
	
	//school
	changeProfileParam("school", "school", "Учебное заведение / Место работы", 5, 100);
	//group
	changeProfileParam("group", "group", "Группа пользователя", 1, 10);
	
	/*
	 * ТРЕТИЙ ШАГ ОТСЕИВАНИЯ ДУШ
	 * Ковчег
	 */
	
	header('location: index.php?service=user&id=' . (int)$_GET['id']);
?>