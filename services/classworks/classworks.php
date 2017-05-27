<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::teacher);
	
	isset($_GET['page']) or $_GET['page'] = 1;
	
	(int)$_GET['page']>0 or $_GET['page']=1;
	
	if (!$db_result = $db->query("SELECT count(id) FROM `spm_classworks` WHERE `teacherId` = '" . $_SESSION["uid"] . "';"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
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
	
	if (!$db_result = $db->query("SELECT * FROM `spm_classworks` WHERE `teacherId` = '" . $_SESSION["uid"] . "' ORDER BY `id` DESC LIMIT " . ($current_page * $articles_per_page - $articles_per_page) . " , " . $articles_per_page . ";"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
	SPM_header("Подсистема уроков", "Список уроков");
?>

<div align="right" style="margin-bottom: 10px;">
	<a href="index.php?service=classworks.edit" class="btn btn-success btn-flat">Создать урок</a>
</div>

<div class="panel panel-primary" style="border-radius: 0;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Уроки</h3>
	</div>
	<div class="panel-body" style="padding: 0;">
		<?php if ($total_articles_number == 0 || $db_result->num_rows === 0): ?>
		<div align="center">
			<h1>Упс!</h1>
			<p class="lead">Вы ещё не создали ни один урок. Для создания урока воспользуйтесь кнопкой "Создать урок", которая расположена выше.</p>
		</div>
		<?php else: ?>
		<div class="table-responsive" style="background-color: white;">
			<table class="table table-bordered table-hover" style="margin: 0;">
				<thead>
					<th width="10%">ID</th>
					<th width="39%">Наименование</th>
					<th width="15%">Время начала</th>
					<th width="15%">Время конца</th>
					<th width="11%">Действия</th>
				</thead>
				<tbody>
					<?php while ($classwork = $db_result->fetch_assoc()): ?>
					<tr>
						<td><?=$classwork['id']?></td>
						<td><?=$classwork['name']?></td>
						<td><?=$classwork['startTime']?></td>
						<td><?=$classwork['endTime']?></td>
						<td>
							<form action="" method="post" style="margin: 0;">
								<input type="hidden" name="id" value="<?=$classwork['id']?>">
								<a href="index.php?service=classworks.result&id=<?=$classwork['id']?>" class="btn btn-primary btn-xs">STAT</a>
								<a href="index.php?service=classworks.edit&id=<?=$classwork['id']?>" class="btn btn-warning btn-xs">EDIT</a>
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