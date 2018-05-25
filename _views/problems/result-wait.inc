<?php

/*
 * ███████╗██╗███╗   ███╗██████╗ ██╗     ███████╗██████╗ ███╗   ███╗
 * ██╔════╝██║████╗ ████║██╔══██╗██║     ██╔════╝██╔══██╗████╗ ████║
 * ███████╗██║██╔████╔██║██████╔╝██║     █████╗  ██████╔╝██╔████╔██║
 * ╚════██║██║██║╚██╔╝██║██╔═══╝ ██║     ██╔══╝  ██╔═══╝ ██║╚██╔╝██║
 * ███████║██║██║ ╚═╝ ██║██║     ███████╗███████╗██║     ██║ ╚═╝ ██║
 * ╚══════╝╚═╝╚═╝     ╚═╝╚═╝     ╚══════╝╚══════╝╚═╝     ╚═╝     ╚═╝
 *
 * SimplePM WebApp
 * A part of SimplePM programming contests management system.
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