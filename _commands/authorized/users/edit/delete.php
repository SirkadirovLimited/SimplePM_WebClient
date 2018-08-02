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

isset($_GET['id']) or Security::ThrowError("input");
$_GET['id'] = abs((int)$_GET['id']);

// Суперюзера удалять нельзя ни при каких условиях
($_GET['id'] > 1) or Security::ThrowError(
    _("Ви не маєте права видаляти локального адміністратора системи!")
);

// Проверка на существование указанного пользователя
UserInfo::UserExists($_GET['id'])
    or Security::ThrowError("404");

// Проверяем разрешения на удаление
Security::CheckAccessPermissionsForEdit($_GET['id'], false)
    or Security::ThrowError("403");

// CheckAccessPermissionsForEdit не всегда срабатывает так, как ожидалось
($_GET['id'] != Security::getCurrentSession()['user_info']->getUserId())
    or Security::ThrowError(_("Ви не маєте права видаляти самого себе з системи!"));

////////////////////////////////////////////////////////////////////////////////////

// Удаляем пользователя
@$database->query(
    sprintf(
        "
            DELETE FROM
              `spm_users`
            WHERE
              `id` = '%s'
            LIMIT
              1
            ;
        ",
        $_GET['id']
    )
);

// Удаляем его запросы на отправку
@$database->query(
    sprintf(
        "
            DELETE FROM
              `spm_submissions`
            WHERE
              `userId` = '%s'
            ;
        ",
        $_GET['id']
    )
);

// Удаляем связанные с ним TeacherID
@$database->query(
    sprintf(
        "
            DELETE FROM
              `spm_teacherid`
            WHERE
              `userId` = '%s'
            ;
        ",
        $_GET['id']
    )
);