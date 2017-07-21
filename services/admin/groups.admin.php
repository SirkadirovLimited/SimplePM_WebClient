<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::teacher | PERMISSION::administrator);
	
	if (isset($_POST['rename'])){
		(isset($_POST['id']) && (int)$_POST['id'] > 0)
			or die(header('location: index.php?service=error&err=403'));
		
		isset($_POST['groupName'])
			or die(header('location: index.php?service=error&err=403'));
		
		$_POST['groupName'] = mysqli_real_escape_string($db, strip_tags(trim($_POST['groupName'])));
		
		(strlen($_POST['groupName']) > 0 && strlen($_POST['groupName']) <= 255)
			or die(header('location: index.php?service=error&err=403'));
		
		$query_str = "
			UPDATE
				`spm_users_groups`
			SET
				`name` = '" . $_POST['groupName'] . "'
			WHERE
				`id` = '" . (int)$_POST['id'] . "'
			AND
				`teacherId` = '" . $_SESSION["uid"] . "'
			LIMIT
				1
			;
		";
		
		if (!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		exit(header('location: ' . $_SERVER["REQUEST_URI"]));
	}
	
	if (isset($_POST['del'])){
		(isset($_POST['id']) && (int)$_POST['id'] > 0)
			or die(header('location: index.php?service=error&err=403'));
		
		$query_str = "
			DELETE FROM
				`spm_users_groups`
			WHERE
				`id` = '" . (int)$_POST['id'] . "'
			AND
				`teacherId` = '" . $_SESSION["uid"] . "'
			LIMIT 1;
		";
		
		if (!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		exit(header('location: ' . $_SERVER["REQUEST_URI"]));
	}
	
	if (isset($_POST['addGroup'])){
		$query_str = "
			INSERT INTO
				`spm_users_groups`
			SET
				`name` = 'New group',
				`teacherId` = '" . $_SESSION["uid"] . "'
			;
		";
		
		if (!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		exit(header('location: ' . $_SERVER["REQUEST_URI"]));
	}
	
	$query_str = "
		SELECT
			`id`,
			`name`
		FROM
			`spm_users_groups`
		WHERE
			`teacherId` = '" . $_SESSION["uid"] . "'
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	SPM_header("Групи користувачів", "Управління");
?>

<?php if ($query->num_rows > 0): ?>
<table class="table table-bordered table-hover">
	<?php while ($group = $query->fetch_assoc()): ?>
	<tr>
		<form action="index.php?service=groups.admin" method="post">
			<input type="hidden" name="id" value="<?=$group['id']?>">
			<td width="80%" style="padding: 0;" class="active">
				<input type="text" name="groupName" value="<?=$group['name']?>" class="form-control">
			</td>
			<td width="10%" style="padding: 0;">
				<button type="submit" name="rename" class="btn btn-success btn-flat btn-block"><span class="fa fa-pencil"></span> Зберегти</button>
			</td>
			<td width="10%" style="padding: 0;">
				<button type="submit" name="del" class="btn btn-danger btn-flat btn-block"><span class="fa fa-trash"></span> Видалити</button>
			</td>
		</form>
	</tr>
	<?php endwhile; ?>
</table>
<?php else: ?>
<div align="center"><h4>Ви ще не створили ні одної групи!</h4></div>
<?php endif; ?>
<form action="" method="post">
	<button type="submit" class="btn btn-primary btn-flat btn-block" name="addGroup">Створити групу</button>
</form>


<?php SPM_footer(); ?>