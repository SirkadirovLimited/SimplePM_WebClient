<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	//Do things only if user authenticated
	if ( isset( $_SESSION["uid"] ) && (int)$_SESSION["uid"] > 0){
		
		//Query string
		$query_str = "
			SELECT
				`sessionId`,
				`banned`
			FROM
				`spm_users`
			WHERE
				`id` = '" . $_SESSION['uid'] . "'
			LIMIT
				1
			;
		";
		
		//SQL query
		if (!$db_result = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		//If user not found
		if ($db_result->num_rows == 0):
			unset($_SESSION);
			session_destroy();
			header('location: index.php');
			die;
		endif;
		
		//Fetch associative array
		$userInfo = $db_result->fetch_assoc();
		
		//Clear cache
		$db_result->free();
		unset($db_result);
		
		//Check if user banned
		if ($userInfo['banned'] == 1):
			unset($_SESSION);
			session_destroy();
			die(header('location: index.php'));
		endif;
		
		//Check if another user logged in in the same account
		if ($userInfo['sessionId'] != session_id()):
			unset($_SESSION['uid']);
			session_destroy();
			die(header('location: index.php'));
		endif;
		
		//Set user online
		spm_setUserOnline($_SESSION["uid"], true);
	}
?>