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
		<a href="index.php?service=teacherID" style="margin: 0;" class="btn btn-primary btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
				Teacher ID<br/>(Управління)
			</p>
		</a>
	</div>
	<div class="col-md-4" style="padding: 0;">
		<a href="http://spm.sirkadirov.com/" terget="_blank" class="btn btn-primary btn-xlarge btn-block btn-flat">
			<p class="admin-btn">
				<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
				Перейти на офіційний сайт<br/>SimplePM
			</p>
		</a>
	</div>
</div>
<?php SPM_footer(); ?>