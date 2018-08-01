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

    <div id="jumbotron-head" class="jumbotron text-white bg-dark">
        <h4 class="lead">$(title)</h4>
    </div>

</div>

<main role="main" class="container">
$(content)
</main>

<footer class="footer" style="margin-top: 20px;">
    <p>Powered by <a href="https://spm.sirkadirov.com/">SimplePM</a> - Copyright &copy; 2016-2018, <a href="https://sirkadirov.com/">Sirkadirov Team</a>. All rights reserved.</p>
</footer>

<?php include(_SPM_modules_ . "issue_finder.php"); ?>

<?php include _SPM_template_ . "Parts/footer.inc"; ?>