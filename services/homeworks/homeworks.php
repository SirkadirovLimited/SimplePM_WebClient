<?php
	
	deniedOrAllowed(
		PERMISSION::student |
		PERMISSION::teacher
	);
	
	SPM_header("Домашні завдання", "Список домашніх завдань");
	
	if (permission_check($_SESSION['permissions'], PERMISSION::teacher)):
	
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
		$groups_arr[] = $tmp;
	
	unset($tmp);
	
?>

<style>
	
	#groupsTabControl .tab-content {
		color : white;
		background-color: #428bca;
		padding : 5px 15px;
	}
	
	#groupsTabControl .nav-pills > li > a {
		border-radius: 0;
	}
	.scroller {
		margin: 0;
		padding: 0;
	}
	
	@media all and (max-width: 480px) {
		.scroller {
			overflow-x: scroll;
		}
	}
	
</style>

<div id="groupsTabControl">	
	<ul class="nav nav-pills">
		
		<?php foreach ($groups_arr as $group): ?>
		<li><a href="#group<?=$group['id']?>" data-toggle="tab"><?=$group['name']?></a></li>
		<?php endforeach; ?>
		
		<li><a href="index.php?service=homework.edit&id=0">Створити завдання</a></li>
		
	</ul>
	
	<div class="tab-content clearfix">
		
		<?php foreach ($groups_arr as $group): ?>
		
		<?php
			
			$query_str = "
				SELECT
					`id`,
					`name`,
					`subject`,
					`creationDate`,
					`endingDate`,
					`type`
				FROM
					`spm_homeworks`
				WHERE
					`teacherId` = '" . $_SESSION['uid'] . "'
				;
			";
			
			$query = $db->query($query_str)
				or die('Database error occured!');
			
		?>
		
		<div class="tab-pane scroller" id="group<?=$group['id']?>">
			
			<?php if ($query->num_rows > 0): ?>
			<table class="table table-bordered table-hover datatable responsive no-wrap" style="background-color: white;">
				
				<thead>
					<th>ID</th>
					<th>Назва</th>
					<th>Тема</th>
					<th>Дата створення</th>
					<th>Дата закінчення</th>
					<th>Тип завдання</th>
					<th>Управління</th>
				</thead>
				
				<tbody>
					
					<?php while ($homework = $query->fetch_assoc()): ?>
					<tr>
						
						<td>
							<?=$homework['id']?>
						</td>
						<td>
							<?=$homework['name']?>
						</td>
						<td>
							<?=$homework['subject']?>
						</td>
						<td>
							<?=$homework['creationDate']?>
						</td>
						<td>
							<?=$homework['endingDate']?>
						</td>
						<td>
							<?=$homework['type']?>
						</td>
						<td>
							
							<form method="post" style="margin: 0;">
								<a
									href="index.php?service=homeworks&edit=<?=$homework['id']?>"
									class="btn btn-flat btn-xs btn-warning"
								>EDIT</a>
								<button
									class="btn btn-flat btn-xs btn-danger"
									name="del"
									value="<?=$homework['id']?>"
								>DEL</button>
							</form>
							
						</td>
						
					</tr>
					<?php endwhile; ?>
					
				</tbody>
				
			</table>
			<?php else: ?>
			<h4>Домашніх завдань для обраної групи користувачів не знайдено!</h4>
			<?php endif; ?>
				
		</div>
		<?php endforeach; ?>
		
	</div>
	
</div>

<script>
	$(document).ready(function () {
		$('#groupsTabControl a:first').tab('show');
	});
</script>

<?php elseif (permission_check($_SESSION['permissions'], PERMISSION::student)): ?>



<?php endif; ?>
<link rel="stylesheet" href="<?=_S_TPL_?>plugins/datatables/dataTables.bootstrap.css">
<script src="<?=_S_TPL_?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=_S_TPL_?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
	
	$(document).ready(function() {
		
		//Инициализируем плагин JQuery Data Tables
		var dataTable = $('.dataTable').DataTable({
			"responsive": true,
			"searching": false,
			"lengthChange": false,
			"language": {
				"zeroRecords": "Нічого не знайдено!",
				"info": "Сторінка _PAGE_ з _PAGES_",
				"infoEmpty": "Нічого не знайдено!",
				"infoFiltered": "(знайдено з _MAX_ записів)"
			}
		});

		//Указываем метод и колонку сортировки по-умолчанию
		//dataTable.columns( '#b-heading' ).order( 'desc' ).draw();
		
	});

</script>
<?php
	
	SPM_footer();
	
?>
