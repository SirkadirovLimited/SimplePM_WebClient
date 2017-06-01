<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	/////////////////////////////////////
	//         SAFETY CHECKER          //
	/////////////////////////////////////
	
	isset($_GET["uid"]) or $_GET["uid"] = 0;
	$_GET["uid"] = (int)$_GET["uid"];
	
	if (!spm_checkIfUserExists($_GET["uid"]))
		$_GET["uid"] = 0;
	
	/////////////////////////////////////
	//         MESSAGE SENDER          //
	/////////////////////////////////////
	
	elseif (isset($_POST["sendMsg"])){
		
		isset($_POST["message"]) or exit(header('location: ' . $_SERVER["REQUEST_URI"]));
		
		$_POST["message"] = htmlspecialchars(strip_tags(trim($_POST["message"])));
		
		strlen($_POST["message"]) > 0 or exit(header('location: ' . $_SERVER["REQUEST_URI"]));
		
		$query_str = "
			INSERT INTO
				`spm_messages`
			SET
				`from` = '" . $_SESSION["uid"] . "',
				`to` = '" . $_GET["uid"] . "',
				`date` = now(),
				`unread` = true,
				`message` = '" . $_POST["message"] . "'
			;
		";
		
		@$db->query($query_str);
		
		exit(header('location: ' . $_SERVER["REQUEST_URI"]));
	}
	
	/////////////////////////////////////
	//  MESSAGES FROM SELECTED DIALOG  //
	/////////////////////////////////////
	
	if ($_GET["uid"] > 0){
		
		$query_str = "
			SELECT
				*
			FROM
				`spm_messages`
			WHERE
				(
					`from` = '" . $_SESSION["uid"] . "'
				AND
					`to` = '" . $_GET["uid"] . "'
				)
			OR
				(
					`from` = '" . $_GET["uid"] . "'
				AND
					`to` = '" . $_SESSION["uid"] . "'
				)
			ORDER BY
				`date` ASC
			LIMIT
				0, " . $_SPM_CONF["SERVICES"]["messages"]["max_messages_to_show"] . "
			;
		";
		
		if (!$query_messages = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		/* SET ALL MESSAGES OF THE CURRENT DIALOG AS SAWN */
		
		$query_str = "
			UPDATE
				`spm_messages`
			SET
				`unread` = false
			WHERE
				`from` = '" . $_GET["uid"] . "'
			AND
				`to` = '" . $_SESSION["uid"] . "'
			;
		";
		
		if (!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
	}
	
	/////////////////////////////////////
	//          CONTACTS LIST          //
	/////////////////////////////////////
	
	$query_str = "
		SELECT DISTINCT LEAST(`from`, `to`),
		GREATEST(`from`, `to`)
		FROM
			`spm_messages`
		WHERE
			`from` = '" . $_SESSION["uid"] . "'
		OR
			`to` = '" . $_SESSION["uid"] . "'
		;
	";
	
	if (!$query_contacts = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/////////////////////////////////////
	
	SPM_header("Диалоги", "Старший брат следит за тобой!");
?>
<div class="box box-primary box-solid direct-chat" style="margin-bottom: 0;">
	<div class="box-header with-border">
		<h3 class="box-title"><?=spm_getUserFullnameByID($_GET["uid"])?></h3>
		<div class="box-tools pull-right">
			<?php if ($_GET["uid"] > 0): ?>
			<a
				href="index.php?service=user&id=<?=$_GET["uid"]?>"
				class="btn btn-box-tool"
				target="popup"
				onclick="
					window.open(
						'index.php?service=user&id=<?=$_GET["uid"]?>',
						'popup',
						'width=600,height=600,menubar=no,location=no,toolbar=no'
					);
					return false;
				"
				data-toggle="tooltip"
				title="Просмотреть профиль собеседника"
			>
				<i class="fa fa-user"></i> <span class="hidden-xs">Профиль собеседника</span>
			</a>
			<?php endif; ?>
			<button
				class="btn btn-box-tool"
				data-toggle="tooltip"
				data-widget="chat-pane-toggle"
				title="Меню выбора собеседника"
			>
				<i class="fa fa-comments"></i> <span class="hidden-xs">Выбор собеседника</span>
			</button>
		</div>
	</div>
	<div class="box-body">
		<div class="direct-chat-messages" style="height: 70vh;">
			<?php if ($_GET["uid"] <= 0): ?>
			
			<div class="direct-chat-msg">
				<div class="direct-chat-info clearfix">
				</div>
				<img class="direct-chat-img" src="index.php?service=image&uid=0" alt="Аватар">
				<div class="direct-chat-text">
					Приветствуем вас в разделе "Диалоги"!<br/>
					Тут вы можете общаться на различные темы, но запомните: тут запрещено делиться исходными кодами программ!
				</div>
			</div>
			<div class="direct-chat-msg">
				<div class="direct-chat-info clearfix">
				</div>
				<img class="direct-chat-img" src="index.php?service=image&uid=0" alt="Аватар">
				<div class="direct-chat-text">
					Для выбора собеседника используйте кнопку 
					" <i><i class="fa fa-comments"></i> Выбор собеседника</i> ", которая расположена в меню выше.
				</div>
			</div>
			<div class="direct-chat-msg">
				<div class="direct-chat-info clearfix">
				</div>
				<img class="direct-chat-img" src="index.php?service=image&uid=0" alt="Аватар">
				<div class="direct-chat-text">
					Для того, чтобы начать новый диалог, выполните эти несложные действия:<br>
					<ol style="margin-top: 5px; margin-bottom: 5px;">
						<li>Посетите профиль интересующего вас пользователя</li>
						<li>В меню действий выберите пункт "Написать сообщение"</li>
						<li>Общайтесь с удовольствием!</li>
					</ol>
					Спасибо за внимание!
				</div>
			</div>
			
			<?php elseif ($query_messages->num_rows == 0): ?>
			
			<div class="direct-chat-msg">
				<div class="direct-chat-info clearfix">
				</div>
				<img class="direct-chat-img" src="index.php?service=image&uid=0" alt="Аватар">
				<div class="direct-chat-text">
					Список сообщений пуст. а жаль...
				</div>
			</div>
			<div class="direct-chat-msg">
				<div class="direct-chat-info clearfix">
				</div>
				<img class="direct-chat-img" src="index.php?service=image&uid=0" alt="Аватар">
				<div class="direct-chat-text">
					Для создания нового сообщения воспользуйтесь формой, расположенной ниже.
				</div>
			</div>
			
			<?php elseif ($query_messages->num_rows > 0): ?>
			<?php while ($message = $query_messages->fetch_assoc()): ?>
			
			<?php if ($message['to'] == $_SESSION["uid"]): ?>
			<div class="direct-chat-msg">
				<div class="direct-chat-info clearfix">
					<span class="direct-chat-name pull-left"></span>
					<span class="direct-chat-timestamp pull-right"><?=$message['date']?></span>
				</div>
				<img class="direct-chat-img" src="index.php?service=image&uid=<?=$message['from']?>" alt="Аватар">
				<div class="direct-chat-text">
					<?=spm_runSmilesRun(htmlspecialchars_decode(str_replace("\n", "<br/>", $message['message'])))?>
				</div>
			</div>
			<?php elseif ($message['from'] == $_SESSION["uid"]): ?>
			<div class="direct-chat-msg right">
				<div class="direct-chat-info clearfix">
					<span class="direct-chat-name pull-right"></span>
					<span class="direct-chat-timestamp pull-left"><?=$message['date']?></span>
				</div>
				<img class="direct-chat-img" src="index.php?service=image&uid=<?=$message['from']?>" alt="Аватар">
				<div class="direct-chat-text">
					<?=spm_runSmilesRun(htmlspecialchars_decode(str_replace("\n", "<br/>", $message['message'])))?>
				</div>
			</div>
			<?php endif; ?>
			
			<?php endwhile; ?>
			<?php endif; ?>
		</div>
		<div class="direct-chat-contacts" style="height: 70vh;">
			<ul class="contacts-list">
				<?php while ($contact = $query_contacts->fetch_array()): ?>
				<?php
					$selected_id = ($contact[0] == $_SESSION["uid"] ? $contact[1] : $contact[0]);
				?>
				<li>
					<a href="index.php?service=messages&uid=<?=$selected_id?>">
						<img class="contacts-list-img" src="index.php?service=image&uid=<?=$selected_id?>" style="min-height: 40px; min-width: 40px;">
						<div class="contacts-list-info">
							<span class="contacts-list-name">
								<i><?=spm_getUserFullnameByID($selected_id)?></i>
								<small class="contacts-list-date pull-right"></small>
							</span>
							<span class="contacts-list-msg">
								Перейти к диалогу
							</span>
						</div>
					</a>
				</li>
				<?php endwhile; ?>
			</ul>
		</div>
	</div>
	<div class="box-footer">
		<form
			action="<?=$_SERVER["REQUEST_URI"]?>"
			method="post"
			style="margin: 0;"
		>
			<div class="input-group">
				<textarea
					name="message"
					placeholder="Введите ваше сообщение..."
					rows="1"
					class="form-control"
					style="resize: none;"
					autocomplete="off"
					<?=($_GET["uid"] == 0 ? "disabled" : "")?>
					required
				></textarea>
				<span class="input-group-btn">
					<button
						type="submit"
						class="btn btn-primary btn-flat"
						name="sendMsg"
						<?=($_GET["uid"] == 0 ? "disabled" : "")?>
					>
						&nbsp;<i class="fa fa-paper-plane"></i>&nbsp;
					</button>
				</span>
			</div>
		</form>
		<small style="padding-left: 2px; padding-top: 2px;">
			<b>
				Смайлы: 
				<a data-toggle="tooltip" title="XD (смеющийся)">XD</a> 
				<a data-toggle="tooltip" title=":) (радостный)">:)</a> 
				<a data-toggle="tooltip" title=":( (грустный)">:(</a> 
				<a data-toggle="tooltip" title=":DEVIL: (очень злой)">:DEVIL:</a> 
				<a data-toggle="tooltip" title=":| (нет слов)">:|</a> 
				<a data-toggle="tooltip" title=":WORRIED: (озабоченный)">:WORRIED:</a> 
				<a data-toggle="tooltip" title=":A: (злой)">:A:</a> 
				<a data-toggle="tooltip" title="BD (крутой)">BD</a>
			</b>
		</small>
	</div>
</div>

<script>
	$('.direct-chat-messages').stop().scrollTop($('.direct-chat-messages')[0].scrollHeight);
</script>

<?php
	/////////////////////////////////////
	//    TEMPORARY TABLES CLEARING    //
	/////////////////////////////////////
	
	$query_contacts->free();
	
	/////////////////////////////////////
	
	SPM_footer();
?>