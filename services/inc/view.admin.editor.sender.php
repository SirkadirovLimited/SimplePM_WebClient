<?php
	defined("__view.admin__") or die('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	global $_SPM_CONF;
	global $db;
	
	if (isset($_POST['id']) && isset($_POST['pname']) && isset($_POST['pcontent'])){
		
		// Variable cleaning
		$_POST['pname'] = mysqli_real_escape_string($db, htmlspecialchars(trim($_POST['pname'])));
		$_POST['pcontent'] = mysqli_real_escape_string($db, htmlspecialchars(trim($_POST['pcontent'])));
		
		// MySQL query
		$query_str = "
			INSERT INTO
				`spm_pages`
			SET
				`id` = " . ($_POST['id'] > 0 ? $_POST['id'] : 'null') . ",
				`name` = '" . $_POST['pname'] . "',
				`content` = '" . $_POST['pcontent'] . "'
			ON DUPLICATE KEY
			UPDATE
				`name` = '" . $_POST['pname'] . "',
				`content` = '" . $_POST['pcontent'] . "'
			;
		";
		
		if (!$db_result = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		// View success message
		_spm_view_msg("Зміни внесено успішно!", "success");
		
	}
?>