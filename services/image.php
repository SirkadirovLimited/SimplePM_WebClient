<?php
	if (isset($_GET['uid']) && ((int)$_GET['uid'] >= 0)) {
		
		/*
		 * ФУНКЦИОНАЛ АВАТАРОВ ПОЛЬЗОВАТЕЛЕЙ
		 */
		
		$img_id = (int)$_GET['uid'];
		
		$query_str = "
			SELECT
				`avatar`
			FROM
				`spm_users`
			WHERE
				`id` = '" . $img_id . "'
			LIMIT
				1
			;
		";
		
		if (!$db_result = $db->query($query_str))
			die;
		
		header("Content-type: image/jpeg");
		header("Cache-control: public");
		header("Cache-control: max-age=600"); //Save cache for ten minutes
		
		if ($db_result->num_rows === 1) {
			$image = $db_result->fetch_assoc();
			
			if ($image['avatar'] != null)
				print($image['avatar']);
			else
				print(file_get_contents(_S_MEDIA_IMG_ . "no-avatar.png"));
		}
		else
			print(file_get_contents(_S_MEDIA_IMG_ . "no-avatar.png"));
		
	}
	/*elseif (isset($_GET['id']) && ((int)$_GET['id'] > 0))
	{
		
		
		$img_id = (int)$_GET['id'];
		
		$query_str = "
			SELECT
				`mime`,
				`content`
			FROM
				`spm_images`
			WHERE
				`id` = '" . $img_id . "'
			LIMIT
				1
			;
		";
		
		if(!$db_result = $db->query($query_str))
			die;
		
		if ($db_result->num_rows === 1) {
			
			$image = $db_result->fetch_assoc();
			
			header("Content-type: " . $image['mime']);
			header("Cache-control: public");
			header("Cache-control: max-age=86400"); //Save cache for one day
			
			print($image['content']);
			
		}
		
	}*/
	else
	{
		header("Content-type: image/png");
		header("Cache-control: public");
		header("Cache-control: max-age=99999999");
		
		print(file_get_contents(_S_MEDIA_IMG_ . "no-img.png"));
	}
	
	exit;
?>