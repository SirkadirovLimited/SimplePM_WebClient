<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	function spm_getTeacherLinkById($teacherId){
		
		global $db;
		
		if ($teacherId == 0):
			return "<a>Учитель/Куратор:<br/><b>Тёмная сторона силы, admin</b></a>";
		elseif($teacherId > 0):
			if (!$db_get = $db->query("SELECT `firstname`,`secondname`,`thirdname`,`group` FROM `spm_users` WHERE `id`='" . $teacherId . "' LIMIT 1;"))
				die('<strong>Произошла ошибка при попытке соединения с базой данных! Пожалуйста, повторите ваш запрос позже!</strong>');
			
			if ($db_get->num_rows == 0):
				return "<a>Учитель/Куратор:<br/><b>Ничейный пользователь</b></a>";
			elseif ($db_get->num_rows === 1):
				$tUser = $db_get->fetch_assoc();
				$db_get->free();
				unset($db_get);
				
				return "<a href='index.php?service=user&id=" . $teacherId . "'>Учитель/Куратор:<br/><b>" . $tUser['secondname'] . " " . $tUser['firstname'] . " " . $tUser['thirdname'] . ", " . $tUser['group'] . "</b></a>";
			endif;
		endif;
		
	}
	
	if (!isset($_GET['id']) || strlen(trim($_GET['id'])) == 0):
		
		header("Location: index.php?service=user&id=" . $_SESSION['uid']);
		
	else:
		$id = intval( htmlspecialchars( trim( $_GET['id'] ) ), 0 ); //Stay safe
		if (!$db_result = $db->query("SELECT * FROM `spm_users` WHERE id = '$id'"))
			die("<strong>Произошла ошибка при отправке запроса к базе данных. Посетите данную страницу позже.</strong>");
		
		if ($db_result->num_rows == 0):
			
			SPM_header("Ошибка 404");
			include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["404"]);
			SPM_footer();
			exit();
			
		else:
			
			$user_info = $db_result->fetch_assoc();
			
			$db_result->free();
			unset($db_result);
			
			if ($user_info['online'] == true)
				$user_is_online = "<span class='label label-success'>Online</span>";
			else
				$user_is_online = "<span class='label label-danger'>Offline</span>";
			
			$user_fullname = $user_info['secondname'] . " " . $user_info['firstname'] . " " . $user_info['thirdname'];
			
			SPM_header($user_fullname, "Профиль пользователя", "Профиль пользователя");
?>
<div class="row">
	<div class="col-md-4">
		<style>
			.userAvatar {
				width: 100%;
				height: auto;
				box-shadow: 3px 5px 15px -7px #000000;
				margin-top: 10px;
				margin-bottom: 10px;
				z-index: -1;
			}
		</style>
		<div>
			<img src="index.php?service=image&uid=<?=$id?>" class="userAvatar" />
			<h4 style="z-index: 10; position: absolute; right: 20px; top: 10px;"><?=$user_is_online?></h4>
		</div>
		
		<?php if (permission_check($user_info["permissions"], PERMISSION::administrator)):?>
		<img src="<?=_S_MEDIA_IMG_?>dark_side.jpg" class="userAvatar" />
		<?php endif; ?>
		
		<div class="small-box bg-green">
			<div class="inner">
				<h3><?=$user_info["bcount"]?></h3>
				<p>ПОЛУЧЕННЫЕ БАЛЛЫ</p>
			</div>
			<a href="index.php?service=bad_problems&uid=<?=$user_info['id']?>" class="small-box-footer">
				Отложенные задачи <i class="fa fa-arrow-circle-right"></i>
            </a>
		</div>
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3><?=$user_info["rating"]?></h3>
				<p>РЕЙТИНГ ПОЛЬЗОВАТЕЛЯ</p>
			</div>
			<a href="index.php?service=rating" class="small-box-footer">
				Глобальный рейтинг <i class="fa fa-arrow-circle-right"></i>
            </a>
		</div>
		
		<?php if ($_SESSION['uid'] == $user_info['id']): ?>
		<h3>Редактирование</h3>
		<ul class="nav nav-pills nav-stacked">
			<li role="presentation"><a href="index.php?service=user.edit&id=<?=$id?>#editProfile">Редактировать информацию</a></li>
			<li role="presentation"><a href="index.php?service=user.edit&id=<?=$id?>#editAvatar">Изменить аватар</a></li>
			<li role="presentation"><a href="index.php?service=user.edit&id=<?=$id?>#editPass">Изменить пароль</a></li>
			<li role="presentation"><a href="index.php?service=user.edit&id=<?=$id?>#settings">Настройки</a></li>
		</ul>
		<?php else: ?>
		<h3>Действия</h3>
		<ul class="nav nav-pills nav-stacked">
			<li role="presentation"><a href="index.php?service=messages.send&id=<?php print($id); ?>">Отправить сообщение</a></li>
		</ul>
		<?php endif; ?>
	</div>
	<div class="col-md-8">
		<h3 style="margin-top: 0;">Основная информация</h3>
		<ul class="nav nav-pills nav-stacked">
			<li><a>Полное имя:<br/><b><?=$user_info['secondname'] . " " . $user_info['firstname'] . " " . $user_info['thirdname']?></b></a></li>
			<li><a>Имя пользователя:<br/><b><?=$user_info['username']?></b></a></li>
			<li><a>Дата рождения:<br/><b><?=$user_info['bdate']?></b></a></li>
			
			<li><a>Страна:<br/><b><?=@$SPM_Countries_Get[$user_info['country']]?></b></a></li>
			<li><a>Город:<br/><b><?=$user_info['city']?></b></a></li>
			<li><a>Учебное заведение:<br/><b><?=$user_info['school']?></b></a></li>
		</ul>
		<h3>Контакты</h3>
		<ul class="nav nav-pills nav-stacked">
			<li><a>Номер телефона:<br/><b><?=$user_info['phone']?></b></a></li>
			<li><a>Email:<br/><b><?=$user_info['email']?></b></a></li>
		</ul>
		<h3>Важная информация</h3>
		<ul class="nav nav-pills nav-stacked">
			<li><a>Права доступа:<br/><b><?=$user_info['permissions']?></b></a></li>
			<li><a>Группа:<br/><b>TODO (<?=$user_info['group']?>)</b></a></li>
			<li><?=spm_getTeacherLinkById($user_info['teacherId'])?></li>
		</ul>
	</div>
</div>
<?php		

			unset($user_info);
		endif;
		SPM_footer();
	endif;
?>