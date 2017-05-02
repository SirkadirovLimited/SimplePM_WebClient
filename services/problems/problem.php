<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::student);
	
	(isset($_GET['id']) && (int)$_GET['id']>0) or die('<strong>ID задачи не указан!</strong>');
	
	if (!$db_problem = $db->query("SELECT * FROM `spm_problems` WHERE `id` = '" . (int)$_GET['id'] . "' LIMIT 1;"))
		die('<strong>Указанная задача не найдена!</strong>');
	
	($db_problem->num_rows > 0) or die('<strong>Указанная задача не найдена!</strong>');
	
	$problem_info = $db_problem->fetch_assoc();
	
	if (!$db_submission_get = $db->query("SELECT * FROM `spm_submissions` WHERE (`userId` = '" . $_SESSION['uid'] . "' AND `problemId` = '" . $problem_info['id'] . "') ORDER BY `submissionId` DESC LIMIT 1;"))
		die('<strong>Ошибка при выполнении запроса к базе данных! Пожалуйста, посетите сайт позже!</strong>');
	
	SPM_header("Задача " . $problem_info['id'], "Редактор");
	
	if ($db_submission_get->num_rows > 0){
		$submission = $db_submission_get->fetch_assoc();
		
		$submissionCode = htmlspecialchars($submission['problemCode']);
		$submissionArgs = $submission['customTest'];
		
		_spm_view_msg("<b>ОБРАТИТЕ ВНИМАНИЕ!</b> При при отправке задачи предыдущие попытки будут стёрты!","warning");
	}else{
		$submissionCode = NULL;
		$submissionArgs = NULL;
	}
	$db_submission_get->free();
	unset($db_submission_get);
	
	if (isset($_GET['authorSolution'])){
		
		(permission_check($_SESSION['permissions'], PERMISSION::teacher | PERMISSION::administrator))
			or die('<strong>Error 403: ACCESS DENIED!</strong>');
		
		if (!$db_query = $db->query("SELECT `code` FROM `spm_problems_ready` WHERE `problemId` = '" . $problem_info['id'] . "' LIMIT 1;"))
			die('<strong>Ошибка при выполнении запроса к базе данных! Пожалуйста, обновите страницу!</strong>');
		
		if ($db_query->num_rows > 0)
			$submissionCode = htmlspecialchars($db_query->fetch_array()[0]);
		else
		{
			print('<strong>Указанная задача не имеет авторского решения!</strong>');
			SPM_footer();
			exit;
		}
	}
?>
<script src="<?=_S_TPL_?>plugins/ace/ace.js" charset="utf-8"></script>
<style type="text/css">
    #codeEditor {
		position: relative;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
		height: 400px;
		margin: 0;
		font-size: 15px;
    }
	#args {
		resize: none;
	}
</style>


<div class="panel panel-default" style="margin: 0; margin-bottom: 5px;">
	<div class="panel-heading" align="center" style="padding-top: 5px; padding-bottom: 5px;"><strong>Задача <?=$problem_info['id']?>. Сложность <?=$problem_info['difficulty']?>%</strong></div>
	<div class="panel-body" style="padding: 0;">
		<form action="index.php?service=problem_send" method="post">
			
			<input type="hidden" name="problemId" value="<?=$problem_info['id']?>" />
			
			<div id="codeEditor" contenteditable="true"><?=$submissionCode?></div>
			
			<textarea name="code" class="hidden" id="code"></textarea>
			<textarea class="form-control" rows="4" name="args" id="args"
			style="margin: 0;" placeholder="Введите собственный тест для совершения отладки приложения"><?=$submissionArgs?></textarea>
			
			<select class="form-control" name="codeLang" required>
				<option value>Выберите компилятор</option>
				<option value="1" selected>Free Pascal</option>
			</select>
			
			<div class="row-fluid">
				<div class="col-xs-4 col-md-4" style="padding: 0;">
					<input class="btn btn-info btn-block btn-flat" type="submit" name="syntax" value="Синтаксис" style="margin: 0;" onclick="getcode();" />
				</div>
				<div class="col-xs-4 col-md-4" style="padding: 0;">
					<input class="btn btn-primary btn-block btn-flat" type="submit" name="debug" value="Отладка" style="margin: 0;" onclick="getcode();" />
				</div>
				<div class="col-xs-4 col-md-4" style="padding: 0;">
					<input class="btn btn-success btn-block btn-flat" type="submit" name="release" value="Отправка" style="margin: 0;" onclick="getcode();" />
				</div>
<?php if (permission_check($_SESSION["permissions"], PERMISSION::teacher | PERMISSION::administrator)): ?>
				<div class="col-xs-12 col-md-12" style="padding: 0;">
					<a href="index.php?service=problem&id=<?=$problem_info['id']?>&authorSolution" class="btn btn-default btn-block btn-flat">Получить авторское решение</a>
				</div>
<?php endif; ?>
<?php if (isset($submission) && !permission_check($_SESSION["permissions"], PERMISSION::administrator)): ?>
				<div class="col-xs-12 col-md-12" style="padding: 0;">
					<a href="index.php?service=problem_result&sid=<?=$submission['submissionId']?>" class="btn btn-default btn-block btn-flat">Информация о последней попытке</a>
				</div>
<?php elseif (isset($submission) && permission_check($_SESSION["permissions"], PERMISSION::administrator)):?>
				<div class="col-xs-6 col-md-6" style="padding: 0;">
					<a href="index.php?service=problem_result&sid=<?=$submission['submissionId']?>" class="btn btn-warning btn-block btn-flat">Информация о последней попытке</a>
				</div>
				<div class="col-xs-6 col-md-6" style="padding: 0;">
					<input
						type="submit"
						name="setAsAuthorSolution"
						class="btn btn-danger btn-flat btn-block"
						value="Установить авторское решение"
						onclick="getcode(); return confirm('ВНИМАНИЕ! Это действие может привести к необратимым последствиям и уничтожению предыдущего авторского решения! Вы действительно хотите его перезаписать?');"
					>
				</div>
<?php endif;?>
			</div>
		</form>
	</div>
</div>
<div class="panel panel-default" style="margin: 0;">
	<div class="panel-heading" align="center" style="padding-top: 5px; padding-bottom: 5px;"><?=$problem_info['name']?></div>
	<div class="panel-body">
		<div id="problem_info">
			<p><?=htmlspecialchars_decode($problem_info['description'])?></p>
		</div>
		<!--I/O information-->
<?php
	//input
	if (empty($problem_info['input']))
		$input_string = "Входной поток пуст.";
	else
		$input_string = $problem_info['input'];
	//input_ex
	if (empty($problem_info['input_ex']))
		$input_ex_string = "Входной поток пуст.";
	else
		$input_ex_string = $problem_info['input_ex'];
	//output
	if (empty($problem_info['output']))
		$output_string = "Выходной поток пуст.";
	else
		$output_string = $problem_info['output'];
	//output_ex
	if (empty($problem_info['output_ex']))
		$output_ex_string = "Выходной поток пуст.";
	else
		$output_ex_string = $problem_info['output_ex'];
?>
		<div class="row">
			<div class="col-md-6">
				<h4>INPUT</h4>
				<p><?=$input_string?></p>
			</div>
			<div class="col-md-6">
				<h4>OUTPUT</h4>
				<p><?=$output_string?></p>
			</div>
		</div>
		<!--I/O examples-->
		<div class="row">
			<div class="col-md-6">
				<h4>EXAMPLE INPUT</h4>
				<p><?=$input_ex_string?></p>
			</div>
			<div class="col-md-6">
				<h4>EXAMPLE OUTPUT</h4>
				<p><p><?=$output_ex_string?></p></p>
			</div>
		</div>
	</div>
</div>
<!--script src="<?php print(_S_TPL_); ?>js/jquery-1.min.js"></script-->
<script type="text/javascript">
	var editor = ace.edit("codeEditor");

    //editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/pascal");
	
	editor.getSession().on("change", function () { $('textarea[name="code"]').val(editor.getSession().getValue()); });
	
	function getcode() { document.getElementById("code").innerHTML = editor.getValue(); }
</script>
<?php SPM_footer(); ?>