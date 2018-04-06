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

//global $submission_info;

?>

<style>

    .jumbotron-header {

        position: relative;

        background-image: url('//images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=800&h=150');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: top;

        background-color: #343a40 !important;
        color: white !important;

        text-shadow: 1px 0px 3px #fff;

    }

</style>

<div class="jumbotron jumbotron-fluid jumbotron-header">
    <div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; background-color: transparent; opacity: 0.1; z-index: 1;"></div>
    <div class="container" style="z-index: 2;">
        <h1 class="display-4"><?=_("Очікування результату")?></h1>
        <p class="lead"><?=_("Ваш запит додано у чергу перевірки. Будь ласка, зачекайте.")?></p>
    </div>
</div>

<table class="table table-bordered table-hover">

    <tbody>

    <!-- PARAM -->
    <tr>

        <td>
            <?=_("Ідентифікатор запиту")?>
        </td>

        <td>
            <?=$submission_info['submissionId']?>
        </td>


    </tr>
    <!-- /PARAM -->

    <!-- PARAM -->
    <tr>

        <td>
            <?=_("Ідентифікатор завдання")?>
        </td>

        <td>
            <?=$submission_info['problemId']?>
        </td>


    </tr>
    <!-- /PARAM -->

    <!-- PARAM -->
    <tr>

        <td>
            <?=_("Дата та час запиту")?>
        </td>

        <td>
            <?=$submission_info['time']?>
        </td>


    </tr>
    <!-- /PARAM -->

    <!-- PARAM -->
    <tr>

        <td>
            <?=_("Тип тестування")?>
        </td>

        <td>
            <?=$submission_info['testType']?>
        </td>


    </tr>
    <!-- /PARAM -->

    <!-- PARAM -->
    <tr>

        <td>
            <?=_("Статус")?>
        </td>

        <td id="testing_status_info">
            <?=$submission_info['status']?>
        </td>


    </tr>
    <!-- /PARAM -->

    </tbody>

</table>