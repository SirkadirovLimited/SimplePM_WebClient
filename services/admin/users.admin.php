<?php
	deniedOrAllowed(PERMISSION::teacher | PERMISSION::administrator);
	
	/* Security checks (Vol. 1) */
	isset($_GET['country']) or $_GET['country'] = "%";
	isset($_GET['query']) or $_GET['query'] = "%";
	isset($_GET['school']) or $_GET['school'] = "%";
	isset($_GET['city']) or $_GET['city'] = "%";
	
	/* Security checks (Vol. 2) */
	isset($_GET['group']) && (int)$_GET['group'] >= 0 or $_GET['group'] = "%";
	isset($_GET['tId']) && (int)$_GET['tId'] > 0 or $_GET['tId'] = "%";
	
	/* Security checks (Vol. 3) */
	$_GET['country'] = mysqli_real_escape_string($db, strip_tags(trim($_GET['country'])));
	$_GET['query'] = mysqli_real_escape_string($db, strip_tags(trim($_GET['query'])));
	$_GET['school'] = mysqli_real_escape_string($db, strip_tags(trim($_GET['school'])));
	$_GET['city'] = mysqli_real_escape_string($db, strip_tags(trim($_GET['city'])));
	
	/*
	 * УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЕЙ
	 */
	if (isset($_GET['del']) && $_GET['del'] > 1){
		
		if ((int)$_GET['del'] == $_SESSION['uid'])
			die(header('location: index.php?service=error&err=403'));
		
		if (!permission_check($_SESSION['permissions'], PERMISSION::administrator)){
			
			$query_str = "
				SELECT
					`teacherId`
				FROM
					`spm_users`
				WHERE
					`id` = " . (int)$_GET['del'] . "
				LIMIT
					1
				;
			";
			
			if (!$user_query = $db->query($query_str))
				die(header('location: index.php?service=error&err=db_error'));
			
			if($user_query->num_rows == 0)
				die(header('location: index.php?service=error&err=404'));
			
			if ($user_query->fetch_array()[0] != $_SESSION["uid"])
				die(header('location: index.php?service=error&err=403'));
			
			$user_query->free();
			
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
		
		$query_str_3 = "
			DELETE FROM
				`spm_submissions`
			WHERE
				`userId` = '" . (int)$_GET['del'] . "'
			;
		";
		
		if (!$db->query($query_str) || !$db->query($query_str_2) || !$db->query($query_str_3))
			die(header('location: index.php?service=error&err=404'));
		
		exit(header('location: index.php?service=users.admin'));
		
	}
	
	/*
	 * В зависимости от уровня доступа предоставлять различные возможности
	 */
	if (permission_check($_SESSION['permissions'], PERMISSION::teacher))
		$_GET['tId'] = $_SESSION['uid'];
	
	$where_selector = "
		`teacherId` LIKE '" . $_GET['tId'] . "'
	AND
		`groupid` LIKE '" . $_GET['group'] . "'
	
	AND
	
		`country` LIKE '%" . $_GET['country'] . "%'
	AND
		`city` LIKE '%" . $_GET['city'] . "%'
	AND
		`school` LIKE '%" . $_GET['school'] . "%'
	AND
		
		(
			CONCAT(`secondname`, ' ', `firstname`, ' ', `thirdname`) LIKE '%" . $_GET["query"] . "%'
		OR
			CONCAT(`firstname`, ' ', `thirdname`) LIKE '%" . $_GET["query"] . "%'
		OR
			CONCAT(`secondname`, ' ', `firstname`) LIKE '%" . $_GET["query"] . "%'
		OR
			`secondname` = '" . $_GET["query"] . "'
		OR
			`firstname` = '" . $_GET["query"] . "'
		OR
			`thirdname` = '" . $_GET["query"] . "'
		OR
			`username` LIKE '%" . $_GET["query"] . "%'
		OR
			`email` LIKE '%" . $_GET["query"] . "%'
		)
		
	";
	
	/*
	 * Скрипт, отвечающий за распределение списка по страницам
	 */
	if (!isset($_GET['page']) || (int)$_GET['page'] <= 0)
		$_GET['page'] = 1;
	
	$query_str = "
		SELECT
			count(`id`)
		FROM
			`spm_users`
		WHERE
			" . $where_selector . "
		;
	";
	
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/* Paging math */
	
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
			`id`,
			`username`,
			`groupid`,
			`firstname`,
			`secondname`,
			`thirdname`,
			`teacherId`,
			`permissions`
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
	
	/* Header */
	SPM_header("Користувачі системи", "Управління");
?>

<style>
	.row div
	{
		
		padding-left: 5px;
		padding-right: 5px;
		
	}
</style>

<div class="panel panel-default" style="border-radius: 0; padding-left: 10px; padding-right: 10px;">
	<div class="panel-body" style="padding: 10px;">
		
		<form action="" method="get" style="margin: 0;">
			
			<input type="hidden" name="service" value="users.admin">
			
			<div class="row">
				
				<div class="col-md-3">
					
					<select class="form-control" name="group">
						
						<option value="-1">Вибрати групу</option>
						
						<?php
							if (permission_check($_SESSION['permissions'], PERMISSION::administrator)){
								$_query_mod_1 = '0';
								$_query_mod_2 = " OR `teacherId` = '" . $_SESSION['uid'] . "'";
							}
							elseif (permission_check($_SESSION['permissions'], PERMISSION::teacher))
								$_query_mod_1 = $_SESSION['uid'];
							else
								$_query_mod_1 = $_SESSION['teacherId'];
							
							$query_str = "
								SELECT
									`id`,
									`name`
								FROM
									`spm_users_groups`
								WHERE
									`teacherId` = '" . $_query_mod_1 . "'
									" . @$_query_mod_2 . "
								;
							";
							
							if (!$sub_query = $db->query($query_str))
								die('Database connection error!');
							
							while ($group = $sub_query->fetch_assoc()):
							$selectedGroup = ($_GET['group'] == $group['id'] ? " selected" : "");
						?>
						<option value="<?=$group['id']?>"<?=$selectedGroup?>><?=$group['name']?></option>
						<?php
							
							endwhile;
							
							$sub_query->free();
							
						?>
						
					</select>
					
				</div>
				
				<div class="col-md-6">
					
					<input type="text" class="form-control" name="query" placeholder="Пошук" value="<?=$_GET['query']?>">
					
				</div>
				
				<div class="col-md-3">
					
					<select class="form-control" name="country">
						
						<option value="">Країна / Регіон</option>
						
						<?php
							foreach ($SPM_Countries_Select as $countryArr):
							$selectedCountry = ($_GET['country'] == $countryArr[0] ? " selected" : "");
						?>
						
						<option value="<?=$countryArr[0]?>"<?=$selectedCountry?>><?=$countryArr[1]?></option>
						
						<?php endforeach; ?>
						
					</select>
					
				</div>
				
			</div>
			
			<div class="row" style="margin-top: 10px;">
				
				<div class="col-md-6">
					
					<select class="form-control" name="tId" <?=(permission_check($_SESSION['permissions'], PERMISSION::teacher) ? 'disabled' : '')?>>
						
						<option value="0">Вибрати вчителя</option>
						
						<?php if (permission_check($_SESSION['permissions'], PERMISSION::administrator)): ?>
						
						<?php
							
							$query_str = "
								SELECT
									`id`,
									`firstname`,
									`secondname`,
									`thirdname`
								FROM
									`spm_users`
								WHERE
									`teacherId` = '" . $_SESSION['uid'] . "'
									" . @$_query_mod_2 . "
								;
							";
							
							if (!$sub_query = $db->query($query_str))
								die('Database connection error!');
							
							while ($teacher = $sub_query->fetch_assoc()):
							$selectedTeacher = ($_GET['tId'] == $teacher['id'] ? " selected" : "");
						?>
						<option value="<?=$teacher['id']?>"<?=$selectedTeacher?>>
							<?=$teacher['secondname'] . ' ' . $teacher['firstname'] . ' ' . $teacher['thirdname']?>
						</option>
						<?php
							
							endwhile;
							
							$sub_query->free();
							
						?>
						
						<?php endif; ?>
						
					</select>
					
				</div>
				
				<div class="col-md-3">
					
					<input type="text" class="form-control" name="school" value="<?=str_replace("%", "", $_GET['school'])?>" placeholder="Навчальний заклад">
					
				</div>
				
				<div class="col-md-3">
					
					<input type="text" class="form-control" name="city" value="<?=str_replace("%", "", $_GET['city'])?>" placeholder="Місто / Населений пункт">
					
				</div>
				
			</div>
			
			<div class="row">
				
				<div class="col-md-12">
					
					<button
						class="btn btn-primary btn-block btn-flat"
						style="margin-top: 10px;"
						type="submit"
					>Відобразити</button>
					
				</div>
				
			</div>
			
		</form>
		
	</div>
</div>

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
<?php if ($total_articles_number == 0 || $db_result->num_rows == 0): ?>
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
				<td><?=spm_getUserGroupByID($user['groupid'])?></td>
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
<?php
	
	/* Pagination */
	include(_S_MOD_ . "pagination.php");
	generatePagination(
		$total_pages,
		$current_page,
		4,
		"users.admin",
		"&group=" . $_GET['group']
			. "&query=" . $_GET['query']
			. "&country=" . $_GET['country']
			. "&tId=" . $_GET['tId']
			. "&school=" . $_GET['school']
			. "&city=" . $_GET['city']
	);
	
	/* Footer */
	SPM_footer();
	
?>