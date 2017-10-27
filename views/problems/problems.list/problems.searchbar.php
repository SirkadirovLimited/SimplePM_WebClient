<!-- Пошук по архіву задач -->
<div class="row" style="margin-bottom: 20px;">

	<div class="col-md-12">

		<form action="" method="get">

			<input type="hidden" name="service" value="problems" />
			<input type="hidden" name="page" value="1" />

			<div class="row-fluid">

				<div class="col-md-8" style="padding: 0; margin: 0;">
					
					<input class="form-control" name="query" placeholder="Номер чи ім'я завдання" value="<?=$_GET['query']?>">
					
				</div>

				<div class="col-md-4" style="padding: 0; margin: 0;">

					<!--CATEGORY SELECTOR-->
					<select class="form-control" name="catId" required>

						<option value="*" selected>Усі теми</option>
						<?php

							$query_str = "
								SELECT
									`id`,
									`sort`,
									`name`
								FROM
									`spm_problems_categories`
								ORDER BY
									`sort`
								;
							";

							if(!$db_result_cat = $db->query($query_str))
								die(header('location: index.php?service=error&err=db_error'));
							
							while ($problem_cat = $db_result_cat->fetch_assoc()):

						?>
						<option value="<?=$problem_cat["id"]?>"><?=$problem_cat["name"]?></option>
						<?php endwhile; ?>

					</select>

				</div>

				<div class="col-md-12" style="padding: 0; margin: 0;">

					<button type="submit" class="btn btn-primary btn-block btn-flat">Відобразити</button>

				</div>

			</div>

		</form>

	</div>

</div>