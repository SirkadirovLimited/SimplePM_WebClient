<?php

/*
 * Copyright (C) 2018, Yurij Kadirov.
 * All rights are reserved.
 * Licensed under Apache License 2.0 with additional restrictions.
 *
 * @Author: Yurij Kadirov
 * @Website: https://sirkadirov.com/
 * @Email: admin@sirkadirov.com
 */

$associated_olymp = Security::getCurrentSession()['user_info']->getUserInfo()['associated_olymp'];

?>

<?php if ($associated_olymp > 0): ?>

	<script>

		var countDownDate = new Date("<?=Olymp::GetOlympiadInfo($associated_olymp)['endTime']?>").getTime();

		var olympTimer = setInterval(function() {

			/*
			 * Вычисляем текущее время
			 */

			var now = new Date().getTime();

			/*
			 * Вычисляем  так  называемую   "дистанцию"
			 * до указанного времени начиная с текущего
			 * момента.
			 */

			var distance = countDownDate - now;

			/*
			 * Вычисляем оставшиеся дни,  часы,  минуты и секунды
			 * до указанной нами точки в будущем (точка окончания
			 * текущего соревнования).
			 */

			var days = Math.floor(
				distance / (1000 * 60 * 60 * 24)
			);

			var hours = Math.floor(
				(distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
			);

			var minutes = Math.floor(
				(distance % (1000 * 60 * 60)) / (1000 * 60)
			);

			var seconds = Math.floor(
				(distance % (1000 * 60)) / 1000
			);

			/*
			 * Записываем текущие значения
			 * в специально подготовленный
			 * элемент   пользовательского
			 * интерфейса   веб-приложения
			 * SimplePM.
			 */

			$('#olympiadTimer').text(days + "d " + hours + "h " + minutes + "m " + seconds + "s ");

			/*
			 * Если  так  называемая "дистанция"
			 * до указанной нами точки в будущем
			 * времени меньше нуля (то есть вре-
			 * мя вышло), перенаправляем пользо-
			 * вателей на страницу информации  о
			 * соревновании,  в котором они при-
			 * нимали участие.
			 */

			if (distance < 0) {

				clearInterval(olympTimer);

				$('#olympiadTimer').text("EXPIRED");

				window.location.replace("<?=_SPM_?>index.php/olympiads/olympiad/?id=<?=$associated_olymp?>");

			}

		}, 1000);
	</script>

	<li class="nav-item">
		<a
			id="olympiadTimer"
			class="nav-link"
		>INFINITY</a>
	</li>

<?php endif; ?>