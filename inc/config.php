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
	$_SPM_CONF["BASE"]["ADMIN_MAIL"] = "admin@sirkadirov.com"; //email администратора
	$_SPM_CONF["BASE"]["ONLINE_TIME"] = 10 * 60; //каждое действие гарантирует 10 минутный онлайн
	$_SPM_CONF["BASE"]["DEFAULT_LOCALE"] = "ru";
	date_default_timezone_set('Europe/Kiev'); //временная зона, подробнее на http://php.net/manual/ru/timezones.php
	/*BASE_CONFIGURATION-END*/
	
	/*DB-CONFIG-START*/
	$_SPM_CONF["DB"]["user"] = "Sirkadirov"; //имя пользователя
	$_SPM_CONF["DB"]["pass"] = "Dam900000zaua"; //пароль
	$_SPM_CONF["DB"]["host"] = "localhost"; //ip или домен сервера
	$_SPM_CONF["DB"]["name"] = "simplepm"; //имя базы данных
	/*DB-CONFIG-END*/
	
	/*SERVICES-START*/
	$_SPM_CONF["SERVICE"]["agreement"] = "agreement.php";
	
	$_SPM_CONF["SERVICE"]["register"] = "login/register.php"; //nologin
	$_SPM_CONF["SERVICE"]["login"] = "login/login.php"; //nologin
	$_SPM_CONF["SERVICE"]["forgot"] = "login/forgot.php"; //nologin
	$_SPM_CONF["SERVICE"]["logout"] = "login/logout.php"; //require session(uid)
	
	//                             BASE SERVICES
	$_SPM_CONF["SERVICE"]["news"] = "news.php"; //require session(uid)
	$_SPM_CONF["SERVICE"]["view"] = "view.php"; //require session(uid)
	$_SPM_CONF["SERVICE"]["rating"] = "rating.php"; //require session(uid)
	$_SPM_CONF["SERVICE"]["error"] = "error.php"; //require session(uid)
	
	//                             PROBLEMS SERVICES
	$_SPM_CONF["SERVICE"]["problems"] = "problems/problems.php"; //require >=student
	$_SPM_CONF["SERVICE"]["bad_problems"] = "problems/bad_problems.php"; //require student
	$_SPM_CONF["SERVICE"]["problem"] = "problems/problem.php"; //require student
	
	$_SPM_CONF["SERVICE"]["problem.edit"] = "admin/problems/problem.edit.php"; //require administrator
	$_SPM_CONF["SERVICE"]["problem.edit.tests"] = "admin/problems/problem.edit.tests.php"; //require administrator
	
	$_SPM_CONF["SERVICE"]["problem_send"] = "problems/problem_send.php"; //require student
	$_SPM_CONF["SERVICE"]["problem_result"] = "problems/problem_result.php"; //require student
	
	//                             CLASSWORKS SERVICES
	$_SPM_CONF["SERVICE"]["classworks"] = "classworks/classworks.php"; //require teacher
	$_SPM_CONF["SERVICE"]["classworks.edit"] = "classworks/classworks.edit.php"; //require teacher
	$_SPM_CONF["SERVICE"]["classworks.result"] = "classworks/classworks.result.php"; //require session(uid)
	
	//                             OLYMPIADS SERVICES
	$_SPM_CONF["SERVICE"]["olympiads"] = "olymp/olympiads.php"; //require session(uid)
	
	//                             USER SERVICES
	$_SPM_CONF["SERVICE"]["user"] = "user/user.php"; //require session(uid)
	$_SPM_CONF["SERVICE"]["user.edit"] = "user/user.edit.php"; //require session(uid)
	
	//                             FILES HOSTING SERVICES
	$_SPM_CONF["SERVICE"]["image"] = "image.php"; //require session(uid)
	
	//                             MESSAGING SERVICE
	$_SPM_CONF["SERVICE"]["messages"] = "messages/messages.php"; //require session(uid)
	
	//                             ADMIN SERVICES
	$_SPM_CONF["SERVICE"]["admin"] = "admin/admin.php"; //require permission 256
	
	$_SPM_CONF["SERVICE"]["view.admin"] = "admin/view.admin.php"; //require permission 256
	$_SPM_CONF["SERVICE"]["news.admin"] = "admin/news.admin.php"; //require permission 256
	
	$_SPM_CONF["SERVICE"]["teacherID"] = "admin/teacherID.php"; //require teacher/admin
	$_SPM_CONF["SERVICE"]["users.admin"] = "admin/users.admin.php"; //require teacher/admin
	$_SPM_CONF["SERVICE"]["groups.admin"] = "admin/groups.admin.php"; //require teacher/admin
	/*SERVICES-END*/
	
	/*SERVICES_NOLOGIN-START*/
	$_SPM_CONF["SERVICE_NOLOGIN"]["login"] = $_SPM_CONF["SERVICE"]["login"];
	$_SPM_CONF["SERVICE_NOLOGIN"]["register"] = $_SPM_CONF["SERVICE"]["register"];
	$_SPM_CONF["SERVICE_NOLOGIN"]["forgot"] = $_SPM_CONF["SERVICE"]["forgot"];
	$_SPM_CONF["SERVICE_NOLOGIN"]["agreement"] = $_SPM_CONF["SERVICE"]["agreement"];
	/*SERVICES_NOLOGIN-END*/
	
	/*SERVICES_SETTINGS-START*/
	$_SPM_CONF["SERVICES"]["_AUTOSTART_SERVICE_"] = "news";
	$_SPM_CONF["SERVICES"]["news"]["articles_per_page"] = 5;
	$_SPM_CONF["SERVICES"]["rating"]["articles_per_page"] = 30;
	$_SPM_CONF["SERVICES"]["problems"]["articles_per_page"] = 30;
	$_SPM_CONF["SERVICES"]["messages"]["max_messages_to_show"] = 1000;
	/*SERVICES_SETTINGS-END*/
	
	/*PASSWORD_SETTINGS-START*/
	$_SPM_CONF["PASSWD"]["minlength"] = 4; //Минимальная длина пароля
	$_SPM_CONF["PASSWD"]["maxlength"] = 40; //Максимальная длина пароля
	/*PASSWORD_SETTINGS-END*/
	
	/*TEACHERID_SETTINGS-START*/
	$_SPM_CONF["TEACHERID"]["length"] = 5; //Длина пароля TeacherID
	/*TEACHERID_SETTINGS-END*/
	
	/*SECURITY-START*/
	$_SPM_CONF["SECURITY"]["require_captcha"] = false; //Требовать ввода капчи при
	                                                  //анонимном использовании сайта
	$_SPM_CONF["SECURITY"]["alpha_version_warning"] = true;
	/*SECURITY-END*/
	
	/*PROGRAMMING_LANGUAGES-START*/
	$_SPM_CONF["PROG_LANGS"]["pascal"] = true;
	$_SPM_CONF["PROG_LANGS"]["csharp"] = true;
	$_SPM_CONF["PROG_LANGS"]["cpp"] = true;
	$_SPM_CONF["PROG_LANGS"]["c"] = true;
	$_SPM_CONF["PROG_LANGS"]["lua"] = true;
	$_SPM_CONF["PROG_LANGS"]["python"] = true;
	$_SPM_CONF["PROG_LANGS"]["java"] = false;
	/*PROGRAMMING_LANGUAGES-END*/
	
	function spm_prepare_classwork(){
		
		global $_SPM_CONF;
		unset($_SPM_CONF["SERVICE"]);
		
		//Available services
		$_SPM_CONF["SERVICE"]["logout"] = "login/logout.php";
		
		$_SPM_CONF["SERVICE"]["classworks.problems"] = "classworks/user/problems.php";
		$_SPM_CONF["SERVICE"]["classworks.result"] = "classworks/classworks.result.php";
		
		$_SPM_CONF["SERVICE"]["image"] = "image.php";
		
		$_SPM_CONF["SERVICE"]["problem"] = "problems/problem.php";
		$_SPM_CONF["SERVICE"]["problem_send"] = "problems/problem_send.php";
		$_SPM_CONF["SERVICE"]["problem_result"] = "problems/problem_result.php";
		
		$_SPM_CONF["SERVICE"]["error"] = "error.php";
		
		//Autostart service (main service)
		$_SPM_CONF["SERVICES"]["_AUTOSTART_SERVICE_"] = "classworks.problems";
		
	}
?>