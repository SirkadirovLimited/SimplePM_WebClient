<?php
	
	deniedOrAllowed(PERMISSION::student | PERMISSION::teacher | PERMISSION::administrator);
	
	//Включение скрипта, по запросу удаляющего определённую задачу
	include(_S_SERV_INC_ . "admin/problem.del.php");
	//Включение скрипта, отвечающего за выборку из БД
	//и форматирование результатов выполнения запроса к ней
	include(_S_SERV_INC_ . "problems/problems.list.operator.php");
	//Управляющая панель администратора
	include(_S_VIEW_ . "problems/problems.list/adminbar.php");
	//Панель поиска и кастомной выборки списка задач из базы данных
	include(_S_VIEW_ . "problems/problems.list/problems.searchbar.php");
	
?>
<!--PROBLEMS LIST-->
<?php if ($total_articles_number == 0 || $db_result->num_rows == 0): ?>
<div align="center">
	<h3>Задач не знайдено!/h3>
	<p class="lead">За вашим запитом задач не знайдено. Перефразуйте ваш запит.</p>
</div>
<?php else: ?>
<div class="table-responsive" style="margin: 0;">
	<table class="table table-hover" style="background-color: white; margin: 0;">
		<thead>
			<tr>
				<th width="10%">
					ID&nbsp;
					<small>
						<a href="<?=generate_sort_url(1, $_SORT_BY['id'], $_SORT['asc'])?>"><i class="fa fa-caret-square-o-down"></i></a>
						<a href="<?=generate_sort_url(1, $_SORT_BY['id'], $_SORT['desc'])?>"><i class="fa fa-caret-square-o-up"></i></a>
					</small>
				</th>
				<th width="40%">
					Название задачи&nbsp;
					<small>
						<a href="<?=generate_sort_url(1, $_SORT_BY['name'], $_SORT['asc'])?>"><i class="fa fa-caret-square-o-down"></i></a>
						<a href="<?=generate_sort_url(1, $_SORT_BY['name'], $_SORT['desc'])?>"><i class="fa fa-caret-square-o-up"></i></a>
					</small>
				</th>
				<th width="30%">Категория</th>
				<th width="10%">Решаемость</th>
				<th width="10%">
					B&nbsp;
					<small>
						<a href="<?=generate_sort_url(1, $_SORT_BY["difficulty"], $_SORT['asc'])?>"><i class="fa fa-caret-square-o-down"></i></a>
						<a href="<?=generate_sort_url(1, $_SORT_BY["difficulty"], $_SORT['desc'])?>"><i class="fa fa-caret-square-o-up"></i></a>
					</small>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($problem = $db_result->fetch_assoc()): ?>
			<?php include(_S_VIEW_ . "problems/problems.list/problem.item.php"); ?>
		<?php endwhile; ?>
		</tbody>
		<thead>
			<tr>
				<th width="10%"></th>
				<th width="40%">
					Страница <?=$_GET["page"]?> из <?=$total_pages?>
				</th>
				<th width="30%"></th>
				<th width="10%"></th>
				<th width="10%"></th>
			</tr>
		</thead>
	</table>
</div>
<?php endif;?>

<?php include(_S_MOD_ . "pagination.php"); generatePagination($total_pages, $current_page, 4, "problems", "&catId=&query=" . $_GET["query"] . "&sortby=" . $_GET["sortby"] . "&sort=" . $_GET["sort"]); ?>
<?php SPM_footer(); ?>