<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	global $db;
	global $_SPM_CONF;
	
	if (!$db_result = $db->query("SELECT * FROM `spm_users` WHERE MONTH(bdate) = MONTH(NOW()) LIMIT 0,30"))
			die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
?>
<li class="dropdown messages-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Дни рождения в этом месяце">
		&nbsp;<i class="fa fa-birthday-cake"></i>&nbsp;
		<span class="label label-danger"><?php print($db_result->num_rows); ?></span>
	</a>
	<ul class="dropdown-menu">
		<li class="header">Дни рождения</li>
		<li>
			<ul class="menu">
<?php
	if ($db_result->num_rows === 0){
		print("<b style='padding: 5px;'>Никто не празднует день рождения :(</b>");
	}else{
		while ($u_w_o_s = $db_result->fetch_assoc()) {
?>
<li>
	<a href="index.php?service=user&id=<?php print($u_w_o_s["id"]); ?>">
		<div class="pull-left">
			<img src="index.php?service=image&uid=<?php print($u_w_o_s['id']); ?>" class="img-circle" alt="Avatar">
		</div>
		<h4><?php print($u_w_o_s["secondname"] . " " . $u_w_o_s["firstname"]); ?></h4>
		<p>@<?php print($u_w_o_s["username"]); ?></p>
	</a>
</li>
<?php
		}
	}
	unset($u_w_o_s);
	unset($db_result);
?>
			</ul>
		</li>
		<li class="footer"><a href="#">Полный список</a></li>
	</ul>
</li>