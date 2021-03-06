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
 * Выполняем различные проверки безопасности
 */

isset($_GET['id']) && isset($_POST['new_permissions']) or Security::ThrowError("input");
$_GET['id'] = abs((int)$_GET['id']);
$_POST['new_permissions'] = abs((int)$_POST['new_permissions']);

((int)(Security::getCurrentSession()['user_info']->getUserId()) == 1) or Security::ThrowError(
    _("Змінювати права користувачів може лише суперадміністратор системи!")
);

// Суперюзера удалять нельзя ни при каких условиях
($_GET['id'] > 1) or Security::ThrowError(
    _("Ви не маєте права змінювати свої ж права доступу!")
);

// Проверка на существование указанного пользователя
UserInfo::UserExists($_GET['id'])
    or Security::ThrowError("404");

////////////////////////////////////////////////////////////////////////////////////

@$database->query(
    sprintf(
        "
            UPDATE
              `spm_users`
            SET
              `permissions` = '%s'
            WHERE
              `id` = '%s'
            LIMIT
              1
            ;
        ",
        $_POST['new_permissions'],
        $_GET['id']
    )
);

////////////////////////////////////////////////////////////////////////////////////

// Перезаписываем заголовки
header(
    'location: ' . _SPM_ . 'index.php/users/profile?id=' . $_GET['id'],
    true
);

// Завершаем работу скрипта
exit;