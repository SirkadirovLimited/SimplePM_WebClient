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

@$database->query(sprintf("DELETE FROM `spm_users` WHERE `id` = '%s';", $_GET['id']));

@$database->query(sprintf("DELETE FROM `spm_submissions` WHERE `userId` = '%s';", $_GET['id']));

@$database->query(sprintf("DELETE FROM `spm_teacherid` WHERE `userId` = '%s';", $_GET['id']));