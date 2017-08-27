<?php
	deniedOrAllowed(PERMISSION::teacher | PERMISSION::administrator);
	
	/*
	 * УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЕЙ
	 */
	if (isset($_GET['del']) && $_GET['del'] > 1){
		
		if ((int)$_GET['del'] == $_SESSION['uid'])
			die(header('location: index.php?service=error&err=403'));
		
		if (!permission_check($_SESSION['permissions'], PERMISSION::administrator)){
			
			if (!$user_query = $db->query("SELECT * FROM `spm_users` WHERE `id` = " . (int)$_GET['del'] . ""))
				die(header('location: index.php?service=error&err=db_error'));
			
			if($user_query->num_rows === 0)
				die(header('location: index.php?service=error&err=404'));
			
			$users_admin_user = $user_query->fetch_assoc();
			$user_query->free();
			
			if ($users_admin_user["teacherId"] != $_SESSION["uid"])
				die(header('location: index.php?service=error&err=403'));
			
		}
		
		$query_str = "
			DELETE FROM
				`spm_users`
			WHERE
				`id` = '" . (int)$_GET['del'] . "'
			LIMIT
				1
			;
		";
		
		$query_str_2 = "
			DELETE FROM
				`spm_teacherid`
			WHERE
				`userId` = '" . (int)$_GET['del'] . "'
			LIMIT
				1
			;
		";
		
		if (!$db->query($query_str) || !$db->query($query_str_2))
			die(header('location: index.php?service=error&err=404'));
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
	
	(int)$_GET['page']>0
		or die(header('location: index.php?service=error&err=403'));
	
	if (!$db_result = $db->query("SELECT count(*) FROM `spm_users` WHERE " . $where_selector . ";"))
		die(header('location: index.php?service=error&err=db_error'));
	
	$total_articles_number = (int)($db_result->fetch_array()[0]);
	$articles_per_page = 10;
	$current_page = (int)$_GET['page'];
	
	if ($total_articles_number > 0 && $articles_per_page > 0)
		$total_pages = ceil($total_articles_number / $articles_per_page);
	else
		$total_pages = 1;
	
	if ($current_page > $total_pages)
		$current_page = 1;
	
	$query_str = "
		SELECT
			*
		FROM
			`spm_users`
		WHERE
			" . $where_selector . "
		ORDER BY
			`id` ASC
		LIMIT
			" . ($current_page * $articles_per_page - $articles_per_page) . " , " . $articles_per_page . "
		;
	";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	SPM_header("Користувачі системи", "Управління");
?>

<div align="right" style="margin-bottom: 10px;">
	<a href="index.php?service=groups.admin" class="btn btn-success btn-flat">Групи користувачів</a>
</div>

<div class="table-responsive">
	<table class="table table-bordered table-hover" style="background-color: white;">
		<thead>
			<tr>
				<th width="10%">ID</th>
				<th width="33%">Повне ім'я</th>
				<th width="20%">Логін</th>
				<th width="10%">Група</th>
				<th width="10%">Вчитель</th>
				<th width="8%">Доступ</th>
				<th width="9%"></th>
			</tr>
		</thead>
		<tbody>
<?php if ($total_articles_number == 0 || $db_result->num_rows === 0): ?>
			<tr>
				<td></td>
				<td><b>Користувачів за вашим запитом не знайдено!</b></td>
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
				<td><?=spm_getUserGroupByID($user['group'])?></td>
				<td><a href="index.php?service=user&id=<?=$user['teacherId']?>"><?=spm_getUserFullnameByID($user['teacherId'])?></a></td>
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
							onclick="return confirm('Ця дія потребує підтверждення, бо є незворотньою!');"
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