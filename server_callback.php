<?php
	/*
	 * Copyright (C) 2017, Kadirov Yurij. All rights are reserved.
	 * API CALLBACK v0.1-experimental
	 * DO NOT REDISTRIBUTE IN MASSES!
	 */
	
	error_reporting(0);
	
	DEFINE("SPM_GENUINE", 1);
	isset($_GET["access_key"]) or die('403 Access denied!');
	
	DEFINE("__spm__api__security__key__", "3605624853e096cb75ccd46b4db904dd"); //WARNING! CHANGE IT!!!
	DEFINE("_SPM_API_DIR_", "./api/");
	($_GET["access_key"] == __spm__api__security__key__) or die('403 Access denied!');
	
	if (isset($_GET["cron"]))
		chdir("../");
	
	DEFINE("__spm__security__", 1);
	require_once("./inc/defines.php");
	_spm_guard_clearAllGet();
	
	/*=== SERVICES_LIST_START ===*/
	$_SPM_API_SERVICE["unonline"] = _SPM_API_DIR_ . "unonline.php";
	$_SPM_API_SERVICE["auth"] = _SPM_API_DIR_ . "auth.php";
	$_SPM_API_SERVICE["problem_info"] = _SPM_API_DIR_ . "problem_info.php";
	$_SPM_API_SERVICE["problem_answer"] = _SPM_API_DIR_ . "problem_answer.php";
	/*=== SERVICES_LIST_END ===*/
	
	if (strlen($_GET["cmd"])>0 && isset($_SPM_API_SERVICE[$_GET["cmd"]])){
		header('Content-Type:text/plain');
		include_once($_SPM_API_SERVICE[$_GET["cmd"]]);
	} else {
		//TODO: Реализовать запись лог-файлов
		die('403 Access denied!');
	}
	
	$db->close();
	exit();
?>