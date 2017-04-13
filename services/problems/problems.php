<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::student);
	
	//SORT BY TYPES
	$_SORT_BY["id"] = "id";
	$_SORT_BY["name"] = "name";
	$_SORT_BY["b"] = "difficulty";
	
	//SORT TYPES
	$_SORT["asc"] = "asc";
	$_SORT["desc"] = "desc";
	
	//SECURITY
	isset($_GET['page']) or $_GET['page'] = 1;
	(int)$_GET['page'] > 0 or $_GET['page']=1;
	isset($_GET["query"]) or $_GET["query"]="";
	
	isset($_GET["sortby"]) && isset($_SORT_BY[$_GET["sortby"]]) or $_GET["sortby"] = $_SORT_BY["id"];
	$_GET["sortby"] = $_SORT_BY[$_GET["sortby"]];
	
	isset($_GET["sort"]) && isset($_SORT[$_GET["sort"]]) or $_GET["sort"] = $_SORT["asc"];
	$_GET["sort"] = $_SORT[$_GET["sort"]];
	
	
	if (!$db_result = $db->query("SELECT count(id) AS problems_count FROM `spm_problems`"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
	$total_articles_number = (int)($db_result->fetch_assoc()["problems_count"]);
	$articles_per_page = $_SPM_CONF["SERVICES"]["problems"]["articles_per_page"];
	$current_page = (int)$_GET['page'];
	
	$db_result->free();
	unset($db_result);
	
	if ($total_articles_number > 0 && $articles_per_page > 0)
		$total_pages = ceil($total_articles_number / $articles_per_page);
	else
		$total_pages = 1;
	
	if ($current_page > $total_pages)
		$current_page = 1;
	
	//SQL queries and formatting
	if (!$db_result = $db->query("SELECT `id`,`difficulty`,`catId`,`subCatId`,`name` FROM `spm_problems` ORDER BY `" . $_GET["sortby"] . "` " . $_GET["sort"] . " LIMIT " . ($current_page * $articles_per_page - $articles_per_page) . " , " . $articles_per_page . ";"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
	SPM_header("Архив задач");
	
	/*
	 * FUNCTIONS
	 */
	function generate_sort_url($page = 1, $sortby = "", $sort = ""){
		($sortby != "") or $sortby = $_GET["sortby"];
		($sort != "") or $sort = $_GET["sort"];
		
		return "index.php?service=problems&page=" . $page . "&query=" . $_GET["query"] . "&sortby=" . $sortby . "&sort=" . $sort;
	}
?>
<!--SEARCH-->
		<!--div class="row">
			<div class="col-md-3">
				<form action="" mathod="post">
					<select class="form-control" name="difficulty" required>
						<option value="0" selected>Сложность 0% и выше</option>
						<option value="5">Сложность 5% и выше</option>
						<option value="10">Сложность 10% и выше</option>
						<option value="20">Сложность 20% и выше</option>
						<option value="50">Сложность 50% и выше</option>
					</select>
					<input type="submit" class="btn btn-primary btn-block btn-flat" name="difficultyCmd" value="Применить">
				</form>
			</div>
			<div class="col-md-6">
				<form action="" mathod="post">
					<input class="form-control" name="searchText" placeholder="№ задачи / название задачи" value="<?php print($_GET['query']); ?>">
					<input type="submit" class="btn btn-success btn-block btn-flat" name="searchCmd" value="Поиск">
				</form>
			</div>
			<div class="col-md-3">
				<form action="" method="get">
					<select class="form-control" name="category" required>
						<option value="0" selected>Все темы и подтемы</option>
<?php
	if(!$db_result_cat = $db->query("SELECT * FROM `spm_problems_categories` WHERE `parentId` = '0'"))
		die('Ошибка при попытке подключения к базе данных, попробуйте выполнить ваш запрос позже!');
	
	while ($problem_cat = $db_result_cat->fetch_assoc()){
?>
						<optgroup label="<?php print($problem_cat["name"]); ?>">
							<option value="<?php print($problem_cat["id"]); ?>"><?php print($problem_cat["name"]); ?> (все задачи)</option>
<?php
		if(!$db_result_subcat = $db->query("SELECT * FROM `spm_problems_categories` WHERE `parentId` = '" . $problem_cat['id'] . "';"))
			die('Ошибка при попытке подключения к базе данных, попробуйте выполнить ваш запрос позже!');
	
		while ($problem_subcat = $db_result_subcat->fetch_assoc()){
?>
							<option value="<?php print($problem_subcat["id"]); ?>"><?php print($problem_subcat["name"]); ?></option>
<?php
		}
?>
						</optgroup>
<?php
	}
	unset($problem_cat);
	unset($db_result_cat);
?>
					</select>
					<input type="submit" class="btn btn-primary btn-block btn-flat" name="categoryCmd" value="Применить">
				</form>
			</div>
		</div-->
<!--PROBLEMS LIST-->
<?php
	if ($total_articles_number == 0 || $db_result->num_rows == 0){
?>
		<div align="center">
			<h3>Задач не найдено</h3>
			<p class="lead">По вашему запросу задач не найдено! Попробуйте ввести другой поисковый запрос.</p>
		</div>
<?php
	}else{
?>
		<div class="table-responsive" style="margin: 0;">
			<table class="table table-hover" style="background-color: white; margin: 0;">
				<thead>
					<tr>
						<th width="10%">
							ID&nbsp;
							<small>
								<a href="<?php print(generate_sort_url(1, $_SORT_BY['id'], $_SORT['asc'])); ?>"><i class="fa fa-caret-square-o-down"></i></a>
								<a href="<?php print(generate_sort_url(1, $_SORT_BY['id'], $_SORT['desc'])); ?>"><i class="fa fa-caret-square-o-up"></i></a>
							</small>
						</th>
						<th width="40%">
							Название задачи&nbsp;
							<small>
								<a href="<?php print(generate_sort_url(1, $_SORT_BY['name'], $_SORT['asc'])); ?>"><i class="fa fa-caret-square-o-down"></i></a>
								<a href="<?php print(generate_sort_url(1, $_SORT_BY['name'], $_SORT['desc'])); ?>"><i class="fa fa-caret-square-o-up"></i></a>
							</small>
						</th>
						<th width="30%">Категория</th>
						<th width="10%">Решаемость</th>
						<th width="10%">
							B&nbsp;
							<small>
								<a href="<?php print(generate_sort_url(1, $_SORT_BY["b"], $_SORT['asc'])); ?>"><i class="fa fa-caret-square-o-down"></i></a>
								<a href="<?php print(generate_sort_url(1, $_SORT_BY["b"], $_SORT['desc'])); ?>"><i class="fa fa-caret-square-o-up"></i></a>
							</small>
						</th>
					</tr>
				</thead>
				<tbody>
<?php
		while ($problem = $db_result->fetch_assoc()) {
			//category
			if (!$db_res_cat = $db->query("SELECT `name` FROM `spm_problems_categories` WHERE `id`='" . $problem['catId'] . "' LIMIT 1;"))
				die('Ошибка при подключении к базе данных. Перезагрузите страницу!');
			elseif ($db_res_cat->num_rows == 0){
				$cat_name = "Все задачи";
				$db_res_cat->free();
				unset($db_res_cat);
			} else {
				$cat_name = $db_res_cat->fetch_assoc()["name"];
				$db_res_cat->free();
				unset($db_res_cat);
			}
			//subcategory
			if (!$db_res_sub_cat = $db->query("SELECT `name` FROM `spm_problems_categories` WHERE `id`='" . $problem['subCatId'] . "' LIMIT 1;"))
				die('Ошибка при подключении к базе данных. Перезагрузите страницу!');
			elseif ($db_res_sub_cat->num_rows == 0){
				$sub_cat_name = "Все задачи";
				$db_res_sub_cat->free();
				unset($db_res_cat);
			} else {
				$sub_cat_name = $db_res_sub_cat->fetch_assoc()["name"];
				$db_res_sub_cat->free();
				unset($db_res_sub_cat);
			}
			//submissionInfo
			$submissionQuery = "SELECT `b` FROM `spm_submissions` WHERE (
									`userId` = '" . $_SESSION['uid'] . "' AND 
									`problemId` = '" . $problem['id'] . "'
								) ORDER BY `submissionId` DESC LIMIT 1;";
			if (!$submissionInfoLink = $db->query($submissionQuery))
				die('Ошибка при подключении к базе данных. Перезагрузите страницу!');
			elseif ($submissionInfoLink->num_rows == 0)
				$subm_result = "";
			else{
				$submsnB = floatval($submissionInfoLink->fetch_assoc()['b']);
				//die($submsnB);
				$submissionInfoLink->free();
				unset($submissionInfoLink);
				
				if ($submsnB <= 0)
					$subm_result = "danger";
				elseif ($submsnB < floatval($problem["difficulty"]))
					$subm_result = "warning";
				elseif ($submsnB == floatval($problem["difficulty"]))
					$subm_result = "success";
				else
					$subm_result = "active";
			}
			
?>
					<tr class="<?php print($subm_result); ?>">
						<td><?php print($problem["id"]); ?></td>
						<td><a href="index.php?service=problem&id=<?php print($problem["id"]); ?>"><?php print($problem["name"]); ?></a></td>
						<td><?php print($cat_name . " / " . $sub_cat_name); ?></td>
						<td>0 / 0</td>
						<td><?php print($problem["difficulty"]); ?></td>
					</tr>
<?php
		}
?>
				</tbody>
				<thead>
					<tr>
						<th width="10%"></th>
						<th width="40%">
							Страница <?php print($_GET["page"]); ?> из <?php print($total_pages); ?>
						</th>
						<th width="30%"></th>
						<th width="10%"></th>
						<th width="10%"></th>
					</tr>
				</thead>
			</table>
		</div>
<?php
	}
?>

<?php include(_S_MOD_ . "pagination.php"); generatePagination($total_pages, $current_page, 4, "problems", "&query=" . $_GET["query"] . "&sortby=" . $_GET["sortby"] . "&sort=" . $_GET["sort"]); ?>
<?php SPM_footer(); ?>