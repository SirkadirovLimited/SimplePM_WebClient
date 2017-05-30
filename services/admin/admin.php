<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	SPM_header("Панель администратора");
?>
<style>
	.admin-btn{
		margin-top: 10px;
		margin-bottom: 10px;
	}
</style>

<div class="row">
	<div class="col-md-4" style="padding: 0;">
		<a href="index.php?service=users.admin" style="margin: 0;" class="btn btn-primary btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-user"></span></h3>
				Пользователи системы<br/>(Управление)
			</p>
		</a>
	</div>
	<div class="col-md-4" style="padding: 0;">
		<a href="index.php?service=news.admin" style="margin: 0;" class="btn btn-primary btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-align-justify"></span></h3>
				Новости сайта<br/>(Управление)
			</p>
		</a>
	</div>
	<div class="col-md-4" style="padding: 0;">
		<a href="index.php?service=view.admin" style="margin: 0;" class="btn btn-primary btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-book"></span></h3>
				Страницы сайта<br/>(Управление)
			</p>
		</a>
	</div>
	<div class="col-md-4" style="padding: 0;">
		<a href="index.php?service=problems.admin" style="margin: 0;" class="btn btn-warning btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
				Сгенерировать новые Teacher ID<br/>для всех
			</p>
		</a>
	</div>
	<div class="col-md-4" style="padding: 0;">
		<a href="http://spm.sirkadirov.com/" class="btn btn-success btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
				Перейти на официальный сайт<br/>SimplePM
			</p>
		</a>
	</div>
	<div class="col-md-4" style="padding: 0;">
		<a href="https://github.com/SirkadirovLimited/" class="btn btn-success btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
				Официальный репозиторий<br/>
				проекта на GitHub
			</p>
		</a>
	</div>
</div>

<div class="panel panel-default" style="border-radius: 0; margin-top: 10px;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Связь с автором системы</h3>
	</div>
	<div class="panel-body">
		<dl class="dl-horizontal" style="margin: 20px 20px 20px 0px;">
			<dt>Автор системы</dt>
			<dd>Кадиров Юрий Викторович</dd>
			
			<dt>Email тех. поддержки</dt>
			<dd>admin@sirkadirov.com</dd>
			
			<dt>Телефон тех. поддержки</dt>
			<dd>+380 (98) 121-16-52 (перед звонком через СМС представиться)</dd>
			
			<dt>Примечание</dt>
			<dd>Вибачте за російську мову! Українська локалізація буде вже з наступного оновлення!</dd>
		</dl>
	</div>
</div>
<?php SPM_footer(); ?>