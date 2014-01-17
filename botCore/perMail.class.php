<?php
class perMail extends perUtils {
	public function getBattlesList() {
		global $html;
		$res = array();

		$wb = new cUrlClass;
		$raw_page = $wb->goToPage('lightning/mail/battle');

		$html->load($raw_page);

		$res['items'] = $html->find('div[class=mail-list] > div[class=items]', 0)->innertext;

		return $res;
	}
}
