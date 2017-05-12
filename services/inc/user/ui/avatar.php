<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__spm.user.edit__") or die('403 Access Denied!');
?>
<div class="box box-solid box-primary" id="editAvatar">
	<div class="box-header">
		<h3 class="box-title">Изменить аватар</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-4">
				<style>
					.userAvatar {
						width: 100%;
						heigth: auto;
						box-shadow: 3px 5px 15px -7px #000000;
						margin-top: 10px;
						margin-bottom: 10px;
					}
				</style>
				<img src="index.php?service=image&uid=<?=$_GET['id']?>" class="userAvatar" />
			</div>
			<div class="col-md-8">
				<form enctype="multipart/form-data" action="" method="post">
					<!--AVATAR UPLOAD-->
					<div class="form-group">
						<label for="avatarFile">Файл аватара</label>
						<input type="file" class="form-control" id="avatarFile" name="avatarFile">
						<p class="help-block">Изображение JPG / JPEG / PNG / GIF разрешением не менее 480х320 и не более 1280х720</p>
					</div>
					<input type="submit" class="btn btn-primary btn-block" name="editAvatar" value="Загрузить">
					<input type="submit" class="btn btn-danger btn-block" name="delAvatar" value="Заменить на стандартный">
					<!--/AVATAR UPLOAD-->
				</form>
			</div>
		</div>
	</div>
</div>