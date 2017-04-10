<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	function _spm_view_msg($text, $alert_type="info"){
		print("
		<div class='alert alert-$alert_type alert-dismissible' role='alert'>
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			$text
		</div>");
	}
?>