<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__view.admin__") or die('403 ACCESS DENIED');
	
	deniedOrAllowed(PERMISSION::administrator);
	
	if (!isset($_GET['page']))
		$_GET['page'] = 1;
	
	(int)$_GET['page']>0 or die('<strong>Попытка ввода SQL инъекции заблокирована.</strong>');
	
	if (!$db_result = $db->query("SELECT count(*) AS news_count FROM `spm_news`"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
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
	
	if (!$db_result = $db->query("SELECT * FROM `spm_news` ORDER BY `id` DESC LIMIT " . ($current_page * $articles_per_page - $articles_per_page) . " , " . ($current_page * $articles_per_page) . ";"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
?>
<div align="right" style="margin-bottom: 10px;">
	<a class="btn btn-primary" href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=news.admin&create">Создать новость</a>
</div>
<div class="table-responsive">
	<table class="table table-bordered table-hover" style="background-color: white;">
		<tr class="active">
			<th>ID</th>
			<th>Название новости</th>
			<th>Автор</th>
			<th>Дата публикации</th>
			<th>Действия</th>
		</tr>
<?php
	if ($total_articles_number == 0 || $db_result->num_rows === 0){
?>
		<tr>
			<td></td>
			<td><b>Тут пусто :( Создай пожалуйста новую запись, чтобы раб твой рад был, мой господин! Смилуйся надо мной!</b></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
<?php
	}else{
		while ($article = $db_result->fetch_assoc()) {
?>
		<tr>
			<td><?php print($article['id']); ?></td>
			<td><a href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=news.admin&edit=<?php print($article['id']); ?>">
					<?php print($article['title']); ?>
				</a></td>
			<td><a href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=user&id=<?php print($article['authorId']); ?>">
					Профиль автора
				</a></td>
			<td><?php print($article['date']); ?></td>
			<td><a class="btn btn-default btn-xs" href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=news.admin&edit=<?php print($article['id']); ?>">EDIT</a>
				<a class="btn btn-default btn-xs" href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=news.admin&del=<?php print($article['id']); ?>" onclick ="return confirm('Вы действительно хотите удалить эту страницу? Это действие не обратимо!');">DEL</a> </td>
		</tr>
<?php
		}
?>
<?php
		unset($db_result);
	}
?>
	</table>
</div>
<?php include(_S_MOD_ . "pagination.php"); generatePagination($total_pages, $current_page, 4); ?>