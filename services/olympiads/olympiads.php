<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::olymp);
	
	/*
	 * PAGINATION SCRIPT
	 */
	
	isset($_GET['page']) or $_GET['page'] = 1;
	
	(int)$_GET['page']>0 or $_GET['page']=1;
	
	if (permission_check($_SESSION["uid"], PERMISSION::administrator))
		$count_where = "1";
	else
		$count_where = "`teacherId` = '" . $_SESSION["uid"] . "'";
	
	if (!$db_result = $db->query("SELECT count(id) FROM `spm_olympiads` WHERE " . $count_where . ";"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
	$total_olympiads_number = (int)($db_result->fetch_array()[0]);
	$olympiads_per_page = $_SPM_CONF["SERVICES"]["news"]["articles_per_page"];
	$current_page = (int)$_GET['page'];
	
	$db_result->free();
	unset($db_result);
	
	if ($total_olympiads_number > 0 && $olympiads_per_page > 0)
		$total_pages = ceil($total_olympiads_number / $olympiads_per_page);
	else
		$total_pages = 1;
	
	if ($current_page > $total_pages)
		$current_page = 1;
	
	//SQL queries and formatting
	if (!$db_result = $db->query("SELECT * FROM `spm_olympiads` ORDER BY `id` DESC LIMIT " . ($current_page * $olympiads_per_page - $olympiads_per_page) . " , " . $olympiads_per_page . ";"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
	SPM_header("Олимпиадный режим", "Список олимпиад");
?>
<div class="row">
	<div class="col-md-6">
		<div class="small-box bg-blue">
			<div class="inner">
				<h3><?=$total_olympiads_number?></h3>
				<p>ВСЕГО ОЛИМПИАД</p>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3>0</h3>
				<p>УЧАЩИХСЯ ЗАДЕЙСТВОВАНО</p>
			</div>
		</div>
	</div>
</div>

<div align="right" style="margin-bottom: 10px;">
	<a href="index.php?service=olympiads.edit" class="btn btn-success btn-flat">Запланировать олимпиаду</a>
</div>

<div class="panel panel-primary" style="border-radius: 0;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Олимпиады</h3>
	</div>
	<div class="panel-body" style="padding: 0;">
<?php if ($total_olympiads_number == 0 || $db_result->num_rows === 0):?>
		<div align="center">
			<h4 style="margin: 10px;">Список олимпиад пуст!</h4>
		</div>
<?php else: ?>
		<div class="table-responsive" style="border-radius: 0; margin: 0;">
			<table class="table table-bordered table-hover" style="margin: 0;">
				<thead>
					<th>ID</th>
					<th>Название</th>
					<th width="30%">Время проведения</th>
					<th width="17%">Кол-во учасников</th>
					<th width="10%">Действия</th>
				</thead>
				<tbody>
<?php
		while ($olymp = $db_result->fetch_assoc()):
			if (!$students_count = $db->query("SELECT count(`id`) FROM `spm_olympiads_students` WHERE `olympId` = '" . $olymp['id'] . "';"))
				$students_count = 0;
			else
				$students_count = $students_count->fetch_array()[0];
?>
					<tr>
						<td><?=$olymp['id']?></td>
						<td><a href=""><?=$olymp['name']?></a></td>
						<td><b>Начинается:</b> <?=$olymp['startTime']?><br/><b>Заканчивается:</b> <?=$olymp['endTime']?></td>
						<td><?php print($students_count); ?></td>
						<td>
							<a href="index.php?service=olympiads.edit&id=<?=$olymp['id']?>" class="btn btn-warning btn-xs">EDIT</a>
							<a href="index.php?service=olympiads?del=<?=$olymp['id']?>" class="btn btn-danger btn-xs">EDIT</a>
						</td>
					</tr>
<?php unset($students_count); endwhile; ?>
				</tbody>
			</table>
		</div>
<?php endif; ?>
	</div>
</div>
<?php
	SPM_footer();
?>