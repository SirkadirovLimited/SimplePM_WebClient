<?php
	
	//Перевірка на дозвіл візиту
	deniedOrAllowed(
		PERMISSION::student | 
		PERMISSION::teacher | 
		PERMISSION::administrator
	);
	
	///////////////////////////////////////
	/// SORT BY типи сортування
	///////////////////////////////////////
	
	//Стовпчики які можуть бути відсортовані
	$_SORT_BY["username"] = "username";
	$_SORT_BY["secondname"] = "secondname";
	$_SORT_BY["bcount"] = "bcount";
	$_SORT_BY["rating"] = "rating";
	
	///////////////////////////////////////
	/// SORT типи сортування
	///////////////////////////////////////
	$_SORT["asc"] = "asc";
	$_SORT["desc"] = "desc";
	
	///////////////////////////////////////
	/// ПЕРЕВІРКИ БЕЗПЕКИ
	///////////////////////////////////////
	
	//Перевірка на вказання сторінки
	isset($_GET['page']) or $_GET['page'] = 1;
	(int)$_GET['page'] > 0 or $_GET['page'] = 1;

	//Перевірка на вказання запиту
	isset($_GET["query"]) or $_GET["query"] = "";
	
	//Категорія
	if (isset($_GET['category']) && (int)$_GET['category'] > 0)
		$category = " AND `group` = '" . (int)$_GET['category'] . "' ";
	else
	{

		//Для формування запиту до БД
		$category = "";

		//Для пагінації
		$_GET['category'] = "";

	}
	
	//Query
	$_GET["query"] = $db->real_escape_string(htmlspecialchars(strip_tags(trim($_GET["query"]))));
	
	//Стовпчик сортування
	isset($_GET["sortby"]) && isset($_SORT_BY[$_GET["sortby"]]) or $_GET["sortby"] = $_SORT_BY["rating"];
	$_GET["sortby"] = $_SORT_BY[$_GET["sortby"]];
	
	//Метод сортування
	isset($_GET["sort"]) && isset($_SORT[$_GET["sort"]]) or $_GET["sort"] = $_SORT["desc"];
	$_GET["sort"] = $_SORT[$_GET["sort"]];
	
	///////////////////////////////////////
	/// SQL queries and formatting
	///////////////////////////////////////
	
	//Записів на сторінку
	$articles_per_page = $_SPM_CONF["SERVICES"]["rating"]["articles_per_page"];
	//Номер цієї сторінки
	$current_page = (int)$_GET['page'];
	
	//Формуємо запит на вибірку даних з БД
	$query_str = "
		SELECT
			`id`,
			`firstname`,
			`secondname`,
			`thirdname`,
			`username`,
			`group`,
			`rating`,
			`bcount`
		FROM
			`spm_users`
		WHERE
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
			" . $category . "
		ORDER BY
			`" . $_GET["sortby"] . "` " . $_GET["sort"] . "
		LIMIT
			" . ($current_page * $articles_per_page - $articles_per_page) . " , " . $articles_per_page . "
		;
	";
	
	//Виконання запиту до бази даних
	if (!$db_result = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	//Усього записів
	$total_articles_number = (int)($db_result->num_rows);
	
	//Усього сторінок
	$total_pages = ($total_articles_number > 0 && $articles_per_page > 0) ? ceil($total_articles_number / $articles_per_page) : 1;
	
	//Перевірка безпеки
	if ($current_page > $total_pages)
		$current_page = 1;
	
	///////////////////////////////////////
	/// Генерація заголовку
	///////////////////////////////////////
	
	SPM_header("Учнівський рейтинг");
	
	/*
	 * Функції, що потрібні для роботи функціоналу сервісу
	 */
	function generate_sort_url($page = 1, $sortby = "", $sort = ""){
		($sortby != "") or $sortby = $_GET["sortby"];
		($sort != "") or $sort = $_GET["sort"];
		
		return "index.php?service=rating&page=" . $page . "&query=" . $_GET["query"] . "&sortby=" . $sortby . "&sort=" . $sort;
	}

	///////////////////////////////////////

?>
<!--SEARCH-->
<div class="row">

	<div class="col-md-12">

		<form method="get">

			<input type="hidden" name="service" value="rating">
			
			<div class="row-fluid">

				<div class="col-md-4" style="margin: 0; padding: 0;">
					
					<select class="form-control" name="category" required>
						
						<option value="0" selected>Усі групи</option>
						
						<?php
							
							if (permission_check($_SESSION['permissions'], PERMISSION::administrator))
								$_query_mod_1 = '0';
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
								;
							";
							
							if (!$sub_query = $db->query($query_str))
								die('Database connection error!');
							
							while ($group = $sub_query->fetch_assoc()):
							
						?>

						<option value="<?=$group['id']?>"><?=$group['name']?></option>
						
						<?php endwhile; $sub_query->free(); ?>
						
					</select>

				</div>
				<div class="col-md-8" style="margin: 0; padding: 0;">
					<input type="text" class="form-control" name="query" placeholder="Пошук" value="<?=$_GET['query']?>">
				</div>

			</div>

			<button type="submit" class="btn btn-primary btn-block btn-flat">Знайти</button>

		</form>

	</div>

</div>
<!--PROBLEMS LIST-->
<?php if ($total_articles_number == 0): ?>
		<div align="center">
			<h3>Користувачів не знайдено!</h3>
			<p class="lead">За вашим запитом користувачів не знайдено. Будь ласка, сформулюйте інший пошуковий запит.</p>
		</div>
<?php else: ?>
		<div class="table-responsive" style="margin: 0; width: 100%;">
			<table class="table table-hover" style="background-color: white; margin: 0;">
				<thead>
					<tr>
						<th width="10%">
							ID
						</th>
						<th width="20%">
							Ім'я користувача&nbsp;
							<small>
								<a href="<?=generate_sort_url(1, $_SORT_BY['username'], $_SORT['asc'])?>"><i class="fa fa-caret-square-o-down"></i></a>
								<a href="<?=generate_sort_url(1, $_SORT_BY['username'], $_SORT['desc'])?>"><i class="fa fa-caret-square-o-up"></i></a>
							</small>
						</th>
						<th width="35%">
							Повне ім'я&nbsp;
							<small>
								<a href="<?=generate_sort_url(1, $_SORT_BY['secondname'], $_SORT['asc'])?>"><i class="fa fa-caret-square-o-down"></i></a>
								<a href="<?=generate_sort_url(1, $_SORT_BY['secondname'], $_SORT['desc'])?>"><i class="fa fa-caret-square-o-up"></i></a>
							</small>
						</th>
						<th width="15%">
							Група
						</th>
						<th width="10%">
							B&nbsp;
							<small>
								<a href="<?=generate_sort_url(1, $_SORT_BY["bcount"], $_SORT['asc'])?>"><i class="fa fa-caret-square-o-down"></i></a>
								<a href="<?=generate_sort_url(1, $_SORT_BY["bcount"], $_SORT['desc'])?>"><i class="fa fa-caret-square-o-up"></i></a>
							</small>
						</th>
						<th width="10%">
							R&nbsp;
							<small>
								<a href="<?=generate_sort_url(1, $_SORT_BY["rating"], $_SORT['asc'])?>"><i class="fa fa-caret-square-o-down"></i></a>
								<a href="<?=generate_sort_url(1, $_SORT_BY["rating"], $_SORT['desc'])?>"><i class="fa fa-caret-square-o-up"></i></a>
							</small>
						</th>
					</tr>
				</thead>
				<tbody>
<?php while ($user = $db_result->fetch_assoc()): ?>
<?php
			if ($user["bcount"] == null)
				$user["bcount"] = 0;
			if ($user["rating"] == null)
				$user["rating"] = 0;
?>
					<?php
						$query_str = "
							SELECT
								`name`
							FROM
								`spm_users_groups`
							WHERE
								`id` = '" . $user['group'] . "'
							LIMIT
								1
							;
						";
						
						if (!$query_group = $db->query($query_str))
							die(header('location: index.php?service=error&err=db_error'));
						
						$user['group_name'] = @$query_group->fetch_assoc()['name'];
						$query_group->free();
					?>
					<tr>
						<td><?=$user["id"]?></td>
						<td><a href="index.php?service=user&id=<?=$user["id"]?>"><?=$user["username"]?></a></td>
						<td><a href="index.php?service=user&id=<?=$user["id"]?>"><?=$user["secondname"] . " " . $user["firstname"] . " " . $user["thirdname"]?></a></td>
						<td><?=$user['group_name']?></td>
						<td><?=$user["bcount"]?></td>
						<td><?=$user["rating"]?></td>
					</tr>
<?php endwhile; ?>
				</tbody>
				<thead>
					<tr>
						<th></th>
						<th>
							Сторінка <?=$_GET["page"]?> з <?=$total_pages?>
						</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
			</table>
		</div>
<?php endif;?>

<?php
	
	//Інклудимо модуль пагінації
	include(_S_MOD_ . "pagination.php");
	
	//Генеруємо пагінацію
	generatePagination(
		$total_pages,
		$current_page,
		4,
		"rating",
		"&query=" . $_GET["query"] . "&sortby=" . $_GET["sortby"] . "&sort=" . $_GET["sort"] . "&category=" . (int)$_GET['category']
	);
	
	//Генеруємо футер
	SPM_footer();
	
?>
