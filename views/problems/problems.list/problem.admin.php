<?php if (permission_check($_SESSION["permissions"], PERMISSION::administrator)): ?>
<button class="btn btn-primary btn-flat btn-xs"
		type="button"
		data-toggle="collapse"
		data-target="#control-<?=$problem["id"]?>">
	...
</button>
<div class="collapse" id="control-<?=$problem["id"]?>" style="margin-top: 20px;">
	<a href="index.php?service=problem.edit&id=<?=$problem['id']?>" class="btn btn-warning btn-flat btn-xs btn-block">EDIT</a>
	<form action="index.php?service=problems" method="post">
		<input type="hidden" name="id" value="<?=$problem["id"]?>">
		<input
			type="submit"
			name="del"
			class="btn btn-danger btn-flat btn-xs btn-block"
			value="DEL"
			onclick="return confirm('Вы уверены что хотите удалить данную задачу? Вся информация, связанная с ней будет также удалена!');"
		>
	</form>
</div>
<?php endif; ?>