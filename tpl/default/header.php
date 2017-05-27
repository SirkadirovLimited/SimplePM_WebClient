<?php DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED'); ?>
<header class="main-header">
	<a href="index.php" class="logo">
		<span class="logo-mini"><b>S</b>PM</span>
		<span class="logo-lg"><b>Simple</b>PM</span>
	</a>

	<nav class="navbar navbar-static-top" role="navigation">
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" title="Показать или скрыть главное меню системы"></a>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<?php include(_S_MOD_ . "classwork.php"); ?>
				<?php if (!isset($_SESSION["classwork"])): ?>
					<?php include(_S_MOD_ . "messagesmenu.php"); ?>
					<?php include(_S_MOD_ . "onlinemenu.php"); ?>
					<?php include(_S_MOD_ . "birthdaysmenu.php"); ?>
				<?php endif; ?>
				<?php include(_S_MOD_ . "usermenu.php"); ?>
			</ul>
		</div>
	</nav>
</header>