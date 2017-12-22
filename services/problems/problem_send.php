<?php
	
	/////////////////////////////////////
	
	deniedOrAllowed(
		PERMISSION::student
		| PERMISSION::teacher
		| PERMISSION::administrator
	);
	
	/////////////////////////////////////
	//            SEND TYPE            //
	/////////////////////////////////////
	
	isset($_POST['sendType']) or die(header('location: index.php?service=error&err=input'));
	
	if ($_POST['sendType'] == "syntax")
		$testType = 'syntax';
	if ($_POST['sendType'] == "debug")
		$testType = 'debug';
	if ($_POST['sendType'] == "release")
		$testType = 'release';
	
	/////////////////////////////////////

	isset($_POST['codeLang']) or die(header('location: index.php?service=error&err=input'));
	$_POST['codeLang'] = mysqli_real_escape_string($db, strip_tags(trim($_POST['codeLang'])));

	foreach ($_SPM_CONF["PROG_LANGS"] as $tmp_lang) {
		
		if ($tmp_lang['enabled'] && $tmp_lang['name'] == $_POST['codeLang']) {
			unset($tmp_lang);
			break;
		}
		
	}
	
	!isset($tmp_lang) or die(header('location: index.php?service=error&err=input'));
	
	isset($testType) or die(header('location: index.php?service=error&err=input'));
	
	/////////////////////////////////////
	
	(isset($_POST['problemId']) && ((int)$_POST['problemId'] > 0) && ($_POST['problemId'] = (int)$_POST['problemId']))
		or die(header('location: index.php?service=error&err=input'));
	
	/////////////////////////////////////
	
	(isset($_POST['code']) && (strlen($_POST['code']) > 0) && ($_POST['code'] = mysqli_real_escape_string($db, $_POST['code'])))
		or die(header('location: index.php?service=error&err=input'));
	
	/////////////////////////////////////
	
	isset($_POST['args'])
		or die(header('location: index.php?service=error&err=input'));
	
	$_POST['args'] = mysqli_real_escape_string($db, $_POST['args']);
	
	if (empty($_POST['args']) || $_POST['args'] == null || strlen($_POST['args']) <= 0)
	{
		
		$query_str = "
			SELECT
				`input`
			FROM
				`spm_problems_tests`
			WHERE
				`problemId` = '" . $_POST['problemId'] . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows > 0)
			$_POST['args'] = $query->fetch_array()[0];
		
		$query->free();
		
	}
	
	/////////////////////////////////////
	//         PROBLEM CHECKER         //
	/////////////////////////////////////
	
	//Show disabled problem or not
	if (!isset($_SESSION["classwork"]) && !isset($_SESSION["olymp"]) && permission_check($_SESSION["permissions"], PERMISSION::student))
		$showDisabled = "AND `enabled` = true";
	else
		$showDisabled = "";
	
	$query_str = "
		SELECT
			count(`id`)
		FROM
			`spm_problems`
		WHERE
			`id` = '" . $_POST['problemId'] . "'
			" . $showDisabled . "
		LIMIT
			1
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ((int)($query->fetch_array()[0]) <= 0)
		die(header('location: index.php?service=error&err=404'));
	
	/////////////////////////////////////
	//       CLASSWORK CHECKER         //
	/////////////////////////////////////
	
	$classworkId = isset($_SESSION["classwork"]) ? $_SESSION["classwork"] : 0;
	
	if ($classworkId > 0)
	{
		
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
		
		if ((int)($query->fetch_array()[0]) <= 0)
			die(header('location: index.php?service=error&err=403'));
		
	}
	
	/////////////////////////////////////
	//        OLYMPIAD CHECKER         //
	/////////////////////////////////////
	
	$olympId = isset($_SESSION["olymp"]) ? $_SESSION["olymp"] : 0;
	
	if ($olympId > 0)
	{
		
		$query_str = "
			SELECT
				count(`id`)
			FROM
				`spm_olympiads_problems`
			WHERE
				`problemId` = '" . $_POST['problemId'] . "'
			AND
				`olympId` = '" . $olympId . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ((int)($query->fetch_array()[0]) <= 0)
			die(header('location: index.php?service=error&err=403'));
		
	}
	
	/////////////////////////////////////
	//       SUBMISSIONS CLEANER       //
	/////////////////////////////////////
	
	//$classworkId = 0;
	//$olympId = 111;
	
	$query_str = "
		DELETE FROM
			`spm_submissions`
		WHERE
			`userId` = '" . $_SESSION['uid'] . "'
		AND
			`problemId` = '" . $_POST['problemId'] . "'
		AND
			`classworkId` = '" . $classworkId . "'
		AND
			`olympId` = '" . $olympId . "'
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
			`classworkId` = '" . $classworkId . "',
			`olympId` = '" . $olympId . "',
			`problemCode`='" . $_POST['code'] . "',
			`userId` = '" . $_SESSION['uid'] ."',
			`problemId` = '" . $_POST['problemId'] . "',
			`testType` = '" . $testType . "',
			`codeLang` = '" . $_POST['codeLang'] . "',
			`customTest` = '" . $_POST['args'] . "'
		;
	";
	
	//for ($i = 0; $i < 100; $i++)
	if (!$db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/////////////////////////////////////
	
	$submissionID = $db->insert_id;
	
	header('location: index.php?service=problem_result&sid=' . $submissionID);
	
	/////////////////////////////////////
	
?>