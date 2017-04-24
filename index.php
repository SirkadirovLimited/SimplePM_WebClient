<?php
	/*
	 * SimpleProblemsManager (Web interface)
	 * COPYRIGHT (C) 2016, KADIROV YURIJ. ALL RIGHTS ARE RESERVED.
	 * PUBLIC DISTRIBUTION RESTRICTED!
	 */
	
	//FOR DEVELOPMENT ONLY!
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	
	//Save cookie life time for 24*2 hours, session will be alive in that time.
	session_set_cookie_params(3600*24*2);
	//Starting session
	session_start();
	//SOME DEFINES AND INCLUDES
	DEFINE("SPM_GENUINE", 1); //Security define
	//S_INC defines
	DEFINE("_S_INC_", "./inc/");
	DEFINE("_S_INC_FUNC_", _S_INC_ . "func/");
	DEFINE("_S_INC_CLASS_", _S_INC_ . "class/");
	//S_REQUIRES
	require_once(_S_INC_FUNC_ . "permissions_check.php");
	require_once(_S_INC_FUNC_ . "gen_tpl.php");
	require_once(_S_INC_FUNC_ . "guard.php");
	require_once(_S_INC_ . "config.php");
	require_once(_S_INC_ . "db.php");
	require_once(_S_INC_FUNC_ . "info_msg.php");
	require_once(_S_INC_CLASS_ . "CountryList.php");
	//S_TPL defines
	DEFINE("_S_TPL_", "./tpl/" . $_SPM_CONF["BASE"]["TPL_NAME"] . "/");
	DEFINE("_S_TPL_ERR_", _S_TPL_ . "error_pages/");
	//S_MOD defines
	DEFINE("_S_MOD_", "./modules/");
	//S_SERV defines
	DEFINE("_S_SERV_", "./services/");
	DEFINE("_S_SERV_INC_", _S_SERV_ . "inc/");
	//S_MEDIA defines
	DEFINE("_S_MEDIA_", "./media/");
	DEFINE("_S_MEDIA_FILES_", _S_MEDIA_ . "files/");
	DEFINE("_S_MEDIA_IMG_", _S_MEDIA_ . "img/");
	
	//Some security checks for you
	if ( isset( $_SESSION["uid"] ) && (int)$_SESSION["uid"] > 0){
		
		if (!$db_result = $db->query("SELECT `sessionId`, `banned`, `online` FROM `spm_users` WHERE `id` = '" . mysqli_real_escape_string($db, $_SESSION['uid']) . "' LIMIT 1;"))
			die('<strong>Произошла ошибка при попытке подключения к базе данных! Пожалуйста, посетите сайт позже!</strong>');
		
		if ($db_result->num_rows == 0){
			unset($_SESSION);
			header('location: index.php');
			die;
		}
		
		$userInfo = $db_result->fetch_assoc();
		$db_result->free();
		unset($db_result);
		
		//Check if user banned
		if ($userInfo['banned'] == 1){
			
			if (!$db->query("UPDATE `spm_users` SET `online` = 0 WHERE `id` = '" . mysqli_real_escape_string($db, $_SESSION['uid']) . "' LIMIT 1;"))
				die('<strong>Произошла ошибка при попытке подключения к базе данных! Пожалуйста, посетите сайт позже!</strong>');
			
			unset($_SESSION);
			header('location: index.php');
			die;
		}
		//Check if another user logged in in the same account
		if ($userInfo['sessionId'] != session_id()){
			unset($_SESSION['uid']);
			header('location: index.php');
			die;
		}
		
		if ($userInfo['online'] == 0){
			if (!$db->query("UPDATE `spm_users` SET `online` = 1 WHERE `id` = '" . mysqli_real_escape_string($db, $_SESSION['uid']) . "' LIMIT 1;"))
				die('<strong>Произошла ошибка при попытке подключения к базе данных! Пожалуйста, посетите сайт позже!</strong>');
		}
	}
	
	//Functions autorun
	_spm_guard_clearAllGet();
	
	//Choosing service to start
	if (isset($_GET['service']) && strlen($_GET['service']) > 0)
		$_spm_run_service = preg_replace("/[^a-zA-Z0-9.-_\s]/", "", $_GET['service']);
	else $_spm_run_service = $_SPM_CONF["SERVICE"]["_AUTOSTART_SERVICE_"];
	
	if(!isset($_SESSION['uid']) &&  !isset($_SPM_CONF["SERVICE_NOLOGIN"][$_spm_run_service]))
		$_spm_run_service = "login";
	
	if (!isset($_SPM_CONF["SERVICE"][$_spm_run_service]) && !isset($_SESSION['uid'])){
		include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["404"]);
		die();
	}elseif ( ( !isset($_SPM_CONF["SERVICE"][$_spm_run_service]) && isset($_SESSION['uid']) ) || !file_exists(_S_SERV_ . $_SPM_CONF["SERVICE"][$_spm_run_service])){
		SPM_header("Ошибка 404");
		include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["404"]);
		SPM_footer();
		die();
	}
	//CONTENT
	include_once(_S_SERV_ . $_SPM_CONF["SERVICE"][$_spm_run_service]);
	
	//Close database connection for more security
	$db->close();
?>