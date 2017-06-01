<?php
	/*
	 * SimpleProblemsManager (Web interface)
	 * COPYRIGHT (C) 2016, KADIROV YURIJ. ALL RIGHTS ARE RESERVED.
	 * PUBLIC DISTRIBUTION RESTRICTED!
	 */
	
	//FOR DEVELOPMENT ONLY!
	ini_set('error_reporting', E_ALL); // 0 for release!
	ini_set('display_errors', 1); // 0 for release!
	ini_set('display_startup_errors', 1); // 0 for release!
	
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
	include_once(_S_INC_FUNC_ . "permissions_check.php");
	include_once(_S_INC_FUNC_ . "gen_tpl.php");
	include_once(_S_INC_FUNC_ . "guard.php");
	include_once(_S_INC_ . "config.php");
	include_once(_S_INC_ . "db.php");
	include_once(_S_INC_FUNC_ . "info_msg.php");
	include_once(_S_INC_FUNC_ . "user_info.php");
	include_once(_S_INC_FUNC_ . "smiles.php");
	include_once(_S_INC_CLASS_ . "CountryList.php");
	//S_TPL defines
	DEFINE("_S_TPL_", "./tpl/" . $_SPM_CONF["BASE"]["TPL_NAME"] . "/");
	DEFINE("_S_TPL_ERR_", _S_TPL_ . "error_pages/");
	//S_MOD defines
	DEFINE("_S_MOD_", "./modules/");
	//S_SERV defines
	DEFINE("_S_SERV_", "./services/");
	DEFINE("_S_SERV_INC_", _S_SERV_ . "inc/");
	//S_VIEW defines
	DEFINE("_S_VIEW_", "./views/");
	//S_MEDIA defines
	DEFINE("_S_MEDIA_", "./media/");
	DEFINE("_S_MEDIA_FILES_", _S_MEDIA_ . "files/");
	DEFINE("_S_MEDIA_IMG_", _S_MEDIA_ . "img/");
	
	//Functions autorun
	_spm_guard_clearAllGet();
	
	/* CHECKS-START */
	include_once(_S_INC_FUNC_ . "user_check.php"); //user checker
	include_once(_S_INC_FUNC_ . "classworks_check.php"); //classworks checker
	/* CHECKS-END */
	
	//Choosing service to start
	if (isset($_GET['service']) && strlen($_GET['service']) > 0)
		$_spm_run_service = preg_replace("/[^a-zA-Z0-9.-_\s]/", "", $_GET['service']);
	else
		$_spm_run_service = $_SPM_CONF["SERVICES"]["_AUTOSTART_SERVICE_"];
	
	if(!isset($_SESSION['uid']) && !isset($_SPM_CONF["SERVICE_NOLOGIN"][$_spm_run_service]))
		$_spm_run_service = "login";
	
	if (!isset($_SPM_CONF["SERVICE"][$_spm_run_service]) && !isset($_SESSION['uid'])):
		include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["404"]);
		die();
	elseif ( ( !isset($_SPM_CONF["SERVICE"][$_spm_run_service]) && isset($_SESSION['uid']) ) || !file_exists(_S_SERV_ . $_SPM_CONF["SERVICE"][$_spm_run_service])):
		SPM_header("Ошибка 404");
		include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["404"]);
		SPM_footer();
		die();
	endif;
	
	//CONTENT
	include_once(_S_SERV_ . $_SPM_CONF["SERVICE"][$_spm_run_service]);
	
	//Close database connection
	$db->close();
?>