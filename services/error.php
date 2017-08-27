<?php
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
			if (isset($_SESSION["classwork"]) || isset($_SESSION["olymp"]))
				exit(header('location: index.php'));
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
	
	SPM_header("Помилка " . $errId, "Сторінка інформації");
?>
<div class="error-page">
	<h2 class="headline text-red"><?=$errId?></h2>
	<div class="error-content">
		<h3><i class="fa fa-warning text-red"></i> Упс... щось зламалося!</h3>
		<p>
			Виникла помилка при обробці вашого запиту, тож всі введені вами дані було втрачено. Спробуйте зробити все заново, ознайомтесь з інформацією про помилку, або зв'яжіться з нами!
		</p>
		
		<a
			href="https://spm.sirkadirov.com/wiki/doku.php?id=services:error-pages"
			target="_blank"
			class="btn btn-warning btn-flat btn-block"
		>Інформація про помилку</a>
		<a
			href="mailto:<?=$_SPM_CONF["BASE"]["ADMIN_MAIL"]?>?subject=SimplePM error <?=$errId?>, uid <?=$SESSION['uid']?>"
			class="btn btn-danger btn-flat btn-block"
		>Зв'язатися з адміністратором</a>
	</div>
</div>
<?php SPM_footer(); ?>