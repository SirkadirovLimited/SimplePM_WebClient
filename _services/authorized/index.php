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

define("__PAGE_TITLE__", _("Головна сторінка"));
define("__PAGE_LAYOUT__", "default");

?>
<style>
    h3.welcome {
        text-align: center;
        margin-top: 60px;
        margin-bottom: 60px;
    }

    div.row div.col-md-3 div.card {
        margin: 10px;
    }
    div.row div.col-md-3 div.card:hover {
        transform: scale(1.01, 1.01);
        border-color: #343a40;
    }

    #simplepm-name:hover {
        background-color: #343a40;
        color: #ffffff;
    }
</style>

<h3 class="welcome">
    <?=sprintf(_("Вітаємо Вас на головній сторінці веб-додатку %s!"), "<span id=\"simplepm-name\">SimplePM</span>")?>
</h3>

<style>

    .card a {
        color: #212121 !important;
    }

</style>

<div class="card-columns">

    <?php if (Security::CheckAccessPermissions(PERMISSION::STUDENT | PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR)): ?>

        <div class="card border-success">

            <div class="card-body">

                <h5 class="card-title">
                    <a href="<?=_SPM_?>index.php/problems/archive/"><?=_("Архів завдань")?></a>
                </h5>

                <p class="card-text">
                    <?=_("Вирішуйте завдання у вільному режимі. Чим більше завдань буде вирішено - тим вище Ви будете у рейтингу!")?>
                </p>

            </div>

        </div>

        <div class="card border-success">

            <div class="card-body">

                <h5 class="card-title">
                    <a href="<?=_SPM_?>index.php/problems/difficult/"><?=_("Відкладені завдання")?></a>
                </h5>

                <p class="card-text">
                    <?=_("Не змогли вирішити якесь завдання, але забули яке саме? В цьому сервісі міститься список відкладених Вами завдань.")?>
                </p>

            </div>

        </div>

        <div class="card border-success">

            <div class="card-body">

                <h5 class="card-title">
                    <a href="<?=_SPM_?>index.php/problems/submissions/"><?=_("Спроби")?></a>
                </h5>

                <p class="card-text">
                    <?=_("Отримайте інформацію про всі Ваші спроби вирішити завдання знаходячись лише на одній сторінці.")?>
                </p>

            </div>

        </div>

        <div class="card border-success">

            <div class="card-body">

                <h5 class="card-title">
                    <a href="<?=_SPM_?>index.php/problems/rating/"><?=_("Рейтинг користувачів")?></a>
                </h5>

                <p class="card-text">
                    <?=_("Перевірте свій рейтинг, рейтинг своїх друзів та підопічних!")?>
                </p>

            </div>

        </div>

        <div class="card border-success">

            <div class="card-body">

                <h5 class="card-title">
                    <a href="<?=_SPM_?>index.php/users/profile/"><?=_("Моя сторінка")?></a>
                </h5>

                <p class="card-text">
                    <?=_("Все що потрібно знати про себе - в єдиному сервісі!")?>
                </p>

            </div>

        </div>

        <?php if (Security::CheckAccessPermissions(PERMISSION::STUDENT)): ?>

            <div class="card border-success">

                <div class="card-body">

                    <h5 class="card-title">
                        <a href="<?=_SPM_?>index.php/olympiads/join/"><?=_("Приєднатись до змагання")?></a>
                    </h5>

                    <p class="card-text">
                        <?=_("Приєднайтесь до існуючого змагання, щоб отримати різні винагороди або оцінки!")?>
                    </p>

                </div>

            </div>

        <?php endif; ?>

    <?php endif; ?>

    <?php if (Security::CheckAccessPermissions(PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR)): ?>

        <div class="card border-warning">

            <div class="card-body">

                <h5 class="card-title">
                    <a href="<?=_SPM_?>index.php/users/TeacherID/"><?=_("TeacherID")?></a>
                </h5>

                <p class="card-text">
                    <?=_("Запрошуйте нових користувачів до системи за допомогою унікального коду запрошення.")?>
                </p>

            </div>

        </div>

        <div class="card border-warning">

            <div class="card-body">

                <h5 class="card-title">
                    <a href="<?=_SPM_?>index.php/users/groups/"><?=_("Управління групами")?></a>
                </h5>

                <p class="card-text">
                    <?=_("Користувацькі групи - найкращий спосіб розмежувати користувачів між собою, наприклад, за їх класами.")?>
                </p>

            </div>

        </div>

        <div class="card border-warning">

            <div class="card-body">

                <h5 class="card-title">
                    <a href="<?=_SPM_?>index.php/olympiads/list/"><?=_("Режим \"Змагання\"")?></a>
                </h5>

                <p class="card-text">
                    <?=_("Універсальний спосіб проведення будь-яких видів практичних робіт та змагань зі спортивного програмування!")?>
                </p>

            </div>

        </div>

    <?php endif; ?>

    <div class="card border-info">

        <div class="card-body">

            <h5 class="card-title">
                <a href="https://spm.sirkadirov.com/" target="_blank"><?=_("Офіційний сайт SimplePM")?></a>
            </h5>

            <p class="card-text">
                <?=_("<strong>SimplePM</strong> - це та сама система, в якій Ви знаходитесь прямо зараз. Відвідайте її веб-сайт для отримання детальної інформації про це.")?>
            </p>

        </div>

    </div>

    <div class="card border-info">

        <div class="card-body">

            <h5 class="card-title">
                <a href="https://simplepm.atlassian.net/" target="_blank"><?=_("Настанови з адміністрування та використання")?></a>
            </h5>

            <p class="card-text">
                <?=_("Отримайте детальні інструкції з використання та адміністрування цієї системи, дізнайтеся подробиці про таємний функціонал системи та багато іншого.")?>
            </p>

        </div>

    </div>

    <div class="card border-info">

        <div class="card-body">

            <h5 class="card-title">
                <a href="https://simplepm.atlassian.net/projects/GENERAL/board"><?=_("Повідомити про проблему")?></a>
            </h5>

            <p class="card-text">
                <?=_("Знайшли помилку в системі? Надайте інформацію про неї розробникам!")?>
            </p>

        </div>

    </div>

</div>