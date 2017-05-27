<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	mb_internal_encoding('UTF-8');
	$user_shortname = explode(" ", $_SESSION['full_name']);
?>
<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu">
			<?php if (isset($_SESSION["classwork"])): ?>
			
			<li class="header">РЕЖИМ УРОКА</li>
			<li><a href="index.php?service=classworks.problems"><i class="fa fa-users"></i> <span>Урок</span></a></li>
			<li><a href="index.php?service=classworks.result"><i class="fa fa-users"></i> <span>Рейтинг</span></a></li>
			
			<?php elseif (isset($_SESSION["olymp"])): ?>
			
			<li class="header">СОРЕВНОВАНИЕ</li>
			
			<?php else: ?>
			
			<li class="header">ГЛАВНОЕ МЕНЮ</li>
			<?php
				//user
				include_once(_S_TPL_ . "sidebar/user.inc.php");
				//student
				if (permission_check($_SESSION['permissions'], PERMISSION::student))
					include_once(_S_TPL_ . "sidebar/student.inc.php");
				//teacher
				if (permission_check($_SESSION['permissions'], PERMISSION::teacher))
					include_once(_S_TPL_ . "sidebar/teacher.inc.php");
				//admin
				if (permission_check($_SESSION['permissions'], PERMISSION::administrator))
					include_once(_S_TPL_ . "sidebar/admin.inc.php");
				//olymp
				//if (permission_check($_SESSION['permissions'], PERMISSION::olymp))
				//	include_once(_S_TPL_ . "sidebar/olymp.inc.php");
			?>
			
			<?php endif; ?>
		</ul>
	</section>
</aside>