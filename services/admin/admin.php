<?php
	deniedOrAllowed(PERMISSION::administrator);
	
	SPM_header("Панель адміністратора", "Головна сторінка");
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
				Користувачі системи<br/>(Управління)
			</p>
		</a>
	</div>
	<div class="col-md-4" style="padding: 0;">
		<a href="index.php?service=teacherID" style="margin: 0;" class="btn btn-warning btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
				Teacher ID<br/>(Управління)
			</p>
		</a>
	</div>
	<div class="col-md-4" style="padding: 0;">
		<a href="http://spm.sirkadirov.com/" class="btn btn-success btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
				Перейти на офіційний сайт<br/>SimplePM
			</p>
		</a>
	</div>
	<div class="col-md-4" style="padding: 0;">
		<a href="https://github.com/SirkadirovTeam/" class="btn btn-success btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
				Офіційний репозиторій<br/>
				проекту на GitHub
			</p>
		</a>
	</div>
	<div class="col-md-4" style="padding: 0;">
		<a href="https://simplepm.atlassian.net/" class="btn btn-success btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
				Настанови з адміністрування<br/>
				та використання SimplePM
			</p>
		</a>
	</div>
</div>

<div class="panel panel-default" style="border-radius: 0; margin-top: 10px;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Зв'язок з автором системи</h3>
	</div>
	<div class="panel-body">
		<dl class="dl-horizontal" style="margin: 20px 20px 20px 0px;">
			<dt>Повне ім'я</dt>
			<dd>Кадіров Юрій Вікторович</dd>
			
			<dt>Email тех. підтримки</dt>
			<dd>admin@sirkadirov.com</dd>
			
			<dt>Телефон тех. підтримки</dt>
			<dd>+380 (98) 121-16-52</dd>
		</dl>
	</div>
</div>
<?php SPM_footer(); ?>