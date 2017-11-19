<?php
	
	/*
	 * Copyright (C) 2017, Kadirov Yurij. All rights reserved.
	 * submissionsCount API is a component of SimplePM.
	 */
	
	function spmApi_isReady()
	{
		
		global $db;
		
		isset($_SESSION['uid']) or die('403');
		isset($_GET['id']) && (int)$_GET['id'] > 0 or die('input');
		$_GET['id'] = (int)$_GET['id'];
		
		$query_str = "
			SELECT
				count(`submissionId`)
			FROM
				`spm_submissions`
			WHERE
				`submissionId` = '" . $_GET['id'] . "'
			AND
				`status` = 'ready'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die('db_error');
		
		print((bool)$query->fetch_array()[0] ? "true" : "false");
		
	}
	
?>