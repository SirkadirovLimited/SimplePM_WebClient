<!--SEARCH-->
<div class="row">
	<div class="col-md-8">
		<form action="" mathod="post">
			
			<input type="hidden" name="service" value="problems" />
			<input type="hidden" name="page" value="1" />
			
			<input class="form-control" name="query" placeholder="№ завдання / ім'я завдання" value="<?=$_GET['query']?>">
			<button type="submit" class="btn btn-success btn-block btn-flat">Знайти</button>
		</form>
	</div>
	<div class="col-md-4">
		<form action="index.php" method="get">
			<!--HIDDEN VARIABLES-->
			<input type="hidden" name="service" value="problems" />
			<input type="hidden" name="page" value="1" />
			<!--CATEGORY SELECTOR-->
			<select class="form-control" name="catId" required>
				<option value="*" selected>Усі теми</option>
<?php
	if(!$db_result_cat = $db->query("SELECT * FROM `spm_problems_categories`"))
		die(header('location: index.php?service=error&err=db_error'));
	
	while ($problem_cat = $db_result_cat->fetch_assoc()):
?>
				<option value="<?=$problem_cat["id"]?>"><?=$problem_cat["name"]?></option>
<?php endwhile; ?>
			</select>
			<button type="submit" class="btn btn-primary btn-block btn-flat">Відобразити</button>
		</form>
	</div>
</div>