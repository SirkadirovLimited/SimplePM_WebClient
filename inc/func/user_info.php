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
	
	function spm_getUserGroupByID($groupId){
		
		global $db;
		
		if (!$query = $db->query("SELECT `name` FROM `spm_users_groups` WHERE `id` = '" . (int)$groupId . "' LIMIT 1;"))
			die(header('location: index.php?service=error&err=db_error'));
		
		return @$query->fetch_array()[0];;
	}
?>