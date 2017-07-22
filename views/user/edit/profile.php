<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__spm.user.edit__") or die('403 Access Denied!');
?>
<div class="box box-solid box-default" id="editProfile">
	<div class="box-header">
		<h3 class="box-title">Редагування профіля</h3>
	</div>
	<div class="box-body">
		<p class="text-danger"><b>Заповніть лише ті поля, що хочете змінити!</b></p>
		<form action="index.php?service=user.edit&id=<?=$_GET['id']?>" method="post">
			
			<div class="panel-group" id="accon">
  
				<div class="panel panel-default" style="border-radius: 0;">
					<div class="panel-heading" style="border-radius: 0;">
						<h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accon" href="#contParams">Контактні дані</a>
						</h4>
					</div>
					<div id="contParams" class="panel-collapse collapse" style="border-radius: 0;">
						<div class="panel-body">
							
							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="<?=$user_info['email']?>" value="<?=$user_info['email']?>">
								<p class="help-block">Email, на який будуть приходити сповіщення</p>
							</div>
							<div class="form-group">
								<label for="phone">Телефон</label>
								<input type="phone" class="form-control" id="phone" name="phone" placeholder="<?=$user_info['phone']?>" value="<?=$user_info['phone']?>">
								<p class="help-block">Контактний телефон, на який може будь-хто подзвонити</p>
							</div>
							<div class="form-group">
								<label for="username">Ім'я користувача</label>
								<input type="text" class="form-control" id="username" name="username" placeholder="<?=$user_info['username']?>" value="<?=$user_info['username']?>">
								<p class="help-block">Ім'я користувача, що буде використовуватись при вході в систему</p>
							</div>
							
						</div>
					</div>
				</div>
				
				<div class="panel panel-default" style="border-radius: 0;">
					<div class="panel-heading" style="border-radius: 0;">
						<h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accon" href="#personParams">Персональні дані</a>
						</h4>
					</div>
					<div id="personParams" class="panel-collapse collapse" style="border-radius: 0;">
						<div class="panel-body">
							
							<div class="form-group">
								<label for="name-2">Фамілія</label>
								<input type="text" class="form-control" id="name-2" name="secondname" placeholder="<?=$user_info['secondname']?>" value="<?=$user_info['secondname']?>">
							</div>
							<div class="form-group">
								<label for="name-1">Ім'я</label>
								<input type="text" class="form-control" id="name-1" name="firstname" placeholder="<?=$user_info['firstname']?>" value="<?=$user_info['firstname']?>">
							</div>
							<div class="form-group">
								<label for="name-3">По-батькові</label>
								<input type="text" class="form-control" id="name-3" name="thirdname" placeholder="<?=$user_info['thirdname']?>" value="<?=$user_info['thirdname']?>">
							</div>
							<div class="form-group">
								<label for="date">Дата народження</label>
								<input type="date" class="form-control" id="date" name="bdate" placeholder="<?=$user_info['bdate']?>" value="<?=$user_info['bdate']?>">
								<p class="help-block">Дата народження в форматі Linux (РРРР-ММ-ДД)</p>
							</div>
							
						</div>
					</div>
				</div>
				
				<div class="panel panel-default" style="border-radius: 0;">
					<div class="panel-heading" style="border-radius: 0;">
						<h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accon" href="#geoParams">Геолокация</a>
						</h4>
					</div>
					<div id="geoParams" class="panel-collapse collapse" style="border-radius: 0;">
						<div class="panel-body">
							
							<div class="form-group">
								<label for="country">Країна / регіон</label>
								<select class="form-control" id="country" name="country">
								
									<?php
										foreach ($SPM_Countries_Select as $countryArr):
										$selectedCountry = ($user_info['country'] == $countryArr[0] ? " selected" : "");
									?>
									
									<option value="<?=$countryArr[0]?>"<?=$selectedCountry?>><?=$countryArr[1]?></option>
									
									<?php endforeach; ?>
								
								</select>
								<p class="help-block">Країна чи регіон проживання (навчання)</p>
							</div>
							
							<div class="form-group">
								<label for="city">Місто / населений пункт</label>
								<input type="text" class="form-control" id="city" name="city" placeholder="<?=$user_info['city']?>" value="<?=$user_info['city']?>">
								<p class="help-block">Місто чи населений пункт проживання (навчання)</p>
							</div>
							
						</div>
					</div>
				</div>
				
				<div class="panel panel-default" style="border-radius: 0;">
					<div class="panel-heading" style="border-radius: 0;">
						<h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accon" href="#eduParams">Навчальний заклад та група</a>
						</h4>
					</div>
					<div id="eduParams" class="panel-collapse collapse" style="border-radius: 0;">
						<div class="panel-body">
							
							<div class="form-group">
								<label for="school">Навчальний заклад</label>
								<input type="text" class="form-control" id="school" name="school" placeholder="<?=$user_info['school']?>" value="<?=$user_info['school']?>">
							</div>
							<div class="form-group">
								<label for="group">Група</label>
								<select class="form-control" id="group" name="group">
									
									<?php
										
										$query_str = "
											SELECT
												`id`,
												`name`
											FROM
												`spm_users_groups`
											WHERE
												`teacherId` = '" . $user_info['teacherId'] . "'
											;
										";
										
										if (!$query = $db->query($query_str))
											die(header('location: index.php?service=error&err=db_error'));
										
										while ($group = $query->fetch_assoc()):
											$selectedGroup = ($user_info['group'] == $group['id'] ? " selected" : "");
									?>
									
									<option value="<?=$group['id']?>"<?=$selectedGroup?>><?=$group['name']?></option>
									
									<?php endwhile; ?>
									
								</select>
								<p class="help-block">Група чи клас, ці дані надасть вам ваш вчитель</p>
							</div>
							
						</div>
					</div>
				</div>
				
			</div>
			
			<button type="submit" class="btn btn-primary btn-block btn-flat" name="editProfile">Зберегти зміни</button>
		</form>
	</div>
</div>