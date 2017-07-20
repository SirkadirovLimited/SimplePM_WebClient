<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
?>

<li class="dropdown messages-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Перекладач">
		&nbsp;<i class="fa fa-globe"></i>&nbsp;
	</a>
	<ul class="dropdown-menu">
		<li class="header">Перекладач</li>
		<li>
			<ul class="menu">
				
				<div
					id='MicrosoftTranslatorWidget'
					align='center'
					class='Dark'
					style='color:white; background-color:#555555; width: 100%;'
				></div>
				
				<p style="margin: 5px;">
					SimplePM за замовчуванням підтримує лише українську мову. 
					Завдяки цьому додатку ви можете використовувати SimplePM на своїй рідній мові.
				</p>
				
			</ul>
		</li>
	</ul>
</li>

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