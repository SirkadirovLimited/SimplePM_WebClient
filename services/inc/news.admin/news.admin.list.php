<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__view.admin__") or die('403 ACCESS DENIED');
	
	deniedOrAllowed(PERMISSION::administrator);
	
	if (!isset($_GET['page']))
		$_GET['page'] = 1;
	
	(int)$_GET['page']>0 or die('<strong>Попытка ввода SQL инъекции заблокирована.</strong>');
	
	if (!$db_result = $db->query("SELECT count(*) AS news_count FROM `spm_news`"))
		die(header('location: index.php?service=error&err=db_error'));
	
	$total_articles_number = (int)($db_result->fetch_assoc()["news_count"]);
	$articles_per_page = 10;
	$current_page = (int)$_GET['page'];
	
	$db_result->free();
	unset($db_result);
	
	if ($total_articles_number > 0 && $articles_per_page > 0)
		$total_pages = ceil($total_articles_number / $articles_per_page);
	else
		$total_pages = 1;
	
	if ($current_page > $total_pages)
		$current_page = 1;
	
	if (!$db_result = $db->query("SELECT * FROM `spm_news` ORDER BY `id` DESC LIMIT " . ($current_page * $articles_per_page - $articles_per_page) . " , " . $articles_per_page . ";"))
		die(header('location: index.php?service=error&err=db_error'));
?>
<div align="right" style="margin-bottom: 10px;">
	<a class="btn btn-primary" href="index.php?service=news.admin&create">Создать новость</a>
</div>
<div class="table-responsive">
	<table class="table table-bordered table-hover" style="background-color: white;">
		<thead>
			<th width="10%">ID</th>
			<th width="40%">Название новости</th>
			<th width="20%">Автор</th>
			<th width="20%">Дата публикации</th>
			<th width="10%">Действия</th>
		</thead>
<?php if ($total_articles_number == 0 || $db_result->num_rows == 0): ?>
		<tr>
			<td></td>
			<td><b>Тут пусто :( Создай пожалуйста новую запись, чтобы раб твой рад был, мой господин! Смилуйся надо мной!</b></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
<?php else: ?>
		<?php while ($article = $db_result->fetch_assoc()): ?>
		<tr>
			<td><?=$article['id']?></td>
			<td><a href="index.php?service=news.admin&edit=<?=$article['id']?>">
					<?=$article['title']?>
				</a></td>
			<td><a href="index.php?service=user&id=<?=$article['authorId']?>">
					Профиль автора
				</a></td>
			<td><?=$article['date']?></td>
			<td>
				<div class="btn-group">
					<a
						class="btn btn-warning btn-sm"
						href="index.php?service=news.admin&edit=<?=$article['id']?>"
					><span class="fa fa-pencil"></span></a>
					<a
						class="btn btn-danger btn-sm"
						href="index.php?service=news.admin&del=<?=$article['id']?>"
						onclick ="return confirm('Вы действительно хотите удалить эту страницу? Это действие не обратимо!');"
					><span class="fa fa-trash"></span></a>
				</div>
			</td>
		</tr>
		<?php endwhile; ?>
<?php endif; ?>
	</table>
</div>
<?php include(_S_MOD_ . "pagination.php"); generatePagination($total_pages, $current_page, 4, "news.admin"); ?>