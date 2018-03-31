<?php

/*
 * Copyright (C) 2018, Yurij Kadirov.
 * All rights are reserved.
 * Licensed under Apache License 2.0 with additional restrictions.
 *
 * @Author: Yurij Kadirov
 * @Website: https://sirkadirov.com/
 * @Email: admin@sirkadirov.com
 * @Repo: https://github.com/SirkadirovTeam/SimplePM_Server
 */

define("__PAGE_TITLE__", _("Архів задач"));
define("__PAGE_LAYOUT__", "default");

global $database;

$associated_olymp = Security::getCurrentSession()["user_info"]->getUserInfo()["associated_olymp"];

$query_str = "
    SELECT
      `spm_problems`.`id`,
      `spm_problems`.`name`,
      `spm_problems`.`difficulty`,
      `spm_problems_categories`.`name` AS category_name
    FROM
      `spm_problems`
    RIGHT JOIN
      `spm_problems_categories`
    ON
      `spm_problems`.`category_id` = `spm_problems_categories`.`id`
    WHERE
      `spm_problems`.`enabled` = TRUE
    ORDER BY
      `spm_problems_categories`.`sort` ASC,
      `spm_problems`.`difficulty` ASC
    ;
";

$problems_list = $database->query($query_str);

?>

<div class="row" style="margin-top: 2rem;">

    <?php while ($problem = $problems_list->fetch_assoc()): ?>
        <div class="col-md-4 col-sm-12" style="margin-bottom: 2rem;">

            <a class="card" href="">
                <div class="card-body">
                    <strong class="card-title"><?=$problem["name"]?></strong>
                    <p class="card-text"><?=$problem["category_name"]?></p>
                </div>
            </a>

        </div>
    <?php endwhile; ?>

</div>