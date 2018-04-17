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

/*
 * Производим   включение   используемых
 * и  необходимых файлов исходного кода.
 */

include_once _SPM_includes_ . "ServiceHelpers/Olymp.inc";

/*
 * Всевозможные проверки безопасности
 */

isset($_GET['id']) or Security::ThrowError(_("Ідентифікатор задачі не вказано!"));
$_GET['id'] = abs((int)$_GET['id']);

/*
 * Устанавливаем название и Layout сервиса
 */

define("__PAGE_TITLE__", _("Задача") . " №" . @$_GET['id']);
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;
global $_CONFIG;

/*
 * Получаем идентификатор текущего соревнования
 * для возможного ограничения доступного списка
 * задач.
 */

$associated_olymp = (int)(Security::getCurrentSession()["user_info"]->getUserInfo()["associated_olymp"]);

/*
 * Проверяем,  содержится ли текущая
 * задача  в  списке  доступных  для
 * решения задач во время проведения
 * текущего соревнования или урока.
 */

Olymp::CheckProblemInList($associated_olymp, $_GET['id']);

/*
 * Производим  выборку  информации
 * о текщей задаче из базы данных.
 */

// Формируем запрос на выборку
$query_str = "
    SELECT
      `spm_problems`.`id`,
      `spm_problems`.`difficulty`,
      `spm_problems`.`name`,
      `spm_problems`.`description`,
      `spm_problems`.`authorSolution`,
      `spm_problems`.`authorSolutionLanguage`,
      `spm_problems`.`input_description`,
      `spm_problems`.`output_description`,
      `spm_problems`.`adaptProgramOutput`,
      `spm_problems_categories`.`name` AS category_name,
      `spm_problems_tests`.`input` AS first_test_input,
      `spm_problems_tests`.`output` AS first_test_output
    FROM
      `spm_problems`
    LEFT JOIN
      `spm_problems_tests`
    ON
      `spm_problems`.`id` = `spm_problems_tests`.`problemId`
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
    ORDER BY
      `spm_problems_tests`.`id` ASC
    LIMIT
      1
    ;
";

// Выполняем запрос
$problem_info = $database->query($query_str);

// Если задача не найдена, выбрасываем исключение
if ($problem_info->num_rows == 0)
    Security::ThrowError("404");

// Получаем полную информацию о задаче
$problem_info = $problem_info->fetch_assoc();

/*
 * Получаем информацию о последней
 * попытке пользователя по текущей
 * задаче.
 *
 * Полученная информация будет
 * представлена пользователю в
 * виде инъекции в текущий по-
 * льзовательский интерфейс.
 */

// Формируем запрос на выборку данных
$query_str = "
    SELECT
      `submissionId`,
      `problemCode`,
      `testType`,
      `codeLang`
    FROM
      `spm_submissions`
    WHERE
      `userId` = '" . (int)Security::getCurrentSession()["user_info"]->getUserId() . "'
    AND
      `problemId` = '" . $_GET['id'] . "'
    AND
      `olympId` = '" . $associated_olymp . "'
    ORDER BY
      `time` DESC,
      `submissionId` DESC
    LIMIT
      1
    ;
";

// Выполняем запрос на выборку и получаем данные
$last_submission_info = @$database->query($query_str)->fetch_assoc();

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

<!-- BLOCKLY INIT -->
<script src="<?=_SPM_assets_?>_plugins/blockly/blockly_compressed.js"></script>
<script src="<?=_SPM_assets_?>_plugins/blockly/blocks_compressed.js"></script>
<script src="<?=_SPM_assets_?>_plugins/blockly/lua_compressed.js"></script>

<script src="<?=_SPM_assets_?>_plugins/blockly/msg/js/uk.js"></script>

<xml id="toolbox" style="display: none">
    <category name="<?=_("Логіка")?>" colour="%{BKY_LOGIC_HUE}">
        <block type="controls_if"></block>
        <block type="logic_compare"></block>
        <block type="logic_operation"></block>
        <block type="logic_negate"></block>
        <block type="logic_boolean"></block>
        <block type="logic_null"></block>
        <block type="logic_ternary"></block>
    </category>
    <category name="<?=_("Цикли")?>" colour="%{BKY_LOOPS_HUE}">
        <block type="controls_repeat_ext">
            <value name="TIMES">
                <shadow type="math_number">
                    <field name="NUM">10</field>
                </shadow>
            </value>
        </block>
        <block type="controls_whileUntil"></block>
        <block type="controls_for">
            <value name="FROM">
                <shadow type="math_number">
                    <field name="NUM">1</field>
                </shadow>
            </value>
            <value name="TO">
                <shadow type="math_number">
                    <field name="NUM">10</field>
                </shadow>
            </value>
            <value name="BY">
                <shadow type="math_number">
                    <field name="NUM">1</field>
                </shadow>
            </value>
        </block>
        <block type="controls_forEach"></block>
        <block type="controls_flow_statements"></block>
    </category>
    <category name="<?=_("Обчислення")?>" colour="%{BKY_MATH_HUE}">
        <block type="math_number"></block>
        <block type="math_arithmetic">
            <value name="A">
                <shadow type="math_number">
                    <field name="NUM">1</field>
                </shadow>
            </value>
            <value name="B">
                <shadow type="math_number">
                    <field name="NUM">1</field>
                </shadow>
            </value>
        </block>
        <block type="math_single">
            <value name="NUM">
                <shadow type="math_number">
                    <field name="NUM">9</field>
                </shadow>
            </value>
        </block>
        <block type="math_trig">
            <value name="NUM">
                <shadow type="math_number">
                    <field name="NUM">45</field>
                </shadow>
            </value>
        </block>
        <block type="math_constant"></block>
        <block type="math_number_property">
            <value name="NUMBER_TO_CHECK">
                <shadow type="math_number">
                    <field name="NUM">0</field>
                </shadow>
            </value>
        </block>
        <block type="math_round">
            <value name="NUM">
                <shadow type="math_number">
                    <field name="NUM">3.1</field>
                </shadow>
            </value>
        </block>
        <block type="math_on_list"></block>
        <block type="math_modulo">
            <value name="DIVIDEND">
                <shadow type="math_number">
                    <field name="NUM">64</field>
                </shadow>
            </value>
            <value name="DIVISOR">
                <shadow type="math_number">
                    <field name="NUM">10</field>
                </shadow>
            </value>
        </block>
        <block type="math_constrain">
            <value name="VALUE">
                <shadow type="math_number">
                    <field name="NUM">50</field>
                </shadow>
            </value>
            <value name="LOW">
                <shadow type="math_number">
                    <field name="NUM">1</field>
                </shadow>
            </value>
            <value name="HIGH">
                <shadow type="math_number">
                    <field name="NUM">100</field>
                </shadow>
            </value>
        </block>
        <block type="math_random_int">
            <value name="FROM">
                <shadow type="math_number">
                    <field name="NUM">1</field>
                </shadow>
            </value>
            <value name="TO">
                <shadow type="math_number">
                    <field name="NUM">100</field>
                </shadow>
            </value>
        </block>
        <block type="math_random_float"></block>
    </category>
    <category name="<?=_("Робота з текстом")?>" colour="%{BKY_TEXTS_HUE}">
        <block type="text"></block>
        <block type="text_join"></block>
        <block type="text_append">
            <value name="TEXT">
                <shadow type="text"></shadow>
            </value>
        </block>
        <block type="text_length">
            <value name="VALUE">
                <shadow type="text">
                    <field name="TEXT">abc</field>
                </shadow>
            </value>
        </block>
        <block type="text_isEmpty">
            <value name="VALUE">
                <shadow type="text">
                    <field name="TEXT"></field>
                </shadow>
            </value>
        </block>
        <block type="text_indexOf">
            <value name="VALUE">
                <block type="variables_get">
                    <field name="VAR">{textVariable}</field>
                </block>
            </value>
            <value name="FIND">
                <shadow type="text">
                    <field name="TEXT">abc</field>
                </shadow>
            </value>
        </block>
        <block type="text_charAt">
            <value name="VALUE">
                <block type="variables_get">
                    <field name="VAR">{textVariable}</field>
                </block>
            </value>
        </block>
        <block type="text_getSubstring">
            <value name="STRING">
                <block type="variables_get">
                    <field name="VAR">{textVariable}</field>
                </block>
            </value>
        </block>
        <block type="text_changeCase">
            <value name="TEXT">
                <shadow type="text">
                    <field name="TEXT">abc</field>
                </shadow>
            </value>
        </block>
        <block type="text_trim">
            <value name="TEXT">
                <shadow type="text">
                    <field name="TEXT">abc</field>
                </shadow>
            </value>
        </block>
        <block type="text_print">
            <value name="TEXT">
                <shadow type="text">
                    <field name="TEXT">abc</field>
                </shadow>
            </value>
        </block>
        <block type="text_prompt_ext">
            <value name="TEXT">
                <shadow type="text">
                    <field name="TEXT">abc</field>
                </shadow>
            </value>
        </block>
    </category>
    <category name="<?=_("Списки")?>" colour="%{BKY_LISTS_HUE}">
        <block type="lists_create_with">
            <mutation items="0"></mutation>
        </block>
        <block type="lists_create_with"></block>
        <block type="lists_repeat">
            <value name="NUM">
                <shadow type="math_number">
                    <field name="NUM">5</field>
                </shadow>
            </value>
        </block>
        <block type="lists_length"></block>
        <block type="lists_isEmpty"></block>
        <block type="lists_indexOf">
            <value name="VALUE">
                <block type="variables_get">
                    <field name="VAR">{listVariable}</field>
                </block>
            </value>
        </block>
        <block type="lists_getIndex">
            <value name="VALUE">
                <block type="variables_get">
                    <field name="VAR">{listVariable}</field>
                </block>
            </value>
        </block>
        <block type="lists_setIndex">
            <value name="LIST">
                <block type="variables_get">
                    <field name="VAR">{listVariable}</field>
                </block>
            </value>
        </block>
        <block type="lists_getSublist">
            <value name="LIST">
                <block type="variables_get">
                    <field name="VAR">{listVariable}</field>
                </block>
            </value>
        </block>
        <block type="lists_split">
            <value name="DELIM">
                <shadow type="text">
                    <field name="TEXT">,</field>
                </shadow>
            </value>
        </block>
        <block type="lists_sort"></block>
    </category>
    <sep></sep>
    <category name="<?=_("Змінні")?>" colour="%{BKY_VARIABLES_HUE}" custom="VARIABLE"></category>
    <category name="<?=_("Функції")?>" colour="%{BKY_PROCEDURES_HUE}" custom="PROCEDURE"></category>
</xml>

<!-- /BLOCKLY INIT -->

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a
                class="nav-item nav-link active"
                data-toggle="tab"
                href="#editor-code"
                role="tab"
        ><?=_("Редактор коду")?></a>
        <a
                class="nav-item nav-link"
                data-toggle="tab"
                href="#editor-visual"
                role="tab"
        ><?=_("Візуальний редактор")?></a>
        <a
                class="nav-item nav-link disabled"
                data-toggle="tab"
                href="#editor-file"
                onclick=""
                role="tab"
        ><?=_("Завантажити файл")?></a>

    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div
            class="tab-pane fade show active"
            id="editor-code"
            role="tabpanel"
    >

        <pre id="code_editor"><?=htmlspecialchars($last_submission_info['problemCode'])?></pre>

    </div>
    <div
            class="tab-pane fade"
            id="editor-visual"
            role="tabpanel"
    ><div id="blockly-div" style="width: 100%; height: 400px;"></div></div>
</div>

<form action="<?=_SPM_?>index.php?cmd=problems/send_submission" method="post" enctype="multipart/form-data">

    <input type="hidden" name="problem_id" value="<?=$problem_info["id"]?>">

	<textarea
			id="code_place"
			name="code"
            hidden
            required
	></textarea>

    <textarea
            class="form-control text-white bg-dark"
            id="custom_test"
            name="custom_test"
            placeholder="<?=_("Користувацький тест для Debug-режиму тестування")?>"
            minlength="0"
            maxlength="65000"
    ></textarea>

    <div class="input-group">

        <select
                id="language_selector"
                name="submission_language"
                class="form-control"
                onchange="changeLanguage();"
                required
        >
            <option value><?=_("Виберіть мову програмування")?></option>

            <?php foreach ($_CONFIG->getCompilersConfig() as $compiler): if ($compiler['enabled']): ?>

                <option
                        value="<?=$compiler['language_name']?>"
                        langmode="<?=$compiler['editor_mode']?>"
                        <?=(
                                $compiler['language_name'] == @$last_submission_info['codeLang']
                                    ? "selected"
                                    : ""
                        )?>
                ><?=$compiler['display_name']?> (<?=$compiler['language_name']?>)</option>

            <?php endif; endforeach; ?>

        </select>

        <select
				name="submission_type"
				class="form-control"
				required
		>

            <option value><?=_("Виберіть тип перевірки")?></option>

            <option
                    value="syntax"
                    <?=(
                        @$last_submission_info['testType'] == "syntax"
                            ? "selected"
                            : ""
                    )?>
            ><?=_("Перевірка синтаксису")?></option>

            <option
                    value="debug"
                    <?=(
                        @$last_submission_info['testType'] == "debug"
                            ? "selected"
                            : ""
                    )?>
            ><?=_("Debug-режим")?></option>

            <option
                    value="release"
                    <?=(
                        @$last_submission_info['testType'] == "release"
                            ? "selected"
                            : ""
                    )?>
            ><?=_("Release-режим")?></option>

        </select>

        <button
                type="submit"
                class="btn btn-primary"
				onclick="
				    $('#code_place').text(ace.edit('code_editor').getValue());

				    return $('#code_place').length > 0;
                "
        ><?=_("Відправити")?></button>

    </div>

	<?php if (@(int)$last_submission_info['submissionId'] > 0): ?>

		<a
				class="btn btn-outline-dark btn-block"
				href="<?=_SPM_?>index.php/problems/result/?id=<?=$last_submission_info['submissionId']?>"
		><?=_("Результат останньої відправки")?></a>

	<?php endif; ?>
	<?php if (
			Security::CheckAccessPermissions(
				Security::getCurrentSession()["user_info"]->getUserInfo()['permissions'],
				PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR
			) &&
			$problem_info['authorSolution'] != null &&
			$problem_info['authorSolutionLanguage'] != null
	): ?>
		<button
				type="button"
				class="btn btn-outline-secondary btn-block"
				style="margin: 0;"
				data-toggle="modal"
				data-target="#author_solution"
		><?=_("Отримати авторське рішення")?></button>

		<div class="modal fade" id="author_solution" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><?=_("Авторське рішення задачі")?> (<?=$problem_info['authorSolutionLanguage']?>)</h5>
						<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
					</div>
					<pre class="modal-body"><?=htmlspecialchars($problem_info['authorSolution'])?></pre>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_("Закрити вікно")?></button>
					</div>
				</div>
			</div>
		</div>

	<?php endif; ?>

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
    <div class="card-body text-justify">
        <?=htmlspecialchars_decode(trim($problem_info["description"]))?>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="row">

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><strong><?=_("Опис вхідного потоку")?></strong></h6>

                <p
						class="card-text text-justify"
				><?=strlen($problem_info["input_description"]) > 0 ? $problem_info["input_description"] : _("Немає вхідних даних")?></p>

            </div>

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><strong><?=_("Опис вихідного потоку")?></strong></h6>

                <p
						class="card-text text-justify"
				><?=strlen($problem_info["output_description"]) > 0 ? $problem_info["output_description"] : _("Немає вихідних даних")?></p>

            </div>

        </div>

    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="row">

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><strong><?=_("Приклад вхідного потоку")?> (input.dat)</strong></h6>

                <pre
						class="card-text text-justify"
				><?=strlen($problem_info["first_test_input"]) > 0 ? $problem_info["first_test_input"] : _("Немає вхідних даних")?></pre>

            </div>

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><strong><?=_("Приклад вихідного потоку")?> (output.dat)</strong></h6>

                <pre
						class="card-text text-justify"
				><?=strlen($problem_info["first_test_output"]) > 0 ? $problem_info["first_test_output"] : _("Немає вихідних даних")?></pre>

            </div>

        </div>

    </div>
</div>

<script src="<?=_SPM_assets_?>_plugins/ace/ace.js"></script>
<script>

    function changeLanguage()
    {

        ace.edit('code_editor').session.setMode(
            'ace/mode/' + $('#language_selector option:selected').attr('langmode')
        );

    }

    document.addEventListener('DOMContentLoaded', function() {

        var editor = ace.edit("code_editor");
        editor.setTheme("ace/theme/dracula");

        changeLanguage();

    });

    //editor.session.setMode("ace/mode/c_cpp");
</script>

<!-- BLOCKLY INIT JS -->

<script>

    function urlBase64Decode(str) // From https://jwt.io/js/jwt.js
    {

        var output = str.replace(/-/g, '+').replace(/_/g, '/');

        switch (output.length % 4) {
            case 0:
                break;

            case 2:
                output += '==';
                break;
            case 3:
                output += '=';
                break;
            default:
                throw 'Illegal base64url string!';
        }

        var result = window.atob(output); //polifyll https://github.com/davidchambers/Base64.js

        try {
            return decodeURIComponent(escape(result));
        }
        catch (err) {
            return result;
        }

    }

    document.addEventListener('DOMContentLoaded', function() {

        var blocklyWorkspace = Blockly.inject(
            'blockly-div',
            {

                collapse: true,
                comments: true,
                sounds: false,
                toolbox: document.getElementById('toolbox')

            }
        );

        var blockly_code_checker = (

            ace.edit('code_editor').getValue().startsWith("--[[SIMPLEPM_BLOCKLY_LUA]]--") &&

            $('#language_selector')
                .val() === "<?=$_CONFIG->getWebappConfig()['blockly_lua_language_name']?>"
        );

        if (blockly_code_checker)
        {

            var code = ace.edit('code_editor').getValue();
            var pos = code.lastIndexOf("--[[XML|") + 8;

            var blocklyXmlCodeBase64 = code.substr(
                pos,
                code.length - pos - 8
            );

            Blockly.Xml.domToWorkspace(

                Blockly.Xml.textToDom(

                    urlBase64Decode(
                        blocklyXmlCodeBase64
                    )

                ),

                blocklyWorkspace

            );

        }

        blocklyWorkspace.addChangeListener(function () {

            ace.edit('code_editor').setValue(

                "--[[SIMPLEPM_BLOCKLY_LUA]]--\n"
                + Blockly.Lua.workspaceToCode(blocklyWorkspace) + "\n"
                + "--[[XML|" +

                btoa(

                    Blockly.Xml.domToText(

                        Blockly.Xml.workspaceToDom(

                            blocklyWorkspace

                        )

                    )

                )

                + "|XML]]--"

            );

        });

    });

</script>

<!-- /BLOCKLY INIT JS -->