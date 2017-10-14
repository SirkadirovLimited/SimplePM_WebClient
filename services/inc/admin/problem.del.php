<?php
	if (isset($_POST['del']) && isset($_POST['id']) && $_POST['id'] > 0)
	{
		
		deniedOrAllowed(PERMISSION::administrator);
		
		$_POST['id'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['id'])));
		
		if (!$db->query("DELETE FROM `spm_problems` WHERE `id` = '" . $_POST['id'] . "' LIMIT 1;"))
			die(header('location: index.php?service=error&err=db_error'));
		
		if (!$db->query("DELETE FROM `spm_problems_ready` WHERE `problemId` = '" . $_POST['id'] . "';"))
			die(header('location: index.php?service=error&err=db_error'));
		
		if (!$db->query("DELETE FROM `spm_problems_tests` WHERE `problemId` = '" . $_POST['id'] . "';"))
			die(header('location: index.php?service=error&err=db_error'));
		
		if (!$db->query("DELETE FROM `spm_submissions` WHERE `problemId` = '" . $_POST['id'] . "';"))
			die(header('location: index.php?service=error&err=db_error'));
		
		exit(header('location: ' . $_SERVER['REQUEST_URI']));
		
	}
?>