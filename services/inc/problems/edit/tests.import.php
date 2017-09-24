<?php
	
	/* Security checkers (Vol. 1) */
	isset($_FILES['testsFile']) && !empty($_FILES['testsFile']) or die('<strong>404: File not found!</strong>');
	
	isset($_FILES['testsFile']['error']) && !is_array($_FILES['testsFile']['error'])
		or die(header('location: index.php?service=error&err=input'));
	
	($_FILES['upfile']['error'] == UPLOAD_ERR_OK)
		or die(header('location: index.php?service=error&err=input'));
	
	/* JSON decoding */
	$file_content = file_get_contents($_FILES['testsFile']['tmp_name']); // get file content
	$json = json_decode($file_content, true); // decode json data
	unset($file_content);
	
	/* Delete all old tests */
	$query_str = "
		DELETE FROM
			`spm_problems_tests`
		WHERE
			`problemId` = '" . $_GET['id'] . "'
		;
	";
	
	if (!$db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/* Import new tests */
	
	foreach ($json["tests"] as $test)
	{
		
		$query_str = "
			INSERT INTO
				`spm_problems_tests`
			SET
				`problemId` = '" . $_GET['id'] . "',
				
				`input` = '" . htmlspecialchars_decode($test['input']) . "',
				`output` = '" . htmlspecialchars_decode($test['output']) . "',
				`description` = '" . htmlspecialchars_decode($test['description']) . "',
				
				`timeLimit` = '" . $test['timeLimit'] . "',
				`memoryLimit` = '" . $test['memoryLimit'] . "'
			;
		";
		
		if (!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
	}
	
	header('location: ' . $_SERVER['REQUEST_URI']);
	
?>