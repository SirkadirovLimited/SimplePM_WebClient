<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	abstract class PERMISSION
	{
		const user = 1;
		const student = 2;
		const teacher = 4;
		const olymp = 8;
		const curator = 16;
		const administrator = 256;
	}
	
	function permission_check($user_access, $access_level){
		if ($user_access & $access_level)
			return true;
		else
			return false;
	}
	
	function deniedOrAllowed($permission){
		global $_SPM_CONF;
		
		if (!isset($_SESSION['uid']) || !permission_check($_SESSION['permissions'], $permission)){
			include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["access_denied"]);
			exit;
		}
	}
?>