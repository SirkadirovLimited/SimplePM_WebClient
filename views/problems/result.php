<?php
	
	global $submission;
	
	/*
		Дізнаємося, скільки ж балів дається
		за правильне рішення задачі
	*/
	
	$query_str = "
		SELECT
			`difficulty`
		FROM
			`spm_problems`
		WHERE
			`id` = '" . $submission['problemId'] . "'
		LIMIT
			1
		;
	";
	
	if (!$db_problem_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	$problemDifficulty = $db_problem_result->fetch_array()[0];
	$db_problem_result->free();
	
	
?>

<div class="panel panel-default" style="border-radius: 0;">
	
	<div class="panel-heading">Вихідний потік компілятора</div>
	
	<div class="panel-body" style="padding: 0;">
		
		<pre style="border-radius: 0; margin: 0;"><?=$submission['compiler_text']?></pre>
		
	</div>
	
</div>

<!-- Error output section -->
<?php if ($submission['errorOutput'] != null && $submission['errorOutput'] != ""): ?>
<div class="panel panel-default" style="border-radius: 0;">
	
	<div class="panel-heading">Стандартний потік помилок</div>
	
	<div class="panel-body" style="padding: 0;">
		
		<pre style="border-radius: 0; margin: 0;"><?=$submission['errorOutput']?></pre>
		
	</div>
	
</div>
<?php endif; ?>
<!-- /Error output section -->

<div class="panel panel-default" style="border-radius: 0;">
	
	<div class="panel-heading">Результати тестування</div>
	<div class="panel-body" style="padding: 0;">
		
		<div class="table-responsive" style="border-radius: 0;">
			
			<table class="table table-bordered table-hover" style="margin: 0;">
				<thead>
					<th>Test</th>
					<th>Memory (bytes)</th>
					<th>Processor time (ms)</th>
					<th>Exit code</th>
					<th>Result</th>
				</thead>
				
				<tbody>
					
					<tr class="<?=($submission['hasError'] ? "danger" : "success")?>">
						<td>Компіляція програми</td>
						<td>N/A</td>
						<td>N/A</td>
						<td>N/A</td>
						<td><?=($submission['hasError'] ? '-' : '+')?></td>
					</tr>
					
					<?php
					switch ($submission['testType']):
						case "debug":
					?>
					<tr class="<?=(str_replace("|", "", $submission["tests_result"]) == '+') ? "success" : ((str_replace("|", "", $submission["tests_result"]) == '-') ? "warning" : "danger")?>">
						<td>Користувацький тест</td>
						<td><?=str_replace("|", "", $submission['usedMemory'])?></td>
						<td><?=str_replace("|", "", $submission['usedProcTime'])?></td>
						<td><?=str_replace("|", "", $submission['exitcodes'])?></td>
						<td><?=str_replace("|", "", $submission['tests_result'])?></td>
					</tr>
					<?php
							break;
						
						case "release":
						
							$exitcodes = explode(
									"|",
									$submission["exitcodes"]
							);
							
							$usedMemory = explode(
									"|",
									$submission["usedMemory"]
							);
							
							$usedProcTime = explode(
									"|",
									$submission["usedProcTime"]
							);
							
							$tests_result = explode(
									"|",
									substr_replace(
											$submission["tests_result"],
											"|",
											strrpos(
													$submission["tests_result"],
													"|"
											),
											1
									)
							);
							
							$i = 1;
							
							for ($i = 0; $i < (count($tests_result) - 1); $i++):
							?>
							<tr class="<?=($tests_result[$i] == '+') ? "success" : (($tests_result[$i] == '-') ? "warning" : "danger")?>">
								<td>Тест #<?=$i + 1?></td>
								<td><?=@$usedMemory[$i]?></td>
								<td><?=@$usedProcTime[$i]?></td>
								<td><?=@$exitcodes[$i]?></td>
								<td><?=@$tests_result[$i]?></td>
							</tr>
							
							<?php endfor; break; endswitch; ?>
							
				</tbody>
				
			</table>
			
			<!-- Additional section -->
			<?php if ($submission['testType'] == "release"): ?>
			
			<pre style="border-radius: 0; margin: 0; text-align: center;"><strong>Отримано балів: <?=$submission['b']?> з <?=$problemDifficulty?>.</strong></pre>
			
			<?php endif; ?>
			
		</div>
		
	</div>
	
</div>

<?php if ($submission['testType'] == "debug"): ?>
<div class="panel panel-default" style="border-radius: 0;">
	
	<div class="panel-heading">Вихідний потік вашого рішення</div>
	
	<div class="panel-body" style="padding: 0;">
		
		<pre style="width: 100%; height: 140px; text-align: left; border-radius: 0; margin: 0;"><?=$submission['output']?></pre>
		
	</div>
	
</div>
<?php endif; ?>

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
</style>

<?php if ($submission['classworkId'] > 0 || $submission['olympId'] > 0): ?>
<div class="panel panel-default" style="border-radius: 0;">
	
	<div class="panel-heading">Додаткова інформація</div>
	
	<div class="panel-body" style="padding: 0;">
		
		<div class="table-responsive" style="border-radius: 0;">
			
			<table class="table" style="margin: 0;">
				
				<thead>
					<th>Параметр</th>
					<th>Значення</th>
				</thead>
				
				<tbody>
					<tr>
						<td>Відправник</td>
						<td><?=$submission['userId']?></td>
					</tr>
					<tr>
						<td>Дата та час відправки</td>
						<td><?=$submission['time']?></td>
					</tr>
					<?php if ($submission['olympId'] > 0): ?>
					<tr>
						<td>Штрафний час <a title="Час, що сплинув від початку змагання до моменту відправки рішення.">(?)</a></td>
						<td></td>
					</tr>
				<?php endif; ?>
				</tbody>
				
			</table>
			
		</div>
		
	</div>
	
</div>
<?php endif; ?>

<?php if (isset($_GET['showcode'])): ?>
<div class="panel panel-default" style="border-radius: 0;">
	<div class="panel-heading">Вихідний код кристувацького рішення задачі</div>
	<div class="panel-body" style="padding: 0;">
		
		<div id="codeEditor" contenteditable="false"><?=htmlspecialchars($submission['problemCode'])?></div>
		
	</div>
</div>

<script src="<?=_S_TPL_?>plugins/ace/ace.js" charset="utf-8"></script>
<script>
	
	var editor = ace.edit("codeEditor");
	
	editor.setTheme("ace/theme/github");
	
	editor.setOptions({
		readOnly: true,
		highlightActiveLine: false
	});
	
</script>
<?php endif; ?>

<?php
	
	/*
		Помічаємо результати тестування як
		вже прочитані користувачем
	*/
	if ($_SESSION['uid'] == $submission['userId'] && !$submission['seen']):
		
		$query_str = "
			UPDATE
				`spm_submissions`
			SET
				`seen` = true
			WHERE
				`submissionId` = '" . $submission['submissionId'] . "'
			LIMIT
				1
			;
		";
		
		if (!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
	endif;
	
?>