<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	//$_SESSION["olymp"] = 1;
?>
<?php if (isset($_SESSION["olymp"])): ?>
<script type="text/javascript">
	function startTimer() {
		var my_timer = document.getElementById("olymp_timer");
		var time = my_timer.innerHTML;
		var arr = time.split(":");
		var h = arr[0];
		var m = arr[1];
		var s = arr[2];
		if (s == 0) {
			if (m == 0) {
				if (h == 0) {
					alert("Время вышло! Ссылка на таблицу результатов отправлена в личном сообщении!");
					window.location.reload();
					return;
				}
				h--;
				m = 60;
				if (h < 10)
					h = "0" + h;
			}
			m--;
			if (m < 10)
				m = "0" + m;
			s = 59;
		}
		else
			s--;
		if (s < 10)
			s = "0" + s;
		
		document.getElementById("olymp_timer").innerHTML = h+":"+m+":"+s;
		setTimeout(startTimer, 1000);
	}
</script>
<li class="dropdown messages-menu">
	<a class="dropdown-toggle" title="Время до окончания соревнования">
		&nbsp;<i class="fa fa-hourglass-half"></i>&nbsp;
		<strong id="olymp_timer">00:30:00</strong>
	</a>
</li>
<?php endif; ?>