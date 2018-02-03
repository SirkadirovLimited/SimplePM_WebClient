<?php
	
	deniedOrAllowed(PERMISSION::teacher);
	
	(isset($_POST['id']) && (int)$_POST['id'] > 0)
		or die(header('location: index.php?service=error&err=input'));
	
	$query_str = "
		SELECT
			count(`id`)
		FROM
			`spm_classworks`
		WHERE
			`id` = '" . (int)$_POST['id'] . "'
		AND
			`teacherId` = '" . $_SESSION["uid"] . "'
		LIMIT
			1
		;
	";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($db_result->fetch_array()[0] != 1)
		die(header('location: index.php?service=error&err=404'));
	
	$query_str = "
		DELETE FROM
			`spm_classworks`
		WHERE
			`teacherId` = '" . $_SESSION["uid"] . "'
		AND
			`id` = '" . (int)$_POST['id'] . "'
		LIMIT
			1
		;
	";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	// Delete associated submissions
	
	$query_str = "
		DELETE FROM
			`spm_submissions`
		WHERE
			`classworkId` = '" . (int)$_POST['id'] . "'
		;
	";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	exit(header('location: index.php?service=classworks'));
	
?>
