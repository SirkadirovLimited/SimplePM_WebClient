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
 * Всевозможные проверки безопасности
 */

isset($_GET['id'])
    or $_GET['id'] = Security::getCurrentSession()['user_info']->getUserId();

$_GET['id'] = abs((int)$_GET['id']);

/*
 * Проверка на существование
 * указанного   пользователя
 * системы.
 */

UserInfo::UserExists($_GET['id'])
    or Security::ThrowError("404");

/*
 * Проверяем,  имеет ли право текущий
 * пользователь системы просматривать
 * данный сервис с данными об указан-
 * ном  пользователе  или  нет, после
 * чего   предпринимаем   необходимые
 * действия в его адрес.
 */

Security::CheckAccessPermissionsForEdit($_GET['id'])
    or Security::ThrowError("403");

/*
 * Устанавливаем название и Layout сервиса
 */

define("__PAGE_TITLE__", _("Відкладені задачі"));
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к необходимым
 * глобальным переменным.
 */

global $database;

/*
 * Получаем список отложенных
 * задач для указанного поль-
 * зователя   и  обрабатываем
 * эту информацию.
 */

// Формируем запрос на выборку данных из БД
$query_str = "
    SELECT
      `spm_submissions`.`submissionId`,
      `spm_submissions`.`problemId`,
      `spm_submissions`.`time`,
      `spm_submissions`.`b`,
      
      `spm_problems`.`name`,
      `spm_problems`.`difficulty`
    FROM
      `spm_submissions`
    LEFT JOIN
      `spm_problems`
    ON
      `spm_submissions`.`problemId` = `spm_problems`.`id`
    WHERE
      `spm_submissions`.`olympId` = 0
    AND
      `spm_submissions`.`userId` = '" . $_GET['id'] . "'
    AND
      `spm_submissions`.`b` < `spm_problems`.`difficulty`
    ORDER BY
      `spm_submissions`.`time` DESC,
      `spm_submissions`.`problemId` ASC
    ;
";

// Выполняем запрос и обрабатываем результаты
$difficult_problems = $database->query($query_str)->fetch_all(MYSQLI_ASSOC);

?>

<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">

            <li class="nav-item">
                <a class="nav-link" href="<?=_SPM_?>index.php/users/profile/?id=<?=$_GET['id']?>"><?=_("Профіль")?></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?=_SPM_?>index.php/users/edit/?id=<?=$_GET['id']?>"><?=_("Редагувати сторінку")?></a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="<?=_SPM_?>index.php/problems/difficult/?id=<?=$_GET['id']?>"><?=_("Відкладені задачі")?></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?=_SPM_?>index.php/problems/submissions/?id=<?=$_GET['id']?>"><?=_("Спроби")?></a>
            </li>

        </ul>
    </div>
    <div class="card-body" style="padding: 0;">

        <?php if (sizeof($difficult_problems) > 0): ?>

            <table class="table" style="margin: 0;">

                <thead>

                <tr>

                    <th><?=_("ID")?></th>
                    <th><?=_("Назва задачі")?></th>
                    <th><?=_("Дата та час відправки")?></th>
                    <th><?=_("Спроба")?></th>

                </tr>

                </thead>

                <tbody>

                <?php foreach ($difficult_problems as $difficult_problem): ?>

                    <tr>

                        <td><?=$difficult_problem['problemId']?></td>
                        <td>
                            <a
                                class="btn-link"
                                href="<?=_SPM_?>index.php/problems/problem/?id=<?=$difficult_problem['problemId']?>"
                            ><?=$difficult_problem['name']?></a>
                        </td>
                        <td><?=$difficult_problem['time']?></td>
                        <td>
                            <a
                                class="btn-link"
                                href="<?=_SPM_?>index.php/problems/result/?id=<?=$difficult_problem['submissionId']?>"
                            ><?=$difficult_problem['submissionId']?></a>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <p class="lead text-success text-center" style="margin: 50px;">
                <?=_("Відкладених задач не знайдено!")?>
            </p>

        <?php endif; ?>

    </div>
</div>