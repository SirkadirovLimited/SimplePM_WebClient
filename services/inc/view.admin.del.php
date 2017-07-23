<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__view.admin__") or die('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	global $db;
	global $_SPM_CONF;
	
	if (!$db->query("DELETE FROM `spm_pages` WHERE id='" . htmlspecialchars(trim($_GET['del'])) . "'")){
		_spm_view_msg("Видалення не проведено через помилку.", "danger");
	}else{
		_spm_view_msg("Сторінка успішно видалена.", "success");
	}
?>