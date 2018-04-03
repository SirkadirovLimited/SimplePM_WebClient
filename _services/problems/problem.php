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

define("__PAGE_TITLE__", _("Задача") . " #" . @$_GET['id']);
define("__PAGE_LAYOUT__", "default");

global $database;
global $_CONFIG;

$query_str = "
    SELECT
      `spm_problems`.`id`,
      `spm_problems`.`difficulty`,
      `spm_problems`.`name`,
      `spm_problems`.`description`,
      `spm_problems`.`input_description`,
      `spm_problems`.`output_description`,
      `spm_problems`.`adaptProgramOutput`,
      `spm_problems_categories`.`name` AS category_name
    FROM
      `spm_problems`
    RIGHT JOIN
      `spm_problems_categories`
    ON
      `spm_problems`.`category_id` = `spm_problems_categories`.`id`
    WHERE
      `spm_problems`.`id` = '" . $_GET['id'] . "'
    AND
      `spm_problems`.`enabled` = TRUE
    AND
      `spm_problems`.`authorSolution` IS NOT NULL
    AND
      `spm_problems`.`authorSolutionLanguage` IS NOT NULL
    LIMIT
      1
    ;
";

$problem_info = $database->query($query_str);

if ($problem_info->num_rows == 0)
    Security::ThrowError("404");

$problem_info = $problem_info->fetch_assoc();

?>

<style>
    #code_editor {
        width: 100%;
        height: 400px;
        margin: 0;
    }

    #custom_test {
        width: 100%;
        height: 100px;
        resize: none;
    }

    .card {
        margin: 0;
    }
</style>

<pre id="code_editor"></pre>

<form action="<?=_SPM_?>index.php?cmd=problems/send_submission" method="post">

    <input type="hidden" name="problem_id" value="<?=$problem_info["id"]?>">

	<textarea
			id="code_place"
			name="code"
            hidden
            required
	></textarea>

    <textarea
            class="form-control"
            id="custom_test"
            name="custom_test"
            placeholder="<?=_("Користувацький тест для Debug-режиму тестування")?>"
            minlength="0"
            maxlength="65000"
    ></textarea>

    <div class="input-group">

        <select name="submission_language" class="form-control" required>
            <option value>Виберіть мову програмування</option>

            <?php foreach ($_CONFIG->getCompilersConfig() as $compiler): if ($compiler['enabled']): ?>

                <option value="<?=$compiler['language_name']?>"><?=$compiler['display_name']?></option>

            <?php endif; endforeach; ?>

        </select>

        <select name="submission_type" class="form-control" required>
            <option value>Виберіть тип перевірки</option>
            <option value="syntax">Перевірка синтаксису</option>
            <option value="debug" selected>Debug-режим</option>
            <option value="release">Release-режим</option>
        </select>

        <button
                type="submit"
                class="btn btn-primary"
				onclick="
				    $('#code_place').text(editor.getValue());

				    return $('#code_place').length > 0;
                "
        ><?=_("Відправити")?></button>

    </div>

</form>



<div class="card">
    <div class="card-body text-center" style="padding: 5px;">
        <strong>
            <?=$problem_info["id"]?>.
            <?=$problem_info["name"]?>
            <span class="badge badge-info"><?=$problem_info["category_name"]?></span>
            <span class="badge badge-success"><?=$problem_info["difficulty"]?> points</span>
        </strong>
    </div>
</div>

<div class="card">
    <div class="card-body text-justify" style="padding-bottom: 5px;">
        <?=htmlspecialchars_decode(trim($problem_info["description"]))?>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="row">

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><?=_("Опис вхідного потоку")?></h6>
                <p class="card-text text-justify"><?=$problem_info["input_description"]?></p>

            </div>

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><?=_("Опис вихідного потоку")?></h6>
                <p class="card-text text-justify"><?=$problem_info["output_description"]?></p>

            </div>

        </div>

    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="row">

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><?=_("Приклад вхідного потоку")?> (input.dat)</h6>
                <p class="card-text text-justify"><?=$problem_info["input_description"]?></p>

            </div>

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><?=_("Приклад вихідного потоку")?> (output.dat)</h6>
                <p class="card-text text-justify"><?=$problem_info["output_description"]?></p>

            </div>

        </div>

    </div>
</div>

<script src="<?=_SPM_assets_?>_plugins/ace/ace.js"></script>
<script>
    var editor = ace.edit("code_editor");
    editor.setTheme("ace/theme/dracula");
    editor.session.setMode("ace/mode/c_cpp");
</script>