<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__view.admin__") or die('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	global $db;
	global $_SPM_CONF;
	
	if (!$db->query("DELETE FROM `spm_news` WHERE id='" . $_GET['del'] . "'")){
		_spm_view_msg("Произошла непредвиденная ошибка при удалении новости с базы данных. Накажите вашего сисадмина за это!", "danger");
	}else{
		_spm_view_msg("Новость успешно удалена и больше не доступна.", "success");
	}
?>