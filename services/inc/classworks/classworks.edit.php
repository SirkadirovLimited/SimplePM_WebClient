<?php
	
	deniedOrAllowed(PERMISSION::teacher);
	
	////////
	
	$_GET['id'] > 0 or $_GET['id'] = "null";
	
	////////
	
	isset($_POST["name"]) or die(header('location: index.php?service=error&err=input'));
	isset($_POST["description"]) or die(header('location: index.php?service=error&err=input'));
	
	isset($_POST["startTime"]) or die(header('location: index.php?service=error&err=input'));
	isset($_POST["endTime"]) or die(header('location: index.php?service=error&err=input'));
	
	isset($_POST["studentsGroup"]) or die(header('location: index.php?service=error&err=input'));
	isset($_POST["ratingSystem"]) or die(header('location: index.php?service=error&err=input'));
	isset($_POST["problems-by-id"]) or die(header('location: index.php?service=error&err=input'));
	
	////////
	
	$_POST["name"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["name"])));
	$_POST["description"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["description"])));
	
	$_POST["startTime"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["startTime"])));
	$_POST["endTime"] = mysqli_real_escape_string($db, strip_tags(trim($_POST["endTime"])));
	
	$_POST["studentsGroup"] = (int)$_POST["studentsGroup"];
	$_POST["ratingSystem"] = (int)$_POST["ratingSystem"];
	
	$_POST["problems-by-id"] = mysqli_real_escape_string($db, strip_tags(trim($_POST['problems-by-id'])));
	
	////////
	
	(strlen($_POST["name"]) > 0 && strlen($_POST["name"]) <= 255)
		or die(header('location: index.php?service=error&err=input'));
	
	(strlen($_POST["description"]) > 0 && strlen($_POST["description"]) <= 65535)
		or die(header('location: index.php?service=error&err=input'));
	
	(strlen($_POST["startTime"]) == 19)
		or die(header('location: index.php?service=error&err=input'));
	
	(strlen($_POST["endTime"]) == 19)
		or die(header('location: index.php?service=error&err=input'));
	
	($_POST["studentsGroup"] > 0)
		or die(header('location: index.php?service=error&err=input'));
	
	($_POST["ratingSystem"] >= 0 && $_POST["ratingSystem"] <= 225)
		or die(header('location: index.php?service=error&err=input'));
	
	////////
	
	$query_substr = "
			`name` = '" . $_POST['name'] . "',
			`description` = '" . $_POST['description'] . "',
			
			`startTime` = '" . $_POST['startTime'] . "',
			`endTime` = '" . $_POST['endTime'] . "',
			
			`studentsGroup` = '" . $_POST['studentsGroup'] . "',
			
			`teacherId` = '" . $_SESSION['uid'] . "',
			
			`ratingSystem` = '" . $_POST['ratingSystem'] . "',
			`problemslist` = '" . $_POST['problems-by-id'] . "'
	";
	
	$query_str = "
		INSERT INTO
			`spm_classworks`
		SET
			`id` = " . $_GET["id"] . ",
			
			" . $query_substr . "
		ON DUPLICATE KEY UPDATE
			" . $query_substr . "
		;
	";
	
	if (!$db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	////////
	
	exit(header('location: index.php?service=classworks'));
	
?>
