<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	isset($_SESSION["uid"]) or die('403 ACCESS DENIED');
	
	if (isset($_GET['del']) && (int)$_GET['del']>0){
		$_GET['del'] = (int)$_GET['del'];
		include_once(_S_SERV_INC_ . "messages/msg.del.php");
	}
	
	if (isset($_GET["outbox"])){
		$add_class_inbox = "";
		$add_class_outbox = " active";
		$fto_query = "from";
		$fto1_query = "to";
		$del_fto_query = "delFrom";
	} else {
		$add_class_inbox = " active";
		$add_class_outbox = "";
		$fto_query = "to";
		$fto1_query = "from";
		$del_fto_query = "delTo";
	}
	
	$messages_query = "SELECT `id`,`from`,`to`,`unread`,`title` FROM `spm_messages` WHERE (`" . $fto_query . "` = '" . $_SESSION["uid"] . "' AND `" . $del_fto_query . "` = '0') ORDER BY date DESC;";
	
	if (!$db_get_msg_list = $db->query($messages_query))
		die('<strong>КРИТИЧЕСКАЯ ОШИБКА: Невозможно выполнить запрос к базе данных! Пожалуйста, повторите ваш запрос позже.</strong>');
	
	SPM_header("Сообщения");
	
?>
<div style="margin-bottom: 5px;">
	<div class="btn-group" role="group" style="margin: 1px;">
		<a href="index.php?service=messages.list&inbox" class="btn btn-default<?php print($add_class_inbox); ?>">Входящие</a>
		<a href="index.php?service=messages.list&outbox" class="btn btn-default<?php print($add_class_outbox); ?>">Исходящие</a>
	</div>
</div>
<?php
	
	if ($db_get_msg_list->num_rows === 0){
?>
	<a class="list-group-item">
		<h4 class="list-group-item-heading">Тут одиноко :(</h4>
		<p class="list-group-item-text">Никто не пишет, никто не звонит... вот что значит одиночество...</p>
	</a>
<?php
	} else {
		while ($msgListItem = $db_get_msg_list->fetch_assoc()){
			
			if (!$db_get_uinfo = $db->query("SELECT `firstname`,`secondname`,`thirdname`,`group` FROM `spm_users` WHERE `id` = '" . $msgListItem[$fto1_query] . "' LIMIT 1;"))
				die('<strong>КРИТИЧЕСКАЯ ОШИБКА: Невозможно выполнить запрос к базе данных! Пожалуйста, повторите ваш запрос позже.</strong>');
			
			if ($db_get_uinfo->num_rows === 0)
				$msg_sender = "Пользователь удалён";
			else {
				$msg_sender_arr = $db_get_uinfo->fetch_assoc();
				$msg_sender = $msg_sender_arr["secondname"] . " " . $msg_sender_arr["firstname"] . " " . $msg_sender_arr["thirdname"];
				$msg_sender_group = $msg_sender_arr["group"];
				
				$db_get_uinfo->free();
			}
			unset($db_get_uinfo);
			unset($msg_sender_arr);
			
			if ($msgListItem["unread"] == true)
				$spm__unread = " active";
			else
				$spm__unread = "";
			
?>
<div class="media">
	<div class="media-left">
		<a href="index.php?service=user&id=<?php print($msgListItem[$fto1_query]); ?>">
			<img class="img-circle" src="index.php?service=image&uid=<?php print($msgListItem[$fto1_query]); ?>" width="64px" height="64px" alt="Sender avatar">
		</a>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><a href="index.php?service=user&id=<?php print($msgListItem[$fto1_query]); ?>"><?php print($msg_sender); ?></a>, <?php print($msg_sender_group); ?></h4>
		<b><?php print($msgListItem["title"]); ?></b><br/>
		<a href="index.php?service=messages.send&id=<?php print($msgListItem[$fto1_query]); ?>&reply=<?php print($msgListItem["id"]); ?>">Просмотреть сообщение</a>
		/ <a href="index.php?service=messages.list&del=<?php print($msgListItem["id"]); ?>"
			onclick="return confirm('Вы действительно хотите удалить данное сообщение? Это действие отменить не возможно!')">Удалить</a><br/>
	</div>
</div>
<?php
			
			unset($spm__unread);
			unset($msg_sender);
			unset($msg_reader);
			
		}
		unset($msgListItem);
	}
	unset($db_get_msg_list);
?>

<?php
	SPM_footer();
?>