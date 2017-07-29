<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
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
	
	if (!$query_private = $db->query($query_str))
		die(header('location: index.php?service=error&err=db_error'));
	
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
					<select class="form-control" required>
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
					<select class="form-control" required>
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