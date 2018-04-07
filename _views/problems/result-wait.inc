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