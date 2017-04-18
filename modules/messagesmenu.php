<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	global $db;
	
	if (!$db_result = $db->query("SELECT COUNT(1) AS msgCount FROM `spm_messages` WHERE (`to` = '" . $_SESSION['uid'] . "' AND `unread` = true);"))
			die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	$msg_count = $db_result->fetch_assoc();
?>
<li class="dropdown messages-menu">
	<a href="index.php?service=messages.list" class="dropdown-toggle" title="Личные сообщения (ссылка)">
		&nbsp;<i class="fa fa-comments"></i>&nbsp;
<?php
	if ($msg_count["msgCount"] > 0) {
?>
		<span class="label label-danger"><?php print($msg_count["msgCount"]); ?></span>
<?php
	}else{
?>
		<span class="label label-success">0</span>
<?php
	}
?>
	</a>
</li>
<?php
	$db_result->free();
	unset($db_result);
	unset($msg_count);
?>