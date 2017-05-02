<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	define("__spm.user.edit__", 1);
	
	(isset($_GET['id']) && strlen($_GET['id'])>0 && (int)$_GET['id']>0) or $_GET['id'] = $_SESSION["uid"];
	
	if (!$user = $db->query("SELECT * FROM `spm_users` WHERE `id` = '" . (int)$_GET['id'] . "' LIMIT 1;"))
		die('Произошла непредвиденная ошибка при попытке выборки из базы данных. Пожалуйста, повторите ваш запрос позже!');
	
	if ($user->num_rows === 0){
		SPM_header("Ошибка 404");
		include(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["404"]);
		SPM_footer();
		die();
	}
	
	$user_info = $user->fetch_assoc();
	
	$user->free();
	unset($user);
	
	if (!permission_check($_SESSION["permissions"], PERMISSION::administrator) && ($user_info["teacherId"] != $_SESSION["uid"]) && ($user_info["id"] != $_SESSION["uid"])) {
		SPM_header("Ошибка 403");
		include(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["access_denied"]);
		SPM_footer();
		die();
	}
	
	if (isset($_POST['editPass']))
		include_once(_S_SERV_INC_ . "user/user.edit.password.php");
	elseif (isset($_POST['editAvatar']))
		include_once(_S_SERV_INC_ . "user/user.edit.avatar.php");
	elseif (isset($_POST['editProfile']))
		include_once(_S_SERV_INC_ . "user/user.edit.profile.php");
	
	SPM_header("Редактирование пользователя");
	
?>

<a href="index.php?service=user&id=<?=$_GET['id']?>" class="btn btn-default" style="margin-bottom: 10px; margin-right: 5px;">
	<span class="glyphicon glyphicon-chevron-left"></span> Профиль пользователя
</a>

<?php

	require_once(_S_SERV_INC_ . "user/ui/avatar.php");
	require_once(_S_SERV_INC_ . "user/ui/settings.php");
	require_once(_S_SERV_INC_ . "user/ui/profile.php");
	require_once(_S_SERV_INC_ . "user/ui/teacherId.php");
	require_once(_S_SERV_INC_ . "user/ui/password.php");
	
	SPM_footer();
?>