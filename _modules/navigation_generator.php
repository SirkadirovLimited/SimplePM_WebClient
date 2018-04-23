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
			<a class="dropdown-item disabled"><?=_("Користувачі онлайн")?></a>
			<a class="dropdown-item disabled"><?=_("Дні народження")?></a>
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

		<?php if (
		Security::CheckAccessPermissions(
			Security::getCurrentSession()['user_info']->getUserInfo()['permissions'],
			PERMISSION::TEACHER_MANAGE_PROBLEMS | PERMISSION::ADMINISTRATOR,
			false
		)
		): ?>

			<div class="dropdown-divider"></div>

			<a
					class="dropdown-item text-danger"
					href="<?=_SPM_?>index.php/problems/edit/problem/"
			><?=_("Створити задачу")?></a>

		<?php endif; ?>

		<div class="dropdown-divider"></div>

		<a class="dropdown-item disabled"><?=_("Черга перевірки")?></a>

	</li>

	<li class="nav-item">
		<a
				class="nav-link"
				href="<?=_SPM_?>index.php/olympiads/join/"
		><?=_("Змагання")?></a>
	</li>

<?php else: ?>

    <li class="nav-item">
        <a
            class="nav-link"
            href="<?=_SPM_?>index.php/olympiads/olympiad/"
        ><?=_("Змагання")?></a>
    </li>

    <a
        class="dropdown-item"
        href="<?=_SPM_?>index.php/problems/archive/"
    ><?=_("Архів задач")?></a>

<?php endif; ?>