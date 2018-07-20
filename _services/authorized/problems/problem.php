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
      `spm_problems`.`enabled`,
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
 * Если доступ к задаче ограничен,
 * предоставляем доступ к ней лишь
 * администраторам системы.
 */

(
        // Если пользователь не администратор
        !Security::CheckAccessPermissions(
                PERMISSION::ADMINISTRATOR,
                false
        )
    &&
        // И доступ к задаче ограничен
        !$problem_info['enabled']
) ? Security::ThrowError("403") : true; // Блокируем доступ или ничего не делаем

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

<?php if ($_CONFIG->getWebappConfig()['blockly_enabled']): ?>
    <!-- BLOCKLY INIT -->

    <script src="<?=_SPM_assets_?>_plugins/blockly/blockly_compressed.js"></script>
    <script src="<?=_SPM_assets_?>_plugins/blockly/blocks_compressed.js"></script>
    <script src="<?=_SPM_assets_?>_plugins/blockly/lua_compressed.js"></script>

    <script src="<?=_SPM_assets_?>_plugins/blockly/msg/js/uk.js"></script>

    <?php include(_SPM_configuration_ . "blockly-toolbox.cnf"); ?>

    <!-- /BLOCKLY INIT -->
<?php endif; ?>

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">

        <a
                class="nav-item nav-link"
                data-toggle="tab"
                href="#editor-code"
                role="tab"
        ><?=_("Редактор коду")?></a>

        <?php if ($_CONFIG->getWebappConfig()['blockly_enabled']): ?>
            <a
                    class="nav-item nav-link active"
                    data-toggle="tab"
                    href="#editor-visual"
                    onclick="$('#language_selector').val('<?=$_CONFIG->getWebappConfig()['blockly_lua_language_name']?>');"
                    role="tab"
            ><?=_("Візуальний редактор")?></a>
        <?php endif; ?>

        <a
                class="nav-item nav-link"
                data-toggle="tab"
                href="#editor-file"
                onclick=""
                role="tab"
        ><?=_("Завантажити файл")?></a>

    </div>
</nav>
<div class="tab-content">

    <div
            class="tab-pane fade"
            id="editor-code"
            role="tabpanel"
    >

        <pre id="code_editor"><?=htmlspecialchars($last_submission_info['problemCode'])?></pre>

    </div>

    <?php if ($_CONFIG->getWebappConfig()['blockly_enabled']): ?>
        <div
                class="tab-pane fade show active"
                id="editor-visual"
                role="tabpanel"
        ><div id="blockly-div" style="width: 100%; height: 400px;"></div></div>
    <?php endif; ?>

    <div
            class="tab-pane fade text-white bg-dark"
            id="editor-file"
            role="tabpanel"
    >

        <div class="row">

            <div class="col-md-4"></div>

            <div class="col-md-4 col-sm-12">

                <div style="margin-top: 30px; margin-bottom: 30px;">
                    <input type="file" id="editor-file-input" class="form-control-file">
                </div>

            </div>

            <div class="col-md-4"></div>

        </div>

        <script>

            $(document).ready(function () {

                $(document).on('change', '#editor-file-input', function () {

                    // Отримуємо інформацію про файл
                    var file = document.getElementById("editor-file-input").files[0];

                    // Виконуємо дії лише у випадку наявності обраного файлу
                    if (file) {

                        // Ініціалізуємо змінну типу файлового читаки
                        var reader = new FileReader();

                        // Запускаємо читання обраного користувачем файлу
                        reader.readAsText(file, "UTF-8");

                        // Виконання дій у випадку успіху
                        reader.onload = function (evt) {
                            ace.edit('code_editor').setValue(evt.target.result);
                        };

                        // Виконання дій у випадку помилки
                        reader.onerror = function (evt) {

                            alert('<?=_("Завантаження файлів не підтримується цим веб-браузером!")?>');
                            alert('<?=_("Будь-ласка, використовуйте лише рекомендовані розробниками SimplePM веб-браузери для сумісності та стабільності!")?>');

                        };

                    }

                });

            });

        </script>

    </div>

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

<?php if (Security::CheckAccessPermissions(PERMISSION::TEACHER_MANAGE_PROBLEMS | PERMISSION::ADMINISTRATOR)): ?>

	<div class="card">
		<div class="card-body text-center">

			<a
					href="<?=_SPM_?>index.php/problems/edit/problem/?id=<?=$_GET['id']?>"
					class="btn btn-link text-dark"
			><?=_("Редагувати")?></a>

			<a
					href="<?=_SPM_?>index.php/problems/edit/tests/?id=<?=$_GET['id']?>"
					class="btn btn-link text-dark"
			><?=_("Редагувати тести")?></a>

			<?php if (Security::CheckAccessPermissions(PERMISSION::ADMINISTRATOR)): ?>

				<a
						href="<?=_SPM_?>index.php?cmd=problems/edit/delete&id=<?=$_GET['id']?>"
						class="btn btn-link text-danger"
						onclick="return confirm('<?=_("Ви впевнені? Всі дані включаючи відправки цієї задачі будуть видалені!")?>');"
				><?=_("Видалити задачу")?></a>

			<?php endif; ?>

		</div>
	</div>

<?php endif; ?>

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

        $('#nav-tab a[href="#editor-code"]').tab('show')

    });

    //editor.session.setMode("ace/mode/c_cpp");
</script>

<?php if ($_CONFIG->getWebappConfig()['blockly_enabled']): ?>

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

<?php endif; ?>