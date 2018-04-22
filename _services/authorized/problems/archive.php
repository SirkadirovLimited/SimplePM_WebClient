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

if ($associated_olymp > 0)
{

	/*
	 * Воодим ограничение в список доступных
	 * для решения задач, если текущий польз
	 * ователь принимает участие в соревнова
	 * нии,  дабы не реализовывать отдельный
	 * сервис для этого.
	 */

	// Формируем SQL запрос
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

	// Выполняем запрос и обрабатываем результат
	$query_result = $database->query($query_str)->fetch_array()[0];

	// Устанавливаем лимитер списка доступных задач
	$problem_list_limiter = "
		AND
			`spm_problems`.`id` IN (
				" . substr($query_result, 0, strlen($query_result) - 1) . "
			)
	";

	// Удаляем устаревшие данные
	unset($query_result);

	/*
	 * Мелкие очистки для обеспечения безопасности
	 */

	$_GET['query'] = "";
	$_GET['category'] = "";

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

<style>

	a.card {
		color: #343a40;
	}

</style>

<div class="row" style="margin-top: 2rem;">

    <?php foreach ($problems_list as $problem): ?>

        <?php

        /*
         * Проверяем текущего пользователя
         * на  наличие доступа к указанной
         * задаче,   если  доступа  нет  -
         * просто не показываем  указанную
         * задачу в этом списке.
         */

        $problem_enabled_checker = (

                $problem['enabled']
        ||
                (

                        Security::CheckAccessPermissions(
                            Security::getCurrentSession()['user_info']->getUserInfo()['permissions'],
                            PERMISSION::ADMINISTRATOR,
                            false
                        )
                    &&
                        !$problem['enabled']

                )

        );

        if ($problem_enabled_checker):

        ?>

            <?php

            /*
             * Получаем информацию о пользовательских
             * попытках решить данную задачу.
             *
             * Информацию о  последней   попытке   мы
             * визуализируем    в    пользовательском
             * интерфейсе веб-приложения SimplePM.
             */

            // Формируем запрос на выборку из БД
            $query_str = "
                SELECT
                  `b`
                FROM
                  `spm_submissions`
                WHERE
                  `userId` = '" . (int)Security::getCurrentSession()['user_info']->getUserId() . "'
                AND
                  `problemId` = '" . (int)$problem['id'] . "'
                AND
                  `olympId` = '" . (int)$associated_olymp . "'
                ORDER BY
                  `b` DESC,
                  `time` DESC
                LIMIT
                  1
                ;
            ";

            // Выполняем запрос на выборку данных из БД
            $query = $database->query($query_str);

            /*
             * В зависимости от того, существуют
             * ли необходимые нам попытки решени
             * я текущей задачи текущем пользова
             * телем или нет, выполняем необходи
             * мые действия и производим необход
             * имые изменения в представлении по
             * льзовательского интерфейса данног
             * о сервиса веб-приложения SimplePM
             */

            if ($query->num_rows > 0)
            {

                // Получаем информацию о заработанном бале по попытке
                $submission_points = ($query->fetch_array()[0]);

                /*
                 * В зависимости от полученной оценки
                 * указанного решения поставленной за
                 * дачи, производим необходимые измен
                 * ения в пользовательском интерфейсе
                 * веб-приложения SimplePM.
                 */

                if ($submission_points == 0)
                    $class_addition = "bg-danger text-white";
                elseif ($submission_points < $problem["difficulty"])
                    $class_addition = "bg-warning text-white";
                elseif ($submission_points >= $problem["difficulty"])
                    $class_addition = "bg-success text-white";

            }
            else
                $class_addition = null; // В ином случае - пустота

            ?>

            <div class="col-md-4 col-sm-12" style="margin-bottom: 2rem;">

                <a
                        class="card <?=@$class_addition?>"
                        style="text-decoration: none !important;"
                        href="<?=_SPM_?>index.php/problems/problem/?id=<?=$problem["id"]?>"
                >
                    <div class="card-body">
                        <strong style=""><?=$problem["id"]?>. <?=$problem["name"]?></strong>
                        <p class="card-text">
                            <span class="badge badge-info"><?=$problem["category_name"]?></span>
                            <span class="badge badge-success"><?=$problem["difficulty"]?> points</span>
                        </p>
                    </div>
                </a>

            </div>

        <?php endif; unset($problem_enabled_checker); ?>

    <?php endforeach; ?>

</div>