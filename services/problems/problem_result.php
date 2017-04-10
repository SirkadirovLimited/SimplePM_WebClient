<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::student);
	(isset($_GET['sid']) && (int)$_GET['sid'] > 0) or die('<strong>Зря стараешься, система полностью защищена от атак и инъекций!</strong>');
    
	($db_result = $db->query("SELECT * FROM `spm_submissions` WHERE `submissionId` = '" . (int)$_GET['sid'] . "' AND `userId` = '" . $_SESSION['uid'] . "' LIMIT 1;"))
		or die('<strong>Произошла ошибка при попытке подключения к базе данных! Пожалуйста, повторите ваш запрос позже.</strong>');
	
    ($db_result->num_rows > 0) or die('<strong>По вашему запросу результатов проверки не найдено либо вы не имеете доступа к ним!</strong>');
	
	$submission = $db_result->fetch_assoc();
    
	SPM_header("Результат проверки №" . $submission['submissionId'], "Задача " . $submission['problemId'], "Результат проверки");
	
	if ($submission['status'] == "ready")
		include_once(_S_SERV_INC_ . "problems/ui/result.php");
	else
		include_once(_S_SERV_INC_ . "problems/ui/result_wait.php");
?>

<div class="panel panel-default" style="border-radius: 0;">
	<div class="panel-body">
		<a href="index.php?service=problem&id=<?php print($submission['problemId']); ?>" class="btn btn-primary btn-flat">
			<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Вернуться к задаче
		</a>
		<a href="index.php?service=problems" class="btn btn-default btn-flat">
			<span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Архив задач
		</a>
	</div>
</div>

<?php
	SPM_footer();
?>