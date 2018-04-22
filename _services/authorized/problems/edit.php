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

isset($_GET['id']) or $_GET['id'] = 0;
$_GET['id'] = abs((int)$_GET['id']);

define("__PAGE_TITLE__", _("Редагування задачі"));
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;

?>

<form action="<?=_SPM_?>index.php?cmd=problems/edit/problem" method="post" style="margin-top: 20px;">

    <!-- System information -->

    <div class="form-group">
        <label><?=_("Ідентифікатор задачі")?></label>
        <input type="text" name="id" class="form-control disabled" required disabled>
        <small class="form-text text-muted">
            <?=_("Ідентифікатор задачі, яку потрібно відредагувати. Заповнюється автоматично.")?>
        </small>
    </div>

    <!-- Base information -->

    <div class="form-group">
        <label><?=_("Назва задачі")?></label>
        <input type="text" name="title" class="form-control" maxlength="255" required>
        <small class="form-text text-muted">
            <?=_("Вкажіть назву задачі. Вона повинна бути короткою, але в той самий час передавати основну ідею задачі.")?>
        </small>
    </div>

    <!-- Additional information -->

    <div class="form-group">
        <label><?=_("Категорія задачі")?></label>
        <select name="category_id" class="form-control" required>

            <option><?=_("Виберіть...")?></option>

        </select>
        <small class="form-text text-muted">
            <?=_("Вкажіть категорію, в яку буде додана ця задача.")?>
        </small>
    </div>

    <div class="form-group">
        <label><?=_("Складність задачі")?></label>
        <input
            type="number"
            name="difficulty"
            class="form-control"

            min="1"
            max="255"
            value="1"

            required
        >
        <small class="form-text text-muted">
            <?=_("Вкажіть кількість балів, що будуть надаватись за повне вирішення цієї задачі.")?>
        </small>
    </div>

    <!-- Allow/deny actions -->

    <div class="form-group">
        <div class="form-check">
            <input type="checkbox" name="enabled" class="form-check-input">
            <label class="form-check-label"><?=_("Задача доступна для перегляду та вирішення")?></label>
        </div>

        <small class="form-text text-muted">
            <?=_("Зверніть увагу на те, що цей параметр не блокує доступ до задачі адміністраторам системи.")?>
        </small>
    </div>

    <div class="form-group">
        <div class="form-check">
            <input type="checkbox" name="adaptProgramOutput" class="form-check-input">
            <label class="form-check-label"><?=_("Порівнювати очищені від зайвих пробілів вхідні потоки")?></label>
        </div>

        <small class="form-text text-muted">
            <?=_("Увімкніть для не суворої перевірки вихідних даних, вимкніть для суворої.")?>
        </small>
    </div>

    <!-- Description -->

    <div class="form-group">
        <label><?=_("Детальні умови задачі")?></label>
        <textarea name="description" class="form-control" maxlength="65535" required></textarea>
        <small class="form-text text-muted">
            <?=_("Надайте детальні умови задачі, включаючи всі вимоги, а також обмеження.")?>
        </small>
    </div>

    <!-- Input and output description  -->

    <div class="row">

        <div class="col-md-6 col-sm-12">

            <div class="form-group">
                <label><?=_("Опис вхідних даних")?></label>
                <textarea name="input_description" class="form-control"></textarea>
                <small class="form-text text-muted">
                    <?=_("Надайте детальний опис вхідних даних для користувацької програми.")?>
                </small>
            </div>

        </div>

        <div class="col-md-6 col-sm-12">

            <div class="form-group">
                <label><?=_("Опис вихідних даних")?></label>
                <textarea name="output_description" class="form-control"></textarea>
                <small class="form-text text-muted">
                    <?=_("Надайте детальний опис вихідних даних користувацької програми.")?>
                </small>
            </div>

        </div>

    </div>

    <!-- Author solution & its configuration -->

    <div class="form-group">
        <label><?=_("Початковий код авторського рішення")?></label>
        <textarea
            name="authorSolutionCode"
            class="form-control"

            required
        ></textarea>
        <small class="form-text text-muted">
            <?=_("Авторське рішення використовується для роботи debug-режиму тестування користувацьких рішень.")?>
        </small>
    </div>

    <div class="form-group">
        <label><?=_("Мова програмування авторського рішення")?></label>

        <select name="authorSolutionLanguage" class="form-control" required>

            <option><?=_("Виберіть...")?></option>

        </select>

        <small class="form-text text-muted">
            <?=_("Оберіть мову програмування, на якій було написано авторське рішення задачі.")?>
        </small>
    </div>

    <div align="right">

        <button type="reset" class="btn btn-outline-secondary"><?=_("Відмінити зміни")?></button>
        <button type="submit" class="btn btn-primary"><?=_("Зберегти зміни")?></button>

    </div>

</form>