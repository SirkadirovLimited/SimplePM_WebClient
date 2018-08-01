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
 * Copyright (C) 2016-2018 Yurij Kadirov
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * GNU Affero General Public License applied only to source code of
 * this program. More licensing information hosted on project's website.
 *
 * Visit website for more details: https://spm.sirkadirov.com/
 */

// Запрашиваем доступ к глобальным переменным
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
