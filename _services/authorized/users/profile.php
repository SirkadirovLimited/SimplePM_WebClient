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

define("__PAGE_TITLE__", _("Профіль користвувача"));
define("__PAGE_LAYOUT__", "default");

/*
 * Защита от различных видов атак
 */

isset($_GET['id']) or $_GET['id'] = Security::getCurrentSession()["user_info"]->getUserId();
$_GET['id'] = abs((int)$_GET['id']);

// Если идентификатор 0 и ниже, отображаем страницу текущего пользователя
if ($_GET['id'] <= 0)
    $_GET['id'] = Security::getCurrentSession()["user_info"]->getUserId();

// Проверка пользователя с указанным идентификатором на существование
UserInfo::UserExists(
    $_GET['id']
) or Security::ThrowError(_("Користувача з вказаним ідентифікатором не знайдено!"));

// Получаем информацию о пользователе
$user_info = UserInfo::getUserInfo($_GET['id']);

// Запрашиваем доступ к глобальным переменным
global $database;

?>

<div class="card text-center">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">

            <li class="nav-item">
                <a class="nav-link active" href="<?=_SPM_?>index.php/users/profile/?id=<?=$_GET['id']?>"><?=_("Профіль")?></a>
            </li>

			<?php if (Security::CheckAccessPermissionsForEdit($user_info['id'])): ?>

				<li class="nav-item">
					<a class="nav-link" href="<?=_SPM_?>index.php/users/edit/?id=<?=$_GET['id']?>"><?=_("Редагувати сторінку")?></a>
				</li>

                <li class="nav-item">
                    <a class="nav-link" href="<?=_SPM_?>index.php/problems/difficult/?id=<?=$_GET['id']?>"><?=_("Відкладені задачі")?></a>
                </li>

				<li class="nav-item">
					<a class="nav-link" href="<?=_SPM_?>index.php/problems/submissions/?id=<?=$_GET['id']?>"><?=_("Спроби")?></a>
				</li>

			<?php endif; ?>

        </ul>
    </div>
    <div class="card-body">

        <div style="margin-top: 2rem; margin-bottom: 3rem;">

            <h2 style="margin: 0;">
                <?=$user_info["secondname"]?> <?=$user_info["firstname"]?> <?=$user_info["thirdname"]?>
            </h2>

            <h5 class="text-secondary"><?=$user_info['institution']?></h5>

        </div>

        <!-- INFORMATION BAR -->

        <?php

        $problems_count = @(int)($database->query("
            SELECT
              COUNT(`id`)
            FROM
              `spm_problems`
            ;
        ")->fetch_array()[0]);

        $solved_problems_count = @(int)($database->query(sprintf("
            SELECT
              COUNT(`spm_submissions`.`submissionId`)
            FROM
              `spm_submissions`
            LEFT JOIN
              `spm_problems`
            ON
              `spm_submissions`.`problemId` = `spm_problems`.`id`
            WHERE
              (
                  `spm_submissions`.`olympId` = '0'
                AND
                  `spm_submissions`.`userId` = '%s'
                AND
                  `spm_submissions`.`testType` = 'release'
                AND
                  `spm_submissions`.`b` >= `spm_problems`.`difficulty`
              )
            ;
        ", $user_info['id']))->fetch_array()[0]);

        $difficult_problems_count = @(int)($database->query(sprintf("
            SELECT
              COUNT(`spm_submissions`.`submissionId`)
            FROM
              `spm_submissions`
            LEFT JOIN
              `spm_problems`
            ON
              `spm_submissions`.`problemId` = `spm_problems`.`id`
            WHERE
              (
                  `spm_submissions`.`olympId` = '0'
                AND
                  `spm_submissions`.`userId` = '%s'
                AND
                  `spm_submissions`.`b` < `spm_problems`.`difficulty`
              )
            ;
        ", $user_info['id']))->fetch_array()[0]);

        ?>

        <div class="card" style="margin-top: 30px; margin-bottom: 0;">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 col-sm-12 text-center">

                        <h2><?=(int)$user_info["rating_count"]?></h2>
                        <span class="lead"><?=_("Сума балів")?></span>

                    </div>

                    <div class="col-md-3 col-sm-12 text-center">

                        <h2><?=number_format((float)$user_info["rating"], 2)?></h2>
                        <span class="lead"><?=_("Рейтинг")?></span>

                    </div>

                    <div class="col-md-3 col-sm-12 text-center">

                        <h2><?=$solved_problems_count?></h2>
                        <span class="lead"><?=_("Прийнятих рішень")?></span>

                    </div>

                    <div class="col-md-3 col-sm-12 text-center">

                        <h2><?=$difficult_problems_count?></h2>
                        <span class="lead"><?=_("Відкладені задачі")?></span>

                    </div>

                </div>

            </div>

        </div>

        <div class="progress">

            <div
                    class="progress-bar bg-success"
                    style="width: <?=round($solved_problems_count / $problems_count * 100)?>%"
            ></div>

            <div
                    class="progress-bar bg-danger"
                    style="width: <?=round($difficult_problems_count / $problems_count * 100)?>%"
            ></div>

            <?php if ($solved_problems_count + $difficult_problems_count == $problems_count): ?>

                <div
                        class="progress-bar bg-light"
                        style="opacity: 0; width: <?=round(($problems_count - $solved_problems_count - $difficult_problems_count) / $problems_count * 100)?>%"
                ></div>

            <?php endif; ?>

        </div>

        <!-- /INFORMATION BAR -->

        <div class="row" style="margin-top: 30px;">

            <!-- INFORMATION ABOUT USER STARTS -->

            <div class="col-md-6 col-sm-12 text-left">

                <div class="card">
                    <div class="card-header"><?=_("Інформація про користувача")?></div>
                    <div class="card-body" style="padding: 0;">

                        <div class="list-group">

                            <a class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?=_("Ім'я")?></h6>
                                </div>
                                <p class="mb-1"><?=$user_info["firstname"]?></p>
                            </a>

                            <a class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?=_("Прізвище")?></h6>
                                </div>
                                <p class="mb-1"><?=$user_info["secondname"]?></p>
                            </a>

                            <a class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?=_("По-батькові")?></h6>
                                </div>
                                <p class="mb-1"><?=$user_info["thirdname"]?></p>
                            </a>

                            <a class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?=_("Дата народження")?></h6>
                                </div>
                                <p class="mb-1"><?=$user_info["birthday_date"]?></p>
                            </a>

                        </div>

                    </div>
                </div>

            </div>

            <!-- INFORMATION ABOUT USER ENDS -->

            <!-- SYSTEM INFORMATION ABOUT USER STARTS -->

            <div class="col-md-6 col-sm-12 text-left">

                <div class="card">

                    <div class="card-header"><?=_("Системна інформація")?></div>

                    <div class="card-body" style="padding: 0;">

                        <div class="list-group">

                            <a
                                    href="mailto:<?=$user_info["email"]?>"
                                    class="list-group-item list-group-item-action flex-column align-items-start"
                            >

                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?=_("E-mail адреса")?></h6>
                                </div>

                                <p class="mb-1"><?=$user_info["email"]?></p>

                            </a>

                            <a
                                    href="<?=_SPM_?>index.php/problems/rating/?group=<?=(int)$user_info["groupid"]?>"
                                    class="list-group-item list-group-item-action flex-column align-items-start"
                            >

                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?=_("Група")?></h6>
                                </div>

                                <p class="mb-1">
                                    <?=UserInfo::GetGroupName((int)$user_info["groupid"])?> (gid<?=(int)$user_info["groupid"]?>)
                                </p>

                            </a>

                            <a
                                    href="<?=_SPM_?>index.php/users/profile/?id=<?=$user_info["teacherId"]?>"
                                    class="list-group-item list-group-item-action flex-column align-items-start"
                            >

                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?=_("Куратор")?></h6>
                                </div>

                                <?php if (UserInfo::UserExists($user_info['teacherId'])): ?>

                                    <?php $curator_info = UserInfo::getUserInfo($user_info["teacherId"]); ?>

                                    <p class="mb-1">
                                        <?=$curator_info["secondname"]?>
                                        <?=$curator_info["firstname"]?>
                                        <?=$curator_info["thirdname"]?>, <?=UserInfo::GetGroupName((int)$curator_info["groupid"])?>
                                    </p>

                                    <?php unset($curator_info); ?>

                                <?php else: ?>

                                    <p class="mb-1"><?=_("Сам собі пан")?> (<?=$user_info['teacherId']?>)</p>

                                <?php endif; ?>

                            </a>

                            <a class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?=_("Остання активність на сайті")?></h6>
                                </div>
                                <p class="mb-1"><?=$user_info["last_online"]?></p>
                            </a>

                        </div>

                    </div>

                </div>

            </div>

            <!-- SYSTEM INFORMATION ABOUT USER ENDS -->

        </div>

    </div>
</div>