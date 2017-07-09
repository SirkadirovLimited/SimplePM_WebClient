<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	$query_str = "
		SELECT
			count(`id`)
		FROM
			`spm_messages`
		WHERE
			`to` = '" . $_SESSION['uid'] . "'
		AND
			`unread` = true
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	$messagesCount = $query->fetch_array()[0];
	$query->free();
	
	if ($messagesCount == null)
		$messagesCount = 0;
	
	$enablelinks = !isset($_SESSION["classwork"], $_SESSION["olymp"]);
?>
<li class="dropdown user user-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Выпадающее меню пользователя">
		&nbsp;<img src="index.php?service=image&uid=<?=$_SESSION['uid']?>" class="user-image" alt="Аватар">
		<span class="hidden-xs"><?=spm_getUserShortnameByID($_SESSION['uid'])?></span>&nbsp;
	</a>
	<ul class="dropdown-menu" style="border-color: #3c8dbc; padding: 0; border-radius: 0;">
		<li class="user-header">
			<img src="index.php?service=image&uid=<?=$_SESSION['uid']?>" class="img-circle" alt="Аватар">
			<p style="color: white;">
				<?=spm_getUserFullnameByID($_SESSION['uid'])?>
				<small>@<?=$_SESSION['username']?></small>
			</p>
		</li>
		<?php if ($enablelinks): ?>
		<li class="user-body" style="padding: 0;">
			<ul class="nav nav-pills nav-stacked">
				<li><a href="index.php?service=user&id=<?=$_SESSION['uid']?>"><i class="fa fa-user"></i> Мой профиль</a></li>
				<li><a href="index.php?service=messages"><i class="fa fa-comments"></i> Мои сообщения <i class="fa pull-right"><span class="badge"><?=$messagesCount?></span></i></a></li>
				<?php if (permission_check($_SESSION["permissions"], PERMISSION::student)): ?>
				<li><a href="index.php?service=olympiads"><i class="fa fa-book"></i> Олимпиадный режим</a></li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>
		<li class="user-footer">
			<div class="pull-right">
				<a href="index.php?service=logout" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Выйти</a>
			</div>
		</li>
	</ul>
</li>
<?php if ($messagesCount > 0 && $enablelinks): ?>
<script>
	Push.close('unreadMessages');
	Push.create('<?=$_SPM_CONF["BASE"]["SITE_NAME"]?>', {
		body: 'У вас есть [<?=$messagesCount?>] новых непрочитанных сообщений!',
		icon: {
			x16: '<?=_S_MEDIA_IMG_?>mail.png',
			x32: '<?=_S_MEDIA_IMG_?>mail.png'
		},
		tag: 'unreadMessages',
		timeout: 5000
	});
</script>
<?php endif; ?>