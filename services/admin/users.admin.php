<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	global $db;
	global $_SESSION;
	
	if (!permission_check($_SESSION['permissions'], PERMISSION::teacher)
		&& !permission_check($_SESSION['permissions'], PERMISSION::administrator)){
		include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["access_denied"]);
		die();
	}
	
	if (isset($_GET['del']) && $_GET['del'] > 1){
		if ((int)$_GET['del'] == $_SESSION['uid'])
			die('<strong>Вы не можете удалить собственный аккаунт!</strong>>');
		
		if (!permission_check($_SESSION['permissions'], PERMISSION::administrator)){
			
			if (!$user_query = $db->query("SELECT * FROM `spm_users` WHERE `id` = " . (int)$_GET['del'] . ""))
				die('<strong>Произошла ошибка при выполнении запроса к базе данных!</strong>');
			
			if($user_query->num_rows === 0)
				die('<strong>Пользователь с таким идентификатором не найден!</strong>');
			
			$users_admin_user = $user_query->fetch_assoc();
			$user_query->free();
			unset($user_query);
			
			if (permission_check($_SESSION['permissions'], PERMISSION::teacher)){
				if ($users_admin_user["teacherId"] != $_SESSION["uid"]){
					die('<strong>Вы не можете удалить данного пользователя!</strong>');
				}
				
			}
			if (permission_check($_SESSION['permissions'], PERMISSION::curator)){
				
				if ($users_admin_user["group"] == "admin"
					|| permission_check($users_admin_user["permissions"],PERMISSION::administrator))
					die('<strong>Вы не можете удалить данного пользователя</strong>');
				
			}
			
		}
		if (!$db->query("DELETE FROM `spm_users` WHERE `id` = " . (int)$_GET['del']))
			die('<strong>ПОЛЬЗОВАТЕЛЬ НЕ НАЙДЕН ИЛИ ПРОИЗОШЛА ОШИБКА ПРИ ПОПЫТКЕ ЕГО УДАЛИТЬ.</strong>');
		else
			header('location: index.php?service=users.admin');
	}
	
	if (!isset($_GET['page']))
		$_GET['page'] = 1;
	
	(int)$_GET['page']>0 or die('<strong>Попытка ввода SQL инъекции заблокирована.</strong>');
	
	if (!$db_result = $db->query("SELECT count(*) AS us_count FROM `spm_users`"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
	$total_articles_number = (int)($db_result->fetch_assoc()["us_count"]);
	$articles_per_page = 10;
	$current_page = (int)$_GET['page'];
	
	unset($db_result);
	
	if ($total_articles_number > 0 && $articles_per_page > 0)
		$total_pages = ceil($total_articles_number / $articles_per_page);
	else
		$total_pages = 1;
	
	if ($current_page > $total_pages)
		$current_page = 1;
	
	if (!$db_result = $db->query("SELECT * FROM `spm_users` ORDER BY `id` LIMIT " . ($current_page * $articles_per_page - $articles_per_page) . " , " . ($current_page * $articles_per_page) . ";"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
	SPM_header("Пользователи системы", "Управление");
?>

<?php /*include_once(_S_SERV_INC_ . "users.admin/stats.php");*/ ?>
<?php /*include_once(_S_SERV_INC_ . "users.admin/users.admin.search.php");*/ ?>

<div class="table-responsive">
	<table class="table table-bordered table-hover" style="background-color: white;">
		<thead>
			<tr class="active">
				<th>ID</th>
				<th>Ф.И.О.</th>
				<th>Логин</th>
				<th>Группа</th>
				<th>Учитель</th>
				<th>ACCESS</th>
				<th>Действия</th>
			</tr>
		</thead>
		<tbody>
	<?php
	if ($total_articles_number == 0 || $db_result->num_rows === 0){
?>
			<tr>
				<td></td>
				<td><b>Тут пусто :( Создай пожалуйста новую страницу, чтобы раб твой рад был, мой господин! Смилуйся надо мной!</b></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
<?php
	}else{
		while ($user = $db_result->fetch_assoc()) {
?>
			<tr>
				<td><?php print($user['id']); ?></td>
				<td>
					<a href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=user&id=<?php print($user['id']); ?>">
						<?php print($user['secondname'] . " " . $user['firstname'] . " " . $user['thirdname']); ?>
					</a>
				</td>
				<td><?php print($user['username']); ?></td>
				<td><?php print($user['group']); ?></td>
				<td><?php if ($user['teacherId']>0) print("<a href='" . $_SPM_CONF["BASE"]["SITE_URL"] . "index.php?service=user&id=" . $user['teacherId'] . "'>ID_" . $user['teacherId'] . "</a>"); else print("Тёмная сторона Силы"); ?></td>
				<td><?php print($user['permissions']); ?></td>
				<td><a class="btn btn-primary btn-xs" href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=user.edit&id=<?php print($user['id']); ?>">EDIT</a>
					<a class="btn btn-warning btn-xs" href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=users.admin&ban=<?php print($user['id']); ?>" onclick="return confirm('Вы действительно хотите заблокировать этого пользователя? Не легче его просто застрелить?');">BAN</a>
					<a class="btn btn-danger btn-xs" href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=users.admin&del=<?php print($user['id']); ?>" onclick="return confirm('Вы действительно хотите удалить этого пользователя? Это действие не обратимо!');">DEL</a> </td>
			</tr>
<?php
		}
		unset($db_result);
	}
?>
		</tbody>
	</table>
</div>
<?php include(_S_MOD_ . "pagination.php"); generatePagination($total_pages, $current_page, 4, "users.admin"); ?>
<?php SPM_footer(); ?>