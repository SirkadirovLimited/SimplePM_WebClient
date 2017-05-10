<?php
	//SORT BY TYPES
	$_SORT_BY["id"] = "id";
	$_SORT_BY["name"] = "name";
	$_SORT_BY["b"] = "difficulty";
	
	//SORT TYPES
	$_SORT["asc"] = "asc";
	$_SORT["desc"] = "desc";
	
	//SECURITY
	isset($_GET['page']) or $_GET['page'] = 1; //page value
	(int)$_GET['page'] > 0 or $_GET['page']=1; //page int value check
	isset($_GET["query"]) or $_GET["query"]=""; //query text
	isset($_GET["catId"]) && ((int)$_GET['catId'] > 0) or $_GET["catId"]="*"; //category id
	//Sort by formatting
	isset($_GET["sortby"]) && isset($_SORT_BY[$_GET["sortby"]]) or $_GET["sortby"] = $_SORT_BY["id"];
	$_GET["sortby"] = $_SORT_BY[$_GET["sortby"]];
	//Sort formatting
	isset($_GET["sort"]) && isset($_SORT[$_GET["sort"]]) or $_GET["sort"] = $_SORT["asc"];
	$_GET["sort"] = $_SORT[$_GET["sort"]];
	
	//Count all problems in the archive
	if (!$db_result = $db->query("SELECT count(id) AS problems_count FROM `spm_problems`"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
	$total_articles_number = (int)($db_result->fetch_assoc()["problems_count"]);
	$articles_per_page = $_SPM_CONF["SERVICES"]["problems"]["articles_per_page"];
	$current_page = (int)$_GET['page'];
	
	$db_result->free();
	unset($db_result);
	
	//Get total pages count
	if ($total_articles_number > 0 && $articles_per_page > 0)
		$total_pages = ceil($total_articles_number / $articles_per_page);
	else
		$total_pages = 1;
	//For stability
	if ($current_page > $total_pages)
		$current_page = 1;
	
	/*
	 * SQL SELECT query with params
	 */
	
	//Category id
	if ((int)$_GET['catId'] > 0)
		$selectedCatIdText = "`catId` = '" . (int)$_GET['catId'] . "'";
	else
		$selectedCatIdText = "1";
	//Query text
	if (!empty($_GET['query']) && $_GET['query'] = mysqli_real_escape_string($db, $_GET['query']))
		$queryText = "(`name` LIKE '%" . $_GET['query'] . "%' OR `id` = '" . $_GET['query'] . "')";
	else
		$queryText = "1";
	
	if (!$db_result = $db->query("SELECT `id`,`difficulty`,`catId`,`name` FROM `spm_problems` WHERE " . $selectedCatIdText . " AND " . $queryText . " ORDER BY `" . $_GET["sortby"] . "` " . $_GET["sort"] . " LIMIT " . ($current_page * $articles_per_page - $articles_per_page) . " , " . $articles_per_page . ";"))
		die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
	
	SPM_header("Архив задач", "Просмотр всех доступных задач");
	
	/*
	 * FUNCTIONS
	 */
	function generate_sort_url($page = 1, $sortby = "", $sort = "", $catId = ""){
		($sortby != "") or $sortby = $_GET["sortby"];
		($sort != "") or $sort = $_GET["sort"];
		($catId != "") or $catId = $_GET["catId"];
		
		return "index.php?service=problems&catId=&page=" . $page . "&query=" . $_GET["query"] . "&sortby=" . $sortby . "&sort=" . $sort;
	}
?>