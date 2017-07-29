<?php
	// Start session
	session_start();
	
	// Code generation
	$code = mt_rand(1000,9999);
	// Save code to a session
	$_SESSION["captcha_code"] = $code;
	
	// Create captcha image
	$im = imagecreatetruecolor(200, 100);
	
	// Set some colors
	$bg = imagecolorallocate($im, mt_rand(0,30), mt_rand(0,86), mt_rand(50,165)); // background color
	$fg = imagecolorallocate($im, mt_rand(200,255), mt_rand(200,255), 150); // foreground color
	
	// Set rotator variable
	$nakl = mt_rand(-15, 15);
	
	// Fill image with background
	imagefill($im, 0, 0, $bg);
	
	// Ad some bugs #1
	for ($i = 0; $i < mt_rand(20,200); $i++){
		// Generate random bug color
		$color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
		
		// Write bug string to image
		imagestring($im, 2, mt_rand(0, 200-30), mt_rand(5, 90),  $code, $color);
	}
	
	// Write captcha code
	imagettftext($im, 60, $nakl, mt_rand(200/20, 200/4), 70, $fg, "./inc/Pacifico.ttf", $code);
	
	// Add some bugs #2
	for ($i = 0; $i < mt_rand(2000, 3000); $i++){
		// Generate random bug color
		$color = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
		
		// Paint pixel to image
		imagesetpixel($im, mt_rand(0, 200), mt_rand(0, 100), $color);
	}
	
	// Send headers
	header("Cache-Control: no-cache, must-revalidate"); // disallow to cache image
	header("Content-type: image/png"); // set output content type
	
	// Write image source code to default stream
	imagepng($im);
	
	// Destroy temporary variables
	imagedestroy($im);
?>