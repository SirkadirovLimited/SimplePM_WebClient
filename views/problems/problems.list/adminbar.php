<?php if (permission_check($_SESSION["permissions"], PERMISSION::administrator)): ?>
<div align="right" style="margin-bottom: 10px;">
	<a href="index.php?service=problem-categories" class="btn btn-primary btn-flat">Управління категоріями</a>
	<a href="index.php?service=problem.edit" class="btn btn-success btn-flat">Створити завдання</a>
</div>
<?php endif; ?>