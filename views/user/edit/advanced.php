<?php
	
?>
<div class="box box-danger collapsed-box">
	<div class="box-header with-border">
		<h3 class="box-title">Управління користувачем</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
	<div class="box-body">
		
		<!-- Edit some params -->
		
		<form class="form-horizontal" method="post">
			
			<div class="form-group">
				<label for="accessEdit" class="col-sm-2 control-label">Права користувача</label>
				<div class="col-sm-10">
					<input type="number" min="0" max="65535" class="form-control" id="accessEdit" placeholder="<?=$user_info['permissions']?>" value="<?=$user_info['permissions']?>" required>
					<span class="help-block">Вкажіть суму привілеїв, що надаються користувачу.</span>
					
				</div>
			</div>
			
			<div class="form-group">
				<label for="teacherId" class="col-sm-2 control-label">Ідентифікатор куратора</label>
				<div class="col-sm-10">
					<input type="number" min="1" max="1000000000" class="form-control" id="teacherId" placeholder="<?=$user_info['teacherId']?>" value="<?=$user_info['teacherId']?>" required>
					<span class="help-block">Вкажіть ідентифікатор користувача (вчителя чи адміністратора), якому буде підпорядковуватися користувач.</span>
				</div>
			</div>
			
			<button type="submit" class="btn btn-danger btn-flat btn-block">Зберегти зміни</button>
			
		</form>
		
		<!-- /Edit some params -->
		
		<!-- Operations -->
		
		
		
		<!-- /Operations -->
		
	</div>
</div>