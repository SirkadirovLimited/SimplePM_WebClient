<?php
	
	///////////////////////////////////////
	/// SECURITY CHECKS
	///////////////////////////////////////
	
	// User id
	isset($_POST['uid']) && (int)$_POST['uid'] > 1 or die(header('location: index.php?service=error&err=input'));
	$_POST['uid'] =  (int)($db->real_escape_string(strip_tags(trim($_POST['uid']))));
	// Permissions
	isset($_POST['permissions']) && (int)$_POST['permissions'] >= 0 or die(header('location: index.php?service=error&err=input'));
	$_POST['permissions'] = (int)($db->real_escape_string(strip_tags(trim($_POST['permissions']))));
	// TeacherId
	isset($_POST['teacherId']) && (int)$_POST['teacherId'] >= 0 or die(header('location: index.php?service=error&err=input'));
	$_POST['teacherId'] =  (int)($db->real_escape_string(strip_tags(trim($_POST['teacherId']))));
	
	///////////////////////////////////////
	/// CHANGING THE WORLD
	///////////////////////////////////////
	
	$query_str = "
		UPDATE
			`spm_users`
		SET
			`permissions` = " . $_POST['permissions'] . ",
			`teacherId` = " . $_POST['teacherId'] . "
		WHERE
			`id` = " . $_POST['uid'] . "
		LIMIT
			1
		;
	";
	
	if (!$db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	///////////////////////////////////////
	
	exit(header('location: ' . $_SERVER["REQUEST_URI"]));
	
	///////////////////////////////////////
	
?>