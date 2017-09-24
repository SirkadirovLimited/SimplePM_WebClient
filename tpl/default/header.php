<header class="main-header">
	<a href="index.php" class="logo">
		<span class="logo-mini"><b>S</b>PM</span>
		<span class="logo-lg"><b>Simple</b>PM</span>
	</a>

	<nav class="navbar navbar-static-top" role="navigation">
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" title="Меню"></a>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				
				<!-- Translator widget  ->
				<?php $_SPM_CONF["BASE"]["ENABLE_TRANSLATOR"] && include(_S_MOD_ . "translator.php"); ?>
				
				<!-- Competitions -->
				<?php include(_S_MOD_ . "classwork.php"); ?>
				<?php include(_S_MOD_ . "olympiad.php"); ?>
				
				<!-- Menus  -->
				<?php if (!isset($_SESSION["classwork"]) && !isset($_SESSION["olymp"])): ?>
					
					<?php include(_S_MOD_ . "onlinemenu.php"); ?>
					
					<?php if ($_SPM_CONF["BASE"]["enable_additional_func"]): ?>
						<?php include(_S_MOD_ . "notifications.php"); ?>
						<?php include(_S_MOD_ . "birthdaysmenu.php"); ?>
					<?php endif; ?>
					
				<?php endif; ?>
				
				<!-- User menu -->
				<?php include(_S_MOD_ . "usermenu.php"); ?>
				
			</ul>
		</div>
	</nav>
</header>