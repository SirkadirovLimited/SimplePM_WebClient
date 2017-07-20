<?php DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED'); ?>
				<?php if($_SPM_CONF["BASE"]["ENABLE_TRANSLATOR"]): ?>
				<div
					id='MicrosoftTranslatorWidget'
					align='center'
					class='Dark'
					style='color:white; background-color:#555555; width: 100%;'
				></div>
				<script type='text/javascript'>
					setTimeout(function(){
						{
							var s = document.createElement('script');
							s.type = 'text/javascript';
							s.charset = 'UTF-8';
							s.src = (
								(location && location.href && location.href.indexOf('https') == 0)
								? 'https://ssl.microsofttranslator.com'
								: 'http://www.microsofttranslator.com'
							)
							+'/ajax/v3/WidgetV3.ashx?siteData=ueOIGRSKkd965FeEGM5JtQ**&ctf=False&ui=true&settings=Manual&from=';
							
							var p = document.getElementsByTagName('head')[0] || document.documentElement;
							
							p.insertBefore(s, p.firstChild);
						}
					}, 0);
				</script>
				<?php endif; ?>
			</div>
		</div>
		<script src="<?=_S_TPL_?>plugins/jQuery/jquery-2.2.3.min.js"></script>
		<script src="<?=_S_TPL_?>bootstrap/js/bootstrap.min.js"></script>
		<script src="<?=_S_TPL_?>plugins/iCheck/icheck.min.js"></script>
	</body>
</html>