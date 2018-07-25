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
			Архів задач
		</a>

		<div class="dropdown-menu">

		<a
				class="dropdown-item"
				href="<?=_SPM_?>index.php/problems/archive/"
		><?=_("Архів задач")?></a>

		<a
				class="dropdown-item"
				href="<?=_SPM_?>index.php/problems/difficult/"
		><?=_("Відкладені задачі")?></a>

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
		><?=_("Архів задач")?></a>
	</li>

<?php endif; ?>