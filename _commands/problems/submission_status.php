<?php

/*
 * Copyright (C) 2018, Yurij Kadirov.
 * All rights are reserved.
 * Licensed under Apache License 2.0 with additional restrictions.
 *
 * @Author: Yurij Kadirov
 * @Website: https://sirkadirov.com/
 * @Email: admin@sirkadirov.com
 */

global $database;

isset($_GET['id']) or die('SUBMISSION_ID_NOT_DEFINED');
$_GET['id'] = abs((int)$_GET['id']);

$query_str = "
	SELECT
	  `status`
	FROM
	  `spm_submissions`
	WHERE
	  `submissionId` = '" . $_GET['id'] . "'
	LIMIT
	  1
	;
";

$query = $database->query($query_str);

if ($query->num_rows == 0)
	die('SUBMISSION_NOT_FOUND');

header('Content-Type: text/plain');

exit($query->fetch_object()->status);