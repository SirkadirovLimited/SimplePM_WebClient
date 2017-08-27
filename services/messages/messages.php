<?php
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
	
	SPM_header("Діалоги", "");
?>
<div class="box box-primary box-solid direct-chat" style="margin-bottom: 0;">
	<div class="box-header with-border">
		<h3 class="box-title"><?=spm_getUserShortnameByID($_GET["uid"])?></h3>
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
				title="Подивитись профіль співрозмовника"
			>
				<i class="fa fa-user"></i> <span class="hidden-xs">Профіль співрозмовника</span>
			</a>
			<?php endif; ?>
			<button
				class="btn btn-box-tool"
				data-toggle="tooltip"
				data-widget="chat-pane-toggle"
				title="Меню вибору співрозмовника"
			>
				<i class="fa fa-comments"></i> <span class="hidden-xs">Вибір співрозмовника</span>
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
					Раді вітати вас в підсистемі "Діалоги"!<br/>
					З її допомогою ви можете спілкуватися з усіма користувачами системи.
				</div>
			</div>
			<div class="direct-chat-msg">
				<div class="direct-chat-info clearfix">
				</div>
				<img class="direct-chat-img" src="index.php?service=image&uid=0" alt="Аватар">
				<div class="direct-chat-text">
					Для вибору співрозмовника натисніть кнопку 
					" <i><i class="fa fa-comments"></i> Вибір співрозмовника</i> ", що знаходиться у верхньому меню.
				</div>
			</div>
			<div class="direct-chat-msg">
				<div class="direct-chat-info clearfix">
				</div>
				<img class="direct-chat-img" src="index.php?service=image&uid=0" alt="Аватар">
				<div class="direct-chat-text">
					Для того, щоб створити новий діалог, треба зробити такі дії:<br>
					<ol style="margin-top: 5px; margin-bottom: 5px;">
						<li>Відкрити профіль бажаного співрозмовника</li>
						<li>У меню "Дії" виберіть пункт "Відкрити діалог"</li>
						<li>Спілкуйтесь із радістю!</li>
					</ol>
					Дякуємо за увагу!
				</div>
			</div>
			
			<?php elseif ($query_messages->num_rows == 0): ?>
			
			<div class="direct-chat-msg">
				<div class="direct-chat-info clearfix">
				</div>
				<img class="direct-chat-img" src="index.php?service=image&uid=0" alt="Аватар">
				<div class="direct-chat-text">
					Ви ще не відправили ні одного повідомлення.<br/>
					Для створення нового повідомлення скористайтесь формою нижче.
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
					<?=htmlspecialchars_decode(str_replace("\n", "<br/>", $message['message']))?>
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
					<?=htmlspecialchars_decode(str_replace("\n", "<br/>", $message['message']))?>
				</div>
			</div>
			<?php endif; ?>
			
			<?php endwhile; ?>
			<?php endif; ?>
		</div>
		
		<div class="direct-chat-contacts" style="height: 70vh;">
			<ul class="contacts-list">
				<?php if ($query_contacts->num_rows > 0): ?>
				<?php while ($contact = $query_contacts->fetch_array()): ?>
				<?php $selected_id = ($contact[0] == $_SESSION["uid"] ? $contact[1] : $contact[0]); ?>
				<li>
					<a href="index.php?service=messages&uid=<?=$selected_id?>">
						<img class="contacts-list-img" src="index.php?service=image&uid=<?=$selected_id?>" style="min-height: 40px; min-width: 40px;">
						<div class="contacts-list-info">
							<span class="contacts-list-name">
								<i><?=spm_getUserFullnameByID($selected_id)?></i>
								<small class="contacts-list-date pull-right"></small>
							</span>
							<span class="contacts-list-msg">
								<?php
									$query_str = "
										SELECT
											count(`id`)
										FROM
											`spm_messages`
										WHERE
											`from` = '" . $selected_id . "'
										AND
											`unread` = true
										;
									";
									
									if ($query = $db->query($query_str)):
										$unread_count = (int)$query->fetch_array()[0];
										$query->free();
									else:
										$unread_count = 0;
									endif;
								?>
								<?php if ($unread_count == 0): ?>
								Немає нових повідомлень
								<?php else: ?>
								<?=$unread_count?> нових повідомлень
								<?php endif; ?>
							</span>
						</div>
					</a>
				</li>
				<?php endwhile; ?>
				<?php else: ?>
				<li>
					<a>
						<img class="contacts-list-img" src="index.php?service=image&uid=0" style="min-height: 40px; min-width: 40px;">
						<div class="contacts-list-info">
							<span class="contacts-list-name">
								<i>Діалогів немає</i>
								<small class="contacts-list-date pull-right"></small>
							</span>
							<span class="contacts-list-msg">
								Для створення нового діалогу скористайтесь вказівками бота.
							</span>
						</div>
					</a>
				</li>
				<?php endif; ?>
			</ul>
		</div>
		
	</div>
	<div class="box-footer">
		<script>
			function insertAtCaret(areaId, text) {
				var txtarea = document.getElementById(areaId);
				if (!txtarea) { return; }

				var scrollPos = txtarea.scrollTop;
				var strPos = 0;
				var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
					"ff" : (document.selection ? "ie" : false ) );
				
				if (br == "ie") {
					txtarea.focus();
					var range = document.selection.createRange();
					range.moveStart ('character', -txtarea.value.length);
					strPos = range.text.length;
				} else if (br == "ff") {
					strPos = txtarea.selectionStart;
				}
				
				var front = (txtarea.value).substring(0, strPos);
				var back = (txtarea.value).substring(strPos, txtarea.value.length);
				
				txtarea.value = front + text + back;
				strPos = strPos + text.length;
				if (br == "ie") {
					txtarea.focus();
					var ieRange = document.selection.createRange();
					ieRange.moveStart('character', -txtarea.value.length);
					ieRange.moveStart('character', strPos);
					ieRange.moveEnd('character', 0);
					ieRange.select();
				} else if (br == "ff") {
					txtarea.selectionStart = strPos;
					txtarea.selectionEnd = strPos;
					txtarea.focus();
				}
				
				txtarea.scrollTop = scrollPos;
			}
		</script>
		<form
			action="<?=$_SERVER["REQUEST_URI"]?>"
			method="post"
			accept-charset="utf-8"
			style="margin: 0;"
		>
			<div class="input-group">
				<textarea
					id="message"
					name="message"
					placeholder="Введіть своє повідомлення..."
					rows="1"
					class="form-control"
					style="resize: none;"
					autocomplete="off"
					<?=($_GET["uid"] == 0 ? "disabled" : "")?>
					required
				></textarea>
				<span class="input-group-btn">
					<a
						data-toggle="modal"
						data-target="#modal-emoticons"
						class="<?=($_GET["uid"] == 0 ? "disabled " : "")?>btn btn-default btn-flat"
					>🙂</a>
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
	</div>
</div>

<div class="modal modal-primary fade" id="modal-emoticons">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span></button>
				<h4 class="modal-title">Додання стікеру</h4>
			</div>
			<div class="modal-body">
				<?php
					$emoticons_arr = array(
						'😀', '😁', '😂', '😃', '😄', '😅', '😆', '😇', '😈', 
						'😉', '😊', '😋', '😌', '😍', '😎', '😏', '😐', '😑', 
						'😒', '😓', '😔', '😕', '😖', '😗', '😘', '😙', '😚', 
						'😛', '😜', '😝', '😞', '😟', '😠', '😡', '😢', '😣', 
						'😤', '😥', '😦', '😧', '😨', '😩', '😪', '😫', '😬', 
						'😭', '😮', '😯', '😰', '😱', '😲', '😳', '😴', '😵', 
						'😶', '😷', '😸', '😹', '😺', '😻', '😼', '😽', '😾', 
						'😿', '🙀', '🙁', '🙂', '🙃', '🙄', '🙅', '🙆', '🙇', 
						'🙈', '🙉', '🙊', '🙋', '🙌', '🙍', '🙎', '🙏'
					);
				?>
				<?php foreach ($emoticons_arr as $emoticon): ?>
				<a href="#"><span style="font-size: 20px;" onclick="insertAtCaret('message', '<?=$emoticon?>'); return false;"><?=$emoticon?></span></a>&nbsp;
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

<script>
	$('.direct-chat-messages').stop().scrollTop($('.direct-chat-messages')[0].scrollHeight);
</script>

<?php
	/////////////////////////////////////
	//    TEMPORARY TABLES CLEARING    //
	/////////////////////////////////////
	
	if (isset($query_messages))
		$query_messages->free();
	
	$query_contacts->free();
	
	/////////////////////////////////////
	
	SPM_footer();
?>