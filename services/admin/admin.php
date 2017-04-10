<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	global $_SPM_CONF;
	
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
		
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">CMS</h3>
			</div>
			<div class="panel-body">
				<!--CONTENT-->
				<a href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=users.admin" class="btn btn-default btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-user"></span></h3>
						Пользователи системы
					</p>
				</a>
				<a href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=news.admin" class="btn btn-default btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-align-justify"></span></h3>
						Новости сайта
					</p>
				</a>
				<a href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=view.admin" class="btn btn-default btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-book"></span></h3>
						Страницы сайта
					</p>
				</a>
				<!--/CONTENT-->
			</div>
		</div>
		
	</div>
	<div class="col-md-4">
		
		<div class="panel panel-warning">
			<div class="panel-heading">
				<h3 class="panel-title">Система проверки решений</h3>
			</div>
			<div class="panel-body">
				<!--SPM-->
				<a href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=problems.admin" class="btn btn-default btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-list-alt"></span></h3>
						Архив задач (управление)
					</p>
				</a>
				<a href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=servers.admin" class="btn btn-default btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-cog"></span></h3>
						Управление серверами проверки
					</p>
				</a>
				<!--/SPM-->
			</div>
		</div>
		
	</div>
	<div class="col-md-4">
		
		<div class="panel panel-danger">
			<div class="panel-heading">
				<h3 class="panel-title">Безопасность / управление</h3>
			</div>
			<div class="panel-body">
				<!--SECURITY-->
				<a href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>index.php?service=config.admin" class="btn btn-default btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-cog"></span></h3>
						Конфигурация сайта
					</p>
				</a>
				<a href="" class="btn btn-default btn-xlarge btn-block btn-flat">
					<p class="admin-btn">
						<h3><span class="glyphicon glyphicon-sunglasses"></span></h3>
						Попытки проникновения
					</p>
				</a>
				<!--/SECURITY-->
			</div>
		</div>
		
	</div>
</div>
<!--SPM_BASE-->
<h3>Действия SPM веб-системы</h3>
<ul>
	<li><a href="https://spm-reseller.sirkadirov.com/" target="_blank">Официальный сайт</a></li>
	<li><a href="https://spm-reseller.sirkadirov.com/docs/" target="_blank">Документация</a></li>
	<li><a href="index.php?service=admin&cmd=optimize">Оптимизация БД</a></li>
</ul>	
<?php SPM_footer(); ?>