<?php
	/*
	 * SimpleProblemsManager (Web interface)
	 * COPYRIGHT (C) 2016, KADIROV YURIJ. ALL RIGHTS ARE RESERVED.
	 * PUBLIC DISTRIBUTION RESTRICTED!
	 */
	/*
	** MAIN SimplePM CONFIGURATION FILE
	*/
	/*BASE_CONFIGURATION-START*/
	$_SPM_CONF["BASE"]["SITE_NAME"] = "SimplePM"; // назва сайту
	$_SPM_CONF["BASE"]["SITE_DESCRIPTION"] = "Тестовий сайт SimplePM"; // головна теза сайту
	$_SPM_CONF["BASE"]["SITE_URL"] = $_SERVER['HTTP_HOST']; //аадреса сайту (використовувати значення $_SERVER['HTTP_HOST'] дозволено)
	
	$_SPM_CONF["BASE"]["debug"] = false; // функціонал режиму тестування системи. false для відключення.
	$_SPM_CONF["BASE"]["enable_additional_func"] = true; // деякий користувацький функціонал може бути корисним, але його використання в деяких випадках не рекомендоване.
	
	$_SPM_CONF["BASE"]["TPL_NAME"] = "default"; // ім'я папки шаблону сайта
	$_SPM_CONF["BASE"]["TPL_TYPE_BOXED"] = false; // використовувати шаблон фіксованої довжини
	
	$_SPM_CONF["BASE"]["ADMIN_MAIL"] = "admin@sirkadirov.com"; //email адміністратора
	
	$_SPM_CONF["BASE"]["ONLINE_TIME"] = 10 * 60; // кожна дія, що зроблена користувачем залишає за собою статус онлайн на 10 хвилин
	
	$_SPM_CONF["BASE"]["ENABLE_TRANSLATOR"] = false; // дати можливість користувачам використовувати перекладач на сторінках системи
	date_default_timezone_set('Europe/Kiev'); // часова зона, дивіться на сторінці http://php.net/manual/ru/timezones.php
	/*BASE_CONFIGURATION-END*/
	
	/*DB_CONFIG-START*/
	$_SPM_CONF["DB"]["user"] = "Sirkadirov"; // ім'я користувача СУБД
	$_SPM_CONF["DB"]["pass"] = "Dam900000zaua"; // пароль користувача СУБД
	$_SPM_CONF["DB"]["host"] = "localhost"; //ip адреса чи домен серверу баз даних
	$_SPM_CONF["DB"]["name"] = "simplepm"; // ім'я бази даних
	/*DB_CONFIG-END*/
	
	/*SERVICES-START*/
	//                             LOGIN SERVICES
	$_SPM_CONF["SERVICE"]["register"] = "login/register.php";
	$_SPM_CONF["SERVICE"]["login"] = "login/login.php";
	$_SPM_CONF["SERVICE"]["forgot"] = "login/forgot.php";
	$_SPM_CONF["SERVICE"]["logout"] = "login/logout.php";
	
	//                             BASE SERVICES
	$_SPM_CONF["SERVICE"]["home"] = "home.php";
	$_SPM_CONF["SERVICE"]["view"] = "view.php";
	$_SPM_CONF["SERVICE"]["rating"] = "rating.php";
	$_SPM_CONF["SERVICE"]["error"] = "error.php";
	
	//                             API SERVICES
	$_SPM_CONF["SERVICE"]["api"] = "api.php";
	
	//                             PROBLEMS SERVICES
	$_SPM_CONF["SERVICE"]["problems"] = "problems/problems.php";
	$_SPM_CONF["SERVICE"]["bad_problems"] = "problems/bad_problems.php";
	$_SPM_CONF["SERVICE"]["problem"] = "problems/problem.php";
	
	$_SPM_CONF["SERVICE"]["submissions"] = "problems/submissions.php";
	
	$_SPM_CONF["SERVICE"]["problem.edit"] = "admin/problems/problem.edit.php";
	$_SPM_CONF["SERVICE"]["problem.edit.tests"] = "admin/problems/problem.edit.tests.php";
	$_SPM_CONF["SERVICE"]["problem.edit.tests.import"] = "admin/problems/problem.edit.tests.import.php";
	
	$_SPM_CONF["SERVICE"]["problem-categories"] = "admin/problems/problem-categories.php";
	
	$_SPM_CONF["SERVICE"]["problem_send"] = "problems/problem_send.php";
	$_SPM_CONF["SERVICE"]["problem_result"] = "problems/problem_result.php";
	
	//                             CLASSWORKS SERVICES
	$_SPM_CONF["SERVICE"]["classworks"] = "classworks/classworks.php";
	$_SPM_CONF["SERVICE"]["classworks.edit"] = "classworks/classworks.edit.php";
	$_SPM_CONF["SERVICE"]["classworks.result"] = "classworks/classworks.result.php";
	
	//                             OLYMPIADS SERVICES
	$_SPM_CONF["SERVICE"]["olympiads"] = "olymp/olympiads.php";
	
	$_SPM_CONF["SERVICE"]["olympiads.list"] = "olymp/admin/olympiads.list.php";
	$_SPM_CONF["SERVICE"]["olympiads.edit"] = "olymp/admin/olympiads.edit.php";
	$_SPM_CONF["SERVICE"]["olympiads.result"] = "olymp/olympiads.result.php";
	
	//                             USER SERVICES
	$_SPM_CONF["SERVICE"]["user"] = "user/user.php";
	$_SPM_CONF["SERVICE"]["user.edit"] = "user/user.edit.php";
	
	//                             FILES HOSTING SERVICES
	$_SPM_CONF["SERVICE"]["image"] = "image.php";
	
	//                             ADMIN SERVICES
	$_SPM_CONF["SERVICE"]["admin"] = "admin/admin.php";
	
	$_SPM_CONF["SERVICE"]["view.admin"] = "admin/view.admin.php";
	
	$_SPM_CONF["SERVICE"]["teacherid"] = "admin/teacherID.php";
	$_SPM_CONF["SERVICE"]["users.admin"] = "admin/users.admin.php";
	$_SPM_CONF["SERVICE"]["groups.admin"] = "admin/groups.admin.php";
	/*SERVICES-END*/
	
	/*SERVICES_NOLOGIN-START*/
	$_SPM_CONF["SERVICE_NOLOGIN"]["login"] = $_SPM_CONF["SERVICE"]["login"];
	$_SPM_CONF["SERVICE_NOLOGIN"]["register"] = $_SPM_CONF["SERVICE"]["register"];
	$_SPM_CONF["SERVICE_NOLOGIN"]["forgot"] = $_SPM_CONF["SERVICE"]["forgot"];
	$_SPM_CONF["SERVICE_NOLOGIN"]["api"] = $_SPM_CONF["SERVICE"]["api"];
	/*SERVICES_NOLOGIN-END*/
	
	/*SERVICES_SETTINGS-START*/
	$_SPM_CONF["SERVICES"]["_AUTOSTART_SERVICE_"] = "home";
	$_SPM_CONF["SERVICES"]["messagess"]["enabled"] = true; // включити чи відключити функціонал повідомлень для всіх користувачів системи
	$_SPM_CONF["SERVICES"]["news"]["articles_per_page"] = 5; // кількість записів на одну сторінку
	$_SPM_CONF["SERVICES"]["rating"]["articles_per_page"] = 30; // кількість записів на одну сторінку
	$_SPM_CONF["SERVICES"]["problems"]["articles_per_page"] = 50; // кількість записів на одну сторінку
	$_SPM_CONF["SERVICES"]["messages"]["max_messages_to_show"] = 1000; // максимальна кількість повідомлень, що відображати в діалозі між користувачами
	/*SERVICES_SETTINGS-END*/
	
	/*PASSWORD_SETTINGS-START*/
	$_SPM_CONF["PASSWD"]["minlength"] = 4; // мінімальна довжина паролю
	$_SPM_CONF["PASSWD"]["maxlength"] = 40; // максимальна довжина паролю
	/*PASSWORD_SETTINGS-END*/
	
	/*TEACHERID_SETTINGS-START*/
	$_SPM_CONF["TEACHERID"]["length"] = 5; // довжина коду TeacherID
	/*TEACHERID_SETTINGS-END*/
	
	/*SECURITY-START*/
	$_SPM_CONF["SECURITY"]["require_captcha"] = !false; // заборонити вхід користувачам без введення CAPTCHA
	$_SPM_CONF["SECURITY"]["alpha_version_warning"] = true; // чи відображати застереження щодо нестабільної версії системи
	/*SECURITY-END*/
	
	/*FUNCTIONS-START*/
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
	
	function spm_prepare_olympiad(){
		
		global $_SPM_CONF;
		unset($_SPM_CONF["SERVICE"]);
		
		//Available services
		$_SPM_CONF["SERVICE"]["logout"] = "login/logout.php";
		
		$_SPM_CONF["SERVICE"]["olympiads.problems"] = "olymp/user/problems.php";
		$_SPM_CONF["SERVICE"]["olympiads.result"] = "olymp/olympiads.result.php";
		
		$_SPM_CONF["SERVICE"]["image"] = "image.php";
		
		$_SPM_CONF["SERVICE"]["problem"] = "problems/problem.php";
		$_SPM_CONF["SERVICE"]["problem_send"] = "problems/problem_send.php";
		$_SPM_CONF["SERVICE"]["problem_result"] = "problems/problem_result.php";
		
		$_SPM_CONF["SERVICE"]["error"] = "error.php";
		
		//Autostart service (main service)
		$_SPM_CONF["SERVICES"]["_AUTOSTART_SERVICE_"] = "olympiads.problems";
		
	}
	/*FUNCTIONS-END*/
?>
