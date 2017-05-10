<?php
	isset($_GET['page']) or $_GET['page'] = 1;
	
	(int)$_GET['page']>0 or $_GET['page']=1;
	
	if (permission_check($_SESSION["uid"], PERMISSION::administrator))
		$count_where = "1";
	else
		$count_where = "`teacherId` = '" . $_SESSION["uid"] . "'";
	
	if (!$db_result = $db->query("SELECT count(id) FROM `spm_olympiads` WHERE " . $count_where . ";"))
		die(header('location: index.php?service=error&err=db_error'));
	
	$total_olympiads_number = (int)($db_result->fetch_array()[0]);
	$olympiads_per_page = $_SPM_CONF["SERVICES"]["news"]["articles_per_page"];
	$current_page = (int)$_GET['page'];
	
	$db_result->free();
	unset($db_result);
	
	if ($total_olympiads_number > 0 && $olympiads_per_page > 0)
		$total_pages = ceil($total_olympiads_number / $olympiads_per_page);
	else
		$total_pages = 1;
	
	if ($current_page > $total_pages)
		$current_page = 1;
	
	//SQL queries and formatting
	if (!$db_result = $db->query("SELECT * FROM `spm_olympiads` ORDER BY `id` DESC LIMIT " . ($current_page * $olympiads_per_page - $olympiads_per_page) . " , " . $olympiads_per_page . ";"))
		die(header('location: index.php?service=error&err=db_error'));
?>