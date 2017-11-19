<div>
	<div align="center" class="party" style="margin-top: 50px; margin-bottom: 50px;">
		<img src="<?=_S_MEDIA_IMG_?>etc/loader.svg">
		<h1>Очікування перевірки</h1>
		<p class="lead">Ваш запит додано у чергу перевірок. Будь ласка, зачекайте.</p><br/>
	</div>
	<script>
		function checkForResult()
		{
			
			$.ajax({
				url: "index.php?service=api&module=submissionInfo&command=isReady&id=<?=$submission['submissionId']?>",
			})
			.done(function( data ) {
				if (data == "true")
					location.reload();
			});
			
		}
		
		setInterval(checkForResult, 800);
	</script>
</div>