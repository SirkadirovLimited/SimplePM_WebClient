<?php
	/*
	 * FUNCTIONS LIST
	 */
	function sendResponse($response_arr){
		header('content-type: text/plain');
		print(json_encode($response_arr));
		exit;
	}
	function sendError($error_text){
		sendResponse(array ("error" => $error_text));
	}
	/*
	 * ПЕРВЫЙ КРУГ ОТСЕИВАНИЯ ДУШ
	 * Испытание огнём
	 */
	isset($_POST['submissionId']) or sendError("Submission ID not set!");
	
	
?>