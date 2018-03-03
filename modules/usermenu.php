<?php
	
	$enablelinks = !isset($_SESSION["classwork"]) && !isset($_SESSION["olymp"]);
	
?>
<li class="dropdown user user-menu">
	
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Меню користувача">
		
		&nbsp;<img src="index.php?service=image&uid=<?=$_SESSION['uid']?>" class="user-image" alt="Аватар">
		<span class="hidden-xs"><?=spm_getUserShortnameByID($_SESSION['uid'])?></span>&nbsp;
		
	</a>
	
	<ul class="dropdown-menu" style="border-color: #3c8dbc; padding: 0; border-radius: 0;">
		
		<li class="user-header">
			<img src="index.php?service=image&uid=<?=$_SESSION['uid']?>" class="img-circle" alt="Аватар">
			<p style="color: white;">
				<?=spm_getUserFullnameByID($_SESSION['uid'])?>
				<small>@<?=spm_getUsernameByID($_SESSION['uid'])?></small>
			</p>
		</li>
		
		<?php if ($enablelinks): ?>
		<li class="user-body" style="padding: 0;">
			<ul class="nav nav-pills nav-stacked" style="padding: 0;">
				
				<li><a href="index.php?service=user&id=<?=$_SESSION['uid']?>"><i class="fa fa-user"></i>Мій профіль</a></li>
				
				<?php if (permission_check($_SESSION["permissions"], PERMISSION::student)): ?>
				<li><a href="index.php?service=olympiads"><i class="fa fa-book"></i> Олімпіадний режим</a></li>
				<?php endif; ?>
				
			</ul>
		</li>
		<?php endif; ?>
		
		<li class="user-footer">
			<div class="pull-right">
				<a href="index.php?service=logout" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Вийти</a>
			</div>
		</li>
		
	</ul>
	
</li>