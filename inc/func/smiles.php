<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	function str_replace2($find, $replacement, $subject, $limit = 0){
		if ($limit == 0)
			return str_replace($find, $replacement, $subject);
		
		$ptn = '/' . preg_quote($find,'/') . '/';
		
		return preg_replace($ptn, $replacement, $subject, $limit);
	}
	
	function spm_runSmilesRun($text){
		
		/* SMILES START */
		$smiles[1]["key"] = "XD";
		$smiles[1]["value"] = "&#128518;";
		
		$smiles[2]["key"] = ":DEVIL:";
		$smiles[2]["value"] = "&#128520;";
		
		$smiles[3]["key"] = "BD";
		$smiles[3]["value"] = "&#128526;";
		
		$smiles[4]["key"] = ":|";
		$smiles[4]["value"] = "&#128528;";
		
		$smiles[5]["key"] = ":WORRIED:";
		$smiles[5]["value"] = "&#128543;";
		
		$smiles[6]["key"] = ":A:";
		$smiles[6]["value"] = "&#128544;";
		
		$smiles[7]["key"] = ":)";
		$smiles[7]["value"] = "&#128578;";
		
		$smiles[8]["key"] = ":(";
		$smiles[8]["value"] = "&#128577;";
		/* SMILES END */
		
		/* SUBSTRING START */
		foreach ($smiles as $smile)
			$text = str_replace2($smile["key"], $smile["value"], $text, 15);
		/* SUBSTRING END */
		
		return $text;
	}
?>