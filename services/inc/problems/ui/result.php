<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::student);
	
	global $submission;
	
	if (!$db_problem_result = $db->query("SELECT `difficulty` FROM `spm_problems` WHERE `id` = '" . $submission['problemId'] . "' LIMIT 1;"))
		die('<strong>Произошла ошибка при попытке совершения запроса к базе данных. Пожалуйста, повторите ваш запрос позже!</strong>');
	
	$problemDifficulty = $db_problem_result->fetch_array()[0];
	$db_problem_result->free();
	
	if ($submission['hasError'] == true)
		$smile_name = "philosofy.png";
	elseif ($submission['b'] <= 0 && $submission['testType'] == "release")
		$smile_name = "lol.png";
	elseif ($submission['b'] < $problemDifficulty && $submission['testType'] == "release")
		$smile_name = "bad_thing.jpeg";
	else
		$smile_name = "cool.png";
	
	$result = ($submission['hasError'] ? '-' : '+');
?>
<pre style="border-radius: 0;"><?=$submission['compiler_text']?></pre>

<?php if ($submission['errorOutput'] != null && $submission['errorOutput'] != ""): ?>
<pre style="border-radius: 0;"><?=$submission['errorOutput']?></pre>
<?php endif; ?>

<div class="panel panel-default" style="border-radius: 0;">
	<div class="panel-heading">Результаты тестирования</div>
	<div class="panel-body" style="padding: 20px 5px 20px 5px;">
		
		<div class="row">
			<div class="col-md-3" align="center" style="margin-bottom: 20px;">
				<img class="img-responsive" src="<?=_S_MEDIA_IMG_?>smiles/<?=$smile_name?>" alt="[SMILE]" width="70%" />
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
							<tr>
								<td>Компиляция программы</td>
								<td>N/A</td>
								<td><?=$result?></td>
							</tr>
														
							<?php
							switch ($submission['testType']):
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
					<?php if ($submission['testType'] == "release"): ?>
					<strong>Начислено баллов: <?=$submission['b']?> из <?=$problemDifficulty?> возможных.</strong>
					<?php elseif ($submission['testType'] == "debug" && $submission['output'] != null): ?>
					<textarea class="form-control" style="width: 100%; resize: none;" rows="5" readonly><?=str_replace("\n", "<br/>", $submission['output'])?></textarea>
					<?php endif;?>
				</div>
			</div>
		</div>

	</div>
</div>

<?php if (!$submission['seen']): ?>
<script>
	Push.close('unseenResult');
	Push.create('<?=$_SPM_CONF["BASE"]["SITE_NAME"]?>', {
		body: 'Получен результат проверки вашего решения для задачи <?=$submission['problemId']?>! Просмотрите его сейчас!',
		icon: {
			x16: '<?=_S_MEDIA_IMG_?>smiles/<?=$smile_name?>',
			x32: '<?=_S_MEDIA_IMG_?>smiles/<?=$smile_name?>'
		},
		tag: 'unseenResult',
		timeout: 4000
	});
</script>
<?php
	if (!$db->query("UPDATE `spm_submissions` SET `seen` = true WHERE `submissionId` = '" . $submission['submissionId'] . "' LIMIT 1;"))
		die('<strong>Произошла ошибка при попытке подключения к базе данных! Пожалуйста, обновите страницу!</strong>');
endif;
?>