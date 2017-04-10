<?php
	session_start();
	
	$code=mt_rand(1000,9999);
	$_SESSION["captcha_code"]=$code;
	
	$im = imagecreatetruecolor(200, 100);
	
	$bg = imagecolorallocate($im, mt_rand(0,30), mt_rand(0,86), mt_rand(50,165));
	$fg = imagecolorallocate($im, mt_rand(200,255), mt_rand(200,255), 150);
	
	$nakl = mt_rand(-15, 15);
	
	imagefill($im, 0, 0, $bg);
	
	for ($i = 0; $i < mt_rand(20,200); $i++){
		$color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
		imagestring($im, 2, mt_rand(0, 200-30), mt_rand(5, 90),  $code, $color);
	}
	
	imagettftext($im, 60, $nakl, mt_rand(200/20, 200/4), 70, $fg, "./Pacifico.ttf", $code);
	
	for ($i = 0; $i < mt_rand(2000, 3000); $i++){
		$color = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
		imagesetpixel($im, mt_rand(0, 200), mt_rand(0, 100), $color);
	}
	
	header("Cache-Control: no-cache, must-revalidate");
	header("Content-type: image/png");
	imagepng($im);
	imagedestroy($im);
?>