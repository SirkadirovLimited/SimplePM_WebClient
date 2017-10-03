<?php
	
	function spm_getTeacherLinkById($teacherId){
		
		global $db;
		
		if ($teacherId == 0):
			return "<a>Викладач / куратор:<br/><b>Темна сторона сили, admin</b></a>";
		elseif($teacherId > 0):

			$query_str = "
				SELECT
					`firstname`,
					`secondname`,
					`thirdname`,
					`group`
				FROM
					`spm_users`
				WHERE
					`id`='" . $teacherId . "'
				LIMIT
					1
				;
			";
			
			if (!$db_get = $db->query($query_str))
				die(header('location: index.php?service=error&err=db_error'));
			
			if ($db_get->num_rows == 0):
				return "<a>Викладач / Куратор:<br/><b>Інкогніто</b></a>";
			elseif ($db_get->num_rows === 1):
				
				$tUser = $db_get->fetch_assoc();
				$db_get->free();
				unset($db_get);
				
				$query_str = "
					SELECT
						`name`
					FROM
						`spm_users_groups`
					WHERE
						`id` = '" . $teacherId . "'
					LIMIT
						1
					;
				";
				
				if (!$query = $db->query($query_str))
					die(header('location: index.php?service=error&err=db_error'));
				
				$tUser['group_name'] = @$query->fetch_array()[0];
				
				return "<a href='index.php?service=user&id=" . $teacherId . "'>Викладач / Куратор:<br/><b>" . $tUser['secondname'] . " " . $tUser['firstname'] . " " . $tUser['thirdname'] . ", " . $tUser['group_name'] . "</b></a>";
				
			endif;
			
		endif;
		
	}
	
	if (!isset($_GET['id']) || strlen(trim($_GET['id'])) == 0)
		exit(header("location: index.php?service=user&id=" . $_SESSION['uid']));
	
	$id = (int)$_GET['id']; //Stay safe
	
	if (!$db_result = $db->query("SELECT * FROM `spm_users` WHERE id = '$id'"))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($db_result->num_rows == 0)
		die(header('location: index.php?service=error&err=404'));
	
	$user_info = $db_result->fetch_assoc();
	
	$db_result->free();
	unset($db_result);
	
	if (spm_getUserOnline($id))
		$user_is_online = "<span class='label label-success'>Online</span>";
	else
		$user_is_online = "<span class='label label-danger'>Offline</span>";
	
	$user_fullname = $user_info['secondname'] . " " . $user_info['firstname'] . " " . $user_info['thirdname'];
	
	$user_info['group_name'] = spm_getUserGroupByID($user_info['group']);
	
	SPM_header($user_fullname, "Профіль користувача", "Профіль користувача");
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
		
		<div class="small-box bg-green">
			<div class="inner">
				<h3><?=(int)$user_info["bcount"]?></h3>
				<p>ОТРИМАНІ БАЛИ</p>
			</div>
			<a href="index.php?service=bad_problems&uid=<?=$user_info['id']?>" class="small-box-footer">
				Відкладені задачі <i class="fa fa-arrow-circle-right"></i>
            </a>
		</div>
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3><?=round($user_info["rating"], 2)?></h3>
				<p>РЕЙТИНГ КОРИСТУВАЧА</p>
			</div>
			<a href="index.php?service=rating" class="small-box-footer">
				Глобальный рейтинг <i class="fa fa-arrow-circle-right"></i>
            </a>
		</div>
		
		<?php if ($_SESSION['uid'] == $user_info['id']): ?>
		<h3>Редагування</h3>
		<ul class="nav nav-pills nav-stacked">
			<li><a href="index.php?service=user.edit&id=<?=$id?>#editProfile">Редагувати інформацію</a></li>
			<li><a href="index.php?service=user.edit&id=<?=$id?>#editAvatar">Змінити аватар</a></li>
			<li><a href="index.php?service=user.edit&id=<?=$id?>#editPass">Змінити пароль</a></li>
		</ul>
		<h3>Інформація</h3>
		<ul class="nav nav-pills nav-stacked">
			<li><a href="index.php?service=submissions&uid=<?=$id?>">Список відправок</a></li>
		</ul>
		<?php else: ?>
		<h3>Дії</h3>
		<ul class="nav nav-pills nav-stacked">
			<li><a href="index.php?service=messages&uid=<?=$id?>">Відкрити діалог</a></li>
			<?php if ($_SESSION['uid'] == $user_info['teacherId'] || permission_check($_SESSION['permissions'], PERMISSION::administrator)): ?>
			<li><a href="index.php?service=user.edit&id=<?=$id?>">Редагувати користувача</a></li>
			<li><a href="index.php?service=submissions&uid=<?=$id?>">Список відправок</a></li>
			<?php endif; ?>
		</ul>
		
		<?php endif; ?>
	</div>
	<div class="col-md-8" style="padding-top: 10px;">
		
		<?php if ($user_info['banned']): ?>
		<div class="callout callout-danger">
			<h4>Користувача заблоковано!</h4>
			<p>
				Причиною цьому може слугувати:
			</p>
			<ul>
				<li>Невихованість</li>
				<li>Порушення правил користування сайтом чи EULA</li>
				<li>Підступні спроби зламу системи</li>
				<li>Тощо</li>
			</ul>
			<p>
				За розблокуванням зверніться до одного з адміністраторів системи.
			</p>
		</div>
		<?php endif; ?>
		
		<h3 style="margin-top: 0;">Базова інформація</h3>
		<ul class="nav nav-pills nav-stacked">
			<li><a>Повне ім'я:<br/><b><?=$user_info['secondname'] . " " . $user_info['firstname'] . " " . $user_info['thirdname']?></b></a></li>
			<li><a>Ім'я користувача:<br/><b><?=$user_info['username']?></b></a></li>
			<li><a>Дата народження:<br/><b><?=$user_info['bdate']?></b></a></li>
			
			<li><a>Країна:<br/><b><?=@$SPM_Countries_Get[$user_info['country']]?></b></a></li>
			<li><a>Місто:<br/><b><?=$user_info['city']?></b></a></li>
			<li><a>Навчальний заклад:<br/><b><?=$user_info['school']?></b></a></li>
		</ul>
		<h3>Контактна інформація</h3>
		<ul class="nav nav-pills nav-stacked">
			<li><a>Номер телефону:<br/><b><?=$user_info['phone']?></b></a></li>
			<li><a>Email адреса:<br/><b><?=$user_info['email']?></b></a></li>
		</ul>
		<h3>Системна інформація</h3>
		<ul class="nav nav-pills nav-stacked">
			<li><a>Ідентифікатор користувача:<br/><b><?=$user_info['id']?></b></a></li>
			<li><a>Права доступу:<br/><b><?=$user_info['permissions']?></b></a></li>
			<li><a>Група:<br/><b><?=$user_info['group_name']?> (gid<?=$user_info['group']?>)</b></a></li>
			<li><?=spm_getTeacherLinkById($user_info['teacherId'])?></li>
		</ul>
	</div>
</div>
<?php SPM_footer(); ?>