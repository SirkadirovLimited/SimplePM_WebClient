<?php
	
	//Устанавливаем, что пользователь оффлайн
	spm_setUserOnline($_SESSION["uid"], false);
	
	//Очищаем переменную сессии
	unset($_SESSION);
	
	//Уничтожаем сессию
	session_destroy();
	
	//Перенаправляем пользователя на страницу входа
	header("location: index.php");
	
?>