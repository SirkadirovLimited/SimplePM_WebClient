<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	isset ($_GET['uid']) && (int)$_GET['uid'] > 0 or $_GET['uid'] = $_SESSION['uid'];
	
	if (!$db_result = $db->query("SELECT `submissionId`, `problemId`, `time`, `b` FROM `spm_submissions` WHERE `userId` = '" . (int)$_GET['uid'] . "' AND (`hasError` = true OR `result` like '%-%');"))
		die('<strong>Произошла ошибка при попытке запроса к базе данных. Пожалуйста, обновите страницу!</strong>');
	
	SPM_header("Отложенные задачи", "Полный список", "Отложенные");
?>
<div class="callout callout-warning">
	<h4>Информация</h4>
	<p><strong>Отложенными</strong> называются задачи, которые были не полностью или совсем не решены учащимся, хотя были попытки их решить. 
	Система ограничивает количество отложенных задач по-умолчанию 10 наименованиями, это значение может изменять администратор системы.</p>
</div>
<?php
	
	if ($db_result->num_rows > 0){
?>
<div class="table-responsive" style="margin: 0;">
			<table class="table table-hover" style="background-color: white; margin: 0;">
				<thead>
					<tr>
						<th width="10%">ID</th>
						<th width="40%">Название задачи</th>
						<th width="30%">Категория</th>
						<th width="10%">B</th>
					</tr>
				</thead>
				<tbody>
<?php
		while ($bad_problem = $db_result->fetch_assoc()){
			$db_res_problem = $db->query("SELECT `name`, `catId`, `difficulty` FROM `spm_problems` WHERE `id` = '" . $bad_problem['problemId'] . "' LIMIT 1;")
				or die('<strong>Произошла ошибка при попытке запроса к базе данных. Пожалуйста, обновите страницу!</strong>');
			@$problem_info = $db_res_problem->fetch_assoc();
			$db_res_category = $db->query("SELECT `id`, `name` FROM `spm_problems_categories` WHERE `id` = '" . @$problem_info['catId'] . "' LIMIT 1;")
				or die('<strong>Произошла ошибка при попытке запроса к базе данных. Пожалуйста, обновите страницу!</strong>');
			@$category_info = $db_res_category->fetch_assoc();
			
			if ($bad_problem['b'] == 0)
				$problem_class = "danger";
			elseif ($bad_problem['b'] < $problem_info['difficulty'])
				$problem_class = "warning";
			else
				$problem_class = "active";
?>
					<tr class="<?php print($problem_class); ?>">
						<td><?php print($bad_problem['problemId']); ?></td>
						<td><a href="index.php?service=problem&id=<?php print($bad_problem['problemId']); ?>"><?php print(@$problem_info['name']); ?></a></td>
						<td><?php print(@$category_info['name']); ?></td>
						<td><?php print($bad_problem['b']); ?></td>
					</tr>
<?php
		}
?>
				</tbody>
			</table>
		</div>
<?php
	}else{
?>
<div class="callout callout-danger">
	<h4>Список отложенных задач пуст!</h4>
	<p>Такого не может быть! Список отложенных задач пуст! Поздравляем вас с этой прекрасной новостью!</p>
</div>
<?php
	}
	
	SPM_footer();
?>