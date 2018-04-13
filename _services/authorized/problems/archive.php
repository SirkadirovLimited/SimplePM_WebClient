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

define("__PAGE_TITLE__", _("Архів задач"));
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Получаем идентификатор текущего соревнования
 * для возможного ограничения доступного списка
 * задач.
 */

$associated_olymp = (int)(Security::getCurrentSession()["user_info"]->getUserInfo()["associated_olymp"]);

/*
 * Воодим ограничение в список доступных
 * для решения задач, если текущий польз
 * ователь принимает участие в соревнова
 * нии,  дабы не реализовывать отдельный
 * сервис для этого.
 */

if ($associated_olymp > 0)
{

	$query_str = "
		SELECT
		  `problems_list`
		FROM
		  `spm_olympiads`
		WHERE
		  `id` = '" . $associated_olymp . "'
		LIMIT
		  1
		;
	";

	$query_result = $database->query($query_str)->fetch_array()[0];

	$problem_list_limiter = "
		AND
			`spm_problems`.`id` IN (
				" . substr($query_result, 0, strlen($query_result) - 1) . "
			)
	";

	unset($query_result);

}

/*
 * Формируем запрос на выборку списка задач
 * из базы данных и выполняем его.
 */

// Формируем запрос
$query_str = "
    SELECT
      `spm_problems`.`id`,
      `spm_problems`.`name`,
      `spm_problems`.`difficulty`,
      `spm_problems_categories`.`name` AS category_name
    FROM
      `spm_problems`
    LEFT JOIN
      `spm_problems_categories`
    ON
      `spm_problems`.`category_id` = `spm_problems_categories`.`id`
    AND
      (
      	`spm_problems_categories`.`id` IS NULL
      OR
      	`spm_problems_categories`.`id` IS NOT NULL
      )
    WHERE
      `spm_problems`.`enabled` = TRUE
    AND
      (
        `spm_problems`.`id` LIKE '%" . @(int)$_GET['query'] . "%'
      OR
        `spm_problems`.`name` LIKE '%" . @$_GET['query'] . "%'
      )
    AND
      (
      	`spm_problems`.`category_id` LIKE '%" . @$_GET['category'] . "%'
      OR
      	`spm_problems`.`category_id` IS NULL
      )
    " . @$problem_list_limiter . "
    ORDER BY
      `spm_problems`.`difficulty` ASC
    ;
";

// Выполняем запрос на выборку из БД и получаем массив
$problems_list = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

?>

<?php

if ($associated_olymp <=0)
	include _SPM_views_ . "problems/archive-search-panel.inc";

?>

<div class="row" style="margin-top: 2rem;">

    <?php foreach ($problems_list as $problem): ?>
        <div class="col-md-4 col-sm-12" style="margin-bottom: 2rem;">

            <a
                    class="card"
                    style="text-decoration: none !important;"
                    href="<?=_SPM_?>index.php/problems/problem/?id=<?=$problem["id"]?>"
            >
                <div class="card-body">
                    <strong class="card-title" style="color: #343a40 !important;"><?=$problem["id"]?>. <?=$problem["name"]?></strong>
                    <p class="card-text">
                        <span class="badge badge-info"><?=$problem["category_name"]?></span>
                        <span class="badge badge-success"><?=$problem["difficulty"]?> points</span>
                    </p>
                </div>
            </a>

        </div>
    <?php endforeach; ?>

</div>