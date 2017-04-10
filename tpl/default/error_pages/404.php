<?php DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED'); ?>
<img src="<?php print(_S_MEDIA_IMG_ . "404.png"); ?>" class="img-responsive" width="100%" height="auto" />
<div align="center">
	<h1>Упс... данная страница не найдена!</h1>
	<p class="lead">Извините, но запрашиваемая вами страница не найдена. Проверьте ваш запрос или начните навигацию с главной страницы сайта.</p>
	<p><a href="<?php print($_SPM_CONF["BASE"]["SITE_URL"]); ?>" class="btn btn-primary">Перейти на главную</a></p>
</div>