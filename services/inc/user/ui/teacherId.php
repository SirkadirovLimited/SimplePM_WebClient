<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__spm.user.edit__") or die('403 Access Denied!');
?>
<div class="box box-solid box-warning" id="editTeacherID">
	<div class="box-header">
		<h3 class="box-title">Изменить TeacherID</h3>
	</div>
	<div class="box-body">
		<form action="" method="post">
			<div class="form-group">
				<label for="teacherID">Новый TeacherID</label>
				<input type="text" class="form-control" id="teacherID" name="teacherID" placeholder="*************" required>
			</div>
			
			<div class="form-group">
				<label for="password">Ваш пароль</label>
				<input type="password" class="form-control" id="password" name="password" placeholder="*************" required>
			</div>
			
			<input type="submit" class="btn btn-warning btn-block" name="editTeacherID" value="Применить" onclick="return confirm('Вы действительно хотите изменить TeacherID? Ваши текущие достижения могут быть утеряны!');">
		</form>
	</div>
</div>