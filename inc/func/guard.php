<?php
	function _spm_guard_clearAllGet(){
		
		global $_GET;
		
		foreach ($_GET as &$getParam){
			$getParam = htmlspecialchars(trim($getParam));
		}
		
		return true;
		
	}
?>
