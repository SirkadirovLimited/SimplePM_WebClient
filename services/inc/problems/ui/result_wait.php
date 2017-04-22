<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::student);
?>
<div>
	<img src="<?php print(_S_MEDIA_IMG_); ?>party.png" width="100%">
	<div align="center" class="party" style="margin-top: -20px; margin-bottom: 30px;">
		<h1>Происходит отправка</h1>
		<p class="lead">Отправка, компиляция и тестирование вашего решения может занять некоторое время. Обычно этот процесс занимает не более 1 секунды, но всё зависит от обстоятельств. 
		Пожалуйста, подождите!</p><br/>
		<p class="lead">Страница обновится автоматически.<br/>Если этого не произошло, <a href="">перезагрузите её</a> самостоятельно.</p>
	</div>
	<meta http-equiv='refresh' content='0.5; url='>
</div>