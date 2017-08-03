<?php
	// Категория
	
	$query_str = "
		SELECT
			`name`
		FROM
			`spm_problems_categories`
		WHERE
			`id`='" . $problem['catId'] . "'
		LIMIT
			1
		;
	";
	
	if (!$db_res_cat = $db->query($query_str)):
		die(header('location: index.php?service=error&err=db_error'));
	elseif ($db_res_cat->num_rows == 0):
		$cat_name = "Усі завдання";
	else:
		$cat_name = $db_res_cat->fetch_assoc()["name"];
	endif;
	
	$db_res_cat->free();
	
	// Запросы на решаемость
	$tmp_query_full = "
		SELECT
			count(`submissionId`)
		FROM
			`spm_submissions`
		WHERE
			`problemId` = '" . $problem['id'] . "'
		;
	";
	
	$tmp_query_success = "
		SELECT
			count(`submissionId`)
		FROM
			`spm_submissions`
		WHERE
			`problemId` = '" . $problem['id'] . "'
		AND
			`b` = '" . $problem['difficulty'] . "'
		;
	";
	
	if (!$submissions_total = ($db->query($tmp_query_full))->fetch_array()[0])
		$submissions_total = 0;
	if (!$submissions_successful = $db->query($tmp_query_success)->fetch_array()[0])
		$submissions_successful = 0;
	
	// submissionInfo
	$submissionQuery = "
		SELECT
			`b`
		FROM
			`spm_submissions`
		WHERE
		(
			`userId` = '" . $_SESSION['uid'] . "'
		AND
			`problemId` = '" . $problem['id'] . "'
		)
		ORDER BY
			`submissionId` DESC
		LIMIT
			1
		;
	";
	
	if (!$submissionInfoLink = $db->query($submissionQuery))
		die(header('location: index.php?service=error&err=db_error'));
	elseif ($submissionInfoLink->num_rows == 0)
		$subm_result = "";
	else{
		$submsnB = floatval($submissionInfoLink->fetch_assoc()['b']);
		$submissionInfoLink->free();
		
		if ($submsnB <= 0)
			$subm_result = "danger";
		elseif ($submsnB == floatval($problem["difficulty"]))
			$subm_result = "success";
		else
			$subm_result = "active";
	}
	
?>
<tr class="<?=$subm_result?>">
	<td>
		<?php include(_S_VIEW_ . "problems/problems.list/problem.admin.php"); ?>
	</td>
	<td>
		<a href="index.php?service=problem&id=<?=$problem["id"]?>"><?=$problem["name"]?></a>
	</td>
	<td>
		<?=$cat_name?>
	</td>
	<td title=" (<?=@($submissions_successful/$submissions_total)*100?>%)">
		<?=$submissions_successful?> / <?=$submissions_total?>
	</td>
	<td>
		<?=$problem["difficulty"]?>
	</td>
</tr>