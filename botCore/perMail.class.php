<?php
class perMail extends perUtils {
	public function getBattlesList() {
		global $html;
		$res = array();

		$wb = new cUrlClass;
		$raw_page = $wb->goToPage('lightning/mail/battle');

		$html->load($raw_page);

		$ajaxUpdate = '';
		if (strstr($raw_page, "ajaxUpdate")) {
			$temp = substr($raw_page, strpos($raw_page, "ajaxUpdate':['") + 14);
			$temp = substr($temp, 0, strpos($temp, "']"));
			$ajaxUpdate = $temp;
		}

		$res['items'] = $html->find('div[class=mail-list] > div[class=items]', 0)->innertext;

/*
		$page=1;
		if ($ajaxUpdate != '') {
			while (true) {
				$page++;
				$wb = new cUrlClass;
				$raw_page = $wb->goToPage("lightning/mail/battle/ajax/{$ajaxUpdate}/Mail_page/{$page}?ajax={$ajaxUpdate}");
				$html->load($raw_page);
				array_push($res['items'], $html->find('div[class=mail-list] > div[class=items]', 0)->innertext);
				if (!$html->find('a[class=nm-more]', 0)->innertext) {
					break;
				}
			}
		}
*/

		return $res;
	}
}
