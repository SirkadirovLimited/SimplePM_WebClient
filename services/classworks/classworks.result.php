<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	/////////////////////////////////////
	
	if (isset($_SESSION["classwork"]) && $_SESSION["classwork"] > 0)
		$_GET["id"] = $_SESSION["classwork"];
	elseif (isset($_GET["id"]) && (int)$_GET["id"] > 0)
		$_GET["id"] = (int)$_GET["id"];
	else
		die(header('location: index.php?service=error&err=404'));
	
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			*
		FROM
			`spm_classworks`
		WHERE
			`id` = '" . $_GET["id"] . "'
		LIMIT
			1
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($query->num_rows == 0)
		die(header('location: index.php?service=error&err=404'));
	
	
	/////////////////////////////////////
	
	$classwork = $query->fetch_assoc();
	$query->free();
	
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			`id`,
			`username`,
			`firstname`,
			`secondname`,
			`thirdname`,
			sum(`b`)
		FROM
			`spm_submissions`
		INNER JOIN
			`spm_users`
		ON
			`spm_submissions`.`userId` = `spm_users`.`id`
		
		WHERE
			`classworkId` = '" . $_GET["id"] . "'
		
		GROUP BY
			`userId`
		;
	";
	
	if (!$users_query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			count(`problemId`)
		FROM
			`spm_classworks_problems`
		WHERE
			`classworkId` = '" . $_GET["id"] . "'
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($query->num_rows == 0)
		$problems_count = 0;
	else
		$problems_count = $query->fetch_array()[0];
	
	$query->free();
	
	/////////////////////////////////////
	
	SPM_header("Урок #" . $_GET["id"], "Статистика уроку");
?>

<div class="box box-default box-solid" style="border-radius: 0; margin-bottom: 20px; overflow: hidden;">
	<div class="box-header with-border" style="border-radius: 0;">
		<h3 class="box-title">Інформація про урок</h3>
	</div>
	<div class="box-body" style="padding: 0; overflow-x: auto; padding-right: 15px;">
		
		<div class="row-fluid">
			<div class="col-md-6 col-xs-6">
				
				<dl class="dl-horizontal" style="margin: 20px 20px 20px 0px;">
					<dt>Назва уроку</dt>
					<dd><?=$classwork['name']?></dd>
					
					<dt>Опис уроку</dt>
					<dd><?=$classwork['description']?></dd>
					
					<dt>Група учнів</dt>
					<dd><?=spm_getUserGroupByID($classwork['studentsGroup'])?> (gid_<?=$classwork['studentsGroup']?>)</dd>
				</dl>
				
			</div>
			<div class="col-md-6 col-xs-6">
				
				<dl class="dl-horizontal" style="margin: 20px 20px 20px 0px;">
					<dt>Час початку</dt>
					<dd><?=$classwork['startTime']?></dd>
					
					<dt>Час кінця</dt>
					<dd><?=$classwork['endTime']?></dd>
					
					<dt>Вчитель</dt>
					<dd><a href="index.php?service=user&id=<?=$classwork['teacherId']?>"><span class="fa fa-user"></span> <?=spm_getUserFullnameByID($classwork['teacherId'])?></a></dd>
				</dl>
				
			</div>
		</div>
		
	</div>
</div>

<link rel="stylesheet" href="<?=_S_TPL_?>plugins/datatables/dataTables.bootstrap.css">
<script src="<?=_S_TPL_?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=_S_TPL_?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#studentsRatingTable').DataTable({
			"responsive": true,
			"lengthChange": false,
			"language": {
				"zeroRecords": "Ничего не найдено!",
				"info": "Страница _PAGE_ из _PAGES_",
				"infoEmpty": "Ничего не найдено!",
				"infoFiltered": "(отфильтровано из _MAX_ записей)"
			}
		});
	} );
</script>
<style>
	@media all and (max-width: 480px) {
		#scroller {
			overflow-x: scroll;
		}
	}
</style>

<div id="scroller">
	
	<table id="studentsRatingTable" class="table table-bordered table-hover datatable responsive no-wrap" style="background-color: white; padding: 0;">
		<thead>
				<th>ID</th>
				<th>Ім'я користувача</th>
				<th>Повне ім'я</th>
				<th>Задач</th>
				<th>B</th>
			</thead>
			<tbody>
				<?php while ($user = $users_query->fetch_assoc()): ?>
				<tr>
				<td>
					<?=$user['id']?>
				</td>
				<td>
					<a href="index.php?service=user&id=<?=$user['id']?>"><?=$user['username']?></a>
				</td>
				<td>
					<a href="index.php?service=user&id=<?=$user['id']?>"><?=$user['secondname']?> <?=$user['firstname']?> <?=$user['thirdname']?></a>
				</td>
				<td>
					<?php
						$query_str = "
							SELECT
								count(`submissionId`)
							FROM
								`spm_submissions`
							WHERE
								`userId` = '" . $user['id'] . "'
							AND
								`classworkId` = '" . $_GET["id"] . "'
							;
						";
						
						if (!$query = $db->query($query_str))
							header('location: index.php?service=error&err=db_error');
						
						$right_problems_count = @($query->fetch_array()[0]);
						
						@$query->free();
					?>
					<?=@(int)$right_problems_count?> / <?=@(int)$problems_count?>
				</td>
				<td>
					<?=$user['sum(`b`)']?>
				</td>
			</tr>
			<?php endwhile; ?>
			<?php $users_query->free(); ?>
		</tbody>
	</table>
	
</div>
<?php SPM_footer(); ?>