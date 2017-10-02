<?php
	
	//Формируем название API модуля
	$moduleName = _S_API_ . @$_GET["module"] . ".php";
	
	//Различные проверки безопасности
	(isset($_GET["module"]) && file_exists($moduleName)) or die('404: Empire not found!');
	isset($_GET["command"]) or die('404: Birch Not Found');
	
	//Инклудим файл API
	include_once(_S_API_ . $_GET["module"] . ".php");
	
	//Формируем служебное название функции
	$functionName = "spmApi_" . $_GET["command"];
	
	//Проверяем на наличие API функции
	function_exists($functionName) or die('404: Command not found!');
	
	//Указываем, что будем передавать текстовые данные
	header('Content-Type: text/plain');
	
	//Вызываем запрашиваемую функцию API и выводим
	//результат её выполнения на экран
	print(call_user_func($functionName));
	
?>