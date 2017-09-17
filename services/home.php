<?php
	SPM_header("Головна сторінка");
	
	function getUsersCount($permission){
		
		global $db;
		
		$query_str = "
			SELECT
				count(`id`)
			FROM
				`spm_users`
			WHERE
				`permissions` & " . $permission . "
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		$result = $query->fetch_array()[0];
		
		return (int)$result;
		
	}
	
	function getProblemsCount(){
		
		global $db;
		
		$query_str = "
			SELECT
				count(`id`)
			FROM
				`spm_problems`
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		$result = $query->fetch_array()[0];
		
		return (int)$result;
		
	}
?>
<style>
	#content {
		padding: 0;
	}
	.content-header {
		display: none;
	}
	div .row {
		padding: 10px;
	}
	.bg-blue {
		background-color: #3c8dbc !important;
	}
	
	.small-box:hover {
		-ms-transform: scale(1.01, 1.01);
		-webkit-transform: scale(1.01, 1.01);
		transform: scale(1.01, 1.01);
	}
</style>
<header>
	<img src="<?=_S_MEDIA_IMG_ . "headers/home.svg"?>" width="100%">
</header>
<div class="row">
	<div class="col-md-4">
		<div class="small-box bg-blue">
			<div class="inner">
				<h3><?=getUsersCount(PERMISSION::student)?></h3>
				<p>Учнів в системі</p>
			</div>
			<div class="icon">
				&nbsp;<i class="ion ion-person-add"></i>&nbsp;
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="small-box bg-blue">
			<div class="inner">
				<h3><?=getUsersCount(PERMISSION::teacher | PERMISSION::administrator)?></h3>
				<p>Вчителів та адміністраторів в системі</p>
			</div>
			<div class="icon">
				&nbsp;<i class="ion ion-person-stalker"></i>&nbsp;
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="small-box bg-blue">
			<div class="inner">
				<h3><?=getProblemsCount()?></h3>
				<p>Задач в системі</p>
			</div>
			<div class="icon">
				&nbsp;<i class="ion ion-stats-bars"></i>&nbsp;
			</div>
		</div>
	</div>
</div>

<?php if ($_SPM_CONF["BASE"]["debug"]): ?>
<section style="padding: 10px;">
	<span>Submissions count: <b id="allSubmCount"></b></span><br/>
	<span>Waiting count: <b id="waitingSubmCount"></b></span><br/>
	<span>Processing count: <b id="processingSubmCount"></b></span><br/>
	<span>Ready count: <b id="readySubmCount"></b></span><br/>
</section>

<script>
	$(document).ready(function () {
		
		function submCount(command, container)
		{
			$.ajax({
				url: "index.php?service=api&module=submissionsCount&command=" + command,
			})
			.done(function( data ) {
				$('#' + container).html(data);
			});
		}
		
		setInterval(submCount, 1000, "count", "allSubmCount");
		setInterval(submCount, 1000, "waitingCount", "waitingSubmCount");
		setInterval(submCount, 1000, "processingCount", "processingSubmCount");
		setInterval(submCount, 1000, "readyCount", "readySubmCount");
		
	});
</script>
<?php endif; ?>

<?php
	SPM_footer();
?>