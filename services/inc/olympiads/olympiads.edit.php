<?php
	
	deniedOrAllowed(
		PERMISSION::administrator | 
		PERMISSION::olymp
	);
	
	////////
	
	$_GET['id'] > 0 or $_GET['id'] = "null";
	
	////////
	
	isset($_POST["name"])
		or die(header('location: index.php?service=error&err=input'));
	isset($_POST["description"])
		or die(header('location: index.php?service=error&err=input'));
	
	isset($_POST["startTime"])
		or die(header('location: index.php?service=error&err=input'));
	isset($_POST["endTime"])
		or die(header('location: index.php?service=error&err=input'));
	
	////////
	
	$_POST["name"] = mysqli_real_escape_string(
		$db,
		strip_tags(trim($_POST["name"]))
	);
	$_POST["description"] = mysqli_real_escape_string(
		$db,
		strip_tags(trim($_POST["description"]))
	);
	
	$_POST["startTime"] = mysqli_real_escape_string(
		$db,
		strip_tags(trim($_POST["startTime"]))
	);
	$_POST["endTime"] = mysqli_real_escape_string(
		$db,
		strip_tags(trim($_POST["endTime"]))
	);
	
	////////
	
	(strlen($_POST["name"]) > 0 && strlen($_POST["name"]) <= 255)
		or die(header('location: index.php?service=error&err=input'));
	
	(strlen($_POST["description"]) > 0 && strlen($_POST["description"]) <= 65535)
		or die(header('location: index.php?service=error&err=input'));
	
	(strlen($_POST["startTime"]) == 19)
		or die(header('location: index.php?service=error&err=input'));
	
	(strlen($_POST["endTime"]) == 19)
		or die(header('location: index.php?service=error&err=input'));
	
	if (permission_check($_SESSION["permissions"], PERMISSION::teacher))
		$_POST["type"] = "Private";
	else
		$_POST["type"] = "Public";
	
	////////
	
	$query_substr = "
			`name` = '" . $_POST['name'] . "',
			`description` = '" . $_POST['description'] . "',
			
			`startTime` = '" . $_POST['startTime'] . "',
			`endTime` = '" . $_POST['endTime'] . "',
			
			`type` = '" . $_POST['type'] . "',
			
			`teacherId` = '" . $_SESSION['uid'] . "',
			`problemslist` = '" . $_POST['problems-by-id'] . "'
	";
	
	$query_str = "
		INSERT INTO
			`spm_olympiads`
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
	
	exit(header('location: index.php?service=olympiads.list'));
	
?>
