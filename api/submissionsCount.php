<?php
	/*
	 * Copyright (C) 2017, Kadirov Yurij. All rights reserved.
	 * submissionsCount API is a component of SimplePM.
	 */
	
	/// Ожидающие запросы на отправку
	function spmApi_count()
	{
		
		global $db;
		
		$query_str = "
			SELECT
				count(`submissionId`)
			FROM
				`spm_submissions`
			;
		";
		
		if (!$counter = $db->query($query_str))
			die('db_error');
		
		$result = $counter->fetch_array()[0];
		$counter->free();
		
		return $result;
		
	}
	
	/// Ожидающие запросы на отправку
	function spmApi_waitingCount()
	{
		
		global $db;
		
		$query_str = "
			SELECT
				count(`submissionId`)
			FROM
				`spm_submissions`
			WHERE
				`status` = 'waiting'
			;
		";
		
		if (!$counter = $db->query($query_str))
			die('db_error');
		
		$result = $counter->fetch_array()[0];
		$counter->free();
		
		return $result;
		
	}
	
	/// Выполняющиеся запросы на отправку
	function spmApi_processingCount()
	{
		
		global $db;
		
		$query_str = "
			SELECT
				count(`submissionId`)
			FROM
				`spm_submissions`
			WHERE
				`status` = 'processing'
			;
		";
		
		if (!$counter = $db->query($query_str))
			die('db_error');
		
		$result = $counter->fetch_array()[0];
		$counter->free();
		
		return $result;
		
	}
	
	/// Выполненные запросы на отправку
	function spmApi_readyCount()
	{
		
		global $db;
		
		$query_str = "
			SELECT
				count(`submissionId`)
			FROM
				`spm_submissions`
			WHERE
				`status` = 'ready'
			;
		";
		
		if (!$counter = $db->query($query_str))
			die('db_error');
		
		$result = $counter->fetch_array()[0];
		$counter->free();
		
		return $result;
		
	}
	
?>