<?php
	define("__spm.user.edit__", 1);
	
	(isset($_GET['id']) && (int)$_GET['id'] > 0) or $_GET['id'] = $_SESSION["uid"];
	
	if ((int)$_GET['id'] == 1 && $_SESSION["uid"] != 1)
		die(header('location: index.php?service=error&err=403'));
	
	$query_str = "
		SELECT
			*
		FROM
			`spm_users`
		WHERE
			`id` = '" . (int)$_GET['id'] . "'
		LIMIT
			1
		;
	";
	
	if (!$user = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($user->num_rows === 0)
		die(header('location: index.php?service=error&err=404'));
	
	$user_info = $user->fetch_assoc();
	
	$user->free();
	unset($user);
	
	if (!permission_check($_SESSION["permissions"], PERMISSION::administrator) && ($user_info["teacherId"] != $_SESSION["uid"]) && ($user_info["id"] != $_SESSION["uid"]))
		die(header('location: index.php?service=error&err=403'));
	
	if ((int)$_GET['id'] != 1 && isset($_POST['advancedEdit']) && permission_check($_SESSION["permissions"], PERMISSION::administrator))
		include(_S_SERV_INC_ . "user/user.edit.advanced.php");
	
	elseif (isset($_POST['editPass']))
		include(_S_SERV_INC_ . "user/user.edit.password.php");
	
	elseif (isset($_POST['editAvatar']))
		include(_S_SERV_INC_ . "user/user.edit.avatar.php");
	
	elseif (isset($_POST['editProfile']))
		include(_S_SERV_INC_ . "user/user.edit.profile.php");
	
	SPM_header($user_info['secondname'] . ' ' . $user_info['firstname'] . ' ' . $user_info['thirdname'], "Редагування профілю", "Редагування профілю");
	
?>

<a href="index.php?service=user&id=<?=$_GET['id']?>" class="btn btn-default btn-flat" style="margin-bottom: 10px;">
	<span class="glyphicon glyphicon-chevron-left"></span> Профіль користувача
</a>

<?php
	include(_S_VIEW_ . "user/edit/avatar.php");
	include(_S_VIEW_ . "user/edit/profile.php");
	include(_S_VIEW_ . "user/edit/password.php");
	
	if ((int)$_GET['id'] != 1 && permission_check($_SESSION["permissions"], PERMISSION::administrator))
		include(_S_VIEW_ . "user/edit/advanced.php");
	
	SPM_footer();
?>