<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__spm.user.edit__") or die('403 Access Denied!');
?>
<div class="box box-solid box-success" id="settings">
	<div class="box-header">
		<h3 class="box-title">Настройки</h3>
	</div>
	<div class="box-body">
		<form>
			<h4>Основные настройки</h4>
			<label><input type="checkbox" checked> Панель навигации по-умолчанию открыта</label><br/>
			<label><input type="checkbox" checked> Использовать редактор кода с подсветкой синтаксиса</label><br/>
			<label><input type="checkbox" checked> Показывать всем, что я онлайн</label><br/>
			<h4>Email-оповещения</h4>
			<label><input type="checkbox" checked> Получать уведомления по email</label><br/>
			<label><input type="checkbox" checked> Уведомить меня о публикации новостей</label><br/>
			<input type="submit" class="btn btn-success btn-block" style="margin-top: 10px; margin-bottom: 0;" name="editSettings" value="Сохранить">
		</form>
	</div>
</div>