<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	/////////////////////////////////////
	
	isset($_GET['page']) or $_GET['page'] = 1;
	(int)$_GET['page'] > 0 or $_GET['page'] = 1;
	
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			count(`id`)
		FROM
			`spm_olympiads_problems`
		WHERE
			`olympId` = '" . $_SESSION["olymp"] . "'
		;
	";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/////////////////////////////////////
	
	$total_articles_number = (int)($db_result->fetch_array()[0]);
	$articles_per_page = $_SPM_CONF["SERVICES"]["problems"]["articles_per_page"];
	$current_page = (int)$_GET['page'];
	
	/////////////////////////////////////
	
	$db_result->free();
	
	/////////////////////////////////////
	
	if ($total_articles_number > 0 && $articles_per_page > 0)
		$total_pages = ceil($total_articles_number / $articles_per_page);
	else
		$total_pages = 1;
	
	/////////////////////////////////////
	
	if ($current_page > $total_pages)
		$current_page = 1;
	
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			*
		FROM
			`spm_olympiads`
		WHERE
			`id` = '" . $_SESSION["olymp"] . "'
		LIMIT
			1
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($query->num_rows == 0)
		die(header('location: index.php'));
	
	$olymp = $query->fetch_assoc();
	$query->free();
	
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			`problemId`
		FROM
			`spm_olympiads_problems`
		WHERE
			`olympId` = '" . $_SESSION["olymp"] . "'
		ORDER BY
			`id` ASC
		LIMIT
			" . ($current_page * $articles_per_page - $articles_per_page) . " , " . $articles_per_page . "
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/////////////////////////////////////
	
	function get_ball_by_submission($problemId){
		
		global $db;
		
		$query_str = "
			SELECT
				`b`
			FROM
				`spm_submissions`
			WHERE
				`userId` = '" . $_SESSION["uid"] . "'
			AND
				`problemId` = '" . $problemId . "'
			AND
				`olympId` = '" . $_SESSION["olymp"] . "'
			ORDER BY
				`submissionId` DESC
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows == 0)
			$result = 0;
		else
			$result = $query->fetch_array()[0];
		
		$query->free();
		unset($query);
		
		return (int)$result;
	}
	
	/////////////////////////////////////
	
	SPM_header("Змагання #" . $_SESSION["olymp"], "Список завдань");
?>

<div class="info-box bg-orange">
	<span class="info-box-icon">&nbsp;<i class="fa fa-thumb-tack">&nbsp;</i></span>
	
	<div class="info-box-content" style="word-wrap: break-word;">
		<h4 style="margin-bottom: 0;"><?=$olymp['name']?></h4>
		<p style="margin: 0;"><?=$olymp['description']?></p>
		<p>Початок: <i><?=$olymp['startTime']?></i> Кінець: <i><?=$olymp['endTime']?></i></p>
	</div>
</div>

<div class="box box-primary box-solid" style="border-radius: 0;">
	<div class="box-header with-border" style="border-radius: 0;">
		<h3 class="box-title">Завдання</h3>
	</div>
	<div class="box-body" style="padding: 0;">
		
		<?php if ($total_articles_number == 0 || $query->num_rows == 0): ?>
		<div align="center">
			<h1>Упс!</h1>
			<p class="lead">Завдань немає, але ви тримайтесь :)</p>
		</div>
		<?php else: ?>
		<div class="table-responsive" style="background-color: white;">
			<table class="table table-bordered table-hover" style="margin: 0;">
				<thead>
					<th width="10%">ID</th>
					<th width="80%">Назва задачі</th>
					<th width="10%">Зароблено</th>
				</thead>
				<tbody>
					<?php while ($clw_problem = $query->fetch_assoc()): ?>
					<?php
						$query_str = "
							SELECT
								`id`,
								`name`,
								`difficulty`
							FROM
								`spm_problems`
							WHERE
								`id` = '" . (int)$clw_problem['problemId'] . "'
							LIMIT
								1
							;
						";
						
						if (!$query_problem = $db->query($query_str))
							die(header('location: index.php?service=error&err=db_error'));
						
						if ($query_problem->num_rows > 0):
							$problem_info = $query_problem->fetch_assoc();
					?>
					<tr>
						<td>
							<b><?=$problem_info['id']?></b>
						</td>
						<td>
							<a href="index.php?service=problem&id=<?=$problem_info['id']?>"><?=$problem_info['name']?></a>
						</td>
						<td>
							<?=get_ball_by_submission($problem_info['id'])?> / <?=$problem_info['difficulty']?>
						</td>
					</tr>
					<?php endif; ?>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		<?php endif; ?>
		
	</div>
</div>
<?php $query->free(); ?>
<?php include(_S_MOD_ . "pagination.php"); generatePagination($total_pages, $current_page, 4, "classworks.problems"); ?>
<?php SPM_footer(); ?>