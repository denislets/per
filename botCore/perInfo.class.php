<?php
class perInfo extends perUtils {
	public function getInfo() {
		global $html;
		$res = array();

		$wb = new cUrlClass;
		$raw_page = $wb->goToPage('nest/bird');

		$html->load($raw_page);

		$res['currentExp'] = $html->find('span[class=g-exp]', 0)->innertext;
		$res['maxExp'] = $html->find('span[class=g-exp_max]', 0)->innertext;
		$res['coins'] = $html->find('b[class=g-coins]', 0)->innertext;
		$res['cones'] = $html->find('b[class=g-cones]', 0)->innertext;
		$res['peacocks'] = $html->find('b[class=g-peacocks]', 0)->innertext;
		$res['cookies'] = $html->find('b[class=g-cookies]', 0)->innertext;

		return $res;
	}
}
