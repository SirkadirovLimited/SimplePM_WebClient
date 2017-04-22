<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::student);
	
	(isset($_GET['id']) && (int)$_GET['id']>0) or die('<strong>ID задачи не указан!</strong>');
	
	if (!$db_problem = $db->query("SELECT * FROM `spm_problems` WHERE `id` = '" . (int)$_GET['id'] . "' LIMIT 1;"))
		die('<strong>Указанная задача не найдена!</strong>');
	
	($db_problem->num_rows > 0) or die('<strong>Указанная задача не найдена!</strong>');
	
	$problem_info = $db_problem->fetch_assoc();
	
	if (! $db_submission_get = $db->query("SELECT * FROM `spm_submissions` WHERE (`userId` = '" . $_SESSION['uid'] . "' AND `problemId` = '" . $problem_info['id'] . "') ORDER BY `submissionId` DESC LIMIT 1;"))
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
?>
<script src="<?php print(_S_TPL_); ?>plugins/ace/ace.js" type="text/javascript" charset="utf-8"></script>
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
	<div class="panel-heading" align="center" style="padding-top: 5px; padding-bottom: 5px;"><strong>Задача <?php print($problem_info['id']); ?> [Линейные -> Начало]. Сложность <?php print($problem_info['difficulty']); ?>%</strong></div>
	<div class="panel-body" style="padding: 0;">
		<form action="index.php?service=problem_send" method="post">
			
			<input type="hidden" name="problemId" value="<?php print($problem_info['id']); ?>" />
			
			<div id="codeEditor" contenteditable="true"><?php print($submissionCode); ?></div>
			
			<textarea name="code" class="hidden" id="code"></textarea>
			<textarea class="form-control" rows="4" name="args" id="args"
			style="margin: 0;" placeholder="Введите собственный тест для совершения отладки приложения"><?php print($submissionArgs); ?></textarea>
			
			<select class="form-control" name="codeLang" required>
				<option value>Выберите компилятор</option>
				<option value="1" selected>Free Pascal</option>
				<!--option value="cpp">GNU C++</option-->
				<!--option value="c">GNU C / Objective C</option-->
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
<?php
	if (isset($submission)){
?>
				<div class="col-xs-12 col-md-12" style="padding: 0;">
					<a href="index.php?service=problem_result&sid=<?php print($submission['submissionId']); ?>" class="btn btn-default btn-block btn-flat">Информация о последней попытке</a>
				</div>
<?php
	}
?>
			</div>
		</form>
	</div>
</div>
<div class="panel panel-default" style="margin: 0;">
	<div class="panel-heading" align="center" style="padding-top: 5px; padding-bottom: 5px;"><?php print($problem_info['name']); ?></div>
	<div class="panel-body">
		<div id="problem_info">
			<p><?php print(htmlspecialchars_decode($problem_info['description'])); ?></p>
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
				<p><?php print($input_string); ?></p>
			</div>
			<div class="col-md-6">
				<h4>OUTPUT</h4>
				<p><?php print($output_string); ?></p>
			</div>
		</div>
		<!--I/O examples-->
		<div class="row">
			<div class="col-md-6">
				<h4>EXAMPLE INPUT</h4>
				<p><?php print($input_ex_string); ?></p>
			</div>
			<div class="col-md-6">
				<h4>EXAMPLE OUTPUT</h4>
				<p><p><?php print($output_ex_string); ?></p></p>
			</div>
		</div>
	</div>
</div>
<?php
	if (permission_check($_SESSION['permissions'], PERMISSION::administrator) && isset($submission)){
?>
<div class="panel panel-default" style="border-radius: 0;">
	<div class="panel-body" style="padding: 0;">
		<form action="index.php?service=problems.admin&action=setSolution" method="post" style="margin: 0;">
			<input type="hidden" name="problemId" value="<?php print($problem_info['id']); ?>">
			<input type="hidden" name="submissionId" value="<?php print($submission['submissionId']); ?>">
			<input
				type="submit"
				name="apply"
				class="btn btn-warning btn-flat btn-block"
				value="Сделать текущую попытку авторским решением"
				onclick="return confirm('ВНИМАНИЕ! Это действие может привести к необратимым последствиям и уничтожению предыдущего авторского решения! Вы действительно хотите его перезаписать?');"
			>
		</form>
	</div>
</div>

<?php
	}
?>
<script src="<?php print(_S_TPL_); ?>js/jquery-1.min.js" type="text/javascript"></script>
<script type="text/javascript">
	var editor = ace.edit("codeEditor");
    //editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/pascal");
	
	editor.getSession().on("change", function () { $('textarea[name="code"]').val(editor.getSession().getValue()); });
	
	function getcode() { document.getElementById("code").innerHTML = editor.getValue(); }
</script>
<?php SPM_footer(); ?>