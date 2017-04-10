<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	SPM_header("Серверы проверки","Управление");
	
	if (!$db_res = $db->query("SELECT * FROM `spm_servers`"))
		die('<strong>Произошла ошибка при попытке запроса к базе данных!</strong>');
?>

<div class="row">
	<div class="col-md-6">
		<!-- View -->
<?php
	if ($db_res->num_rows === 0){
?>
		<div class="panel panel-default">
			<div class="panel-body" align="center">
				<h2>Список серверов пуст!</h2>
				<p class="lead">Для добавления нового сервера воспользуйтесь формой справа.</p>
			</div>
		</div>
<?php
	}else{
		while ($server = $db_res->fetch_assoc()){
?>
		<div class="panel panel-default">
			<div class="panel-body">
				<dl class="dl-horizontal" style="margin: 0;">
					<dt>ID</dt>
					<dd><?php print($server["id"]); ?></dd>
					
					<dt>Название</dt>
					<dd><?php print($server["name"]); ?></dd>
					
					<dt>Описание</dt>
					<dd><?php print($server["description"]); ?></dd>
					
					<dt>IP адрес</dt>
					<dd><?php print($server["address"]); ?></dd>
					
					<dt>Действия</dt>
					<dd><a href="" class="link"><span class="glyphicon glyphicon-trash"></span> Удалить</a></dd>
				</dl>
			</div>
		</div>
<?php
		}
	}
?>
	</div>
	<div class="col-md-6">
		<!-- Create new -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Добавить сервер</h3>
			</div>
			<div class="panel-body">
				
				<form action="" method="post">
					
					<div class="form-group">
						<label for="title">Название (только для администраторов)</label>
						<input type="text" class="form-control" id="title" placeholder="Main server">
					</div>
					<div class="form-group">
						<label for="description">Описание (только для администраторов)</label>
						<textarea class="form-control" id="description" placeholder="Main SimplePM server." rows="4" style="resize: none;"></textarea>
					</div>
					<div class="form-group">
						<label for="ip">IP сервера</label>
						<input type="text" class="form-control" id="ip" placeholder="XXX.XXX.XXX.XXX">
					</div>
					
					<input type="submit" class="btn btn-success" value="Сохранить"> <a href="" class="btn btn-danger">Отменить</a>
					
				</form>
				
			</div>
		</div>
	</div>
</div>
<?php
	SPM_footer();
?>