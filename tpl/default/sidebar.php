<?php
	
	function spm_isPageCurrent($service) {
		
		if (spm_onService($service))
			return "class='active'";
		else
			return "";
		
	}
	
?>

<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu">
			<?php if (isset($_SESSION["classwork"])): ?>
			
			<li class="header">РЕЖИМ УРОКУ</li>
			<li><a href="index.php?service=classworks.problems"><i class="fa fa-th-list"></i> <span>Урок</span></a></li>
			<li><a href="index.php?service=classworks.result"><i class="fa fa-star"></i> <span>Рейтинг</span></a></li>
			
			<?php elseif (isset($_SESSION["olymp"])): ?>
			
			<li class="header">РЕЖИМ ЗМАГАННЯ</li>
			<li><a href="index.php?service=olympiads.problems"><i class="fa fa-th-list"></i> <span>Змагання</span></a></li>
			<li><a href="index.php?service=olympiads.result"><i class="fa fa-star"></i> <span>Рейтинг</span></a></li>
			
			<?php else: ?>
			
			<li class="header">ГОЛОВНЕ МЕНЮ</li>
			<?php
				// user
				include_once(_S_TPL_ . "sidebar/user.inc.php");
				// student
				if (permission_check($_SESSION['permissions'], PERMISSION::student | PERMISSION::teacher | PERMISSION::administrator))
					include_once(_S_TPL_ . "sidebar/student.inc.php");
				// teacher
				if (permission_check($_SESSION['permissions'], PERMISSION::teacher))
					include_once(_S_TPL_ . "sidebar/teacher.inc.php");
				// olympiads
				if (permission_check($_SESSION['permissions'], PERMISSION::administrator | PERMISSION::olymp))
					include_once(_S_TPL_ . "sidebar/olymp.inc.php");
				// admin
				if (permission_check($_SESSION['permissions'], PERMISSION::administrator))
					include_once(_S_TPL_ . "sidebar/admin.inc.php");
			?>
			
			<?php endif; ?>
		</ul>
	</section>
</aside>