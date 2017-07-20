<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
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


<div class="row" style="margin-top: 20px;">
	
	<div class="col-md-4">
		
		
		
	</div>
	
	<div class="col-md-4">
		
		
		
	</div>
	
	<div class="col-md-4">
		
		
		
	</div>
	
</div>
<?php SPM_footer(); ?>