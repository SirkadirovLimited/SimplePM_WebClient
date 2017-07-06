<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	include(LOCALE . "error.php");
	
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
	
	SPM_header($LANG["page_title"] . $errId, $LANG["page_desc"]);
?>
<div class="error-page">
	<h2 class="headline text-red"><?=$errId?></h2>
	<div class="error-content">
		<h3><i class="fa fa-warning text-red"></i> <?=$LANG["h3_title"]?></h3>
		<p>
			<?=$LANG["text_1"]?>
			<?=$LANG["text_2"]?>
			<?=$LANG["text_3"]?>
		</p>
		
		<a
			href="https://github.com/SirkadirovTeam/SimplePM_WebClient/wiki/Информация-об-ошибках"
			target="_blank"
			class="btn btn-warning btn-flat btn-block"
		><?=$LANG["err_info"]?></a>
		<a
			href="mailto:<?=$_SPM_CONF["BASE"]["ADMIN_MAIL"]?>?subject=SimplePM error <?=$errId?>, uid <?=$SESSION['uid']?>"
			class="btn btn-danger btn-flat btn-block"
		><?=$LANG["send_email"]?></a>
	</div>
</div>
<?php SPM_footer(); ?>