<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	isset($_SESSION["uid"]) or die('403 ACCESS DENIED');
	isset($_GET["id"]) && ((int)$_GET["id"] > 0) or die('<strong>КРИТИЧЕСКАЯ ОШИБКА: Не указан ID получатея!</strong>');
	
	if (!$db_result_get_user = $db->query("SELECT * FROM `spm_users` WHERE `id` = '" . (int)$_GET["id"] . "' LIMIT 1;"))
		die('<strong>Произошла ошибка при попытке выполнения запроса к базе данных. Пожалуйста, посетите сайт позже!</strong>');
	
	if ($db_result_get_user->num_rows === 0)
		die('<strong>КРИТИЧЕСКАЯ ОШИБКА: ID получатея введен не верно / пользователь удалён или забанен!</strong>');
	
	$spm_sendmsg_user = $db_result_get_user->fetch_assoc();
	
	$db_result_get_user->free();
	unset($db_result_get_user);
	
	if (isset($_POST["msgSend"])){
		define("__spm.messages.send_as_post__", 1);
		include_once(_S_SERV_INC_ . "messages/messages.send.php");
	}
	
	if (isset($_GET["reply"]) && strlen($_GET["reply"]) > 0 && (int)$_GET["reply"] > 0){
		
		if (!$reply = $db->query("SELECT * FROM `spm_messages` WHERE `id` = '" . (int)$_GET["reply"] . "' AND (`from` = '" . $_SESSION["uid"] . "' OR `to` = '" . $_SESSION["uid"] . "') LIMIT 1;"))
			die('<strong>Произошла ошибка при попытке выполнения запроса к базе данных. Пожалуйста, посетите сайт позже!</strong>');
		
		if ($reply->num_rows === 0)
			die('<strong>Сообщения с таким ID несуществует или вы не имеете права просматривать его!</strong>');
		
		$reply_row = $reply->fetch_assoc();
		$reply->free();
		unset($reply);
		
		if ($reply_row["to"] == $_SESSION["uid"]){
			if (!$db->query("UPDATE `spm_messages` SET `unread` = '0' WHERE `id` = '" . (int)$reply_row["id"] . "'"))
				die('<strong>Произошла ошибка при попытке выполнения запроса к базе данных. Пожалуйста, посетите сайт позже!</strong>');
		}
		
		$_s_title = "RE:" . $reply_row["title"];
		$_s_message = " \r\n=================\r\n" . $reply_row["date"] . " \"" . $reply_row["title"] . "\"\r\n=================\r\n" . $reply_row["message"];
	} else {
		$_s_title = "";
		$_s_message = "";
	}
	
	SPM_header("Отправить сообщение");
?>
<div class="form-group">
	<label for="whoGet">Получатель</label>
	<input type="text" class="form-control" id="whoGet" value="<?php print($spm_sendmsg_user["secondname"] . " " . $spm_sendmsg_user["firstname"]); ?>, <?php print($spm_sendmsg_user["group"]); ?>" readonly>
</div>
<form action="index.php?service=messages.send&id=<?php print($spm_sendmsg_user["id"]); ?>" method="post">
	<div class="form-group">
		<label for="title">Тема сообщения</label>
		<input type="text" class="form-control" id="title" name="msg_title" minlength="1" maxlength="255" value="<?php print($_s_title); ?>">
	</div>
	<div class="form-group">
		<label for="message">Сообщение</label>
		<textarea class="form-control" id="message" name="msg_message" rows="10" minlength="2" maxlength="30000" required><?php print($_s_message); ?></textarea>
	</div>
	
	<input type="submit" class="btn btn-success btn-block" name="msgSend" value="Отправить">
	<a href="index.php?service=messages.list" class="btn btn-warning btn-block">Отменить</a>
</form>
<?php
	SPM_footer();
?>