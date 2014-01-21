<?php
// все ссылки в массив
function pc_link_extractor($s) {
	$a = array();
	if (preg_match_all('/<a\s+.*?href=[\"\']?([^\"\' >]*)[\"\']?[^>]*>(.*?)<\/a>/i',
		$s,$matches,PREG_SET_ORDER)) {
			foreach($matches as $match) {
				array_push($a,array($match[1],$match[2]));
			}
		}
	return $a;
}

