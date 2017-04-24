<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__spm.user.edit__") or die('403 Access Denied!');
?>
<div class="box box-solid box-default" id="editProfile">
	<div class="box-header">
		<h3 class="box-title">Редактирование профиля</h3>
	</div>
	<div class="box-body">
		<p class="text-danger"><b>Заполните лишь те поля, значения которых вы хотите изменить!</b></p>
		<form action="index.php?service=user.edit&id=<?php print($_GET['id']); ?>" method="post">
			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="<?php print($user_info['email']); ?>" value="">
				<p class="help-block">Email учащегося, на который приходят уведомления, новости и оповещения</p>
			</div>
			<div class="form-group">
				<label for="username">Имя пользователя</label>
				<input type="text" class="form-control" id="username" name="username" placeholder="<?php print($user_info['username']); ?>" value="">
				<p class="help-block">Имя пользователя, используется при входе в систему</p>
			</div>
			<div class="form-group">
				<label for="name-2">Фамилия</label>
				<input type="text" class="form-control" id="name-2" name="secondname" placeholder="<?php print($user_info['secondname']); ?>" value="">
				<p class="help-block">Фамилия учащегося</p>
			</div>
			<div class="form-group">
				<label for="name-1">Имя</label>
				<input type="text" class="form-control" id="name-1" name="firstname" placeholder="<?php print($user_info['firstname']); ?>" value="">
				<p class="help-block">Имя учащегося</p>
			</div>
			<div class="form-group">
				<label for="name-3">Отчество</label>
				<input type="text" class="form-control" id="name-3" name="thirdname" placeholder="<?php print($user_info['thirdname']); ?>" value="">
				<p class="help-block">Отчество учащегося</p>
			</div>
			<div class="form-group">
				<label for="date">Дата рождения</label>
				<input type="date" class="form-control" id="date" name="bdate" placeholder="<?php print($user_info['bdate']); ?>" value="">
				<p class="help-block">Дата рождения учащегося в формате Linux (ГГГГ-ММ-ДД)</p>
			</div>
			<div class="form-group">
				<label for="country">Страна / Регион</label>
				<select type="text" class="form-control" id="country" name="country" placeholder="<?php print($user_info['country']); ?>">
<?php
	foreach ($SPM_Countries_Select as $countryArr){
		if ($user_info['country'] == $countryArr[0])
			$selectedCountry = " selected";
		else
			$selectedCountry = "";
?>
					<option value="<?php print($countryArr[0]); ?>"<?php print($selectedCountry); ?>><?php print($countryArr[1]); ?></option>
<?php
	}
?>
				</select>
				<p class="help-block">Страна или регион учащегося</p>
			</div>
			<div class="form-group">
				<label for="city">Город / Населённый пункт</label>
				<input type="text" class="form-control" id="city" name="city" placeholder="<?php print($user_info['city']); ?>" value="">
				<p class="help-block">Город проживания учащегося</p>
			</div>
			<div class="form-group">
				<label for="school">Учебное заведение</label>
				<input type="text" class="form-control" id="school" name="school" placeholder="<?php print($user_info['school']); ?>" value="">
				<p class="help-block">Текущее учебное заведение учащегося</p>
			</div>
			<div class="form-group">
				<label for="group">Группа</label>
				<input type="text" class="form-control" id="group" name="group" placeholder="<?php print($user_info['group']); ?>" value="">
				<p class="help-block">Группа или класс учащегося</p>
			</div>
			
			<input type="submit" class="btn btn-primary btn-block" name="editProfile" value="Сохранить">
		</form>
	</div>
</div>