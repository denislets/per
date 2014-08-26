<?php
class perInfo extends perUtils {
	public function getInfo() {
		global $html;
		$res = array();

		$wb = new cUrlClass;
		$raw_page = $wb->goToPage('nest/bird');

		$html->load($raw_page);

		$res['currentExp'] = $html->find('span[class=oqj-12797]', 0)->innertext;
		$res['maxExp'] = $html->find('span[class=dnb-12797]', 0)->innertext;
		$res['coins'] = $html->find('b[class=e36-12797]', 0)->innertext;
		$res['cones'] = $html->find('b[class=tuk-12797]', 0)->innertext;
		$res['peacocks'] = $html->find('b[class=y0n-12797]', 0)->innertext;
		$res['cookies'] = $html->find('b[class=kqx-12797]', 0)->innertext;

		$wb->close();

		return $res;
	}
}
