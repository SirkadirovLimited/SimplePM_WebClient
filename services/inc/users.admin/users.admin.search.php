<?php DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED'); ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Поиск по пользователям</h3>
	</div>
	<div class="panel-body">
		<form action="index.php" method="get">
			<input type="hidden" name="service" value="users.admin">
			
			<div class="row">
				<div class="col-md-3">
					
					<select class="form-control" name="searchBy">
						<option value="id" selected>ID пользователя</option>
						<option value="username">Имя пользователя</option>
						<option value="secondname">Фамилия</option>
						<option value="teacherId">Учитель / Куратор</option>
						<option value="city">Населённый пункт</option>
						<option value="bday">День рождения</option>
						<option value="group">Группа</option>
						<option value="permissions">Права доступа</option>
						<option value="email">Email</option>
					</select>
					
				</div>
				<div class="col-md-7">
					
					<input type="text" class="form-control" id="username" name="query" placeholder="Поисковой запрос">
					
				</div>
				<div class="col-md-2">
					<input type="submit" class="btn btn-primary btn-block" name="search" value="Поиск">
				</div>
			</div>
		</form>
	</div>
</div>