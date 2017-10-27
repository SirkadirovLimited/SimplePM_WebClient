<?php

	defined("__spm_admin_problems_edit__") or die(header('location: index.php?service=error&err=403'));
	deniedOrAllowed(PERMISSION::administrator);
	
	/*
	 * ИСПЫТАНИЕ ЗНАНИЕМ
	 */
	isset($_POST['enabled']) or $problem_info['enabled'] = 0;
	isset($_POST['difficulty']) or $problem_info['difficulty'] = 1;
	isset($_POST['catId']) or $problem_info['catId'] = 1;
	isset($_POST['name']) or die(header('location: index.php?service=error&err=input'));
	isset($_POST['description']) or die(header('location: index.php?service=error&err=input'));
	isset($_POST['debugTimeLimit']) or $problem_info['debugTimeLimit'] = 0;
	isset($_POST['debugMemoryLimit']) or $problem_info['debugMemoryLimit'] = 0;
	isset($_POST['input']) or $_POST['input'] = null;
	isset($_POST['output']) or $_POST['output'] = null;
	
	/*
	 * ИСПЫТАНИЕ ПАМЯТЬЮ
	 */
	$problem_info['id'] = $_GET['id'];
	$problem_info['enabled'] = (int)$_POST['enabled'];
	$problem_info['difficulty'] = (int)$_POST['difficulty'];
	$problem_info['catId'] = (int)$_POST['catId'];
	$problem_info['name'] = $_POST['name'];
	$problem_info['description'] = $_POST['description'];
	$problem_info['debugTimeLimit'] = (int)$_POST['debugTimeLimit'];
	$problem_info['debugMemoryLimit'] = (int)$_POST['debugMemoryLimit'];
	$problem_info['input'] = $_POST['input'];
	$problem_info['output'] = $_POST['output'];
	
	/*
	 * ИСПЫТАНИЕ СИЛОЙ
	 */
	$problem_info['name'] = mysqli_real_escape_string($db, trim($problem_info['name']));
	$problem_info['description'] = mysqli_real_escape_string($db, htmlspecialchars(trim($problem_info['description'])));
	$problem_info['input'] = mysqli_real_escape_string($db, trim($problem_info['input']));
	$problem_info['output'] = mysqli_real_escape_string($db, trim($problem_info['output']));
	
	/*
	 * ИСПЫТАНИЕ ОГНЁМ
	 */
	($problem_info['id'] != null) or $problem_info['id'] = "NULL";
	($problem_info['enabled'] == 0 || $problem_info['enabled'] == 1) or $problem_info['enabled'] = 1;
	($problem_info['difficulty'] >= 1 && $problem_info['difficulty'] <= 100) or $problem_info['difficulty'] = 1;
	($problem_info['catId'] > 0) or $problem_info['catId'] = 1;
	(strlen($problem_info['name']) > 0 && strlen($problem_info['name']) <= 255) or die(header('location: index.php?service=error&err=input'));
	(strlen($problem_info['description']) > 0 && strlen($problem_info['description']) <= 65535) or die(header('location: index.php?service=error&err=input'));
	($problem_info['debugTimeLimit'] > 0) or $problem_info['debugTimeLimit'] = 200;
	($problem_info['debugMemoryLimit'] > 0) or $problem_info['debugMemoryLimit'] = 20971520;
	(strlen($problem_info['input']) > 0 && strlen($problem_info['input']) <= 65535) or $problem_info['input'] = null;
	(strlen($problem_info['output']) > 0 && strlen($problem_info['output']) <= 65535) or $problem_info['output'] = null;
	
	/*
	 * ДА БУДЕТ ПРАЗДНИК!
	 */
	$insert_query_update = "
			`enabled` = " . $problem_info['enabled'] . ",
			`difficulty` = " . $problem_info['difficulty'] . ",
			`catId` = " . $problem_info['catId'] . ",
			`name` = '" . $problem_info['name'] . "',
			`description` = '" . $problem_info['description'] . "',
			`debugTimeLimit` = '" . $problem_info['debugTimeLimit'] . "',
			`debugMemoryLimit` = '" . $problem_info['debugMemoryLimit'] . "',
			`input` = '" . $problem_info['input'] . "',
			`output` = '" . $problem_info['output'] . "'
	";

	$insert_query = "
		INSERT INTO
			`spm_problems`
		SET
			`id` = " . $problem_info['id'] . ",
			" . $insert_query_update . "
		ON DUPLICATE KEY UPDATE
			" . $insert_query_update . "
		;
	";

	$db->query($insert_query) or die(mysqli_error($db));//die(header('location: index.php?service=error&err=db_error'));
	
	header('location: index.php?service=problem.edit&id=' . $db->insert_id . '&success');

?>