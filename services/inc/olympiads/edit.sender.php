<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::olymp);
	
	//Security
	$_GET['id'] > 0 or $_GET['id'] = "NULL";
	
	//Errors count
	$_errorsCount = 0;
	
	/*
	 * ПЕРВЫЙ КРУГ АДА
	 */
	
	$olympTypes = array(
		"olymp" => "olymp",
		"classwork" => "classwork",
		"testing" => "testing"
	);
	
	(isset($_POST['olympType']) && isset($olympTypes[$_POST['olympType']])) or $_errorsCount++;
	
	(isset($_POST['name']) && strlen($_POST['name']) > 0 && strlen($_POST['name']) <= 255) or $_errorsCount++;
	(isset($_POST['description']) && (strlen($_POST['description']) > 0) && (strlen($_POST['description']) <= 65000)) or $_errorsCount++;
	
	(isset($_POST['startTime']) && (strlen($_POST['startTime']) == 19)) or $_errorsCount++;
	(isset($_POST['endTime']) && (strlen($_POST['endTime']) == 19)) or $_errorsCount++;
	
	(isset($_POST['minb']) && ((int)$_POST['minb'] >= 0)) or $_errorsCount++;
	$_POST['minb'] = @(int)$_POST['minb'];
	
	($_errorsCount == 0) or die('<strong>Поля формы заполнены не верно!</strong>');
	
	/*
	 * ВТОРОЙ КРУГ АДА
	 */
	
	$_POST['name'] = mysqli_real_escape_string($db, strip_tags(trim($_POST['name'])));
	$_POST['description'] = mysqli_real_escape_string($db, strip_tags(trim($_POST['description'])));
	
	$_POST['startTime'] = mysqli_real_escape_string($db, strip_tags(trim($_POST['startTime'])));
	$_POST['endTime'] = mysqli_real_escape_string($db, strip_tags(trim($_POST['endTime'])));
	
	$_POST['minb'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['minb'])));
	
	/*
	 * ТРЕТИЙ КРУГ АДА
	 */
	
	if ($_GET['id'] > 0){
		$query_str = "SELECT count(`id`) FROM `spm_olympiads` WHERE `id` = '" . $_GET['id'] . "';";
		if (!$query = $db->query($query_str))
			die('<strong>Форма заполнена не верно!</strong>');
		
		$query->fetch_array()[0] > 0 or die('<strong>Соревнование с таким идентификатором не найдено!</strong>');
		$query->free();
	}
	
	/*
	 * ЧЕТВЁРТЫЙ КРУГ АДА
	 */
	
	$query_ins = "
			`name` = '" . $_POST['name'] . "',
			`description` = '" . $_POST['description'] . "',
			`type` = '" . $_POST['olympType'] . "',
			`startTime` = '" . $_POST['startTime'] . "',
			`endTime` = '" . $_POST['endTime'] . "',
			`minb` = '" . $_POST['minb'] . "'
	";
	$query_str = "
		INSERT INTO
			`spm_olympiads`
		SET
			`id` = " . $_GET['id'] . ",
			`teacherId` = " . $_SESSION['uid'] . ",
			" . $query_ins . "
		ON DUPLICATE KEY UPDATE
			" . $query_ins . "
		;
	";
	
	if (!$db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	//Получаем идентификатор олимпиады
	$_olympId = $db->insert_id;
	
	/*
	 * ПЯТЫЙ КРУГ АДА
	 */
	
	if (isset($_POST['problems-by-id'])){
		
		$problems = explode("\n", $_POST['problems-by-id']);
		
		foreach ($problems as $problem){
			$problemId = (int)mysqli_real_escape_string($db, strip_tags(trim($problem)));
			
			$query_str = "
						INSERT INTO
							`spm_olympiads_problems`
						SET
							`olympId` = '" . $_olympId . "',
							`problemId` = '" . $problemId . "'
						;
			";
			
			@$db->query($query_str);
		}
		
	}
	
	/*
	 * ШЕСТОЙ КРУГ АДА
	 */
	
	
	
	/*
	 * СЕДЬМОЙ КРУГ АДА
	 */
	
	exit(header('location: index.php?service=olympiads'));
?>