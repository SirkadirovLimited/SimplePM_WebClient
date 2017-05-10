<?php if (permission_check($_SESSION["permissions"], PERMISSION::administrator)): ?>
<div align="right" style="margin-bottom: 10px;">
	<a href="index.php?service=problem.edit" class="btn btn-success btn-flat">Создать задачу</a>
</div>
<?php endif; ?>