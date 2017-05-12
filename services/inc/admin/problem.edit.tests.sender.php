<?php
	if (isset($_POST['save'])){
		//Проверяем на правильность заполнения всех полей формы
		(isset($_POST['timeLimit']) && $_POST['timeLimit']>0)
			or die('<strong>Проверьте правильность заполнения полей формы!</strong>');
		(isset($_POST['memoryLimit']) && $_POST['memoryLimit']>0)
			or die('<strong>Проверьте правильность заполнения полей формы!</strong>');
		//Очищаем строки от возможных опасностей
		//id
		$_POST['id'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['id'])));
		//io
		@$_POST['input'] = mysqli_real_escape_string($db, $_POST['input']);
		@$_POST['output'] = mysqli_real_escape_string($db, $_POST['output']);
		//limits...
		$_POST['timeLimit'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['timeLimit'])));
		$_POST['memoryLimit'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['memoryLimit'])));
		
		(isset($_POST['input']) && strlen($_POST['input'])>0) or $_POST['input'] = "";
		(isset($_POST['output']) && strlen($_POST['output'])>0) or $_POST['output'] = "";
		
		//Сохраняем и применяем изменения
		//в базу данных SimplePM_WebClient
		$query_string = "UPDATE 
							`spm_problems_tests` 
						SET
							`input` = '" . $_POST['input'] . "',
							`output` = '" . $_POST['output'] . "',
							`timeLimit` = '" . $_POST['timeLimit'] . "',
							`memoryLimit` = '" . $_POST['memoryLimit'] . "'
						WHERE 
							`id` = '" . $_POST['id'] . "' 
						LIMIT 1;";
		if (!$db->query($query_string))
			die(header('location: index.php?service=error&err=db_error'));
		
		//Перекидываем пользователя на ту же страницу
		//на всякий случай.
		exit(header('location: '. $_SERVER["REQUEST_URI"]));
	} elseif (isset($_POST['del'])){
		//Очищаем строки от возможных опасностей
		$_POST['id'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['id'])));
		
		if (!$db->query("DELETE FROM `spm_problems_tests` WHERE `id` = '" . $_POST['id'] . "' LIMIT 1;"))
			die(header('location: index.php?service=error&err=db_error'));
		
		//Перекидываем пользователя на ту же страницу
		//на всякий случай.
		exit(header('location: '. $_SERVER["REQUEST_URI"]));
	} elseif (isset($_POST['addTest'])){
		//Добавляем новый пустой тест
		//для заданной задачи в базу данных
		if (!$db->query("INSERT INTO `spm_problems_tests` SET `problemId` = '" . (int)$_GET['id'] . "';"))
			die(header('location: index.php?service=error&err=db_error'));
		
		//Перекидываем пользователя на ту же страницу
		//на всякий случай.
		exit(header('location: '. $_SERVER["REQUEST_URI"]));
	}
?>