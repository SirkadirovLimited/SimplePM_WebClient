<?php
	defined("__spm.user.edit__") or die('403 ACCESS DENIED');
	
	global $user_info;
	
	require_once(_S_INC_CLASS_ . "SimpleImage.php");
	
	if (!permission_check($_SESSION["permissions"], PERMISSION::administrator) && ($user_info["teacherId"] != $_SESSION["uid"]) && ($user_info["id"] != $_SESSION["uid"]))
		die(header('location: index.php?service=error&err=403'));
	
	if(!empty($_FILES['avatarFile']['name']))
	{
		
		if ($_FILES['avatarFile']['error'] == 0)
		{
			if (substr($_FILES['avatarFile']['type'], 0, 5) == 'image')
			{
				
				//IMGC
				$imgc = new SimpleImage();
				$imgc->load($_FILES['avatarFile']['tmp_name']);
				$imgc->resizeToWidth(400);
				$imgc->save($_FILES['avatarFile']['tmp_name'], IMAGETYPE_JPEG, 100);
				
				//IMAGE
				$image = file_get_contents($_FILES['avatarFile']['tmp_name']);
				$image = $db->real_escape_string($image);
				
				//MySQL QUERY
				$query_str = "
					UPDATE
						`spm_users`
					SET
						`avatar` = '" . $image . "'
					WHERE
						`id` = '" . $user_info["id"] . "'
					;
				";
				
				if(!$db->query($query_str))
					die(header('location: index.php?service=error&err=db_error'));
				
				//HEADER
				header('location: index.php?service=user.edit&id=' . $user_info["id"]);
				
			}
			else
				die(header('location: index.php?service=error&err=input'));
			
		}
		else
			die(header('location: index.php?service=error&err=input'));
		
	}
	else
		die(header('location: index.php?service=error&err=input'));
	
?>