<?php DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED'); ?>
<li class="dropdown user user-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Выпадающее меню пользователя">
		&nbsp;<img src="index.php?service=image&uid=<?php print($_SESSION['uid']); ?>" class="user-image" alt="Аватар">
		<span class="hidden-xs"><?php print($_SESSION['full_name']); ?></span>&nbsp;
	</a>
	<ul class="dropdown-menu" style="border-color: #3c8dbc; padding: 0; border-radius: 0;">
		<li class="user-header">
			<img src="index.php?service=image&uid=<?php print($_SESSION['uid']); ?>" class="img-circle" alt="Аватар">
			<p style="color: white;">
				<?php print($_SESSION['full_name']); ?>
				<small>@<?php print($_SESSION['username']); ?></small>
			</p>
		</li>
		<!--li class="user-body">
			<ul class="nav nav-pills nav-stacked">
				<li role="presentation"><a href="index.php?service=bad_problems">Отложенные задачи <i class="fa pull-right"><span class="badge">0</span></i></a></li>
			</ul>
		</li-->
		<li class="user-footer">
			<div class="pull-left">
				<a href="index.php?service=user&id=<?php print($_SESSION['uid']); ?>" class="btn btn-default btn-flat">Профиль</a>
			</div>
			<div class="pull-right">
				<a href="index.php?service=logout" class="btn btn-default btn-flat">Выйти</a>
			</div>
		</li>
	</ul>
</li>