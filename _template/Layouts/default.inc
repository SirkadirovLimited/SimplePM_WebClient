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
 * vefification system for programming tasks "SimplePM".
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

<?php include _SPM_template_ . "Parts/footer.inc"; ?>