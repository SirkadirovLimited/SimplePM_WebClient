<?php
	$query_str = "
		SELECT
			`id`, 
			`secondname`,
			`firstname`,
			`username`
		FROM
			`spm_users`
		WHERE
		(
			`teacherId` = '" . $_SESSION['uid'] . "'
		OR
			`teacherId` = '" . $_SESSION['teacherId'] . "'
		OR
			`id` = '" . $_SESSION['teacherId'] . "'
		OR
			`id` = '" . $_SESSION['teacherId'] . "'
		)
		AND
			MONTH(bdate) = MONTH(NOW())
		ORDER BY
			DAY(bdate) DESC,
			lastOnline DESC
		LIMIT
			0, 30
		;
	";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
?>

<?php if ($db_result->num_rows > 0): ?>
<li class="dropdown messages-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Дні народження">
		
		&nbsp;<i class="fa fa-birthday-cake"></i>&nbsp;
		
		<span class="label label-danger"><?=$db_result->num_rows?></span>
		
	</a>
	<ul class="dropdown-menu">
		<li class="header">Дні народження</li>
		<li>
			<ul class="menu">
				<?php if ($db_result->num_rows === 0): ?>
				
				<b style='padding: 5px;'>Іменинників немає :(</b>
				
				<?php else: while ($u_w_o_s = $db_result->fetch_assoc()): ?>
				
				<li>
					<a href="index.php?service=user&id=<?=$u_w_o_s["id"]?>">
						<div class="pull-left">
							<img src="index.php?service=image&uid=<?=$u_w_o_s['id']?>" class="img-circle" alt="User #<?=$u_w_o_s["id"]?>">
						</div>
						<h4><?=$u_w_o_s["secondname"] . " " . $u_w_o_s["firstname"]?></h4>
						<p>@<?=$u_w_o_s["username"]?></p>
					</a>
				</li>
				
				<?php endwhile; endif; ?>
			</ul>
		</li>
		<li class="footer"><a>Показані останні 30 користувачів</a></li>
	</ul>
</li>
<?php endif; ?>
