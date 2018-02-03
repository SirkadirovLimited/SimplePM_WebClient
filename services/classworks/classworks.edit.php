<?php
	deniedOrAllowed(PERMISSION::teacher);
	
	/////////////////////////////////////
	
	isset($_GET['id']) && (int)$_GET['id'] > 0 or $_GET['id'] = 0;
	$_GET['id'] = (int)$_GET['id'];
	
	/////////////////////////////////////
	
	if ($_GET['id'] > 0)
	{
		
		$query_str = "
			SELECT
				*
			FROM
				`spm_classworks`
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
		
	}
	else
    {
		$cwork_info['id'] = "AUTO INCREMENT";
	}
	
	/////////////////////////////////////
	
	if (isset($_POST["sender"]))
		include(_S_SERV_INC_ . "classworks/classworks.edit.php");
	
	SPM_header("Підсистема уроків", "Редагування уроку");
?>

<form method="post">
	
	<div class="box box-primary box-solid" style="border-radius: 0;">
		<div class="box-header with-border" style="border-radius: 0;">
			<h3 class="box-title">Базова конфігурація</h3>
		</div>
		<div class="box-body" style="padding: 0; margin: 0;">
			
			<div class="table-responsive" style="border-radius: 0; margin: 0;">
				
                <table class="table table-bordered" style="margin: 0;">
					
                    <th width="30%">Параметр</th>
                    <th width="70%">Значення</th>
					
                    <tr>
                        <td>ID</td>
                        <td><input type="text" class="form-control" value="<?=$cwork_info['id']?>" disabled></td>
                    </tr>
					
                    <tr>
                        <td>Назва уроку</td>
                        <td><input type="text" class="form-control" name="name" value="<?=@$cwork_info['name']?>" required></td>
                    </tr>
					
                    <tr>
                        <td>Опис уроку</td>
                        <td><textarea class="form-control" style="resize: none;" name="description" rows="5" required><?=@$cwork_info['description']?></textarea></td>
                    </tr>
					
                    <tr>
                        <td>Дата та час початку</td>
                        <td>
                            <input type="text" class="form-control" name="startTime" placeholder="РРРР-ММ-ДД ГГ:ХХ:СС" value="<?=@$cwork_info['startTime']?>" required>
                        </td>
                    </tr>
					
                    <tr>
                        <td>Дата та час закінчення</td>
                        <td>
                            <input type="text" class="form-control" name="endTime" placeholder="РРРР-ММ-ДД ГГ:ХХ:СС" value="<?=@$cwork_info['endTime']?>" required>
                        </td>
                    </tr>
					
                    <tr>
                        <td>
                            Учнівська група
                        </td>
                        <td>
                            <select name="studentsGroup" class="form-control" required>
								<?php if ($_GET['id'] > 0): ?>
								<option value="<?=$cwork_info['studentsGroup']?>" selected><?=spm_getUserGroupByID($cwork_info['studentsGroup'])?> (вибрана)</option>
								<?php endif; ?>
								
								<?php
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
								?>
								
								<?php while ($group = $query->fetch_assoc()): ?>
								<option value="<?=$group['id']?>"><?=$group['name']?></option>
								<?php endwhile; ?>
							
							</select>
						</td>
					</tr>
					
					<tr>
                        <td>Система оцінювання</td>
                        <td>
                            <input type="number" class="form-control" name="ratingSystem" min="0" max="255" value="<?=isset($cwork_info['ratingSystem']) ? $cwork_info['ratingSystem'] : 12 ?>" required>
                        </td>
                    </tr>
					
				</table>
			</div>
			
		</div>
	</div>
	
	<div class="box box-primary box-solid" style="border-radius: 0;">
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
							<td>Задачі за номерами<br/>(через кому)</td>
							<td><textarea class="form-control" style="resize: none;" rows="5" name="problems-by-id"></textarea></td>
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>

	<div align="right">
		
		<a class="btn btn-danger btn-flat" href="index.php?service=classworks">Відмінити</a>
		<button type="reset" class="btn btn-warning btn-flat">Скинути</button>
		<button type="submit" class="btn btn-success btn-flat" name="sender">Зберегти</button>
		
	</div>
</form>

<?php
	SPM_footer();
?>
