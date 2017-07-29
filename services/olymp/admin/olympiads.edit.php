<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
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
	
	<div class="box box-primary box-solid" style="border-radius: 0;">
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
							<td><input type="text" class="form-control" name="name" value="<?=@$cwork_info['name']?>" reqired></td>
						</tr>
						<tr>
							<td>Опис змагання</td>
							<td><textarea class="form-control" style="resize: none;" name="description" rows="5" reqired><?=@$cwork_info['description']?></textarea></td>
						</tr>
						<tr>
							<td>Дата та час початку</td>
							<td>
								<input type="text" class="form-control" name="startTime" placeholder="РРРР-ММ-ДД ГГ:ХХ:СС" value="<?=@$cwork_info['startTime']?>" reqired>
							</td>
						</tr>
						<tr>
							<td>Дата та час кінця</td>
							<td>
								<input type="text" class="form-control" name="endTime" placeholder="РРРР-ММ-ДД ГГ:ХХ:СС" value="<?=@$cwork_info['endTime']?>" reqired>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>
	
<?php if ($_GET['id'] == 0): ?>
	<div class="box box-danger box-solid" style="border-radius: 0;">
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
							<td><textarea class="form-control" style="resize: none;" rows="5" name="problems-by-id"></textarea></td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="callout callout-warning" style="border-radius: 0;  margin: 0;">
				<p><strong>Зауваження:</strong> Після збереження змагання внести зміни у список задач буде не можливо!</p>
				<p><strong>Увага:</strong> У олімпіадному режимі всі заборонені для рішення задачі, що входять до списку, стають доступними для учасників олімпіади!</p>
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