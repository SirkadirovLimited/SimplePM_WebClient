<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	/////////////////////////////////////
	//         SECURITY CHECKS         //
	/////////////////////////////////////
	
	deniedOrAllowed(PERMISSION::student | PERMISSION::teacher | PERMISSION::administrator);
	
	(isset($_GET['id']) && (int)$_GET['id'] > 0)
		or die(header('location: index.php?service=error&err=input'));
	
	/////////////////////////////////////
	//    CLASSWORKS SUBSYSTEM CODE    //
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
				`problemId` = '" . (int)$_GET['id'] . "'
			AND
				`classworkId` = '" . $classworkId . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows == 0 || $query->fetch_array()[0] == 0)
			die(header('location: index.php?service=error&err=403'));
		
	}
	
	/////////////////////////////////////
	//   SELECT PROBLEM INFORMATION    //
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			*
		FROM
			`spm_problems`
		WHERE
			`id` = '" . (int)$_GET['id'] . "'
		LIMIT
			1
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=404'));
	
	($query->num_rows > 0) or die(header('location: index.php?service=error&err=404'));
	
	$problem_info = $query->fetch_assoc();
	
	$query->free();
	
	/////////////////////////////////////
	//    GET LAST USER SUBMISSION     //
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			*
		FROM
			`spm_submissions`
		WHERE
			(
				`userId` = '" . $_SESSION['uid'] . "'
			AND
				`problemId` = '" . $problem_info['id'] . "'
			AND
				`classworkId` = '" . $classworkId . "'
			)
		ORDER BY
			`submissionId` DESC
		LIMIT
			1
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($query->num_rows > 0){
		
		$submission = $query->fetch_assoc();
		
		$submissionCode = htmlspecialchars($submission['problemCode']);
		$submissionArgs = $submission['customTest'];
		$submissionLang = $submission['codeLang'];
		
	} else {
		
		$submissionCode = NULL;
		$submissionArgs = NULL;
		$submissionLang = "unset";
		
	}
	
	$query->free();
	
	/////////////////////////////////////
	//      GET AUTHOR SOLUTION        //
	/////////////////////////////////////
	
	if (isset($_GET['authorSolution'])){
		
		deniedOrAllowed(PERMISSION::teacher | PERMISSION::administrator);
		
		$query_str = "
			SELECT
				`code`
			FROM
				`spm_problems_ready`
			WHERE
				`problemId` = '" . $problem_info['id'] . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows > 0)
			$submissionCode = htmlspecialchars($query->fetch_array()[0]);
		else
			die('<strong>Указанная задача не имеет авторского решения!</strong>');
		
		$query->free();
		
	}
	
	/////////////////////////////////////
	//         GET I/O EXAMPLES        //
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			`input`,
			`output`
		FROM
			`spm_problems_tests`
		WHERE
			`problemId` = '" . (int)$_GET['id'] . "'
		LIMIT
			1
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($query->num_rows == 1){
		
		$tmpArr = $query->fetch_assoc();
		
		$problem_info['input_ex'] = str_replace("\n", "<br/>", $tmpArr['input']);
		$problem_info['output_ex'] = str_replace("\n", "<br/>", $tmpArr['output']);
		
		unset($tmpArr);
		
	}
	
	$query->free();
	
	/////////////////////////////////////
	
	SPM_header("Задача " . $problem_info['id'], "Редактор");
	
	/////////////////////////////////////
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


<div class="panel panel-default" style="margin: 0; margin-bottom: 5px; border-radius: 0;">
	<div
		class="panel-heading"
		align="center"
		style="padding-top: 5px; padding-bottom: 5px; border-radius: 0;">
		<strong>Задача <?=$problem_info['id']?>. Сложность <?=$problem_info['difficulty']?>%</strong>
	</div>
	<div class="panel-body" style="padding: 0; border-radius: 0;">
		
		<form action="index.php?service=problem_send" method="post">
			
			<input type="hidden" name="problemId" value="<?=$problem_info['id']?>" />
			
			<div id="codeEditor" contenteditable="true"><?=$submissionCode?></div>
			
			<textarea name="code" class="hidden" id="code"></textarea>
			<textarea class="form-control" rows="4" name="args" id="args"
			style="margin: 0;" placeholder="Введите собственный тест для совершения отладки приложения"><?=$submissionArgs?></textarea>
			
			<!-- CODE LANGUAGE SELECT -->
			<select class="form-control" name="codeLang" id="codeLang" onchange="changeHighlight()" required>
				<option value <?=($submissionLang == "unset" ? "selected" : "")?>>Выберите компилятор</option>
				
				<?php if ($_SPM_CONF["PROG_LANGS"]["pascal"]): ?>
				<option value="1" <?=($submissionLang == "freepascal" ? "selected" : "")?>>Pascal (Free Pascal / Object Pascal / Delphi)</option>
				<?php endif; ?>
				
				<?php if ($_SPM_CONF["PROG_LANGS"]["csharp"]): ?>
				<option value="2" <?=($submissionLang == "csharp" ? "selected" : "")?>>Mono / C#</option>
				<?php endif; ?>
				
				<?php if ($_SPM_CONF["PROG_LANGS"]["cpp"]): ?>
				<option value="3" <?=($submissionLang == "cpp" ? "selected" : "")?>>C++ (GNU Compiler Collection, g++)</option>
				<?php endif; ?>
				
				<?php if ($_SPM_CONF["PROG_LANGS"]["c"]): ?>
				<option value="4" <?=($submissionLang == "c" ? "selected" : "")?>>C (GNU Compiler Collection, gcc)</option>
				<?php endif; ?>
				
				<?php if ($_SPM_CONF["PROG_LANGS"]["lua"]): ?>
				<option value="5" <?=($submissionLang == "lua" ? "selected" : "")?>>Lua</option>
				<?php endif; ?>
				
				<?php if ($_SPM_CONF["PROG_LANGS"]["python"]): ?>
				<option value="7" <?=($submissionLang == "python" ? "selected" : "")?>>Python</option>
				<?php endif; ?>
				
				<?php if ($_SPM_CONF["PROG_LANGS"]["java"]): ?>
				<option value="6" <?=($submissionLang == "java" ? "selected" : "")?>>Java</option>
				<?php endif; ?>
				
			</select>
			<!-- /CODE LANGUAGE SELECT -->
			
			<!-- CONTROL PANEL -->
			<div class="row-fluid">
				<!-- Syntax -->
				<div class="col-xs-4 col-md-4" style="padding: 0;">
					<input class="btn btn-info btn-block btn-flat" type="submit" name="syntax" value="Синтаксис" style="margin: 0;" onclick="getcode();" />
				</div>
				<!-- Debug -->
				<div class="col-xs-4 col-md-4" style="padding: 0;">
					<input class="btn btn-primary btn-block btn-flat" type="submit" name="debug" value="Отладка" style="margin: 0;" onclick="getcode();" />
				</div>
				<!-- Release -->
				<div class="col-xs-4 col-md-4" style="padding: 0;">
					<input class="btn btn-success btn-block btn-flat" type="submit" name="release" value="Отправка" style="margin: 0;" onclick="getcode();" />
				</div>
				
				<?php if (isset($submission['problemCode'])): ?>
				<!-- Last submission info -->
				<div class="col-xs-12 col-md-12" style="padding: 0;">
					<a href="index.php?service=problem_result&sid=<?=$submission['submissionId']?>" class="btn btn-default btn-block btn-flat">Информация о последней попытке</a>
				</div>
				<?php endif; ?>
				
				<?php if (permission_check($_SESSION["permissions"], PERMISSION::teacher | PERMISSION::administrator)): ?>
				
				<!-- Get author's solution -->
				<div class="col-xs-12 col-md-12" style="padding: 0;">
					<a href="index.php?service=problem&id=<?=$problem_info['id']?>&authorSolution" class="btn btn-warning btn-block btn-flat">Получить авторское решение</a>
				</div>
				
					<?php if (permission_check($_SESSION["permissions"], PERMISSION::administrator)): ?>
					<div class="col-xs-12 col-md-12" style="padding: 0;">
						<input
							type="submit"
							name="setAsAuthorSolution"
							class="btn btn-danger btn-flat btn-block"
							value="Установить авторское решение"
							onclick="getcode(); return confirm('ВНИМАНИЕ! Это действие может привести к необратимым последствиям и уничтожению предыдущего авторского решения! Вы действительно хотите его перезаписать?');"
						>
					</div>
					<?php endif; ?>
				
				<?php endif;?>
			</div>
			<!-- CONTROL PANEL -->
		</form>
		
	</div>
</div>
<div class="panel panel-default" style="margin: 0; border-radius: 0;">
	<div
		class="panel-heading"
		align="center"
		style="padding-top: 5px; padding-bottom: 5px; border-radius: 0;">
		<?=$problem_info['name']?>
	</div>
	<div class="panel-body">
		<div id="problem_info">
			<p><?=htmlspecialchars_decode($problem_info['description'])?></p>
		</div>
		<!-- I/O information -->
		<div class="row">
			<div class="col-md-6">
				<h4>INPUT</h4>
				<p><?=empty($problem_info['input']) ? "Входной поток пуст." : $problem_info['input']?></p>
			</div>
			<div class="col-md-6">
				<h4>OUTPUT</h4>
				<p><?=empty($problem_info['output'])  ? "Выходной поток пуст." : $problem_info['output']?></p>
			</div>
		</div>
		<!--I/O examples-->
		<div class="row">
			<div class="col-md-6">
				<h4>EXAMPLE INPUT</h4>
				<p><?=empty($problem_info['input_ex']) ? "Входной поток пуст." : $problem_info['input_ex']?></p>
			</div>
			<div class="col-md-6">
				<h4>EXAMPLE OUTPUT</h4>
				<p><p><?=empty($problem_info['output_ex']) ? "Выходной поток пуст." : $problem_info['output_ex']?></p></p>
			</div>
		</div>
		<!-- /I/O information -->
	</div>
</div>
<script type="text/javascript">
	function changeHighlight(){
		var editor = ace.edit("codeEditor");
		var selectedLangId = document.getElementById("codeLang").value;
		
		switch (selectedLangId) {
			case "1":
				editor.getSession().setMode("ace/mode/pascal");
				break;
			case "2":
				editor.getSession().setMode("ace/mode/csharp");
				break;
			case "3":
				editor.getSession().setMode("ace/mode/c_cpp");
				break;
			case "4":
				editor.getSession().setMode("ace/mode/c_cpp");
				break;
			case "5":
				editor.getSession().setMode("ace/mode/lua");
				break;
			case "6":
				editor.getSession().setMode("ace/mode/java");
				break;
			case "7":
				editor.getSession().setMode("ace/mode/python");
				break;
		}
	}
	
	$(document).ready(function () {
		changeHighlight();
	});
	
	var editor = ace.edit("codeEditor");
	
    //editor.setTheme("ace/theme/default");
    
	editor.getSession().on("change", function () { $('textarea[name="code"]').val(editor.getSession().getValue()); });
	
	function getcode() { document.getElementById("code").innerHTML = editor.getValue(); }
</script>
<?php unset($submission); SPM_footer(); ?>