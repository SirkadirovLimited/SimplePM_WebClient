<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator | PERMISSION::olymp);
	
	if (isset($_POST['del']))
		include(_S_SERV_INC_ . "olympiads/olympiads.del.php");
	
	isset($_GET['page']) or $_GET['page'] = 1;
	
	(int)$_GET['page']>0 or $_GET['page']=1;
	
	$query_str = "
		SELECT
			count(id)
		FROM
			`spm_olympiads`
		WHERE
			`teacherId` = '" . $_SESSION["uid"] . "'
		;
	";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	$total_articles_number = (int)($db_result->fetch_array()[0]);
	$articles_per_page = $_SPM_CONF["SERVICES"]["news"]["articles_per_page"];
	$current_page = (int)$_GET['page'];
	
	$db_result->free();
	unset($db_result);
	
	if ($total_articles_number > 0 && $articles_per_page > 0)
		$total_pages = ceil($total_articles_number / $articles_per_page);
	else
		$total_pages = 1;
	
	if ($current_page > $total_pages)
		$current_page = 1;
	
	$query_str = "
		SELECT
			`id`,
			`name`,
			`startTime`,
			`endTime`,
			`type`
		FROM
			`spm_olympiads`
		WHERE
			`teacherId` = '" . $_SESSION["uid"] . "'
		ORDER BY
			`id` DESC
		LIMIT
			" . ($current_page * $articles_per_page - $articles_per_page) . " , " . $articles_per_page . "
		;
	";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	SPM_header("Підсистема змагань", "Список олімпіад");
?>

<div align="right" style="margin-bottom: 10px;">
	<a href="index.php?service=olympiads.edit" class="btn btn-success btn-flat">Створити олімпіаду</a>
</div>

<div class="panel panel-primary" style="border-radius: 0;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Олімпіади</h3>
	</div>
	<div class="panel-body" style="padding: 0;">
		<?php if ($total_articles_number == 0 || $db_result->num_rows === 0): ?>
		<div align="center">
			<h1>Упс!</h1>
			<p class="lead">Ні одної олімпіади не знайдено!</p>
		</div>
		<?php else: ?>
		<div class="table-responsive" style="background-color: white;">
			<table class="table table-bordered table-hover" style="margin: 0;">
				<thead>
					<th width="10%">ID</th>
					<th width="29%">Назва</th>
					<th width="15%">Час початку</th>
					<th width="15%">Час кінця</th>
					<th width="11%">Дії</th>
				</thead>
				<tbody>
					<?php while ($olymp = $db_result->fetch_assoc()): ?>
					<?php
						if ($olymp['endTime'] < date("Y-m-d H:i:s"))
							$tr_add_class = "active";
						elseif ($olymp['startTime'] > date("Y-m-d H:i:s"))
							$tr_add_class = "";
						else
							$tr_add_class = "success";
					?>
					<tr class="<?=$tr_add_class?>">
						<td><?=$olymp['id']?></td>
						<td><?=$olymp['name']?></td>
						<td><?=$olymp['startTime']?></td>
						<td><?=$olymp['endTime']?></td>
						<td>
							<form method="post" style="margin: 0;">
								<input type="hidden" name="id" value="<?=$olymp['id']?>">
								<a href="index.php?service=olympiads.result&id=<?=$olymp['id']?>" class="btn btn-primary btn-xs">STAT</a>
								<a href="index.php?service=olympiads.edit&id=<?=$olymp['id']?>" class="btn btn-warning btn-xs">EDIT</a>
								<button type="submit" name="del" class="btn btn-danger btn-xs">DEL</button>
							</form>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php
	SPM_footer();
?>