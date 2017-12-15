<?php
	
	/////////////////////////////////////
	//         SECURITY CHECKS         //
	/////////////////////////////////////
	
	deniedOrAllowed(
		PERMISSION::student |
		PERMISSION::teacher |
		PERMISSION::administrator
	);
	
	(isset($_GET['id']) && (int)$_GET['id'] > 0)
		or die(header('location: index.php?service=error&err=input'));
	
	/////////////////////////////////////
	//    CLASSWORKS SUBSYSTEM CODE    //
	/////////////////////////////////////
	
	$classworkId = isset($_SESSION["classwork"]) ? $_SESSION["classwork"] : 0;
	
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
	//     OLYMPIADS SUBSYSTEM CODE    //
	/////////////////////////////////////
	
	$olympId = isset($_SESSION["olymp"]) ? $_SESSION["olymp"] : 0;
	
	if ($olympId > 0) {
		
		$query_str = "
			SELECT
				count(`id`)
			FROM
				`spm_olympiads_problems`
			WHERE
				`problemId` = '" . (int)$_GET['id'] . "'
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
	//   SELECT PROBLEM INFORMATION    //
	/////////////////////////////////////
	
	//Show disabled problem or not
	$checkTrueEnabled = !isset($_SESSION["classwork"]) && !isset($_SESSION["olymp"]);
	$checkTrueEnabled = $checkTrueEnabled && permission_check($_SESSION["permissions"], PERMISSION::student);
	$showDisabled = $checkTrueEnabled ? "AND `enabled` = true" : "";
	unset($checkTrueEnabled);

	$query_str = "
		SELECT
			*
		FROM
			`spm_problems`
		WHERE
			`id` = '" . (int)$_GET['id'] . "'
		" . $showDisabled . "
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
			AND
				`olympId` = '" . $olympId . "'
			)
		ORDER BY
			`submissionId` DESC
		LIMIT
			1
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($query->num_rows > 0)
	{
		$submission = $query->fetch_assoc();
		
		$submissionCode = htmlspecialchars($submission['problemCode']);
		$submissionArgs = $submission['customTest'];
		$submissionLang = $submission['codeLang'];
	}
	else
	{
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
			die('<strong>Вказана задача не має авторського рішення!</strong>');
		
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
	
	SPM_header("Задача " . $problem_info['id'], "Редактор коду");
	
	/////////////////////////////////////
?>
<script src="<?=_S_TPL_?>plugins/ace/ace.js" charset="utf-8"></script>
<script src="<?=_S_TPL_?>plugins/ace/ext-language_tools.js" charset="utf-8"></script>

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
		style="padding-top: 5px; padding-bottom: 5px; border-radius: 0;"
	>
		
		<strong>Задача <?=$problem_info['id']?>. Складність <?=$problem_info['difficulty']?>%</strong>
		
	</div>
	<div class="panel-body" style="padding: 0; border-radius: 0;">
		
		<form action="index.php?service=problem_send" method="post">
			
			<input
				type="hidden"
				name="problemId"
				value="<?=$problem_info['id']?>"
			>
			
			<div id="codeEditor"><?=$submissionCode?></div>
			
			<textarea
				name="code"
				class="hidden"
				id="code"
			></textarea>
			
			<textarea
				class="form-control"
				rows="4"
				name="args"
				id="args"
				style="margin: 0;"
				placeholder="Введіть свій тест для перевірки правильності рішення (для Debug)"
			><?=$submissionArgs?></textarea>
			
			<div class="row-fluid">
				
				<div class="col-xs-4 col-md-4" style="padding: 0;">
					
					<!-- CODE LANGUAGE SELECT -->
					<select class="form-control" name="codeLang" id="codeLang" onchange="changeHighlight()" required>
						
						<option value <?=($submissionLang == "unset" ? "selected" : "")?>>Виберіть компілятор</option>
						
						<?php foreach ($_SPM_CONF["PROG_LANGS"] as $language): ?>
						<?php if ($language['enabled']): ?>
						<option
							value="<?=$language['name']?>"
							<?=($submissionLang == $language['name'] ? "selected" : "")?>
						><?=$language['displayName']?></option>
						<?php endif; ?>
						<?php endforeach; ?>

					</select>
					<!-- /CODE LANGUAGE SELECT -->
					
				</div>
				
				<div class="col-xs-4 col-md-4" style="padding: 0;">
					
					<select class="form-control" name="sendType" required>
						
						<option value selected>Виберіть тип відправки</option>
						<option value="syntax">Перевірка синтаксису</option>
						<option value="debug">Debug-режим</option>
						<option value="release">Release-режим</option>

					</select>
					
				</div>
				
				<div class="col-xs-4 col-md-4" style="padding: 0;">
					
					<button
						class="btn btn-primary btn-block btn-flat"
						type="submit"
						name="submit"
						style="margin: 0;"
						onclick="getcode();"
					>Відправити</button>
					
				</div>
				
			</div>
			
			<!-- CONTROL PANEL -->
			<div class="row-fluid">
				
				<?php if (isset($submission['problemCode'])): ?>
				<!-- Last submission info -->
				<div class="col-xs-12 col-md-12" style="padding: 0;">
					
					<a
						href="index.php?service=problem_result&sid=<?=$submission['submissionId']?>"
						class="btn btn-default btn-block btn-flat"
					>Інформація про останню відправку</a>
					
				</div>
				<?php endif; ?>
			</div>
			<!-- CONTROL PANEL -->
		</form>
		
	</div>
</div>
<div class="panel panel-default" style="margin: 0; border-radius: 0; text-align: justify;">
	<div
		class="panel-heading"
		align="center"
		style="padding-top: 5px; padding-bottom: 5px; border-radius: 0;"
	>
		
		<?=$problem_info['name']?>

	</div>
	<div class="panel-body">

		<div id="problem_info" style="font-size: 12pt;">
			
			<p><?=htmlspecialchars_decode($problem_info['description'])?></p>

		</div>

		<!-- I/O information -->
		<div class="row" style="font-size: 12pt;">
			
			<div class="col-md-6">
				<h4>Вхідний потік</h4>
				<p><?=strlen($problem_info['input']) <= 0 ? "Вхідний потік пустий." : str_replace("\n", "<br>", $problem_info['input'])?></p>
			</div>

			<div class="col-md-6">
				<h4>Вихідний потік</h4>
				<p><?=strlen($problem_info['output']) <= 0  ? "Вихідний потік пустий." : str_replace("\n", "<br>", $problem_info['output'])?></p>
			</div>

		</div>

		<!--I/O examples-->
		<div class="row" style="text-align: left;">

			<div class="col-md-6">
				<h4>Приклад вхідного потоку</h4>
				<p><?=@strlen($problem_info['input_ex']) <= 0 ? "Вхідний потік пустий." : str_replace("\n", "<br>", $problem_info['input_ex'])?></p>
			</div>

			<div class="col-md-6">
				<h4>Приклад вихідного потоку</h4>
				<p><p><?=@strlen($problem_info['output_ex']) <= 0 ? "Вихідний потік пустий." : str_replace("\n", "<br>", $problem_info['output_ex'])?></p></p>
			</div>

		</div>
		<!-- /I/O information -->
		
		<?php if (permission_check($_SESSION['permissions'], PERMISSION::administrator)): ?>
		<div align="right">
			
			<form action="index.php?service=problems" method="post">
				
				<input type="hidden" name="id" value="<?=(int)$_GET["id"]?>">
				
				<a
					class="btn btn-flat btn-xs btn-warning"
					href="index.php?service=problem.edit&id=<?=(int)$_GET['id']?>"
				>EDIT</a>
				
				<button
					type="submit"
					name="del"
					class="btn btn-danger btn-flat btn-xs"
					onclick="return confirm('Ви дійсно хочете видалити цю задачу?');"
				>DEL</button>
				
			</form>
			
		</div>
		<?php endif; ?>
		
	</div>
</div>

<?php if (permission_check($_SESSION['permissions'], PERMISSION::teacher | PERMISSION::administrator)): ?>
<div class="panel-group" id="authorSolutionPanel" role="tablist">
	
	<div class="panel panel-default" style="border-radius: 0;">
		
		<div class="panel-heading" role="tab">
			
			<h4 class="panel-title">
				
				<a role="button" data-toggle="collapse" data-parent="#authorSolutionPanel" href="#authorSolutionSubPanel">
					Авторське рішення задачі
				</a>
				
			</h4>
			
		</div>
		
		<div id="authorSolutionSubPanel" class="panel-collapse collapse" role="tabpanel" style="border-radius: 0;">
			
			<div class="panel-body" style="padding: 0;">
				
				<style type="text/css">
					#authorSolutionCodeEditor {
						position: relative;
						top: 0;
						right: 0;
						bottom: 0;
						left: 0;
						height: 400px;
						margin: 0;
						font-size: 15px;
					}
				</style>

				
				<div id="authorSolutionCodeEditor"><?=$submissionCode?></div>
				
				<script>
					
					var AuthorEditor = ace.edit("authorSolutionCodeEditor");
					
					AuthorEditor.setOptions({
						highlightActiveLine: false
					});
					
				</script>
				
				<?php if (permission_check($_SESSION["permissions"], PERMISSION::administrator)): ?>
				<!-- CODE LANGUAGE SELECT -->
				<select class="form-control" name="codeLang" id="codeLang" onchange="changeHighlight()" required>
					
					<option value <?=($submissionLang == "unset" ? "selected" : "")?>>Виберіть компілятор</option>
					
					<?php foreach ($_SPM_CONF["PROG_LANGS"] as $language): ?>
					<?php if ($language['enabled']): ?>
					<option
						value="<?=$language['name']?>"
						<?=($submissionLang == $language['name'] ? "selected" : "")?>
					><?=$language['displayName']?></option>
					<?php endif; ?>
					<?php endforeach; ?>
					
				</select>
				<!-- /CODE LANGUAGE SELECT -->
				
				<div class="col-xs-12 col-md-12" style="padding: 0;">
					<button
						type="submit"
						name="setAsAuthorSolution"
						class="btn btn-warning btn-flat btn-block"
						onclick="getcode(); return confirm('Це діяння незворотнє! Ви впевнені?');"
					>Встановити авторське рішення</button>
				</div>
				<?php endif; ?>
				
			</div>
			
		</div>
		
	</div>
	
</div>
<?php endif; ?>

<script type="text/javascript">

	function changeHighlight(){
		
		var editor = ace.edit("codeEditor");
		var selectedLangId = document.getElementById("codeLang").value;
		
		switch (selectedLangId) {

			case "freepascal":
				editor.getSession().setMode("ace/mode/pascal");
				break;
			case "csharp":
				editor.getSession().setMode("ace/mode/csharp");
				break;
			case "cpp":
				editor.getSession().setMode("ace/mode/c_cpp");
				break;
			case "c":
				editor.getSession().setMode("ace/mode/c_cpp");
				break;
			case "lua":
				editor.getSession().setMode("ace/mode/lua");
				break;
			case "java":
				editor.getSession().setMode("ace/mode/java");
				break;
			case "python":
				editor.getSession().setMode("ace/mode/python");
				break;
			case "php":
				editor.getSession().setMode("ace/mode/php");
				break;

		}
		
		ace.require("ace/ext/language_tools");
		
		editor.setOptions({
			
			enableBasicAutocompletion: true,
			enableSnippets: true,
			enableLiveAutocompletion: true,
			
			hScrollBarAlwaysVisible: false,
			vScrollBarAlwaysVisible: false,
			
			highlightGutterLine: true,
			animatedScroll: true,
			
			showInvisibles: false,
			showPrintMargin: true,
			
			fadeFoldWidgets: true,
			showFoldWidgets: true,
			showLineNumbers: true,
			showGutter: true,
			displayIndentGuides: true,
			autoScrollEditorIntoView: true

		});
		
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