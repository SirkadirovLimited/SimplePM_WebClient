<?php
	deniedOrAllowed(PERMISSION::administrator);
	
	/////////////////////////////////////
	
	if (isset($_POST['update']))
	{
		
		// First sequrity
		isset($_POST['id']) && (int)$_POST['id'] > 0 && (int)$_POST['id'] <= 65535 or die(header('location: index.php?service=error&err=input'));
		isset($_POST['sort']) && (int)$_POST['sort'] >= 0 && (int)$_POST['sort'] <= 65535 or die(header('location: index.php?service=error&err=input'));
		isset($_POST['name']) && strlen($_POST['name']) > 0 && strlen($_POST['name']) <= 255 or die(header('location: index.php?service=error&err=input'));
		
		// Second security
		$_POST['id'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['id'])));
		$_POST['sort'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['sort'])));
		$_POST['name'] = mysqli_real_escape_string($db, strip_tags(trim($_POST['name'])));
		
		// MySQL query
		$query_str = "
			UPDATE
				`spm_problems_categories`
			SET
				`sort` = '" . $_POST['sort'] . "',
				`name` = '" . $_POST['name'] . "'
			WHERE
				`id` = '" . $_POST['id'] . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		// Header sending
		die(header('location: ' . $_SERVER["REQUEST_URI"]));
		
	}
	
	if (isset($_POST['delete']))
	{
		
		// First security
		isset($_POST['id']) && (int)$_POST['id'] > 0 && (int)$_POST['id'] <= 65535 or die(header('location: index.php?service=error&err=input'));
		
		// Second sequrity
		$_POST['id'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['id'])));
		
		// MySQL query
		$query_str = "
			DELETE
			FROM
				`spm_problems_categories`
			WHERE
				`id` = '" . $_POST['id'] . "'
			LIMIT
				1
			;
		";
		
		if (!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		// Header sending
		die(header('location: ' . $_SERVER["REQUEST_URI"]));
		
	}
	
	if (isset($_POST['create']))
	{
		
		// MySQL query
		$query_str = "
			INSERT INTO
				`spm_problems_categories`
			SET
				`name` = NULL
			;
		";
		
		if (!$db->query($query_str))
			die(mysqli_error($db));//die(header('location: index.php?service=error&err=db_error'));
		
		// Header sending
		die(header('location: ' . $_SERVER["REQUEST_URI"]));
		
	}
	
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			`id`,
			`name`,
			`sort`
		FROM
			`spm_problems_categories`
		ORDER BY
			`sort` ASC
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/////////////////////////////////////
	
	SPM_header("Категорії завдань", "Управління");
	
	/////////////////////////////////////
	
?>

<div align="right">
	<form method="post">
		<button type="submit" name="create" class="btn btn-primary btn-flat">Створити</button>
	</form>
</div>

<?php if ($query->num_rows > 0):?>

<style>
	category, categories {
		display: block;
	}
	
	category {
		margin-top: 4px;
		margin-bottom: 4px;
	}
</style>

<categories>
	
	<?php while ($category = $query->fetch_assoc()): ?>
	<category>
		<form method="post">
			<div class="input-group">
				
				<input type="hidden" name="id" value="<?=$category['id']?>">
				
				<span class="input-group-addon"><strong>ID <?=$category['id']?></strong></span>
				
				<input
					type="number"
					class="form-control"
					placeholder="Вага категорії"
					title="Вага категорії"
					name="sort"
					value="<?=$category['sort']?>"
				>
				<input
					type="text"
					class="form-control"
					placeholder="Назва категорії"
					title="Назва категорії"
					name="name"
					value="<?=$category['name']?>"
				>
				
				<span class="input-group-addon">
					<button type="submit" class="btn btn-success btn-flat" type="button" name="update">Зберегти</button>
					<button type="submit" class="btn btn-danger btn-flat" type="button" name="delete">Видалити</button>
				</span>
				
			</div>
		</form>
	</category>
	<?php endwhile; ?>
	
</categories>

<?php else: ?>

<div align="center">
	<h1>Упс!</h1>
	<p class="lead">
		Категорій завдань не знайдено!
	</pn>
</div>

<?php endif; SPM_footer(); ?>