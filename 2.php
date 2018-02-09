<?php
	function get_shingle($text, $n=3) {
		
		$shingles = array();
		$text = clean_text($text);
		
		$elements = explode(" ",$text);
		
		for ($i = 0; $i < (count($elements) -$n + 1); $i++) {
			
			$shingle = '';
			
			for ($j=0;$j<$n;$j++)
				$shingle .= mb_strtolower(trim($elements[$i+$j]), 'UTF-8')." ";
			
			if (strlen(trim($shingle)))
				$shingles[$i] = trim($shingle, ' -');
			
		}
		
		return $shingles;  
		
	}
	
	function clean_text($text) {
		
		$new_text = preg_replace("[\,|\.|\'|\"|\\|\/]","",$text);
	    $new_text = preg_replace("[\n|\t]"," ",$new_text);
		$new_text = preg_replace('/(\s\s+)/', ' ', trim($new_text));
		
		return $new_text;
		
	}
	
	function check_it($first, $second) {
		
		if (strlen($first) > 200000 || strlen($second) > 200000)
		    return 0;
		
		for ($i = 1; $i < 5; $i++) {
		    
			$first_shingles = array_unique(get_shingle($first,$i));
		    $second_shingles = array_unique(get_shingle($second,$i));
		
			if(count($first_shingles) < $i-1 || count($second_shingles) < $i-1)
				continue;
		    
		    $intersect = array_intersect($first_shingles, $second_shingles);
		    
		    $merge = array_unique(array_merge($first_shingles, $second_shingles));
		    
		   	$diff = (count($intersect) / count($merge)) / 0.01;
		    
			print("Количество слов - $i. Процент схожести - ".round($diff, 2)."%<br />");
			
		}
	}
	
	//check_it(strip_tags($_POST['text1']), strip_tags($_POST['text2']));
	
?>