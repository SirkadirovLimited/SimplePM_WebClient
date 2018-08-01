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

define("__PAGE_TITLE__", _("Дні народження"));
define("__PAGE_LAYOUT__", "skeleton");

// Запрашиваем доступ к глобальным переменным
global $database;

// Получаем полную информацию о текущем пользователе
$current_user_info = Security::getCurrentSession()['user_info']->getUserInfo();

// Генерируем запрос к БД
$query_str = sprintf("
    SELECT
      `id`,
      
      `firstname`,
      `secondname`,
      
      `birthday_date`
    FROM
      `spm_users`
    WHERE
      (
        `id` = '%s'
      OR
        `groupid` = '%s'
      OR
        `id` = '%s'
      OR
        `teacherId` = '%s'
      )
    AND
      (
        MONTH(`birthday_date`) = MONTH(NOW())
      )
    ORDER BY
      DAY(`birthday_date`) ASC,
      `secondname` ASC,
      `firstname` ASC
    ;
",
    $current_user_info['id'],
    $current_user_info['groupid'],
    $current_user_info['teacherId'],
    $current_user_info['id']
);

// Для устранения возможных конфликтов и сохранения памяти
unset($current_user_info);

// Выполняем запрос и производим выборку информации из БД
$birthdays_users_list = $database->query($query_str)->fetch_all(MYSQLI_BOTH);

?>

<div class="alert alert-secondary" style="margin: 0;">
    <?=_("Дні народження у цьому місяці")?> (<?=sizeof($birthdays_users_list)?>)
</div>

<?php if (sizeof($birthdays_users_list) > 0): ?>

    <ul class="list-group list-group-flush">

        <?php foreach ($birthdays_users_list as $user_item): ?>

            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="<?=_SPM_?>index.php/users/profile/?id=<?=$user_item['id']?>"><?=$user_item['firstname']?> <?=$user_item['secondname']?></a>
                <span class="badge badge-secondary badge-pill"><?=$user_item['birthday_date']?></span>
            </li>

        <?php endforeach; ?>

    </ul>

<?php else: ?>

    <h1 class="text-center" style="font-size: 10em;"><?=_(":(")?></h1>

    <h5 class="text-center"><?=_("Нажаль, іменинників не знайдено!")?></h5>

<?php endif; ?>
