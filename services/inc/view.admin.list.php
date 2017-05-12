<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__view.admin__") or die('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	if (!$db_result = $db->query("SELECT * FROM `spm_pages`"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
?>
<div align="right" style="margin-bottom: 10px;">
	<a class="btn btn-primary" href="index.php?service=view.admin&create">Создать страницу</a>
</div>
<div class="table-responsive">
	<table class="table table-bordered table-hover" style="background-color: white;">
		<tr class="active">
			<th width="10%">ID</th>
			<th width="80%">Название страницы</th>
			<th width="10%">Действия</th>
		</tr>
<?php if ($db_result->num_rows === 0): ?>
		<tr>
			<td></td>
			<td><b>Тут пусто :( Создай пожалуйста новую страницу, чтобы раб твой рад был, мой господин! Смилуйся надо мной!</b></td>
			<td></td>
		</tr>
<?php else: ?>
		<?php while ($pages = $db_result->fetch_assoc()): ?>
		<tr>
			<td><?=$pages['id']?></td>
			<td><a href="index.php?service=view&id=<?=$pages['id']?>">
					<?=$pages['name']?>
				</a></td>
			<td><a class="btn btn-warning btn-xs" href="index.php?service=view.admin&edit=<?=$pages['id']?>">EDIT</a>
				<a class="btn btn-danger btn-xs" href="index.php?service=view.admin&del=<?=$pages['id']?>" onclick ="return confirm('Вы действительно хотите удалить эту страницу? Это действие не обратимо!');">DEL</a> </td>
		</tr>
		<?php endwhile; ?>
	</table>
</div>
<?php endif; ?>