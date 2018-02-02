<?php
	
	/*
	 * Функция проверяет наличие указанного
	 * пользователя в списке ТОП учащихся.
	 * Ограничение рейтинга ТОП указывать
	 * не обязательно, значение по-умолчанию
	 * равняется ТОП-10.
	 **/
	function _spm_user_top_check($username, $topLimit = 10) {
		
		global $db;
		
		$query_str = "
			SELECT
				count(`id`)
			FROM
				`spm_users`
			WHERE
				`username` = '" . $username . "'
			ORDER BY
				`rating` DESC,
				`b` DESC,
				`birthday` ASC,
				`username` ASC,
				`teacherId` ASC
			LIMIT
				0, " . $topLimit . "
			;
		";
		
		if (!$query = $db->query($query_str))
			return false;
		
		return (bool)((int)($query->fetch_array()[0]));
		
	}
	
?>
