<?php
	
	/////////////////////////////////////
	
	if (isset($_SESSION["olymp"]) && $_SESSION["olymp"] > 0)
		$_GET["id"] = $_SESSION["olymp"];
	elseif (isset($_GET["id"]) && (int)$_GET["id"] > 0)
		$_GET["id"] = (int)$_GET["id"];
	else
		die(header('location: index.php?service=error&err=404'));
	
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			`id`,
			`name`,
			`description`,
			`startTime`,
			`endTime`,
			`teacherId`,
			`type`,
			`testingType`
		FROM
			`spm_olympiads`
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
	
	$olymp = $query->fetch_assoc();
	$query->free();
	
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			SEC_TO_TIME(sum(TIME_TO_SEC(TIMEDIFF(`time`, '" . $olymp['startTime'] . "')))) AS penalty,
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
			`olympId` = '" . $_GET["id"] . "'
		
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
			`spm_olympiads_problems`
		WHERE
			`olympId` = '" . $_GET["id"] . "'
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
	
	$query_str = "
		SELECT
			sum(`difficulty`)
		FROM
			`spm_problems`
		WHERE
			`id` IN (
				SELECT
					`problemId`
				FROM
					`spm_olympiads_problems`
				WHERE
					`olympId` = '" . $_GET["id"] . "'
			)
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	$users_max_b = $query->fetch_array()[0];
	
	/////////////////////////////////////
	
	SPM_header("Змагання #" . $_GET["id"], "Статистика змагання");

	/////////////////////////////////////
	
?>

<div class="box box-default box-solid" style="border-radius: 0; margin-bottom: 20px; overflow: hidden;">
	<div class="box-header with-border" style="border-radius: 0;">
		<h3 class="box-title">Інформація про олімпіаду</h3>
	</div>
	<div class="box-body" style="padding: 0; overflow-x: auto; padding-right: 15px;">
		
		<div class="row-fluid" style="text-align: justify;">
			<div class="col-md-6 col-xs-6">
				
				<dl class="dl-horizontal" style="margin: 20px 20px 20px 0px;">
					<dt>Назва змагання</dt>
					<dd><?=$olymp['name']?></dd>
					
					<dt>Опис змагання</dt>
					<dd><?=$olymp['description']?></dd>
					
					<dt>Тип змагання</dt>
					<dd><?=$olymp['type']?></dd>

					<dt>Тип оцінювання</dt>
					<dd><?=$olymp['testingType']?></dd>
				</dl>
				
			</div>
			<div class="col-md-6 col-xs-6">
				
				<dl class="dl-horizontal" style="margin: 20px 20px 20px 0px;">
					<dt>Час початку</dt>
					<dd><?=$olymp['startTime']?></dd>
					
					<dt>Час кінця</dt>
					<dd><?=$olymp['endTime']?></dd>
					
					<dt title="Максимальна кількість балів">Максимальна кількість балів</dt>
					<dd><?=$users_max_b?></dd>
					
					<dt>Куратор змагання</dt>
					<dd><a href="index.php?service=user&id=<?=$olymp['teacherId']?>"><span class="fa fa-user"></span> <?=spm_getUserFullnameByID($olymp['teacherId'])?></a></dd>
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

		//Инициализируем плагин JQuery Data Tables
		var dataTable = $('#studentsRatingTable').DataTable({
			"responsive": true,
			"lengthChange": false,
			"language": {
				"zeroRecords": "Нічого не знайдено!",
				"info": "Сторінка _PAGE_ з _PAGES_",
				"infoEmpty": "Нічого не знайдено!",
				"infoFiltered": "(знайдено з _MAX_ записів)"
			}
		});

		//Указываем метод и колонку сортировки по-умолчанию
		dataTable.columns( '#b-heading' ).order( 'desc' ).draw();

	});

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

			<?php if ($_SESSION['uid'] == $olymp['teacherId']): ?>
			<th>Інформація про спроби</th>
			<?php endif; ?>

			<th>Час пенальті</th>
			<th id="b-heading">B</th>
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
					
					<a href="index.php?service=user&id=<?=$user['id']?>">
						<?=$user['secondname']?> <?=$user['firstname']?> <?=$user['thirdname']?>
					</a>
					
				</td>

				<?php if ($_SESSION['uid'] == $olymp['teacherId']): ?>
				<td>

					<a
						href="index.php?service=submissions&uid=<?=$user['id']?>&olympId=<?=$_GET['id']?>"
						target="_blank"
					>Подивитись</a>

				</td>
				<?php endif; ?>

				<td>

					<?=$user['penalty']?>

				</td>

				<td>

					<?=$user['sum(`b`)']?> / <?=$users_max_b?>

				</td>

			</tr>
			<?php endwhile; ?>

			<?php $users_query->free(); ?>

		</tbody>

	</table>
	
</div>

<?php SPM_footer(); ?>