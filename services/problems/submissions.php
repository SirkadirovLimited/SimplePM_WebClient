<?php
	
	/*
	 * Submissions list viewer
	 */
	
	/* Security checkers */
	isset($_GET['uid']) && $_GET['uid'] > 0 or die(header('location: index.php?service=error&err=404'));
	$_GET['uid'] = (int)$_GET['uid'];
	
	/* Modificators */
	isset($_GET['cwId']) && (int)$_GET['cwId'] > 0 or $_GET['cwId'] = 0;
	isset($_GET['olympId']) && (int)$_GET['olympId'] > 0 or $_GET['olympId'] = 0;
	
	/* Security checkers (Vol. 2) */
	$_GET['cwId'] = (int)$_GET['cwId'];
	$_GET['olympId'] = (int)$_GET['olympId'];
	
	/* Security checkers (Vol. 3) */
	
	$_logical_1 = $_SESSION['uid'] != $_GET['uid'];
	$_logical_2 = !permission_check($_SESSION['permissions'], PERMISSION::administrator | PERMISSION:: teacher);
	
	if ($_logical_1 && $_logical_2)
		die(header('location: index.php?service=error&err=403'));
	
	if (permission_check($_SESSION['permissions'], PERMISSION::teacher) && $_SESSION['uid'] != spm_getUserTeacherId($_GET['uid']))
		die(header('location: index.php?service=error&err=403'));
	
	/* SQL queries & formation */
	
	$query_str = "
		SELECT
			`submissionId`,
			`problemId`,
			`b`
		FROM
			`spm_submissions`
		WHERE
			`userId` = '" . $_GET['uid'] . "'
		AND
			`classworkId` = '" . $_GET['cwId'] . "'
		AND
			`olympId` = '" . $_GET['olympId'] . "'
		ORDER BY
			`problemId` ASC
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/* Header */
	SPM_header("Список спроб", "Інформація про всі спроби вирішити задачі");
	
?>

<style>
	
	.col-md-3
	{
		margin: 0;
		padding: 2px;
	}
	
	.col-md-6
	{
		margin: 0;
		padding: 0px;
	}
	
	.col-md-12
	{
		margin: 0;
		margin-top: 5px;
		padding: 0;
	}
	
	label
	{
		padding-left: 5px;
		margin-bottom: 1px;
	}
	
</style>

<div class="panel panel-default" style="border-radius: 0; padding: 0;">
	<div class="panel-body" style="padding-top: 0; padding-bottom: 0;">
		
		<h3 style="margin-top: 5px; margin-bottom: 5px;"><?=spm_getUserFullnameByID($_GET['uid'])?>, <?=spm_getGroupNameByUserId($_GET['uid'])?></h3>
		
	</div>
</div>

<?php if ($query->num_rows > 0): ?>

<div class="row" style="margin-left: 0px; margin-right: 0px;">

	<?php while ($submission = $query->fetch_assoc()): ?>

	<?php
		
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
		
		if (!$query_sub = $db->query($query_str))
			$problem_diff = -1;
		else
			$problem_diff = $query_sub->fetch_array()[0];
		
		if ($submission['b'] <= 0)
			$view_mode = "btn-danger";
		elseif ($submission['b'] > 0 && $submission['b'] < $problem_diff)
			$view_mode = "btn-warning";
		elseif ($submission['b'] == $problem_diff)
			$view_mode = "btn-success";
		else
			$view_mode = "btn-default";
		
		@$query_sub->free();
	?>
	
	<div class="col-md-3">
		
		<a
			href=""
			class="btn <?=$view_mode?> btn-flat btn-block"
		><?=$submission['problemId']?> (<?=$submission['b']?>/<?=$problem_diff?>)</a>
		
	</div>
	
	<?php endwhile; $query->free(); ?>

</div>

<?php else: ?>

<p class="lead">Вибачте, але за вашим запитом спроб не знайдено!</p>

<?php endif; ?>

<?php
	/* Footer */
	SPM_footer();
?>