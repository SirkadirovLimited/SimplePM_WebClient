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

?>

<style>

    .jumbotron-header {

        position: relative;

        background-color: #343a40 !important;
        color: white !important;

    }

</style>

<div class="jumbotron jumbotron-fluid jumbotron-header">
    <div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; background-color: transparent; opacity: 0.1; z-index: 1;"></div>
    <div class="container" style="z-index: 2;">
        <h1><?=_("Очікування результату")?></h1>
        <p class="lead"><?=_("Ваш запит додано у чергу перевірки. Будь ласка, зачекайте.")?></p>
    </div>
</div>

<?php include(_SPM_views_ . "problems/result-info-view.inc"); ?>

<script>

	function submission_status_checker()
	{

		$.ajax({
			url: "<?=_SPM_?>index.php?cmd=problems/submission_status&id=<?=$submission_info['submissionId']?>",
		})
			.done(function( status )
			{

				$('#testing_status_info').text(status);

				if (status == "ready")
					location.reload();

			});

	}

	document.addEventListener('DOMContentLoaded', function() {

		submission_status_checker();
		setInterval(submission_status_checker, 500);

	});

</script>