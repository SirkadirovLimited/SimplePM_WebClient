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
 * Copyright (C) 2016-2018 Yurij Kadirov
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * GNU Affero General Public License applied only to source code of
 * this program. More licensing information hosted on project's website.
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