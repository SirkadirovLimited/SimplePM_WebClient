<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	abstract class PERMISSION
	{
		const user = 1;
		const student = 2;
		const teacher = 4;
		const olymp = 8;
		const administrator = 256;
	}
	
	function permission_check($user_access, $access_level){
		if ($user_access & $access_level)
			return true;
		else
			return false;
	}
	
	function deniedOrAllowed($permission){
		if (!isset($_SESSION['uid']) || !permission_check($_SESSION['permissions'], $permission))
			die(header('location: index.php?service=error&err=403'));
	}
?>