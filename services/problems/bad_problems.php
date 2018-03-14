<?php
	
	isset ($_GET['uid']) && (int)$_GET['uid'] > 0 or $_GET['uid'] = $_SESSION['uid'];
	
	$query_str = "
		SELECT
			`spm_submissions`.`submissionId`,
			`spm_submissions`.`problemId`,
			`spm_submissions`.`time`,
			`spm_submissions`.`b`,
			`spm_problems`.`name`,
			`spm_problems`.`difficulty`
		FROM
			`spm_submissions`
		INNER JOIN
			`spm_problems`
		ON
			spm_submissions.problemId = spm_problems.id
		WHERE
			`userId` = '" . (int)$_GET['uid'] . "'
		AND
			`classworkId` = '0'
		AND
			`olympId` = '0'
		AND
			(
				`hasError` = true
			OR
				`b` = 0
			)
		ORDER BY
			`time` ASC
		;
	";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	SPM_header("Відкладені задачі", "Повний список");
?>
<?php if ($db_result->num_rows > 0): ?>
<div class="table-responsive" style="margin: 0;">
	<table class="table table-bordered table-hover" style="background-color: white; margin: 0;">
		<thead>
			<th width="10%">ID</th>
			<th width="40%">Назва задачі</th>
			<th width="30%">Дата / Час спроби</th>
			<th width="10%">Ідентифікатор</th>
			<th width="10%">B</th>
		</thead>
		<tbody>
			<?php while ($bad_problem = $db_result->fetch_assoc()): ?>
			<tr>
				<td><?=$bad_problem['problemId']?></td>
				<td><a href="index.php?service=problem&id=<?=$bad_problem['problemId']?>"><?=@$bad_problem['name']?></a></td>
				<td><?=$bad_problem['time']?></td>
				<td><a href="index.php?service=problem_result&sid=<?=$bad_problem['submissionId']?>" title="Подивитись інформацію про спробу"><?=$bad_problem['submissionId']?></a></td>
				<td><?=$bad_problem['b'] . "/" . $bad_problem['difficulty']?></td>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php else: ?>
<div class="callout callout-success">
	<h4 style="display: inline;">Список відкладених задач пустий!</h4>
</div>
<?php endif; ?>
<?php SPM_footer(); ?>