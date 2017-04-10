<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	SPM_header("Проекты","Все проекты");
?>
<div align="right" style="margin-bottom: 10px;">
	<a href="index.php?service=projects.add" class="btn btn-success">Добавить проект</a>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">SimplePM</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<img src="index.php?service=image&uid=3" class="img-responsive img-rounded" />
				<div class="list-group" style="margin-top: 10px; margin-bottom: 0;">
					<a class="list-group-item"><b>Дата релиза:</b> TBD</a>
					<a class="list-group-item"><b>Категория:</b> Веб-проекты</a>
				</div>
				<div class="list-group" style="margin-top: 10px; margin-bottom: 0;">
					<a href="" class="list-group-item">Официальный сайт</a>
					<a href="" class="list-group-item">GitHub</a>
					<a href="" class="list-group-item">BitBucket</a>
					<a href="" class="list-group-item">Google Play</a>
					<a href="" class="list-group-item">App Store</a>
				</div>
			</div>
			<div class="col-md-8">
				<h4><b>Описание проекта</b></h4>
				<p style="font-size: 13pt;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris a erat et elit maximus imperdiet. 
				Nullam maximus posuere nunc. Vivamus id nunc at ligula egestas dignissim nec a eros. Pellentesque a mattis turpis, 
				non lobortis est. Aliquam et bibendum est. Suspendisse a turpis hendrerit, malesuada odio et, posuere sem. In hac 
				habitasse platea dictumst. Etiam non justo imperdiet, cursus nunc nec, luctus urna. Curabitur dignissim ipsum 
				ornare commodo consectetur. Integer sed eros risus.
				Proin dignissim mattis ante, at ullamcorper turpis mollis nec. Duis aliquam ante non dictum aliquet. Vivamus tincidunt 
				imperdiet velit, et semper eros feugiat suscipit. Mauris pharetra, ligula ut sagittis interdum, odio dui imperdiet urna, 
				vitae lacinia leo libero eget sapien. Duis ut orci nisi. Aenean eleifend mi mauris, ac dignissim ipsum aliquam sed. 
				Aenean varius luctus ipsum, sed fringilla nisl. Curabitur sagittis pharetra arcu. Aenean sit amet orci faucibus, 
				faucibus nisi vel, convallis diam.</p>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<b>Автор:</b> <a href="">Кадиров Юрий</a><br/>
		<b>Дата публикации:</b> 2016-12-18
	</div>
</div>
<?php
	SPM_footer();
?>