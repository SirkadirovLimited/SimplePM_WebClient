<?php

/*
 * Copyright (C) 2018, Yurij Kadirov.
 * All rights are reserved.
 * Licensed under Apache License 2.0 with additional restrictions.
 *
 * @Author: Yurij Kadirov
 * @Website: https://sirkadirov.com/
 * @Email: admin@sirkadirov.com
 */

/*
 * Получаем идентификатор ассоциированого соревнования
 */

$associated_olymp = (int)(Security::getCurrentSession()["user_info"]->getUserInfo()["associated_olymp"]);

/*
 * Различные проверки безопасности
 */

if ($associated_olymp > 0)
	$_GET['id'] = $associated_olymp;

isset($_GET['id']) or Security::ThrowError("input");
$_GET['id'] = abs((int)$_GET['id']);

/*
 * Получаем  информацию  о  необходимом
 * нам  соревновании.  Если  ничего  не
 * найдено - отображаем страницу ошибки
 */

// Получаем полную информацию о соревновании
$olymp_info = Olymp::GetOlympiadInfo($_GET['id']);

// Дополнительная защита
if (!is_array($olymp_info))
	Security::ThrowError("404");

/*
 * Глобальные константы
 */

define("__PAGE_TITLE__", _("Інформація про змагання №") . $_GET['id']);
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

?>

<style>

	.jumbotron-header {

		position: relative;

		background-color: #343a40 !important;
		color: white !important;

		padding-top: 20px;
		padding-bottom: 20px;

	}

	.jumbotron-header p.lead {
		margin-bottom: 0;
	}

	a.card {
		color: #343a40;
	}

</style>

<div class="jumbotron jumbotron-fluid jumbotron-header">
	<div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; background-color: transparent; opacity: 0.1; z-index: 1;"></div>
	<div class="container" style="z-index: 2;">
		<h1><?=$olymp_info['name']?></h1>
		<p class="lead"><?=$olymp_info['description']?></p>
	</div>
</div>