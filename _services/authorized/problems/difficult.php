<?php

/*
 * Copyright (C) 2018, Yurij Kadirov.
 * All rights are reserved.
 * Licensed under Apache License 2.0 with additional restrictions.
 *
 * @Author: Yurij Kadirov
 * @Website: https://sirkadirov.com/
 * @Email: admin@sirkadirov.com
 */

/*
 * Всевозможные проверки безопасности
 */

isset($_GET['id']) or Security::ThrowError(_("Ідентифікатор задачі не вказано!"));
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
    <div class="card-body">



    </div>
</div>