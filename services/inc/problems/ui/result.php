<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::student);
	
	global $submission;
	
	if (!$db_problem_result = $db->query("SELECT `difficulty` FROM `spm_problems` WHERE `id` = '" . $submission['problemId'] . "' LIMIT 1;"))
		die('<strong>Произошла ошибка при попытке совершения запроса к базе данных. Пожалуйста, повторите ваш запрос позже!</strong>');
	
	$problemDifficulty = $db_problem_result->fetch_array()[0];
	$db_problem_result->free();
	unset($db_problem_result);
	
	if ($submission['hasError'] == true)
		$smile_name = "philosofy.png";
	elseif ($submission['b'] <= 0 && $submission['testType'] == "release")
		$smile_name = "lol.png";
	elseif ($submission['b'] < $problemDifficulty && $submission['testType'] == "release")
		$smile_name = "bad_thing.jpeg";
	else
		$smile_name = "cool.png";
?>
<pre style="border-radius: 0;"><?php print($submission['compiler_text']); ?></pre>

<?php
	if ($submission['errorOutput'] != null && $submission['errorOutput'] != ""){
?>
<pre style="border-radius: 0;"><?php print($submission['errorOutput']); ?></pre>
<?php
	}
?>

<div class="panel panel-default" style="border-radius: 0;">
	<div class="panel-heading">Результаты тестирования</div>
	<div class="panel-body" style="padding: 20px 5px 20px 5px;">
		
		<div class="row">
			<div class="col-md-3" align="center" style="margin-bottom: 20px;">
				<img class="img-responsive" src="<?php print(_S_MEDIA_IMG_); ?>smiles/<?php print($smile_name); ?>" alt="[SMILE]" width="70%" />
			</div>
			<div class="col-md-9" align="center">
				
				<div class="table-responsive" style="border-radius: 0;">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th width="75%">Тест</th>
								<th width="15%">Exitcode</th>
								<th width="10%">Результат</th>
							</tr>
						</thead>
						<tbody>
<?php
	if ($submission['hasError'] == true)
		$result = '-';
	else
		$result = '+';
?>
<tr>
	<td>Компиляция программы</td>
	<td>N/A</td>
	<td><?php print($result); ?></td>
</tr>
							
<?php
	switch ($submission['testType']){
		case "syntax":
?>
<tr>
	<td>Тесты отсутствуют</td>
	<td>N/A</td>
	<td>N/A</td>
</tr>
<?php
			break;
		case "debug":
?>
<tr>
	<td>Пользовательский тест</td>
	<td><?php print($submission['exitcodes']); ?></td>
	<td><?php print($submission['result']); ?></td>
</tr>
<?php
			break;
		case "release":
			
			$i = 1;
			foreach (str_split($submission['result']) as $res){
?>
<tr>
	<td>Тест #<?php print($i); ?></td>
	<td>?</td>
	<td><?php print($res); ?></td>
</tr>
<?php
				$i++;
			}
			
			break;
	}
?>
						</tbody>
					</table>
<?php
	if ($submission['testType'] == "release"){
?>
					<strong>Начислено баллов: <?php print($submission['b']); ?> из <?php print($problemDifficulty); ?> возможных.</strong>
<?php
	}
	elseif ($submission['testType'] == "debug" && $submission['output'] != null){
?>
					<!--pre style="border-radius: 0; text-align: left;"><?php print($submission['output']); ?></pre-->
					<textarea class="form-control" style="width: 100%; resize: none;" rows="5" readonly><?php print($submission['output']); ?></textarea>
<?php
	}
?>
				</div>
			</div>
		</div>

	</div>
</div>