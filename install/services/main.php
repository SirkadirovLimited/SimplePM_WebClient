<?php
	DEFINED("__SPM_INSTALLER__") or die('<strong>403 ACCESS DENIED!</strong>');
	
	include(_header);
?>
<h3>Установка SimplePM_WebClient</h3>
<div class="progress">
	<div class="progress-bar progress-bar-success" style="min-width: 3em; width: 0%;">
		0%
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<ul class="nav nav-pills nav-stacked">
			<li class="active"><a>Начало установки</a></li>
			<li><a>Подключение к БД</a></li>
			<li><a>Первоначальная конфигурация</a></li>
			<li><a></a></li>
		</ul>
	</div>
	<div class="col-md-9">
	
	</div>
</div>
<?php
	include(_footer);
?>