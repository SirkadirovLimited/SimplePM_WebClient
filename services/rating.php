<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::student);
	
	//SORT BY TYPES
	$_SORT_BY["id"] = "id";
	$_SORT_BY["username"] = "username";
	$_SORT_BY["secondname"] = "secondname";
	$_SORT_BY["group"] = "group";
	$_SORT_BY["bcount"] = "bcount";
	$_SORT_BY["rating"] = "rating";
	
	//SORT TYPES
	$_SORT["asc"] = "asc";
	$_SORT["desc"] = "desc";
	
	//SECURITY
	isset($_GET['page']) or $_GET['page'] = 1;
	(int)$_GET['page'] > 0 or $_GET['page']=1;
	isset($_GET["query"]) or $_GET["query"]="";
	
	isset($_GET["sortby"]) && isset($_SORT_BY[$_GET["sortby"]]) or $_GET["sortby"] = $_SORT_BY["rating"];
	$_GET["sortby"] = $_SORT_BY[$_GET["sortby"]];
	
	isset($_GET["sort"]) && isset($_SORT[$_GET["sort"]]) or $_GET["sort"] = $_SORT["desc"];
	$_GET["sort"] = $_SORT[$_GET["sort"]];
	
	
	if (!$db_result = $db->query("SELECT count(id) FROM `spm_users`"))
		die(header('location: index.php?service=error&err=db_error'));
	
	$total_articles_number = (int)($db_result->fetch_array()[0]);
	$articles_per_page = $_SPM_CONF["SERVICES"]["rating"]["articles_per_page"];
	$current_page = (int)$_GET['page'];
	
	$db_result->free();
	
	if ($total_articles_number > 0 && $articles_per_page > 0)
		$total_pages = ceil($total_articles_number / $articles_per_page);
	else
		$total_pages = 1;
	
	if ($current_page > $total_pages)
		$current_page = 1;
	
	//SQL queries and formatting
	if (!$db_result = $db->query("SELECT `id`,`firstname`,`secondname`,`thirdname`,`username`,`group`,`rating`,`bcount` FROM `spm_users` ORDER BY `" . $_GET["sortby"] . "` " . $_GET["sort"] . " LIMIT " . ($current_page * $articles_per_page - $articles_per_page) . " , " . $articles_per_page . ";"))
		die(header('location: index.php?service=error&err=db_error'));

	SPM_header("Рейтинг учащихся", "Глобальный");
	
	/*
	 * FUNCTIONS
	 */
	function generate_sort_url($page = 1, $sortby = "", $sort = ""){
		($sortby != "") or $sortby = $_GET["sortby"];
		($sort != "") or $sort = $_GET["sort"];
		
		return "index.php?service=rating&page=" . $page . "&query=" . $_GET["query"] . "&sortby=" . $sortby . "&sort=" . $sort;
	}
?>
<!--SEARCH-->
		<div class="row">
			<div class="col-md-12">
				<form action="" method="get">
					<select class="form-control" name="category" required>
						<option value="0" selected>Все группы</option>
					</select>
					<input type="submit" class="btn btn-primary btn-block btn-flat" name="categoryCmd" value="Применить">
				</form>
			</div>
			<div class="col-md-9">
			</div>
		</div>
<!--PROBLEMS LIST-->
<?php if ($total_articles_number == 0 || $db_result->num_rows == 0): ?>
		<div align="center">
			<h3>Пользователей не найдено!</h3>
			<p class="lead">По вашему запросу пользователей не найдено! Попробуйте ввести другой поисковый запрос.</p>
		</div>
<?php else: ?>
		<div class="table-responsive" style="margin: 0; width: 100%;">
			<table class="table table-hover" style="background-color: white; margin: 0;">
				<thead>
					<tr>
						<th width="10%">
							ID&nbsp;
							<small>
								<a href="<?=generate_sort_url(1, $_SORT_BY['id'], $_SORT['asc'])?>"><i class="fa fa-caret-square-o-down"></i></a>
								<a href="<?=generate_sort_url(1, $_SORT_BY['id'], $_SORT['desc'])?>"><i class="fa fa-caret-square-o-up"></i></a>
							</small>
						</th>
						<th width="20%">
							Имя пользователя&nbsp;
							<small>
								<a href="<?=generate_sort_url(1, $_SORT_BY['username'], $_SORT['asc'])?>"><i class="fa fa-caret-square-o-down"></i></a>
								<a href="<?=generate_sort_url(1, $_SORT_BY['username'], $_SORT['desc'])?>"><i class="fa fa-caret-square-o-up"></i></a>
							</small>
						</th>
						<th width="35%">
							Полное имя&nbsp;
							<small>
								<a href="<?=generate_sort_url(1, $_SORT_BY['secondname'], $_SORT['asc'])?>"><i class="fa fa-caret-square-o-down"></i></a>
								<a href="<?=generate_sort_url(1, $_SORT_BY['secondname'], $_SORT['desc'])?>"><i class="fa fa-caret-square-o-up"></i></a>
							</small>
						</th>
						<th width="15%">
							GROUP&nbsp;
							<small>
								<a href="<?=generate_sort_url(1, $_SORT_BY["group"], $_SORT['asc'])?>"><i class="fa fa-caret-square-o-down"></i></a>
								<a href="<?=generate_sort_url(1, $_SORT_BY["group"], $_SORT['desc'])?>"><i class="fa fa-caret-square-o-up"></i></a>
							</small>
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
						if (!$query_group = $db->query("SELECT `name` FROM `spm_users_groups` WHERE `id` = '" . $user['group'] . "' LIMIT 1;"))
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
							Страница <?=$_GET["page"]?> из <?=$total_pages?>
						</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
			</table>
		</div>
<?php endif;?>

<?php include(_S_MOD_ . "pagination.php"); generatePagination($total_pages, $current_page, 4, "rating", "&sortby=" . $_GET["sortby"] . "&sort=" . $_GET["sort"]); ?>
<?php SPM_footer(); ?>