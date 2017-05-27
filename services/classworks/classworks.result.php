<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	/////////////////////////////////////
	
	if (isset($_SESSION["classwork"]) && $_SESSION["classwork"] > 0)
		$_GET["id"] = $_SESSION["classwork"];
	elseif (isset($_GET["id"]) && (int)$_GET["id"] > 0)
		$_GET["id"] = (int)$_GET["id"];
	else
		die('<strong>Идентификатор урока указан не верно!</strong>');
	
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			*
		FROM
			`spm_classworks`
		WHERE
			`id` = '" . $_GET["id"] . "'
		LIMIT
			1
		;
	";
	
	if (!$query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	if ($query->num_rows == 0)
		die('<strong>Идентификатор урока указан не верно!</strong>');
	
	
	/////////////////////////////////////
	
	$classwork = $query->fetch_assoc();
	$query->free();
	
	/////////////////////////////////////
	
	$query_str = "
		SELECT
			`id`,
			`username`,
			`firstname`,
			`secondname`,
			`thirdname`,
			sum(`b`)
		FROM
			`spm_submissions`
		INNER JOIN
			`spm_users`
		ON
			`spm_submissions`.`userId` = `spm_users`.`id`
		
		WHERE
			`classworkId` = '" . $_GET["id"] . "'
		
		GROUP BY
			`userId`
		ORDER BY
			sum(`b`) DESC
		;
	";
	
	if (!$users_query = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	/////////////////////////////////////
	
	SPM_header("Урок #1", "Статистика урока");
?>

<div class="box box-default box-solid" style="border-radius: 0;">
	<div class="box-header with-border" style="border-radius: 0;">
		<h3 class="box-title">Информация об уроке</h3>
	</div>
	<div class="box-body" style="padding: 0;">
		
		<div class="row">
			<div class="col-md-6">
				
				<dl class="dl-horizontal" style="margin: 20px 20px 20px 0px;">
					<dt>Название урока</dt>
					<dd><?=$classwork['name']?></dd>
					
					<dt>Описание урока</dt>
					<dd><?=$classwork['description']?></dd>
					
					<dt>Группа учащихся</dt>
					<dd>gid_<?=$classwork['studentsGroup']?></dd>
				</dl>
				
			</div>
			<div class="col-md-6">
				
				<dl class="dl-horizontal" style="margin: 20px 20px 20px 0px;">
					<dt>Время начала</dt>
					<dd><?=$classwork['startTime']?></dd>
					
					<dt>Время конца</dt>
					<dd><?=$classwork['endTime']?></dd>
					
					<dt>Учитель</dt>
					<dd><a href="index.php?service=user&id=<?=$classwork['teacherId']?>"><span class="fa fa-user"></span> id<?=$classwork['teacherId']?></a></dd>
				</dl>
				
			</div>
		</div>
		
	</div>
</div>

<div class="box box-primary box-solid" style="border-radius: 0;">
	<div class="box-header with-border" style="border-radius: 0;">
		<h3 class="box-title">Рейтинг пользователей</h3>
	</div>
	<div class="box-body" style="padding: 0;">
		
		<div class="table-responsive" style="background-color: white;">
			<table class="table table-bordered table-hover" style="margin: 0;">
				<thead>
					<th width="10%">ID</th>
					<th width="20%">Имя пользователя</th>
					<th width="50%">Полное имя</th>
					<th width="10%">Задач</th>
					<th width="10%">B</th>
				</thead>
				<tbody>
					<?php while ($user = $users_query->fetch_assoc()): ?>
					<tr>
						<td>
							<?=$user['id']?>
						</td>
						<td>
							<a href="index.php?service=user&id=<?=$user['id']?>"><?=$user['username']?></a>
						</td>
						<td>
							<a href="index.php?service=user&id=<?=$user['id']?>"><?=$user['secondname']?> <?=$user['firstname']?> <?=$user['thirdname']?></a>
						</td>
						<td>
							0 / 2
						</td>
						<td>
							<?=$user['sum(`b`)']?>
						</td>
					</tr>
					<?php endwhile; ?>
					<?php $users_query->free(); ?>
				</tbody>
			</table>
		</div>

	</div>
</div>

<?php SPM_footer(); ?>