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
	 * для решения задач, если текущий пользователь
	 * принимает участие в соревновании, дабы не
	 * реализовывать отдельный сервис для этого.
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

	// Устанавливаем лимитер списка доступных задач
	$problem_list_limiter = "
		AND
			`spm_problems`.`id` IN (
				" . $database->query($query_str)->fetch_array()[0] . "
			)
	";

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
      `spm_problems`.`enabled`,
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
        `spm_problems`.`id` LIKE '%" . @abs((int)$_GET['query']) . "%'
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
      `spm_problems`.`id` ASC
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

<table class="table table-bordered table-hover">

    <thead>

        <tr>

            <th><?=_("ID")?></th>
            <th><?=_("Назва завдання")?></th>
            <th><?=_("Вирішуваність")?></th>
            <th><?=_("Категорія")?></th>
            <th><?=_("Рейтинг")?></th>
            <th><?=_("")?></th>

        </tr>

    </thead>

    <?php foreach ($problems_list as $problem): ?>

        <?php

        /*
         * Проверяем текущего пользователя
         * на наличие доступа к указанной
         * задаче, если доступа нет -
         * просто не показываем указанную
         * задачу в этом списке.
         */

        $problem_enabled_checker = (

                $problem['enabled']
        ||
                (

                        Security::CheckAccessPermissions(PERMISSION::ADMINISTRATOR)
                    &&
                        !$problem['enabled']

                )

        );

        if ($problem_enabled_checker):

        ?>

            <tr>

                <td>
                    <strong><?=$problem["id"]?></strong>
                </td>

                <td>

                    <a
                            style="color: #212121 !important;"
                            href="<?=_SPM_?>index.php/problems/problem?id=<?=$problem["id"]?>"
                    ><?=$problem["name"]?></a>

                    <?php if (!$problem['enabled']): ?>

                        <span class="badge badge-warning"><?=_("Відключена")?></span>

                    <?php endif; ?>

                </td>

                <td>

                    <?php

                    $query_str = sprintf("
                        SELECT
                          SUM(
                            CASE WHEN (
                                  `spm_submissions`.`testType` = 'release'
                                AND
                                  `spm_submissions`.`b` > 0
                            ) THEN 1 ELSE 0 END
                          ) AS success_count,
                          COUNT(`spm_submissions`.`submissionId`) AS all_count
                        FROM
                          `spm_problems`
                        RIGHT JOIN
                          `spm_submissions`
                        ON
                          `spm_problems`.`id` = `spm_submissions`.`problemId`
                        WHERE
                          `olympId` = '0'
                        AND
                          `problemId` = '%s'
                        ;
                    ", $problem['id']);

                    $problem_submissions_count = $database->query($query_str)->fetch_assoc();

                    ?>

                    <span>
                        <?=$problem_submissions_count['success_count']?>&nbsp;/&nbsp;<?=$problem_submissions_count['all_count']?>
                    </span>

                </td>

                <td>
                    <?=$problem["category_name"]?>
                </td>

                <td>
                    <?=$problem["difficulty"]?> points
                </td>

                <td class="text-center">

                    <?php

                    // Формируем запрос на выборку из БД
                    $query_str = sprintf("
                        SELECT
                          `b`
                        FROM
                          `spm_submissions`
                        WHERE
                          `userId` = '%s'
                        AND
                          `problemId` = '%s'
                        AND
                          `olympId` = '%s'
                        ORDER BY
                          `b` DESC,
                          `time` DESC
                        LIMIT
                          1
                        ;
                    ",
                        (int)Security::getCurrentSession()['user_info']->getUserId(),
                        (int)$problem['id'],
                        (int)$associated_olymp
                    );

                    // Выполняем запрос на выборку данных из БД
                    $query = $database->query($query_str);

                    ?>

                    <?php if ($query->num_rows > 0): $submission_points = ($query->fetch_array()[0]); ?>

                        <?php if ($submission_points <= 0): ?>

                            <span class="badge badge-danger"><?=_("Не зараховано")?></span>

                        <?php elseif ($submission_points < $problem["difficulty"]): ?>

                            <span class="badge badge-warning"><?=_("Часткове зарахування")?></span>

                        <?php elseif ($submission_points >= $problem["difficulty"]): ?>

                            <span class="badge badge-success"><?=_("Вирішено")?></span>

                        <?php endif; ?>

                    <?php else: ?>

                        <span class="badge badge-info"><?=_("Не вирішено")?></span>

                    <?php endif; $query->free(); ?>

                </td>

            </tr>

        <?php endif; unset($problem_enabled_checker); ?>

    <?php endforeach; ?>

</table>