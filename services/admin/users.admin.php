<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::teacher | PERMISSION::administrator);
	
	/*
	 * УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЕЙ
	 */
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
			
			if ($users_admin_user["teacherId"] != $_SESSION["uid"]){
				die('<strong>Вы не можете удалить данного пользователя!</strong>');
			}
			
		}
		if (!$db->query("DELETE FROM `spm_users` WHERE `id` = " . (int)$_GET['del']))
			die('<strong>ПОЛЬЗОВАТЕЛЬ НЕ НАЙДЕН ИЛИ ПРОИЗОШЛА ОШИБКА ПРИ ПОПЫТКЕ ЕГО УДАЛИТЬ.</strong>');
		else
			exit(header('location: index.php?service=users.admin'));
	}
	
	/*
	 * В зависимости от уровня доступа предоставлять различные возможности
	 */
	if (permission_check($_SESSION['permissions'], PERMISSION::administrator))
		$where_selector = "1";
	elseif (permission_check($_SESSION['permissions'], PERMISSION::teacher))
		$where_selector = "`teacherId` = '" . $_SESSION["uid"] . "'";
	
	/*
	 * Скрипт, отвечающий за распределение списка по страницам
	 */
	if (!isset($_GET['page']))
		$_GET['page'] = 1;
	
	(int)$_GET['page']>0 or die('<strong>Попытка ввода SQL инъекции заблокирована.</strong>');
	
	if (!$db_result = $db->query("SELECT count(*) AS us_count FROM `spm_users` WHERE " . $where_selector . ";"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
	$total_articles_number = (int)($db_result->fetch_assoc()["us_count"]);
	$articles_per_page = 10;
	$current_page = (int)$_GET['page'];
	
	if ($total_articles_number > 0 && $articles_per_page > 0)
		$total_pages = ceil($total_articles_number / $articles_per_page);
	else
		$total_pages = 1;
	
	if ($current_page > $total_pages)
		$current_page = 1;
	
	if (!$db_result = $db->query("SELECT * FROM `spm_users` WHERE " . $where_selector . " ORDER BY `id` LIMIT " . ($current_page * $articles_per_page - $articles_per_page) . " , " . $articles_per_page . ";"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
	SPM_header("Пользователи системы", "Управление");
?>

<div class="table-responsive">
	<table class="table table-bordered table-hover" style="background-color: white;">
		<thead>
			<tr>
				<th width="10%">ID</th>
				<th width="33%">Ф.И.О.</th>
				<th width="20%">Логин</th>
				<th width="10%">Группа</th>
				<th width="10%">Учитель</th>
				<th width="8%">Доступ</th>
				<th width="9%"></th>
			</tr>
		</thead>
		<tbody>
<?php if ($total_articles_number == 0 || $db_result->num_rows === 0): ?>
			<tr>
				<td></td>
				<td><b>Ни одного пользователя не найдено! Используйте TeacherID, чтобы пригласить новых пользователей в систему!</b></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
<?php else: ?>
			<?php while ($user = $db_result->fetch_assoc()): ?>
			<tr>
				<td><?=$user['id']?></td>
				<td>
					<a href="index.php?service=user&id=<?=$user['id']?>">
						<?=$user['secondname'] . " " . $user['firstname'] . " " . $user['thirdname']?>
					</a>
				</td>
				<td><?=$user['username']?></td>
				<td><?=$user['group']?></td>
				<td><?php if ($user['teacherId']>0) print("<a href='index.php?service=user&id=" . $user['teacherId'] . "'>ID_" . $user['teacherId'] . "</a>"); else print("Тёмная сторона Силы"); ?></td>
				<td><?=$user['permissions']?></td>
				<td>
					<div class="btn-group">
						<a
							class="btn btn-warning btn-sm"
							href="index.php?service=user.edit&id=<?=$user['id']?>"
						><span class="fa fa-pencil"></span></a>
						<a
							class="btn btn-danger btn-sm"
							href="index.php?service=users.admin&del=<?=$user['id']?>"
							onclick="return confirm('Вы действительно хотите удалить этого пользователя? Это действие не обратимо!');"
						><span class="fa fa-trash"></span></a>
					</div>
				</td>
			</tr>
			<?php endwhile; ?>
<?php endif; ?>
		</tbody>
	</table>
</div>
<?php include(_S_MOD_ . "pagination.php"); generatePagination($total_pages, $current_page, 4, "users.admin"); ?>
<?php SPM_footer(); ?>