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
