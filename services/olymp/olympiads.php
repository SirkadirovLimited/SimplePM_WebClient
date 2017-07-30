<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::student);
	
	if (isset($_POST['join']))
	{
		
		// Security
		$_POST['join'] = (int)mysqli_real_escape_string($db, strip_tags(trim($_POST['join'])));
		
		// MySQL select query string
		$query_str = "
			SELECT
				count(`id`) AS count,
				`type`,
				`teacherId`
			FROM
				`spm_olympiads`
			WHERE
				`id` = '" . $_POST['join'] . "'
			AND
				`endTime` > now()
			LIMIT
				1
			;
		";
		
		// MySQL select query 
		if (!$query = $db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		// Getting response data
		$query_data = $query->fetch_assoc();
		
		// Let's free temporary res
		$query->free();
		
		// Security checkers
		if ($query_data['type'] == 'Private' && $query_data['teacherId'] != $_SESSION['teacherId'])
			die(header('location: index.php?service=error&err=403'));
		
		if ((int)($query_data['count']) <= 0)
			die(header('location: index.php?service=error&err=404'));
		
		// Set associated session variables
		$_SESSION["olymp"] = $_POST['join'];
		
		// MySQL update query string
		$query_str = "
			UPDATE
				`spm_users`
			SET
				`associatedOlymp` = '" . $_POST['join'] . "'
			WHERE
				`id` = '" . $_SESSION["uid"] . "'
			LIMIT
				1
			;
		";
		
		// MySQL update query 
		if (!$db->query($query_str))
			die(header('location: index.php?service=error&err=db_error'));
		
		// Redirect user to the main service
		//exit(header('location: index.php'));
		
	}
	
	// MySQL select private olympiads query string
	$query_str = "
		SELECT
			`id`,
			`name`
		FROM
			`spm_olympiads`
		WHERE
			`type` = 'Private'
		AND
			`teacherId` = '" . $_SESSION["teacherId"] . "'
		AND
			`endTime` > now()
		;
	";
	
	// MySQL select query
	if (!$query_private = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	// MySQL select query string
	$query_str = "
		SELECT
			`id`,
			`name`
		FROM
			`spm_olympiads`
		WHERE
			`type` = 'Public'
		AND
			`endTime` > now()
		;
	";
	
	// MySQL select public olympiads query
	if (!$query_public = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
	SPM_header("Олімпіадний режим", "Головна сторінка");
?>
<img
	src="<?=_S_MEDIA_IMG_?>headers/olymp.jpg"
	width="100%"
>
<div class="bg-primary" align="center">
	
	<div class="row" style="padding: 20px 10px 20px 10px;">
		
		<div class="col-md-2"></div>
		
		<div class="col-md-4">
			
			<form method="post">
				
				<h4>Приєднатися до закритого змагання</h4>
				
				<div class="input-group">
					<select class="form-control" name="join" required>
						<option>Оберіть змагання</option>
						<?php if ($query_private->num_rows > 0): ?>
							
							<?php while ($olymp = $query_private->fetch_assoc()): ?>
							<option value="<?=$olymp['id']?>"><?=$olymp['name']?></option>
							<?php endwhile; ?>
							
						<?php endif; ?>
					</select>
					<div class="input-group-btn">
						<button type="submit" class="btn btn-default btn-flat">
							&nbsp;<i class="glyphicon glyphicon-chevron-right"></i>&nbsp;
						</button>
					</div>
				</div>
				
			</form>
			
		</div>
		
		<div class="col-md-4">
			
			<form method="post">
				
				<h4>Приєднатися до публічного змагання</h4>
				
				<div class="input-group">
					<select class="form-control" name="join" required>
						<option>Оберіть змагання</option>
						<?php if ($query_public->num_rows > 0): ?>
							
							<?php while ($olymp = $query_public->fetch_assoc()): ?>
							<option value="<?=$olymp['id']?>"><?=$olymp['name']?></option>
							<?php endwhile; ?>
							
						<?php endif; ?>
					</select>
					<div class="input-group-btn">
						<button type="submit" class="btn btn-default btn-flat">
							&nbsp;<i class="glyphicon glyphicon-chevron-right"></i>&nbsp;
						</button>
					</div>
				</div>
				
			</form>
			
		</div>
		
		<div class="col-md-2"></div>
		
	</div>
	
</div>

<div align="center" style="padding: 20px 10px 20px 10px; background-color: #f39c12; color: white;">
	
	<h4>Підтримувані браузери</h4>
	
	<span style="font-size: 25pt;">
		<i class="fa fa-opera"></i>
		<i class="fa fa-chrome"></i>
		<i class="fa fa-firefox"></i>
		<i class="fa fa-safari"></i>
	</span>
	
</div>

<?php SPM_footer(); ?>