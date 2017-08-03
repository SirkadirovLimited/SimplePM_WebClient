<?php if (permission_check($_SESSION["permissions"], PERMISSION::administrator)): ?>
<button
	class="btn btn-primary btn-flat btn-xs"
	type="button"
	data-toggle="collapse"
	data-target="#control-<?=$problem["id"]?>"
>&nbsp;<strong><i class="fa fa-cog"></i>&nbsp;<?=$problem["id"]?></strong>&nbsp;</button>

<div class="collapse" id="control-<?=$problem["id"]?>" style="margin-top: 20px;">
	<a href="index.php?service=problem.edit&id=<?=$problem['id']?>" class="btn btn-warning btn-flat btn-xs btn-block">EDIT</a>
	<form action="index.php?service=problems" method="post">
		<input type="hidden" name="id" value="<?=$problem["id"]?>">
		<button
			type="submit"
			name="del"
			class="btn btn-danger btn-flat btn-xs btn-block"
			onclick="return confirm('Ви дійсно хочете видалити цю задачу?');"
		>DEL</button>
	</form>
</div>
<?php else: ?>
<strong><?=$problem["id"]?></strong>
<?php endif; ?>