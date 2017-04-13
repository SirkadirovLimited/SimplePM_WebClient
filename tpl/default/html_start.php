<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	global $_SPM_CONF;
?>
<html>
	<head>
		<title><?php print($_TPL_PAGESUBNAME . " - " . $_SPM_CONF["BASE"]["SITE_NAME"]); ?></title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?php print(_S_TPL_); ?>bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<link rel="stylesheet" href="<?php print(_S_TPL_); ?>dist/css/AdminLTE.min.css">
		<link rel="stylesheet" href="<?php print(_S_TPL_); ?>dist/css/skins/skin-blue.min.css">
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style>
			 .layout-boxed{
				 background: url('<?php print(_S_TPL_); ?>dist/img/bg.jpg');
				 background-repeat: no-repeat;
				 background-attachment: fixed;
				 background-size: 100% 100%;
			 }
		</style>
	</head>
	<body class="hold-transition skin-blue sidebar-mini layout-boxed">
<?php
	/*if (!isset($_SESSION['dialogShown'])){
		$_SESSION['dialogShown'] = true;
?>
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">ОБРАТИТЕ ВНИМАНИЕ!</h4>
			</div>
			<div class="modal-body" style="font-size: 12pt;">
				<p><b>Уважаемый пользователь системы!</b></p>
				<p>Функционал отправки, отладки и тестирования задач является экспериментальным. Прошу вас не пытаться "подломать" серверную часть SimplePM! 
				При этом, я буду очень благодарен тем людям, кто своевременно сообщит о найденных багах, уязвимостях и прочих лазейках в системе проверки! 
				При релизе системы многие из них получат вознаграждение за найденные с их помощью уязвимости!</p>
				<p><b>Хочу напомнить, что за вашими действиями ведётся круглосуточное слежение, все ваши действия на сайте логгируются, а введённые данные 
				(будь то логины, пароли, данные заполнения форм, коды программ и так далее).</b></p>
				<p>Все псевдо-"хацкеры" будут жестоко наказаны. Будьте бдительны, не поддавайтесь искушениям и да пребудет с вами Сила!</p>
				<p>С уважением, Кадиров Юрий.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">Я принимаю условия</button>
			</div>
		</div>
	</div>
</div>
<script>
	window.onload = function () {
		$('#infoModal').modal('show');
	}
</script>
<?php
	}*/
?>
		<div class="wrapper">