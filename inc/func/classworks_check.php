<?php
	
	if (isset($_SESSION["uid"]) && permission_check($_SESSION["permissions"], PERMISSION::student) && !isset($_SESSION["olymp"])){
		
		$query_str = "
			SELECT
				`teacherId`,
				`group`
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
		
		$user = $query->fetch_assoc();
		$query->free();
		
		$query_str = "
			SELECT
				`id`
			FROM
				`spm_classworks`
			WHERE
				`teacherId` = '" . $user["teacherId"] . "'
			AND
				`studentsGroup` = '" . $user["group"] . "'
			AND
				`startTime` <= now()
			AND
				`endTime` >= now()
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows == 0)
			unset($_SESSION["classwork"]);
		else
		{
			$_SESSION["classwork"] = $query->fetch_array()[0];
			spm_prepare_classwork();
		}
		
		$query->free();
		unset($query);
		
	}
?>