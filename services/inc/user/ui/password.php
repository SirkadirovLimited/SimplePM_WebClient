<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__spm.user.edit__") or die('403 Access Denied!');
?>
<div class="box box-solid box-danger" id="editPass">
	<div class="box-header">
		<h3 class="box-title">Изменить пароль</h3>
	</div>
	<div class="box-body">
		<form action="index.php?service=user.edit&id=<?php print($_GET['id']); ?>" method="post">
			<div class="form-group">
				<label for="old-password">Текущий пароль</label>
				<input type="password" class="form-control" id="old-password" name="old-password" placeholder="*************" required>
			</div>
			
			<div class="form-group">
				<label for="password">Новый пароль</label>
				<input type="password" class="form-control" id="password" name="password" placeholder="*************" required>
			</div>
			<div class="form-group">
				<label for="password2">Повторите новый пароль</label>
				<input type="password" class="form-control" id="password2" name="password2" placeholder="*************" required>
			</div>
			
			<input type="submit" class="btn btn-danger btn-block" name="editPass" value="Сохранить">
		</form>
	</div>
</div>