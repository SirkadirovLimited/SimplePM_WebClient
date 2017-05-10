<!--SEARCH-->
<div class="row">
	<div class="col-md-8">
		<form action="" mathod="post">
			<!--HIDDEN VARIABLES-->
			<input type="hidden" name="service" value="problems" />
			<input type="hidden" name="page" value="1" />
			<!--QUERY SELECTER-->
			<input class="form-control" name="query" placeholder="№ задачи / название задачи" value="<?php print($_GET['query']); ?>">
			<input type="submit" class="btn btn-success btn-block btn-flat" value="Поиск">
		</form>
	</div>
	<div class="col-md-4">
		<form action="index.php" method="get">
			<!--HIDDEN VARIABLES-->
			<input type="hidden" name="service" value="problems" />
			<input type="hidden" name="page" value="1" />
			<!--CATEGORY SELECTOR-->
			<select class="form-control" name="catId" required>
				<option value="*" selected>Все темы и подтемы</option>
<?php
	if(!$db_result_cat = $db->query("SELECT * FROM `spm_problems_categories`"))
		die(header('location: index.php?service=error&err=db_error'));
	
	while ($problem_cat = $db_result_cat->fetch_assoc()):
?>
				<option value="<?php print($problem_cat["id"]); ?>"><?php print($problem_cat["name"]); ?></option>
<?php endwhile; ?>
			</select>
			<input type="submit" class="btn btn-primary btn-block btn-flat" value="Применить">
		</form>
	</div>
</div>