<?php
	/*
	 * Copyright (C) 2017, Kadirov Yurij. All rights are reserved.
	 * Author's website: https://sirkadirov.com/
	 * Product's website: http://spm.sirkadirov.com/
	 * Primary support e-mail: admin@sirkadirov.com
	 * Secondary support e-mail: sirkadirov@sirkadirov.com
	 */
	DEFINE("__SPM_INSTALLER__", 1, true); //for security
	define("_installer_enabled_", true); //enable or disable installer
	
	define("services", "./services/"); //path to services directory
	define("tpl", "./tpl/"); //path to template directory
	
	define("_header", tpl . "header.php");
	define("_footer", tpl . "footer.php");
	
	/*
	 * As this subsystem is not the main in the SImplePM,
	 * it has poor security! After installation, you MUST
	 * remove this directory!
	 */
	
	//Start only if installer enabled!
	_installer_enabled_ == true or die('<strong>403 Access Denied!</strong>');
	
	isset($_GET['service']) or $_GET['service'] = "main";
	$service_path = services . $_GET['service'] . ".php";
	
	if (file_exists($service_path))
		include($service_path);
	else
		die('<strong>404 Service Not Found!</strong>');
?>