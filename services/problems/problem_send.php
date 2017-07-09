<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::student | PERMISSION::teacher | PERMISSION::administrator);
	
	/////////////////////////////////////
	// REQUIRED INCLUDES AND VARIABLES //
	/////////////////////////////////////
	
	include_once(_S_SERV_INC_ . "problems/codeLang.php");
	$meta_refresh = "<meta http-equiv='refresh' content='5; url=index.php?service=problem&id=" . $_POST['problemId'] . "'>";
	$setAsAuthorSolution = 'false';
	
	/////////////////////////////////////
	//            SEND TYPE            //
	/////////////////////////////////////
	
	if (isset($_POST['syntax']))
		$testType = 'syntax';
	if (isset($_POST['debug']))
		$testType = 'debug';
	if (isset($_POST['release']))
		$testType = 'release';
	
	/////////////////////////////////////
	//  AUTHOR SOLUTION SEND CHECKER   //
	/////////////////////////////////////
	
	if (isset($_POST['setAsAuthorSolution'])){
		deniedOrAllowed(PERMISSION::administrator);
		$testType = 'release';
		$setAsAuthorSolution = 'true';
	}
	
	/////////////////////////////////////
	//       CODE LANG SWITCHER        //
	/////////////////////////////////////
	
	@$codeLang = switchCodeLang($_POST['codeLang']);
	
	/////////////////////////////////////
	
	isset($testType) or die('<strong>Тип проверки решения не указан!</strong>' . $meta_refresh);
	
	/////////////////////////////////////
	
	(isset($_POST['problemId']) && ((int)$_POST['problemId'] > 0) && ($_POST['problemId'] = (int)$_POST['problemId']))
		or die('<strong>Идентификатор задачи указан не верно!</strong>' . $meta_refresh);
	
	/////////////////////////////////////
	
	(isset($_POST['code']) && (strlen($_POST['code']) > 0) && ($_POST['code'] = mysqli_real_escape_string($db, $_POST['code'])))
		or die('<strong>Исходный код вашего решения не указан!</strong>' . $meta_refresh);
	
	/////////////////////////////////////
	
	isset($_POST['args']) or die('<strong>Попытка POST инъекции заблокирована!</strong>' . $meta_refresh);
	$_POST['args'] = mysqli_real_escape_string($db, $_POST['args']);
	
	/////////////////////////////////////
	//         PROBLEM CHECKER         //
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			count(`id`)
		FROM
			`spm_problems`
		WHERE
			`id` = '" . $_POST['problemId'] . "'
		LIMIT
			1
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($query->num_rows == 0 || $query->fetch_array()[0] == 0)
		die('<strong>Задача с указанным ID не существует!</strong>');
	
	/////////////////////////////////////
	//       CLASSWORK CHECKER         //
	/////////////////////////////////////
	
	if (isset($_SESSION["classwork"]))
		$classworkId = $_SESSION["classwork"];
	else
		$classworkId = 0;
	
	if ($classworkId > 0) {
		
		$query_str = "
			SELECT
				count(`id`)
			FROM
				`spm_classworks_problems`
			WHERE
				`problemId` = '" . $_POST['problemId'] . "'
			AND
				`classworkId` = '" . $classworkId . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows == 0 || $query->fetch_array()[0] == 0)
			die('<strong>Задача с указанным ID не включена в урок!</strong>');
		
	}
	
	/////////////////////////////////////
	//       SUBMISSIONS CLEANER       //
	/////////////////////////////////////
	
	$query_str = "
		DELETE FROM
			`spm_submissions`
		WHERE
			`userId` = '" . $_SESSION['uid'] . "'
		AND
			`problemId` = '" . $_POST['problemId'] . "'
		AND
			`classworkId` = '" . $classworkId . "'
		;
	";
	
	if (!$db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/////////////////////////////////////
	//     NEW SUBMISSION INSERTER     //
	/////////////////////////////////////
	
	$query_str = "
		INSERT INTO
			`spm_submissions`
		SET
			`setAsAuthorSolution` = " . $setAsAuthorSolution . ",
			`classworkId` = '" . $classworkId . "',
			`olympId` = '0',
			`problemCode`='" . $_POST['code'] . "',
			`userId` = '" . $_SESSION['uid'] ."',
			`problemId` = '" . $_POST['problemId'] . "',
			`testType` = '" . $testType . "',
			`codeLang` = '" . $codeLang . "',
			`customTest` = '" . $_POST['args'] . "'
		;
	";
	
	//for ($i = 1; $i <= 80; $i++)
	if (!$db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/////////////////////////////////////
	
	$submissionID = $db->insert_id;
	header('location: index.php?service=problem_result&sid=' . $submissionID);
?>
