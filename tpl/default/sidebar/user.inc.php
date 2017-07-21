<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	$query_str = "
		SELECT
			`id`,
			`name`
		FROM
			`spm_pages`
		ORDER BY
			`name` ASC
		LIMIT
			0, 30
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
?>
<!--USER-->
<li><a href="index.php?service=news"><i class="fa fa-newspaper-o"></i> <span>Новини</span></a></li>
<li><a href="index.php?service=rating"><i class="fa fa-bar-chart"></i> <span>Рейтинг</span></a></li>
<?php if ($query->num_rows > 0): ?>
<li class="treeview">
	<a href="#"><i class="fa fa-file-text"></i> <span>Сторінки</span>
		<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<?php while ($page = $query->fetch_assoc()): ?>
		<li><a href="index.php?service=view&id=<?=$page['id']?>"><i class="fa fa-paperclip"></i> <?=$page['name']?></a></li>
		<?php endwhile; ?>
	</ul>
</li>
<?php endif; ?>