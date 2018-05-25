<?php

/*
 * ███████╗██╗███╗   ███╗██████╗ ██╗     ███████╗██████╗ ███╗   ███╗
 * ██╔════╝██║████╗ ████║██╔══██╗██║     ██╔════╝██╔══██╗████╗ ████║
 * ███████╗██║██╔████╔██║██████╔╝██║     █████╗  ██████╔╝██╔████╔██║
 * ╚════██║██║██║╚██╔╝██║██╔═══╝ ██║     ██╔══╝  ██╔═══╝ ██║╚██╔╝██║
 * ███████║██║██║ ╚═╝ ██║██║     ███████╗███████╗██║     ██║ ╚═╝ ██║
 * ╚══════╝╚═╝╚═╝     ╚═╝╚═╝     ╚══════╝╚══════╝╚═╝     ╚═╝     ╚═╝
 *
 * SimplePM WebApp
 * A part of SimplePM programming contests management system.
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
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

/*
 * Производим выборку списка категорий задач из БД
 */

// Формируем запрос на выборку из БД
$query_str = "
    SELECT
      `id`,
      `name`
    FROM
      `spm_problems_categories`
    ORDER BY
      `sort` ASC,
      `id` ASC
    ;
";

// Выполняем запрос и производим выборку всех данных
$categories_list = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

?>

<div class="card">
    <div class="card-body">

        <form method="get">

            <div class="input-group">

                <div class="input-group-prepend">

                    <select name="category" class="form-control" style="min-width: 20px; max-width: 300px;">

                        <option value=""><?=_("Виберіть категорію завдань")?></option>

                        <?php foreach ($categories_list as $category): ?>
                            <option
                                value="<?=$category["id"]?>"
                                <?=(@$_GET['category'] == $category["id"] ? "selected" : "")?>
                            ><?=$category["name"]?></option>
                        <?php endforeach; ?>

                    </select>

                </div>

                <input
                    name="query"
                    type="text"
                    class="form-control"
                    style="min-width: 100px;"
                    placeholder="<?=_("Введіть запит для пошуку...")?>"
                    value="<?=@$_GET['query']?>"
                >

                <div class="input-group-prepend">

                    <button type="submit" class="btn btn-primary"><?=_("Знайти")?></button>

                </div>

            </div>

        </form>

    </div>
</div>
