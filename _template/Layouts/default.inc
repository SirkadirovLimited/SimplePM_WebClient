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

include _SPM_template_ . "Parts/header.inc";

?>

<div class="container">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">

        <a class="navbar-brand" href="<?=_SPM_?>"><strong>Simple</strong>PM</a>

        <button
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#mainNav"
        ><span class="navbar-toggler-icon"></span></button>

        <div class="collapse navbar-collapse" id="mainNav">

            <ul class="navbar-nav mr-auto">

                <?php include(_SPM_modules_ . "navigation_generator.php"); ?>

            </ul>

            <ul class="navbar-nav">

                <?php include(_SPM_modules_ . "navigation_modules_generator.php"); ?>

            </ul>

        </div>

    </nav>

    <div class="jumbotron text-white bg-dark">
        <h4 class="lead">$(title)</h4>
    </div>

</div>

<main role="main" class="container">
$(content)
</main>

<footer class="footer" style="margin-top: 20px;">
    <p>Powered by <a href="https://spm.sirkadirov.com/">SimplePM</a> - Copyright &copy; 2016-2018, <a href="https://sirkadirov.com/">Sirkadirov Team</a>. All rights reserved.</p>
</footer>

<?php include _SPM_template_ . "Parts/footer.inc"; ?>