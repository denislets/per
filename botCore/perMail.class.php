<?php
class perMail extends perUtils {
	public function getBattlesList() {
		global $html;
		$res = array();

		$wb = new cUrlClass;
		$raw_page = $wb->goToPage('lightning/mail/battle');
		$wb->close();

		$html->load($raw_page);

		$items = '';
		$items.= $html->find('div[class=mail-list] > div[class=items]', 0)->innertext;
		$page = 1;
		$next = (bool)$html->find('a[class=nm-more]', 0)->innertext;

		if (BOT_DEBUG) {
			file_put_contents('mail_page_'.$page.'.html', $raw_page);
		}

		while ($next) {
			$page++;
			$wb = new cUrlClass;

			usleep(1000000); // sleep for 1 sec.

			$raw_page = $wb->goToPage("lightning/mail/battle/Mail_page/{$page}");
			$wb->close();

			if (BOT_DEBUG) {
				file_put_contents('mail_page_'.$page.'.html', $raw_page);
			}

			$html->load($raw_page);
			$items.= $html->find('div[class=mail-list] > div[class=items]', 0)->innertext;

			$next = (bool)@$html->find('a[class=nm-more]', 0)->innertext;
		}

		$links = pc_link_extractor($items);
		$battles = array();
		foreach ($links as $link) {
			if (strstr($link[0], 'world/battle/log')) {
				$battle_url = substr($link[0], 1);
				$battles[] = $battle_url;
			}
		}
		
		$res['items'] = $battles;

		return $battles;
	}
}
