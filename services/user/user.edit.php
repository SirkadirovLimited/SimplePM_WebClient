<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	define("__spm.user.edit__", 1);
	
	(isset($_GET['id']) && strlen($_GET['id'])>0 && (int)$_GET['id'] > 0) or $_GET['id'] = $_SESSION["uid"];
	
	if (!$user = $db->query("SELECT * FROM `spm_users` WHERE `id` = '" . (int)$_GET['id'] . "' LIMIT 1;"))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($user->num_rows === 0)
		die(header('location: index.php?service=error&err=404'));
	
	$user_info = $user->fetch_assoc();
	
	$user->free();
	unset($user);
	
	if (!permission_check($_SESSION["permissions"], PERMISSION::administrator) && ($user_info["teacherId"] != $_SESSION["uid"]) && ($user_info["id"] != $_SESSION["uid"]))
		die(header('location: index.php?service=error&err=403'));
	
	if (isset($_POST['editPass']))
		include_once(_S_SERV_INC_ . "user/user.edit.password.php");
	elseif (isset($_POST['editAvatar']))
		include_once(_S_SERV_INC_ . "user/user.edit.avatar.php");
	elseif (isset($_POST['editProfile']))
		include_once(_S_SERV_INC_ . "user/user.edit.profile.php");
	
	SPM_header("Редактирование пользователя");
	
?>

<a href="index.php?service=user&id=<?=$_GET['id']?>" class="btn btn-default">
	<span class="glyphicon glyphicon-chevron-left"></span> Профиль пользователя
</a>

<?php
	require_once(_S_SERV_INC_ . "user/ui/avatar.php");
	require_once(_S_SERV_INC_ . "user/ui/profile.php");
	require_once(_S_SERV_INC_ . "user/ui/password.php");
	
	SPM_footer();
?>