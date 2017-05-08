<?php
	DEFINED("__SPM_INSTALLER__") or die('<strong>403 ACCESS DENIED!</strong>');
	
	include(_header);
?>
<div class="progress">
	<div class="progress-bar progress-bar-success" style="min-width: 3em; width: 0%;">
		0%
	</div>
</div>
<div style="height: 50%; overflow-y: auto;">
	<h3>Добро пожаловать!</h3>
	<p>
		Добро пожаловать в графический интерфейс установщика SimplePM_WebClient! Данный установщик позволит вам произвести первоначальную 
		настройку <b>SimplePM_WebClient</b> на вашем веб-сервере. Для продолжения установки нажмите "Далее".
	</p>
</div>
<nav>
	<ul class="pager">
		<li class="previous disabled"><a href="#"><span>&larr;</span> Назад</a></li>
		<li class="next"><a href="#">Далее <span>&rarr;</span></a></li>
	</ul>
</nav>
<?php
	include(_footer);
?>