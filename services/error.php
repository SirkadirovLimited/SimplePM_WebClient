<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	isset($_GET["err"]) or $_GET["err"] = "404";
	
	switch ($_GET["err"]){
		case "db_error":
			$errId = "DB";
		break;
		case "input":
			$errId = "IERR";
		break;
		case "injection":
			$errId = "INJE";
		break;
		case "404":
			$errId = "404";
		break;
		case "403":
			$errId = "403";
		break;
		case "500":
			$errId = "500";
		break;
		default:
			$errId = "AMY";
		break;
	}
	
	SPM_header("Ошибка " . $errId, "Страница информации");
?>
<div class="error-page">
	<h2 class="headline text-red"><?=$errId?></h2>
	<div class="error-content">
		<h3><i class="fa fa-warning text-red"></i> Упс... Что-то произошло!</h3>
		<p>
			Произошла ошибка при обработке вашего запроса. Все введённые вами данные утрачены.
			Попробуйте сделать всё <a href="index.php">заново</a> и не плачте!
			Информацию о часто возникаемых ошибках можно просмотреть на данной странице.
		</p>
		
		<a
			href="https://github.com/SirkadirovTeam/SimplePM_WebClient/wiki/Информация-об-ошибках"
			target="_blank"
			class="btn btn-warning btn-flat btn-block"
		>Информация об ошибке</a>
		<a
			href="mailto:<?=$_SPM_CONF["BASE"]["ADMIN_MAIL"]?>?subject=SimplePM error <?=$errId?>, uid <?=$SESSION['uid']?>"
			class="btn btn-danger btn-flat btn-block"
		>Наказать виновного!</a>
	</div>
</div>
<?php SPM_footer(); ?>