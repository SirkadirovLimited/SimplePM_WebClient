<?php
	
	/* Ready submissions count */
	$query_str = "
		SELECT
			`submissionId`,
			`problemId`
		FROM
			`spm_submissions`
		WHERE
			`userId` = '" . $_SESSION['uid'] . "'
		AND
			`classworkId` = '0'
		AND
			`olympId` = '0'
		AND
			`status` = 'ready'
		AND
			`seen` = 0
		;
	";
	
	if (!$query_submissions = $db->query($query_str))
		die();
	
	/* Waiting submissions count */
	$query_str = "
		SELECT
			count(`submissionId`)
		FROM
			`spm_submissions`
		WHERE
			`userId` = '" . $_SESSION['uid'] . "'
		AND
			`classworkId` = '0'
		AND
			`olympId` = '0'
		AND
			`status` = 'waiting'
		AND
			`seen` = 0
		;
	";
	
	if (!$query_wsubm = $db->query($query_str))
		die();
	
	$waiting_submissions = (int)$query_wsubm->fetch_array()[0];
	$query_wsubm->free();
	
	$notifications_count = $query_submissions->num_rows + $waiting_submissions;
	
?>

<?php if ($notifications_count > 0): ?>
<li class="dropdown notifications-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		&nbsp;<i class="fa fa-bell-o"></i>
		<span class="label label-info"><?=$notifications_count?></span>&nbsp;
	</a>
	<ul class="dropdown-menu">
		<li class="header">Нові сповіщення</li>
		<li>
			<ul class="menu">
				<?php while ($submission_info = $query_submissions->fetch_assoc()): ?>
				<li>
					<a href="index.php?service=problem_result&sid=<?=$submission_info['submissionId']?>">
						<i class="fa fa-check-circle text-green"></i>
						Рішення задачі <?=$submission_info['problemId']?> перевірено. Подивіться результат!
					</a>
				</li>
				<?php endwhile; $query_submissions->free(); ?>
				
				<?php if ($waiting_submissions > 0): ?>
				<li>
					<a>
						<i class="fa fa-check-circle text-orange"></i>
						<?=$waiting_submissions?> відправок очікують обробки
					</a>
				</li>
				<?php endif; ?>
			</ul>
		</li>
	</ul>
</li>
<?php endif; ?>