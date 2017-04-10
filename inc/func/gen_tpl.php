<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	function SPM_header($_TPL_PAGENAME, $_TPL_PAGEDESC = null, $_TPL_PAGESUBNAME = null){
		if (isset($_SESSION['uid'])){
			if ($_TPL_PAGESUBNAME == null)
				$_TPL_PAGESUBNAME = $_TPL_PAGENAME;
			
			include(_S_TPL_ . "html_start.php");
			include(_S_TPL_ . "header.php");
			include(_S_TPL_ . "sidebar.php");
			include(_S_TPL_ . "content_start.php");
		}
	}
	
	function SPM_footer(){
		if (isset($_SESSION['uid'])){
			include(_S_TPL_ . "footer.php");
			include(_S_TPL_ . "html_end.php");
		}
	}
?>