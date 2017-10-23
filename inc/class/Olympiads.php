<?php
	
	class Olympiads {

		public function getPenaltyTime($submissionId) {

			$query_str = "
				SELECT
					`time`
				FROM
					`spm_submissions`
				WHERE
					`submissionId` = '" . $submissionId . "'
				LIMIT
					1
				;
			";

		}

	}

?>