<?php
	defined("__spm.user.edit__") or die('403 ACCESS DENIED');
	
	/*
	 * FUNCTIONS LIST
	 */
	
	function changeProfileParam($param_post_name, $param_db_name, $param_min_length = 3, $param_max_length = 100, $is_email = false){
		
		global $db;
		
		if (isset($_POST[$param_post_name]) && strlen($_POST[$param_post_name]) >= $param_min_length && strlen($_POST[$param_post_name]) <= $param_max_length){
			
			if ($is_email)
				@(filter_var($_POST[$param_post_name], FILTER_VALIDATE_EMAIL)) or die(header('location: index.php?service=error&err=input'));
			
			$query_str = "
				UPDATE
					`spm_users`
				SET
					`" . $param_db_name . "` = '" . $_POST[$param_post_name] . "'
				WHERE
					`id` = '" . (int)$_GET['id'] . "'
				;
			";
			
			if (!$db->query($query_str))
				die(header('location: index.php?service=error&err=input'));
			
		}
		
	}
	
	/*
	 * ПЕРВЫЙ ШАГ ОТСЕИВАНИЯ ДУШ
	 * Сжигание на костре
	 */
	 
	//username
	(!isset($_POST["username"])) or $_POST["username"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["username"])));
	//email
	(!isset($_POST["email"])) or $_POST["email"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["email"])));
	//phone
	(!isset($_POST["phone"])) or $_POST["phone"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["phone"])));
	
	//secondname
	(!isset($_POST["secondname"])) or $_POST["secondname"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["secondname"])));
	//secondname
	(!isset($_POST["firstname"])) or $_POST["firstname"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["firstname"])));
	//secondname
	(!isset($_POST["thirdname"])) or $_POST["thirdname"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["thirdname"])));
	
	//bday
	(!isset($_POST["bdate"])) or $_POST["bdate"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["bdate"])));
	
	//country
	(!isset($_POST["country"])) or $_POST["country"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["country"])));
	//city
	(!isset($_POST["city"])) or $_POST["city"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["city"])));
	
	//school
	(!isset($_POST["school"])) or $_POST["school"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["school"])));
	//group
	(!isset($_POST["group"])) or $_POST["group"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["group"])));
	
	if (isset($_POST["group"])){
		$query_str = "
			SELECT
				`teacherId`
			FROM
				`spm_users`
			WHERE
				`id` = '" . (int)$_GET["id"] . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows == 0)
			die(header('location: index.php?service=error&err=input'));
		
		$teacherId = $query->fetch_array()[0];
		$query->free();
		
		$query_str = "
			SELECT
				count(`id`)
			FROM
				`spm_users_groups`
			WHERE
				`id` = '" . (int)$_POST["group"] . "'
			AND
				`teacherId` = '" . $teacherId . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		$count = $query->fetch_array()[0];
		
		if ($query->num_rows == 0 || $count == null || $count == 0)
			die(header('location: index.php?service=error&err=input'));
	}
	
	/*
	 * ВТОРОй ШАГ ОТСЕИВАНИЯ ДУШ
	 * Испытание пофигизмом
	 */
	
	//username
	changeProfileParam("username", "username", 3, 100);
	//email
	changeProfileParam("email", "email", 6, 100, true);
	//phone
	changeProfileParam("phone", "phone", 6, 50);
	
	//secondname
	changeProfileParam("secondname", "secondname", 3, 100);
	//firstname
	changeProfileParam("firstname", "firstname", 3, 100);
	//thirdname
	changeProfileParam("thirdname", "thirdname", 3, 100);
	
	//bday
	changeProfileParam("bdate", "bdate", 10, 10);
	
	//country
	changeProfileParam("country", "country", 1, 100);
	//city
	changeProfileParam("city", "city", 3, 100);
	
	//school
	changeProfileParam("school", "school", 5, 100);
	//group
	changeProfileParam("group", "group", 1, 10);
	
	/*
	 * ТРЕТИЙ ШАГ ОТСЕИВАНИЯ ДУШ
	 * Ковчег
	 */
	
	exit(header('location: index.php?service=user&id=' . (int)$_GET['id']));
?>