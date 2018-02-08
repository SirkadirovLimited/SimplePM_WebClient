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
	
	/*
		Різноманітні перевірки на наявність
		помилок у користувацькому рішенні
	*/
	$check_error = $submission['hasError'] == true;
	$check_error = $check_error && (
		$submission['testType'] == "debug" &&
		(
			strpos($submission['result'], '-') !== false ||
			strlen($submission['result']) <= 0
		)
	);
	$check_error = $check_error && ($submission['testType'] == "release" && $submission['b'] <= $problemDifficulty);
	
	/*
		Після отримання результатів обираємо
		потрібний смайл для відображення
	*/
	$smile_name = (!$check_error) ? "success.svg" : "error.svg";
	
?>

<!-- Compiler output string -->
<pre style="border-radius: 0;"><?=$submission['compiler_text']?></pre>

<!-- Error output section -->
<?php if ($submission['errorOutput'] != null && $submission['errorOutput'] != ""): ?>
<pre style="border-radius: 0;"><?=$submission['errorOutput']?></pre>
<?php endif; ?>
<!-- /Error output section -->

<div class="panel panel-default" style="border-radius: 0;">
	
	<div class="panel-heading">Результати тестування</div>
	<div class="panel-body" style="padding: 20px 5px 20px 5px;">
		
		<div class="row">
			
			<div class="col-md-3" align="center" style="margin-bottom: 20px;">
				<img class="img-responsive" src="<?=_S_MEDIA_IMG_?>smiles/<?=$smile_name?>" alt="[SMILE]" width="70%" />
			</div>
			
			<div class="col-md-9" align="center">
				
				<div class="table-responsive" style="border-radius: 0;">
					
					<table class="table table-bordered">
						<thead>
							<th width="75%">Тест</th>
							<th width="15%">Exitcode</th>
							<th width="10%">Результат</th>
						</thead>
						<tbody>
							<tr>
								<td>Компіляція програми</td>
								<td>N/A</td>
								<td><?=($submission['hasError'] ? '-' : '+')?></td>
							</tr>
														
							<?php
							switch ($submission['testType']):
								case "debug":
							?>
							<tr>
								<td>Користувацький тест</td>
								<td><?=$submission['exitcodes']?></td>
								<td><?=$submission['result']?></td>
							</tr>
							<?php
									break;
								case "release":
									$submission["exitcodes"] = mb_substr($submission["exitcodes"], 1, mb_strlen($submission["exitcodes"])-1);
									$exitcodes = explode("|", $submission["exitcodes"]);
									$i = 1;
									foreach (str_split($submission['result']) as $res):
							?>
							<tr>
								<td>Тест #<?=$i?></td>
								<td><?=@$exitcodes[$i-1]?></td>
								<td><?=$res?></td>
							</tr>
							<?php
										$i++;
									endforeach;
									
									break;
							endswitch;
							?>
						</tbody>
					</table>

					<!-- Additional section -->
					<?php if ($submission['testType'] == "release"): ?>

					<strong>Отримано балів: <?=$submission['b']?> з <?=$problemDifficulty?>.</strong>

					<?php elseif ($submission['testType'] == "debug"): ?>

					<pre style="width: 100%; height: 140px; text-align: left; border-radius: 0;"><?=$submission['output']?></pre>
					
					<?php endif;?>

				</div>
			</div>
		</div>

	</div>
</div>

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
		
		<div id="codeEditor" contenteditable="false"><?=$submission['problemCode']?></div>
		
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