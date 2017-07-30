<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	if (isset($_SESSION["uid"]) && permission_check($_SESSION["permissions"], PERMISSION::student)){
		
		$query_str = "
			SELECT
				`associatedOlymp`
			FROM
				`spm_users`
			WHERE
				`id` = '" . $_SESSION["uid"] . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		$associatedOlympId = $query->fetch_array()[0];
		$query->free();
		
		if ($associatedOlympId > 0)
		{
			
			$query_str = "
				SELECT
					count(`id`)
				FROM
					`spm_olympiads`
				WHERE
					`id` = '" . $associatedOlympId . "'
				AND
					`endTime` >= now()
				LIMIT
					1
				;
			";
			
			if (!$query = $db->query($query_str))
				die(header('location: index.php?service=error&err=db_error'));
			
			if ((int)($query->fetch_array()[0]) <= 0)
			{
				$query_str = "
					UPDATE
						`spm_users`
					SET
						`associatedOlymp` = '0'
					WHERE
						`id` = '" . $_SESSION["uid"] . "'
					LIMIT
						1
					;
				";
				
				if (!$db->query($query_str))
					die(header('location: index.php?service=error&err=db_error'));
				
				unset($_SESSION["olymp"]);
			}
			else
			{
				// Set associated session variables
				$_SESSION["olymp"] = $associatedOlympId;
				
				// Unset unused session variables
				unset($_SESSION["classwork"]);
				
				// Prepare for olympiad
				spm_prepare_olympiad();
			}
			
		}
		
	}
?>