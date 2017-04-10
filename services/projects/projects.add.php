<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	SPM_header("Проекты","Добавить новый");
?>

<?php _spm_view_msg("Решение о публикации вашего проекта в каталоге принимает Модератор, Куратор или Администратор. Проекты, не соответствующие <a href=''>данным правилам</a> не будут опубликованы.", "info"); ?>

<form action="" method="post" enctype="multipart/form-data">

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Создание / Редактирование проекта</h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-4">
					<img src="index.php?service=image&uid=2" class="img-responsive img-rounded" />
					
					<div style="margin-top: 10px; margin-bottom: 0;">
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">Название</div>
								<input type="text" class="form-control" placeholder="Hello, World!">
							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">Дата релиза</div>
								<input type="date" class="form-control" placeholder="ГГГГ-ММ-ДД">
							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">Изображение</div>
								<input type="file" class="form-control">
							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">Категория</div>
								<select class="form-control">
									<option value="">Программный продукт</option>
									<option value="">Игры</option>
									<option value="">Веб-проект</option>
									<option value="" selected>Другое</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<h4><b>Описание проекта</b></h4>
					<textarea class="form-control" name="projectDescription" rows="10" style="resize: vertical;"></textarea>
					
					<h4 style="margin-top: 20px;"><b>Ссылки</b></h4>
					
					<div style="padding: 10px;">
					
						<div class="form-group">
							<label for="website">Официальный сайт</label>
							<div class="input-group">
								<div class="input-group-addon">http://</div>
								<input type="text" class="form-control" id="website" placeholder="example.com">
							</div>
						</div>
						<div class="form-group">
							<label for="github">Репозиторий на GitHub</label>
							<div class="input-group">
								<div class="input-group-addon">https://github.com/</div>
								<input type="text" class="form-control" id="github" placeholder="SirkadirovLimited/SimplePM">
							</div>
						</div>
						<div class="form-group">
							<label for="email">Email для связи (публичный)</label>
							<input type="email" class="form-control" id="email" placeholder="admin@example.com">
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer" align="right">
			<a href="index.php?service=projects" class="btn btn-danger">Отменить</a>
			<input type="submit" class="btn btn-success" value="Сохранить" />
		</div>
	</div>

</form>

<?php
	SPM_footer();
?>