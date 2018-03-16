<?php
	
	defined("__spm.user.edit__") or die('403 ACCESS DENIED');
	
	global $user_info;
	
	/*
	 * Загружаем в память необходимые файлы
	 */
	
	require_once(_S_INC_CLASS_ . "SimpleImage.php");
	
	/*
	 * Различные проверки безопасности
	 */
	
	if (!permission_check($_SESSION["permissions"], PERMISSION::administrator) &&
	($user_info["teacherId"] != $_SESSION["uid"]) &&
	($user_info["id"] != $_SESSION["uid"]))
		die(header('location: index.php?service=error&err=403'));
	
	/*
	 * Загрузка изображения на сервер
	 */
	
	if(!empty($_FILES['avatarFile']['name']) && $_FILES['avatarFile']['error'] == 0 && substr($_FILES['avatarFile']['type'], 0, 5) == 'image')
	{
		
		/*
		 * Пересохранение изображения с новым разрешением
		 */
		
		$imgc = new SimpleImage();
		$imgc->load($_FILES['avatarFile']['tmp_name']);
		
		$imgc->resizeToWidth(400);
		
		$imgc->save($_FILES['avatarFile']['tmp_name'], IMAGETYPE_JPEG, 100);
		
		/*
		 * Получаем исходники изображения
		 */
		
		$image = file_get_contents($_FILES['avatarFile']['tmp_name']);
		$image = mysqli_real_escape_string($db, $image);
		
		/*
		 * Формируем запрос к базе данных
		 */
		
		$query_str = "
			UPDATE
				`spm_users`
			SET
				`avatar` = '" . $image . "'
			WHERE
				`id` = '" . $user_info["id"] . "'
			LIMIT
				1
			;
		";
		
		/*
		 * Выполняем запрос к базе данных
		 */
		
		if(!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		/*
		 * Переадресация пользователя на страницу
		 * редактирования текущего профиля.
		 */
		
		exit(header('location: index.php?service=user.edit&id=' . $user_info["id"]));
		
	}
	else
		die(header('location: index.php?service=error&err=input'));
	
?>
