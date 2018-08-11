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

?>

<?php if (!Olymp::IsAssociatedWithOlymp()): ?>

	<!-- Home -->

	<li class="nav-item">
		<a class="nav-link" href="<?=_SPM_?>"><?=_("Головна")?></a>
	</li>

	<!-- Users -->

	<li class="nav-item dropdown">

		<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
			<?=_("Користувачі")?>
		</a>

		<div class="dropdown-menu">
			<a class="dropdown-item" href="<?=_SPM_?>index.php/problems/rating/"><?=_("Рейтинг")?></a>
			<div class="dropdown-divider"></div>
			<a
                    href="#"

                    data-toggle="modal"
                    data-target="#iframe_modal-modal-dialog"

                    data-title="<?=_("Користувачі онлайн")?>"
                    data-src="<?=_SPM_?>index.php/modals/online"

                    class="dropdown-item"
            ><?=_("Користувачі онлайн")?></a>
            <a
                    href="#"

                    data-toggle="modal"
                    data-target="#iframe_modal-modal-dialog"

                    data-title="<?=_("Дні народження")?>"
                    data-src="<?=_SPM_?>index.php/modals/birthdays"

                    class="dropdown-item"
            ><?=_("Дні народження")?></a>
		</div>

	</li>

	<!-- Problems archive -->

	<li class="nav-item dropdown">

		<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
            <?=_("Архів завдань")?>
		</a>

		<div class="dropdown-menu">

		<a
				class="dropdown-item"
				href="<?=_SPM_?>index.php/problems/archive/"
		><?=_("Архів завдань")?></a>

		<a
				class="dropdown-item"
				href="<?=_SPM_?>index.php/problems/difficult/"
		><?=_("Відкладені завдання")?></a>

		<?php if (Security::CheckAccessPermissions(PERMISSION::TEACHER_MANAGE_PROBLEMS | PERMISSION::ADMINISTRATOR, false)): ?>

			<div class="dropdown-divider"></div>

			<a
					class="dropdown-item text-danger"
					href="<?=_SPM_?>index.php/problems/edit/problem/"
			><?=_("Створити задачу")?></a>

		<?php endif; ?>

		<div class="dropdown-divider"></div>

		<a class="dropdown-item disabled"><?=_("Черга перевірки")?></a>

	</li>

	<?php if (Security::CheckAccessPermissions(PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR)): ?>

		<li class="nav-item">
			<a
					class="nav-link"
					href="<?=_SPM_?>index.php/olympiads/list/"
			><?=_("Змагання")?></a>
		</li>

	<?php endif; ?>

	<?php if (Security::CheckAccessPermissions(PERMISSION::STUDENT)): ?>

		<li class="nav-item">
			<a
					class="nav-link"
					href="<?=_SPM_?>index.php/olympiads/join/"
			><?=_("Змагання")?></a>
		</li>

	<?php endif; ?>

<?php else: ?>

    <li class="nav-item">
        <a
            class="nav-link"
            href="<?=_SPM_?>index.php/olympiads/olympiad/"
        ><?=_("Змагання")?></a>
    </li>

	<li class="nav-item">
		<a
			class="nav-link"
			href="<?=_SPM_?>index.php/problems/archive/"
		><?=_("Архів завдань")?></a>
	</li>

<?php endif; ?>