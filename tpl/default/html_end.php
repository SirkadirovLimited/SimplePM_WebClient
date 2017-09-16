		<script>
			$(document).ready(function () {
				$(document).ajaxStart(function() { Pace.restart(); });
			});
		</script>
		
		<script src="<?=_S_TPL_?>plugins/pace/pace.min.js"></script>
		<script src="<?=_S_TPL_?>plugins/sparkline/jquery.sparkline.min.js"></script>
		<script src="<?=_S_TPL_?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
		
		<script src="<?=_S_TPL_?>bootstrap/js/bootstrap.min.js"></script>
		<script src="<?=_S_TPL_?>dist/js/app.min.js"></script>
	</body>
</html>