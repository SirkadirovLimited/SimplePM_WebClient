<?php
	
	/* Security checkers (Vol. 1) */
	deniedOrAllowed(PERMISSION::administrator);
	isset($_GET['id']) && (int)$_GET['id'] > 0 or die(header('location: index.php?service=error&err=404'));
	$_GET['id'] = (int)$_GET['id'];
	
	/* Security checkers (Vol. 2) */
	$query_str = "
		SELECT
			count(`id`)
		FROM
			`spm_problems`
		WHERE
			`id` = '" . $_GET['id'] . "'
		LIMIT
			1
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ((int)$query->fetch_array()[0] < 1)
		die(header('location: index.php?service=error&err=404'));
	
	/* We are including something here... */
	if (isset($_POST['send']))
		include(_S_SERV_INC_ . "problems/edit/tests.import.php");
	
	/* Header */
	SPM_header("Задача №" . $_GET['id'], "Імпорт тестів");
	
?>

<p class="lead">
	Для імпорту тестів до задачі оберіть JSON файл (що містить інформацію про них) в формі, що знаходиться нижче. 
	Після цього натисніть кнопку "Імпортувати файл..." та трохи зачекайте.
</p>

<form action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data">
	
	<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
	
	<input name="testsFile" type="file" class="form-control" required>
	
	<button type="submit" class="btn btn-flat btn-primary btn-block" name="send">Імпортувати файл тестів до задачі</button>
	
</form>

<?php
	
	/* Footer */
	SPM_footer();
	
?>