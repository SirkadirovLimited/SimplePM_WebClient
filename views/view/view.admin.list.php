<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__view.admin__") or die('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	$query_str = "
		SELECT
			`id`,
			`name`
		FROM
			`spm_pages`
		;
	";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
?>
<div align="right" style="margin-bottom: 10px;">
	<a class="btn btn-primary" href="index.php?service=view.admin&create">Створити сторінку</a>
</div>
<div class="table-responsive">
	<table class="table table-bordered table-hover" style="background-color: white;">
		<thead>
			<th width="10%">ID</th>
			<th width="80%">Назва сторінки</th>
			<th width="10%">Дії</th>
		</thead>
<?php if ($db_result->num_rows === 0): ?>
		<tr>
			<td></td>
			<td><b>На сайті ще немає ні одної сторінки!</b></td>
			<td></td>
		</tr>
<?php else: ?>
		<?php while ($pages = $db_result->fetch_assoc()): ?>
		<tr>
			<td><?=$pages['id']?></td>
			<td><a href="index.php?service=view&id=<?=$pages['id']?>">
					<?=$pages['name']?>
				</a></td>
			<td>
				<div class="btn-group">
					<a
						class="btn btn-warning btn-sm"
						href="index.php?service=view.admin&edit=<?=$pages['id']?>"
					><span class="fa fa-pencil"></span></a>
					<a
						class="btn btn-danger btn-sm"
						href="index.php?service=view.admin&del=<?=$pages['id']?>"
						onclick = "return confirm('Ви дійсно хочете видалити цю сторінку?');"
					><span class="fa fa-trash"></span></a>
				</div>
			</td>
		</tr>
		<?php endwhile; ?>
	</table>
</div>
<?php endif; ?>