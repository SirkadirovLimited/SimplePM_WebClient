<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	!isset($_SESSION["uid"]) or SPM_header("Ошибка 403", "Доступ запрещён!");
?>
<img src="<?=_S_MEDIA_IMG_?>403.jpg" class="img-responsive" />
<div align="center" style="margin-top: 20px;">
	<p class="lead">Доступ к данному разделу сайта вам не предоставлен! Информация о попытке доступа отправлена администратору сайта.</p>
	<h4><strong>Возможные причины возникновения ошибки:</strong></h4>
	<p>
		<ul>
			<li>Вы не имеете прав для просмотра данной страницы</li>
			<li>Вы не авторизированы</li>
			<li>Ваш аккаунт был заблокирован</li>
			<li>Произошла внутренняя ошибка сервера</li>
			<li>Проводится особо важное соревнование либо тестирование</li>
			<li>Система SimplePM находится в стадии обновления</li>
		</ul>
	</p>
	<a href="index.php" class="btn btn-primary btn-flat btn-block">На главную</a>
</div>
<?php !isset($_SESSION["uid"]) or SPM_footer(); ?>