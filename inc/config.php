<?php
	/*
	 * SimpleProblemsManager (Web interface)
	 * COPYRIGHT (C) 2016, KADIROV YURIJ. ALL RIGHTS ARE RESERVED.
	 * PUBLIC DISTRIBUTION RESTRICTED!
	 */
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	/*
	** MAIN SimplePM CONFIGURATION FILE
	*/
	/*BASE_CONFIGURATION-START*/
	$_SPM_CONF["BASE"]["SITE_NAME"] = "SimplePM"; //имя сайта
	$_SPM_CONF["BASE"]["SITE_DESCRIPTION"] = "SPM test site."; //краткое описание сайта
	$_SPM_CONF["BASE"]["SITE_KEYWORDS"] = "SPM,demo,site,website,php,sirkadirov"; //ключевые слова сайта
	$_SPM_CONF["BASE"]["SITE_URL"] = $_SERVER['HTTP_HOST']; //адрес сайта (можно использовать $_SERVER['HTTP_HOST'])
	$_SPM_CONF["BASE"]["TPL_NAME"] = "default"; //имя папки шаблона
	/*BASE_CONFIGURATION-END*/
	
	/*DB-CONFIG-START*/
	$_SPM_CONF["DB"]["user"] = "Sirkadirov"; //имя пользователя
	$_SPM_CONF["DB"]["pass"] = "Dam900000zaua"; //пароль
	$_SPM_CONF["DB"]["host"] = "localhost"; //ip или домен сервера
	$_SPM_CONF["DB"]["name"] = "simplepm"; //имя базы данных
	/*DB-CONFIG-END*/
	
	/*SERVICES-START*/
	$_SPM_CONF["SERVICE"]["_AUTOSTART_SERVICE_"] = "news"; //Главный сервис
	
	$_SPM_CONF["SERVICE"]["agreement"] = "agreement.php";
	
	$_SPM_CONF["SERVICE"]["register"] = "login/register.php"; //nologin
	$_SPM_CONF["SERVICE"]["login"] = "login/login.php"; //nologin
	$_SPM_CONF["SERVICE"]["forgot"] = "login/forgot.php"; //nologin
	$_SPM_CONF["SERVICE"]["logout"] = "login/logout.php"; //require session(uid)
	
	//                             BASE SERVICES
	$_SPM_CONF["SERVICE"]["news"] = "news.php"; //require session(uid)
	$_SPM_CONF["SERVICE"]["view"] = "view.php"; //require session(uid)
	$_SPM_CONF["SERVICE"]["rating"] = "rating.php"; //require session(uid)
	
	//                             PROBLEMS SERVICES
	$_SPM_CONF["SERVICE"]["problems"] = "problems/problems.php"; //require >=student
	$_SPM_CONF["SERVICE"]["bad_problems"] = "problems/bad_problems.php"; //require student
	$_SPM_CONF["SERVICE"]["problem"] = "problems/problem.php"; //require student
	
	$_SPM_CONF["SERVICE"]["problem.edit"] = "admin/problems/problem.edit.php"; //require administrator
	$_SPM_CONF["SERVICE"]["problem.edit.tests"] = "admin/problems/problem.edit.tests.php"; //require administrator
	
	$_SPM_CONF["SERVICE"]["problem_send"] = "problems/problem_send.php"; //require student
	$_SPM_CONF["SERVICE"]["problem_result"] = "problems/problem_result.php"; //require student
	
	//                             OLYMPIADS SERVICES
	$_SPM_CONF["SERVICE"]["olympiads"] = "olympiads/olympiads.php"; //require teacher/administrator
	$_SPM_CONF["SERVICE"]["olympiads.edit"] = "olympiads/olympiads.edit.php"; //require teacher/administrator
	$_SPM_CONF["SERVICE"]["olympiads.result"] = "olympiads/olympiads.result.php"; //require session(uid)
	
	//                             USER SERVICES
	$_SPM_CONF["SERVICE"]["user"] = "user/user.php"; //require session(uid)
	$_SPM_CONF["SERVICE"]["user.edit"] = "user/user.edit.php"; //require session(uid)
	
	//                             FILES HOSTING SERVICES
	$_SPM_CONF["SERVICE"]["image"] = "image.php"; //require session(uid)
	
	//                             MESSAGING SERVICE
	$_SPM_CONF["SERVICE"]["messages.list"] = "messages/messages.list.php"; //require session(uid)
	$_SPM_CONF["SERVICE"]["messages.send"] = "messages/messages.send.php"; //require session(uid)
	
	//                             ADMIN SERVICES
	$_SPM_CONF["SERVICE"]["admin"] = "admin/admin.php"; //require permission 256
	
	$_SPM_CONF["SERVICE"]["view.admin"] = "admin/view.admin.php"; //require permission 256
	$_SPM_CONF["SERVICE"]["news.admin"] = "admin/news.admin.php"; //require permission 256
	
	$_SPM_CONF["SERVICE"]["teacherID"] = "admin/teacherID.php"; //require teacher/admin
	$_SPM_CONF["SERVICE"]["users.admin"] = "admin/users.admin.php"; //require teacher/admin
	/*SERVICES-END*/
	
	/*SERVICES_NOLOGIN-START*/
	$_SPM_CONF["SERVICE_NOLOGIN"]["login"] = $_SPM_CONF["SERVICE"]["login"];
	$_SPM_CONF["SERVICE_NOLOGIN"]["register"] = $_SPM_CONF["SERVICE"]["register"];
	$_SPM_CONF["SERVICE_NOLOGIN"]["forgot"] = $_SPM_CONF["SERVICE"]["forgot"];
	$_SPM_CONF["SERVICE_NOLOGIN"]["agreement"] = $_SPM_CONF["SERVICE"]["agreement"];
	/*SERVICES_NOLOGIN-END*/
	
	/*ERROR_PAGES-START*/
	$_SPM_CONF["ERR_PAGE"]["404"] = "404.php"; //Страница не найдена
	$_SPM_CONF["ERR_PAGE"]["access_denied"] = "access_denied.php"; //Доступ запрещён
	$_SPM_CONF["ERR_PAGE"]["403"] = "access_denied.php"; //Доступ запрещён
	$_SPM_CONF["ERR_PAGE"]["unauthorized"] = "unauthorized.php"; //Неавторизирован
	$_SPM_CONF["ERR_PAGE"]["401"] = "unauthorized.php"; //Доступ запрещён
	/*ERROR_PAGES-END*/
	
	/*SERVICES_SETTINGS-START*/
	$_SPM_CONF["SERVICES"]["news"]["articles_per_page"] = 5;
	$_SPM_CONF["SERVICES"]["rating"]["articles_per_page"] = 30;
	$_SPM_CONF["SERVICES"]["problems"]["articles_per_page"] = 30;
	/*SERVICES_SETTINGS-END*/
	
	/*PASSWORD_SETTINGS-START*/
	$_SPM_CONF["PASSWD"]["minlength"] = 4; //Минимальная длина пароля
	$_SPM_CONF["PASSWD"]["maxlength"] = 40; //Максимальная длина пароля
	/*PASSWORD_SETTINGS-END*/
	
	/*TEACHERID_SETTINGS-START*/
	$_SPM_CONF["TEACHERID"]["length"] = 15; //Длина пароля TeacherID
	/*TEACHERID_SETTINGS-END*/
	
	/*SECURITY-START*/
	$_SPM_CONF["SECURITY"]["require_captcha"] = false; //Требовать ввода капчи при
	                                                  //анонимном использовании сайта
	/*SECURITY-END*/
?>