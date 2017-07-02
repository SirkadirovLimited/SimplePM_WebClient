<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	$db_query = "
		SELECT
			count(`id`)
		FROM
			`spm_users`
		WHERE
			(now() - `lastOnline`) < " . $_SPM_CONF["BASE"]["ONLINE_TIME"] . "
		;
	";
	
	if (!$db_count = $db->query($db_query))
			die(header('location: index.php?service=error&err=db_error'));
	
	$db_query = "
		SELECT
			`id`,
			`secondname`,
			`firstname`,
			`username`
		FROM
			`spm_users`
		WHERE
			(now() - `lastOnline`) < " . $_SPM_CONF["BASE"]["ONLINE_TIME"] . "
		LIMIT
			0, 5
		;
	";
	
	if (!$db_result = $db->query($db_query))
			die(header('location: index.php?service=error&err=db_error'));
	
	$users_online_count = $db_count->fetch_array()[0];
	$db_count->free();
?>
<li class="dropdown messages-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Кто онлайн?">
		&nbsp;<i class="fa fa-user"></i>&nbsp;
		<span class="label label-warning"><?=$users_online_count?></span>
	</a>
	<ul class="dropdown-menu">
		<li class="header">Пользователи онлайн (<?=$users_online_count?>)</li>
		<li>
			<ul class="menu">
			<?php if ($db_result->num_rows === 0): ?>
				<b>Тут никого нет...Стоп! А ты кто такой?!</b>
			<?php else: ?>
				<?php while ($u_w_o_s = $db_result->fetch_assoc()): ?>
				<li>
					<a href="index.php?service=user&id=<?=$u_w_o_s["id"]?>">
						<div class="pull-left">
							<img src="index.php?service=image&uid=<?=$u_w_o_s['id']?>" class="img-circle" alt="Avatar">
						</div>
						<h4><?=$u_w_o_s["secondname"] . " " . $u_w_o_s["firstname"]?></h4>
						<p>@<?=$u_w_o_s["username"]?></p>
					</a>
				</li>
				<?php endwhile; ?>
			<?php endif; ?>
			</ul>
		</li>
		<li class="footer"><a href="index.php?service=online">Полный список</a></li>
	</ul>
</li>
<?php
	$db_result->free();
?>