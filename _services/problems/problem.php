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

/*
 * Всевозможные проверки безопасности
 */

isset($_GET['id']) or Security::ThrowError(_("Ідентифікатор задачі не вказано!"));
$_GET['id'] = abs((int)$_GET['id']);

define("__PAGE_TITLE__", _("Задача") . "#" . @$_GET['id']);
define("__PAGE_LAYOUT__", "default");

global $database;

$query_str = "
    SELECT
      `id`,
      `difficulty`,
      `name`,
      `description`,
      `input_description`,
      `output_description`,
      `adaptProgramOutput`
    FROM
      `spm_problems`
    WHERE
      `enabled` = TRUE
    AND
      `authorSolution` IS NOT NULL
    AND
      `authorSolutionLanguage` IS NOT NULL
    LIMIT
      1
    ;
";

$problem_info = $database->query($query_str);

if ($problem_info->num_rows == 0)
    Security::ThrowError("404");

$problem_info = $problem_info->fetch_assoc();

?>

