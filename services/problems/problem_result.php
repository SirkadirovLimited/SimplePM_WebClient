<?php
	
	/////////////////////////////////////////////
	/// РАЗНООБРАЗНЫЕ ПРОВЕРКИ БЕЗОПАСНОСТИ
	/////////////////////////////////////////////
	
	deniedOrAllowed(PERMISSION::student | PERMISSION::teacher | PERMISSION::administrator);
	(isset($_GET['sid']) && (int)$_GET['sid'] > 0)
		or die(header('location: index.php?service=error&err=input'));
    
	/////////////////////////////////////////////
	/// ВРЕМЯ ИНТЕРЕСНЕНЬКИХ ЗАПРОСОВ К БД
	/////////////////////////////////////////////
	
	$query_str = "
		SELECT
			*
		FROM
			`spm_submissions`
		WHERE
			`submissionId` = '" . (int)$_GET['sid'] . "'
		LIMIT
			1
		;
	";
	
	($db_result = $db->query($query_str))
		or die(header('location: index.php?service=error&err=db_error'));
	
    ($db_result->num_rows > 0) or die(header('location: index.php?service=error&err=404'));
	
	$submission = $db_result->fetch_assoc();
	
	/////////////////////////////////////////////
	/// ПРОВЕРКА НА ПРАВОМЕРНОСТЬ ДЕЯНИЙ
	/////////////////////////////////////////////
	
	$checker_1 = $submission['userId'] != $_SESSION["uid"];
	$checker_2 = spm_getUserTeacherId($submission['userId']) != $_SESSION['uid'];
	$checker_3 = !permission_check($_SESSION['permissions'], PERMISSION::administrator);
	
	if ($checker_1 && $checker_2 && $checker_3)
		die(header('location: index.php?service=error&err=403'));
    
	/////////////////////////////////////////////
	/// ОТСЫЛАЕМ ВЕРХНЮЮ ЧАСТЬ ШАБЛОНА
	/////////////////////////////////////////////
	
	SPM_header("Спроба №" . $submission['submissionId'], "Задача " . $submission['problemId'], "Результат перевірки");
	
	/////////////////////////////////////////////
	/// ЕЩЁ КАКОЙ-то НЕПОНЯТНЫЙ КОД
	/////////////////////////////////////////////
	
	if ($submission['status'] == "ready")
		include_once(_S_VIEW_ . "problems/result.php");
	else
		include_once(_S_VIEW_ . "problems/result_wait.php");
	
	/////////////////////////////////////////////
	
?>

<div class="panel panel-default" style="border-radius: 0;">
	<div class="panel-body">
		<a href="index.php?service=problem&id=<?php print($submission['problemId']); ?>" class="btn btn-primary btn-flat">
			<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Повернутися до задачі
		</a>
		<?php if (!isset($_SESSION["classwork"], $_SESSION["olymp"])): ?>
		<a href="index.php?service=problems" class="btn btn-default btn-flat">
			<span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Архів задач
		</a>
		<?php endif; ?>
	</div>
</div>

<?php
	
	/////////////////////////////////////////////
	/// ОТПРАВЛЯЕМ НИЖНЮЮ ЧАСТЬ ШАБЛОНА
	/////////////////////////////////////////////
	
	SPM_footer();
	
	/////////////////////////////////////////////
	
?>