<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	SPM_header("Конфигурация сайта");
?>
<script src="<?php print(_S_TPL_); ?>plugins/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<style type="text/css">
    #codeEditor {
		position: relative;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
		height: 400px;
		margin: 0;
		font-size: 15px;
    }
</style>
<p class="lead">Параметры системы SPM находятся в файле ./inc/config.php. Отредактировать его вы можете с помощью стороннего ПО через FTP протокол. Запомните: безопасность превыше всего!</p>
<?php
	SPM_footer();
?>