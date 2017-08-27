<?php
	defined("__spm.user.edit__") or die('403 Access Denied!');
?>
<div class="box box-primary" id="editAvatar">
	<div class="box-header">
		<h3 class="box-title">Змінити аватар</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
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
						<label for="avatarFile">Виберіть файл</label>
						<input type="file" class="form-control" id="avatarFile" name="avatarFile" required>
						<p class="help-block">Зображення у форматі JPG / JPEG / PNG / GIF.</p>
					</div>
					<button type="submit" class="btn btn-primary btn-block btn-flat" name="editAvatar">Завантажити</button>
					<!--/AVATAR UPLOAD-->
				</form>
			</div>
		</div>
	</div>
</div>