<?php DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php print($_SPM_CONF["BASE"]["SITE_NAME"]); ?></title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?php print(_S_TPL_); ?>bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<link rel="stylesheet" href="<?php print(_S_TPL_); ?>dist/css/AdminLTE.min.css">
		<link rel="stylesheet" href="<?php print(_S_TPL_); ?>plugins/iCheck/square/blue.css">
	  
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<style>
			.login-page{
				 background: url('<?php print(_S_TPL_); ?>dist/img/bg.jpg');
				 background-repeat: no-repeat;
				 background-attachment: fixed;
				 background-size: 100% 100%;
			}
		</style>
		<script>
			if (navigator.userAgent.indexOf("MSIE") != -1){
				alert('ВНИМАНИЕ! Internet Explorer не поддерживается сайтом. Используйте более современный браузер!');
			}
		</script>
	</head>
	<body class="hold-transition login-page">

		<div class="login-box">
			<div class="login-logo">
				<a href="index.php" style="color: white;"><b>Simple</b>PM</a>
			</div>
			<div class="login-box-body">