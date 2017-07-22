<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__spm.user.edit__") or die('403 Access Denied!');
?>
<div class="box box-solid box-danger" id="editPass">
	<div class="box-header">
		<h3 class="box-title">Змінити пароль</h3>
	</div>
	<div class="box-body">
		<form action="index.php?service=user.edit&id=<?=$_GET['id']?>" method="post">
			<?php if ($_SESSION["uid"] == $_GET['id']): ?>
			<div class="form-group">
				<label for="old-password">Теперішній пароль</label>
				<input type="password" class="form-control" id="old-password" name="old-password" placeholder="*************">
			</div>
			<?php endif; ?>
			
			<div class="form-group">
				<label for="password">Новий пароль</label>
				<input type="password" class="form-control" id="password" name="password" placeholder="*************" required>
			</div>
			<div class="form-group">
				<label for="password2">Повторіть новий пароль</label>
				<input type="password" class="form-control" id="password2" name="password2" placeholder="*************" required>
			</div>
			
			<button type="submit" class="btn btn-danger btn-block btn-flat" name="editPass">Змінити пароль</button>
		</form>
	</div>
</div>