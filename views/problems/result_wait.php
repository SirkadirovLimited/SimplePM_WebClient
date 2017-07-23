<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::student);
?>
<div>
	<div align="center" class="party" style="margin-top: -20px; margin-bottom: 30px;">
		<h1>Очікування перевірки</h1>
		<p class="lead">Автоматизована перевірка рішень - дуже складний процес, тому ми просимо вас зачекати.</p><br/>
		
		<p class="lead">Сторінка буде перезавантажена автоматично.<br/>Якщо цього не сталося, <a href="">перезавантажте її</a>.</p>
		
		<p class="lead">Якщо перевірка займає багато часу, перейдіть до рішення інших задач, а ознайомитися із результатами тестування можна і потім.</p>
	</div>
	<meta http-equiv='refresh' content='2; url='>
</div>