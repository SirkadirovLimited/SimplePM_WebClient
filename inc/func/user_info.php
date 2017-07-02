<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	function spm_checkIfUserExists($userId){
		
		global $db;
		
		$query_str = "
			SELECT
				count(`id`)
			FROM
				`spm_users`
			WHERE
				`id` = '" . (int)$userId . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		$count = $query->fetch_array()[0];
		$query->free();
		
		if ($count == 1)
			return true;
		else
			return false;
		
	}
	
	function spm_getUserFullnameByID($userId){
		
		global $db;
		
		$query_str = "
			SELECT
				`firstname`,
				`secondname`,
				`thirdname`
			FROM
				`spm_users`
			WHERE
				`id` = '" . (int)$userId . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows == 0)
			$result = "Тёмная сторона Силы";
		else {
			$user_info = $query->fetch_assoc();
			
			if ($user_info['thirdname'] == null)
				$user_info['thirdname'] = "";
			else
				$user_info['thirdname'] = " " . $user_info['thirdname'];
			
			$result = $user_info['secondname'] . " " . $user_info['firstname'] . $user_info['thirdname'];
		}
		
		return $result;
		
	}
	
	function spm_getUserShortnameByID($userId){
		
		global $db;
		
		$query_str = "
			SELECT
				`firstname`,
				`secondname`
			FROM
				`spm_users`
			WHERE
				`id` = '" . (int)$userId . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows == 0)
			$result = "Тёмная сторона Силы";
		else {
			$user_info = $query->fetch_assoc();
			
			$result = $user_info['secondname'] . " " . $user_info['firstname'];
		}
		
		return $result;
		
	}
	
	function spm_getUserGroupByID($groupId){
		
		global $db;
		
		$query_str = "
			SELECT
				`name`
			FROM
				`spm_users_groups`
			WHERE
				`id` = '" . (int)$groupId . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		return @$query->fetch_array()[0];
		
	}
	
	function spm_getUserOnline($user_id){
		
		global $db;
		global $_SPM_CONF;
		
		$query_str = "
			SELECT
				now() - `lastOnline`
			FROM
				`spm_users`
			WHERE
				`id` = '" . $user_id . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows == 0)
			return false;
		
		$sleep_time = $query->fetch_array()[0];
		
		$query->free();
		unset($query);
		
		if ($sleep_time < $_SPM_CONF["BASE"]["ONLINE_TIME"])
			return true;
		else
			return false;
		
	}
	
	function spm_setUserOnline($userId, $status = true){
		
		global $db;
		
		if ($status)
			$lastOnline = "now()";
		else
			$lastOnline = "'2001-07-27 10:30:00'";
		
		$query_str = "
			UPDATE
				`spm_users`
			SET
				`lastOnline` = " . $lastOnline . "
			WHERE
				`id` = " . $userId . "
			LIMIT
				1
			;
		";
		
		if (!$db_result = $db->query($query_str))
				die(header('location: index.php?service=error&err=db_error'));
		
	}
?>