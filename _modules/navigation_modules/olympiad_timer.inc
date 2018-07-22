<?php

/*
 * ███████╗██╗███╗   ███╗██████╗ ██╗     ███████╗██████╗ ███╗   ███╗
 * ██╔════╝██║████╗ ████║██╔══██╗██║     ██╔════╝██╔══██╗████╗ ████║
 * ███████╗██║██╔████╔██║██████╔╝██║     █████╗  ██████╔╝██╔████╔██║
 * ╚════██║██║██║╚██╔╝██║██╔═══╝ ██║     ██╔══╝  ██╔═══╝ ██║╚██╔╝██║
 * ███████║██║██║ ╚═╝ ██║██║     ███████╗███████╗██║     ██║ ╚═╝ ██║
 * ╚══════╝╚═╝╚═╝     ╚═╝╚═╝     ╚══════╝╚══════╝╚═╝     ╚═╝     ╚═╝
 *
 * SimplePM WebApp is a part of software product "Automated
 * verification system for programming tasks "SimplePM".
 *
 * Copyright 2018 Yurij Kadirov
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Visit website for more details: https://spm.sirkadirov.com/
 */

/*
 * Запрашиваем доступ к
 * глобальным переменным.
 */

global $database;

// Получаем идентификатор связанного соревнования
$associated_olymp = Security::getCurrentSession()['user_info']->getUserInfo()['associated_olymp'];

?>

<?php if ($associated_olymp > 0): ?>

	<script>

		var timeErrChanger = new Date().getTime() - new Date("<?=$database->query("SELECT CURRENT_TIMESTAMP;")->fetch_array()[0]?>").getTime();

		var countDownDate = new Date("<?=Olymp::GetOlympiadInfo($associated_olymp)['endTime']?>").getTime();

		var olympTimer = setInterval(function() {

			// Вычисляем текущее время
			var now = new Date().getTime() - timeErrChanger;

			/*
			 * Вычисляем так называемую "дистанцию"
			 * до указанного времени начиная с текущего
			 * момента.
			 */

			var distance = countDownDate - now;

			/*
			 * Вычисляем оставшиеся дни, часы, минуты и секунды
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
			 * элемент пользовательского
			 * интерфейса веб-приложения
			 * SimplePM.
			 */

			$('#olympiadTimer').text(days + "d " + hours + "h " + minutes + "m " + seconds + "s ");

			/*
			 * Если так называемая "дистанция"
			 * до указанной нами точки в будущем
			 * времени меньше нуля (то есть вре-
			 * мя вышло), перенаправляем пользо-
			 * вателей на страницу информации о
			 * соревновании, в котором они при-
			 * нимали участие.
			 */

			if (distance < 0) {

				clearInterval(olympTimer);

				alert('<?=_("Змагання вже закінчилося! Ви будете переадресовані на сторінку рейтингу.")?>');

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