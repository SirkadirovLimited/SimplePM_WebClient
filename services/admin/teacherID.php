<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	if ( !permission_check($_SESSION['permissions'], PERMISSION::teacher)
	&& !permission_check($_SESSION['permissions'], PERMISSION::administrator) ){
		
		SPM_header("Ошибка 403");
		include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["access_denied"]);
		SPM_footer();
		exit;
		
	}
	
	SPM_header("TeacherID");
?>
<div class="alert alert-warning" align="center">
	<h1>SIRK-ABILY-20020411ADM</h1>
	<p><b>ВНИМАНИЕ!</b> Не передавайте этот код никому кроме ваших учеников, позаботьтесь о безопасности!</p>
</div>
<p class="lead"><strong>TeacherID</strong> - это ваш персональный идентификатор, с помощью которого ваши студенты или подчинённые могут регистрироваться в системе.</p>
<p class="lead"><strong>Текущий статус TeacherID:</strong> включён (<a href="index.php?service=teacherID&toggle">Изменить на противоположный</a>)</p>
<?php SPM_footer(); ?>