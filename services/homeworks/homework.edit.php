<?php
	
	/*
	 * Різноманітні перевірки безпеки
	 **/
	
	$_GET['id'] = (isset($_GET['id']) && (int)$_GET['id'] > 0) ? (int)$_GET['id'] : "NULL";
	
	deniedOrAllowed(
		PERMISSION::teacher
	);
	
	/*
	 * Вибірка списку груп користувачів
	 **/
	
	$query_str = "
		SELECT
			`id`,
			`name`
		FROM
			`spm_users_groups`
		WHERE
			`teacherId` = '" . $_SESSION['uid'] . "'
		ORDER BY
			`id` ASC
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	while ($tmp = $query->fetch_assoc())
		$user_groups_arr[] = $tmp;
	
	unset($tmp);
	
	/*
	 * Формуємо header сторінки
	 **/
	SPM_header("Домашні завдання", "Редагування домашнього завдання");
	
?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css">
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

<div align="left" style="margin-bottom: 10px;">
	<a href="index.php?service=homeworks" class="btn btn-default btn-flat">
		<span class="glyphicon glyphicon-chevron-left"></span>
		&nbsp;Список домашніх завдань
	</a>
</div>

<form action="<?=$_SERVER['REQUEST_URI']?>" method="post">

	<table class="table table-bordered table-hover" style="background-color: #fff; margin: 0;">
		
		<thead>
			<th width="30%">Параметр</th>
			<th width="70%">Значення</th>
		</thead>
		
		<tbody>
			
			<tr>
				
				<td>
					ID
				</td>
				
				<td>
					<input
						type="number"
						name="id"
						min="0"
						value="<?=(int)$_GET['id']?>"
						required
						class="form-control"
					>
				</td>
				
			</tr>
			
			<tr>
				
				<td>
					Назва завдання
				</td>
				
				<td>
					<input
						type="text"
						name="name"
						value=""
						minlength="1"
						maxlength="255"
						required
						class="form-control"
					>
				</td>
				
			</tr>
			
			<tr>
				
				<td>
					Тема завдання
				</td>
				
				<td>
					<input
						type="text"
						name="subject"
						value=""
						minlength="1"
						maxlength="255"
						required
						class="form-control"
					>
				</td>
				
			</tr>
			
			<tr>
				
				<td>
					Дата уроку
				</td>
				
				<td>
					<input
						type="datetime"
						name="classwork"
						value=""
						placeholder="РРРР-ММ-ДД ЧЧ:ХХ:СС"
						minlength="19"
						maxlength="19"
						required
						class="form-control"
					>
				</td>
				
			</tr>
			
			<tr>
				
				<td>
					Група користувачів
				</td>
				
				<td>
					<select name="group" required class="form-control">
						
						<option value>Групу користувачів не вибрано</option>
						
						<?php foreach ($user_groups_arr as $group): ?>
						<option value="<?=$group['id']?>"><?=$group['name']?></option>
						<?php endforeach; ?>
						
					</select>
				</td>
				
			</tr>
			
			<tr>
				
				<td>
					Список завдань
				</td>
				
				<td>
					
					<div>
						<input
							type="text"
							data-role="tagsinput"
							placeholder="Додати через кому..."
							required
							class="form-control"
						>
					</div>

				</td>
				
			</tr>
			
		</tbody>
		
	</table>

	<div align="right" style="margin-top: 10px;">
		<button type="reset" class="btn btn-warning btn-flat"><i class="fa fa-ban"></i> Відмінити зміни</button>
		<button type="submit" class="btn btn-success btn-flat"><i class="fa fa-floppy-o"></i> Зберегти зміни</button>
	</div>

</form>

<?php
	
	/* Формуємо footer сторінки */
	SPM_footer();
	
?>
