<?php
	deniedOrAllowed(PERMISSION::administrator | PERMISSION::olymp);
	
	/////////////////////////////////////
	
	isset($_GET['id']) && (int)$_GET['id'] > 0 or $_GET['id'] = 0;
	$_GET['id'] = (int)$_GET['id'];
	
	/////////////////////////////////////
	
	if ($_GET['id'] > 0){
		
		$query_str = "
			SELECT
				*
			FROM
				`spm_olympiads`
			WHERE
				`id` = '" . (int)$_GET['id'] . "'
			AND
				`teacherId` = '" . $_SESSION["uid"] . "'
			LIMIT
				1
			;
		";
		
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($query->num_rows == 0)
			die(header('location: index.php?service=error&err=404'));
		
		$cwork_info = $query->fetch_assoc();
		
	} else {
		$cwork_info['id'] = "AUTO INCREMENT";
	}
	
	/////////////////////////////////////
	
	if (isset($_POST["sender"]))
		include(_S_SERV_INC_ . "olympiads/olympiads.edit.php");
	
	SPM_header("Підсистема змагань", "Редагування олімпіади");
?>

<form method="post">
	
	<div class="box box-primary" style="border-radius: 0;">
		<div class="box-header with-border" style="border-radius: 0;">
			<h3 class="box-title">Базова конфігурація</h3>
		</div>
		<div class="box-body" style="padding: 0; margin: 0;">
			
			<div class="table-responsive" style="border-radius: 0; margin: 0;">
				<table class="table table-bordered" style="margin: 0;">
					<thead>
						<th width="30%">Параметр</th>
						<th width="70%">Значення</th>
					</thead>
					<tbody>
						<tr>
							<td>ID</td>
							<td><input type="text" class="form-control" value="<?=$cwork_info['id']?>" disabled></td>
						</tr>
						<tr>
							<td>Назва змагання</td>
							<td><input type="text" class="form-control" name="name" value="<?=@$cwork_info['name']?>" required></td>
						</tr>
						<tr>
							<td>Опис змагання</td>
							<td><textarea class="form-control" style="resize: none;" name="description" rows="5" minlength="1" required><?=@$cwork_info['description']?></textarea></td>
						</tr>
						<tr>
							<td>Дата та час початку</td>
							<td>
								<input type="text" class="form-control" name="startTime" placeholder="РРРР-ММ-ДД ГГ:ХХ:СС" value="<?=@$cwork_info['startTime']?>" required>
							</td>
						</tr>
						<tr>
							<td>Дата та час завершення</td>
							<td>
								<input type="text" class="form-control" name="endTime" placeholder="РРРР-ММ-ДД ГГ:ХХ:СС" value="<?=@$cwork_info['endTime']?>" required>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>
	
	<!--div class="box box-warning" style="border-radius: 0;">
		<div class="box-header with-border" style="border-radius: 0;">
			<h3 class="box-title">Додаткові опції</h3>
		</div>
		<div class="box-body" style="padding: 0; margin: 0;">
			
			<div class="table-responsive" style="border-radius: 0; margin: 0;">
				<table class="table table-bordered" style="margin: 0;">
					<thead>
						<th width="30%">Параметр</th>
						<th width="70%">Значення</th>
					</thead>
					<tbody>
						<tr>
							<td>Параметри виходу учасників зі змагання</td>
							<td>
								<label>
									<input type="checkbox" name="allowExit" value="1" id="cbx1">
									<span style="font-weight: 400;">Дозволити вихід зі змагання</span>
									</input>
								</label>
								<br>
								<label>
									<input type="checkbox" name="onExitDeleteData" value="1" id="cbx1">
									<span style="font-weight: 400;">При виході зі змагання видалити результати користувача</span>
									</input>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>
	</div-->
	
<?php if ($_GET['id'] == 0): ?>
	<div class="box box-danger" style="border-radius: 0;">
		<div class="box-header with-border" style="border-radius: 0;">
			<h3 class="box-title">Список задач</h3>
		</div>
		<div class="box-body" style="padding: 0; margin: 0;">
			
			<div class="table-responsive" style="border-radius: 0; margin: 0;">
				<table class="table table-bordered" style="margin: 0;">
					<thead>
						<th width="30%">Параметр</th>
						<th width="70%">Значення</th>
					</thead>
					<tbody>
						<tr>
							<td>Задачі за номерами<br/>(1 рядок - 1 номер)</td>
							<td><textarea class="form-control" style="resize: vertical;" rows="5" name="problems-by-id"></textarea></td>
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>
<?php endif; ?>

	<div align="right">
		<a class="btn btn-danger btn-flat" href="index.php?service=olympiads.list">Відмінити</a>
		<button type="reset" class="btn btn-warning btn-flat">Скинути</button>
		<button type="submit" class="btn btn-success btn-flat" name="sender">Зберегти</button>
	</div>
</form>

<?php
	SPM_footer();
?>