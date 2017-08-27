<?php
	function _spm_guard_clearAllGet(){
		global $_GET;
		
		foreach ($_GET as &$getParam){
			$getParam = htmlspecialchars(trim($getParam));
		}
		
		return true;
	}
	
	function spm_urlExists($url=NULL)  
	{  
		if($url == NULL) return false;  
		$ch = curl_init($url);  
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		$data = curl_exec($ch);  
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
		curl_close($ch);  
		if($httpcode>=200 && $httpcode<300){  
			return true;  
		} else {  
			return false;  
		}  
	}
	
	function spm_pingDomain($domain){
		$starttime = microtime(true);
		$file = fsockopen($domain,80,$errno,$errstr,10);
		$stoptime = microtime(true);
		$status = 0;

		if (!$file) $status = -1;  // Site is down
		else {
			fclose($file);
			$status = ($stoptime-$starttime)*1000;
			$status = floor($status);
		}
		return $status;
	}
?>