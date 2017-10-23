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
			`classworkId`,
			`olympId`,
			`time`,
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

<script>
	$(function () {
  		$('[data-toggle="popover"]').popover({
  			"placement": "auto",
  			"html": true,
  			"trigger": "focus",
  			"container": 'body'
  		});
	});
</script>

<style>
	
	label
	{
		padding-left: 5px;
		margin-bottom: 1px;
	}

	.submissionInfoBox
	{
		display: inline-block;
		margin: 2px;
	}
	
</style>

<a href="index.php?service=user&id=<?=$_GET['uid']?>" title="Перейти на сторінку користувача">
	<div class="panel panel-default" style="border-radius: 0; padding: 0;">
		<div class="panel-body" style="padding: 0;">
			
			<h3 style="margin: 5px;"><?=spm_getUserFullnameByID($_GET['uid'])?>, <?=spm_getGroupNameByUserId($_GET['uid'])?></h3>
			
		</div>
	</div>
</a>

<?php if ($query->num_rows > 0): ?>


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

<div class="submissionInfoBox">
	
	<div class="btn-group" role="group">
		
		<a
			href="index.php?service=problem_result&sid=<?=$submission['submissionId']?>&showcode"
			class="btn <?=$view_mode?> btn-flat"
		><?=$submission['problemId']?> (<?=$submission['b']?>/<?=$problem_diff?>)</a>
		
		<a
			tabindex="0"
			role="button"
			data-trigger="focus"
			class="btn <?=$view_mode?> btn-flat"
			data-toggle="popover"
			title="Інформація про спробу"
			data-content="
				<b>Номер запиту:</b> <?=$submission['submissionId']?><br>
				<b>Дата та час відправки:</b> <?=$submission['time']?><br>
				<?php if ($submission['classworkId'] > 0): ?>
				<b>Урок:</b> <a href='index.php?service=classworks.result&id=<?=$submission['classworkId']?>'>Перейти на сторінку статистики</a><br>
				<?php endif; ?>
				<?php if ($submission['olympId'] > 0): ?>
				<b>Змагання:</b> <a href='index.php?service=olympiads.result&id=<?=$submission['olympId']?>'>Перейти на сторінку статистики</a><br>
				<?php endif; ?>
			"
		>&nbsp;<i class="fa fa-info-circle"></i>&nbsp;</a>

	</div>

</div>
	
<?php endwhile; $query->free(); ?>

<?php else: ?>

<p class="lead">Вибачте, але за вашим запитом спроб не знайдено!</p>

<?php endif; ?>

<?php
	/* Footer */
	SPM_footer();
?>