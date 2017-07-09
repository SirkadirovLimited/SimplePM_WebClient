<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	isset ($_GET['uid']) && (int)$_GET['uid'] > 0 or $_GET['uid'] = $_SESSION['uid'];
	
	$query_str = "
		SELECT
			`submissionId`,
			`problemId`,
			`time`,
			`b`
		FROM
			`spm_submissions`
		WHERE
			`userId` = '" . (int)$_GET['uid'] . "'
		AND
			(
				`hasError` = true
			OR
				`result` like '%-%'
			OR
				`b` = 0
			)
		ORDER BY
			`time` ASC
		;";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	SPM_header("Отложенные задачи", "Полный список", "Отложенные");
?>
<?php if ($db_result->num_rows > 0): ?>
<div class="table-responsive" style="margin: 0;">
	<table class="table table-bordered table-hover" style="background-color: white; margin: 0;">
		<thead>
			<th width="10%">ID</th>
			<th width="40%">Название задачи</th>
			<th width="30%">Дата / Время попытки</th>
			<th width="10%">SubmissionID</th>
			<th width="10%">B</th>
		</thead>
		<tbody>
			<?php while ($bad_problem = $db_result->fetch_assoc()): ?>
			<?php
				$query_str = "
					SELECT
						`name`,
						`difficulty`
					FROM
						`spm_problems`
					WHERE
						`id` = '" . $bad_problem['problemId'] . "'
					LIMIT
						1
					;
				";
				
				if (!$query_sm = $db->query($query_str))
					die(header('location: index.php?service=error&err=db_error'));
				
				$problem_info = $query_sm->fetch_assoc();
			?>
			<tr>
				<td><?=$bad_problem['problemId']?></td>
				<td><a href="index.php?service=problem&id=<?=$bad_problem['problemId']?>"><?=@$problem_info['name']?></a></td>
				<td><?=$bad_problem['time']?></td>
				<td><a href="index.php?service=problem_result&sid=<?=$bad_problem['submissionId']?>" title="Просмотреть информацию о попытке"><?=$bad_problem['submissionId']?></a></td>
				<td><?=$bad_problem['b'] . "/" . $problem_info['difficulty']?></td>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php else: ?>
<div class="callout callout-danger">
	<h4>Список отложенных задач пуст!</h4>
	<p>Такого не может быть! Список отложенных задач пуст! Поздравляем вас с этой прекрасной новостью!</p>
</div>
<?php endif; ?>
<?php SPM_footer(); ?>