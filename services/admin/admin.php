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
	<div class="col-md-4">
		
		<div class="panel panel-primary" style="border-radius: 0;">
			<div class="panel-heading" style="border-radius: 0;">
				<h3 class="panel-title">CMS</h3>
			</div>
			<div class="panel-body" style="padding: 0;">
				<!--CONTENT-->
				<a href="index.php?service=users.admin" style="margin: 0;" class="btn btn-primary btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-user"></span></h3>
						Пользователи системы<br/>(Управление)
					</p>
				</a>
				<a href="index.php?service=news.admin" style="margin: 0;" class="btn btn-primary btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-align-justify"></span></h3>
						Новости сайта<br/>(Управление)
					</p>
				</a>
				<a href="index.php?service=view.admin" style="margin: 0;" class="btn btn-primary btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-book"></span></h3>
						Страницы сайта<br/>(Управление)
					</p>
				</a>
				<!--/CONTENT-->
			</div>
		</div>
		
	</div>
	<div class="col-md-4">
		
		<div class="panel panel-warning" style="border-radius: 0;">
			<div class="panel-heading" style="border-radius: 0;">
				<h3 class="panel-title">Система проверки решений</h3>
			</div>
			<div class="panel-body" style="padding: 0;">
				<!--SPM-->
				<a href="index.php?service=problems.admin" style="margin: 0;" class="btn btn-warning btn-xlarge btn-block btn-flat disabled">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
						Сгенерировать новые Teacher ID<br/>для всех
					</p>
				</a>
				<!--/SPM-->
			</div>
		</div>
		
	</div>
	<div class="col-md-4">
		
		<div class="panel panel-danger" style="border-radius: 0;">
			<div class="panel-heading" style="border-radius: 0;">
				<h3 class="panel-title">Безопасность / управление</h3>
			</div>
			<div class="panel-body" style="padding: 0;">
				<!--SECURITY-->
				<a href="index.php?service=config.admin" style="margin: 0;" class="btn btn-danger btn-xlarge btn-block btn-flat disabled">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
						Редактировать файл<br/>конфигурации SimplePM_WebClient
					</p>
				</a>
				<a href="index.php?service=bigbrother.admin" style="margin: 0;" class="btn btn-danger btn-xlarge btn-block btn-flat disabled">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
						Подозрительные действия<br/>пользователей
					</p>
				</a>
				<a href="index.php?service=optimisation.admin" style="margin: 0;" class="btn btn-danger btn-xlarge btn-block btn-flat disabled">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
						Оптимизация базы данных<br/>SimplePM
					</p>
				</a>
				<!--/SECURITY-->
			</div>
		</div>
		
	</div>
</div>

<div class="panel panel-success" style="border-radius: 0;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">SimplePM_WebClient</h3>
	</div>
	<div class="panel-body" style="padding: 0;">
		<!--SIMPLEPM_WEBCLIENT-->
		<div class="row" style="margin: 0;">
			<div class="col-md-4" style="padding: 0;">
				<a href="http://spm.sirkadirov.com/" class="btn btn-success btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
						Перейти на официальный сайт<br/>SimplePM
					</p>
				</a>
			</div>
			<div class="col-md-4" style="padding: 0;">
				<a href="index.php?service=problems.admin" class="btn btn-success btn-xlarge btn-block btn-flat disabled">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
						Проверить SimplePM_WebClient<br/>на наличие обновлений
					</p>
				</a>
			</div>
			<div class="col-md-4" style="padding: 0;">
				<a href="http://spm.sirkadirov.com/" class="btn btn-success btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
						Перейти к документации SimplePM<br/>(внешней)
					</p>
				</a>
			</div>
			<div class="col-md-4" style="padding: 0;">
				<a href="index.php?service=problems.admin" class="btn btn-success btn-xlarge btn-block btn-flat disabled">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
						Поддержать проект<br/>
						и его разработчиков
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
			<div class="col-md-4" style="padding: 0;">
				<a href="index.php?service=problems.admin" class="btn btn-success btn-xlarge btn-block btn-flat disabled">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
						Информация о внешних сервисах<br/>
						и модулях системы
					</p>
				</a>
			</div>
		</div>
		<!--SIMPLEPM_WEBCLIENT-->
	</div>
</div>

<div class="panel panel-default" style="border-radius: 0;">
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