<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
?>
<div class="content-wrapper" style="background-color: #efefef;">
	<section class="content-header">
		<h1>
			<?=$_TPL_PAGENAME?>
			<small><?=$_TPL_PAGEDESC?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?=$_SPM_CONF["BASE"]["SITE_URL"]?>"><i class="fa fa-home"></i> SimplePM</a></li>
			<li class="active"><?=$_TPL_PAGESUBNAME?></li>
		</ol>
	</section>
    <section class="content" id="content" style="word-wrap: break-word;">