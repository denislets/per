<?php
class perBattle extends perUtils {
	public function getBattleByURL($link = 'world/battle/log/id/411906/r/09996566e4c0c56bf82216f52857fb61') {
		global $html;

		$player1 = array();
		$player2 = array();

		$wb = new cUrlClass;
		$raw_page = $wb->goToPage($link);

		$html->load($raw_page);
		$stats_html = new simple_html_dom;

		$info1 = strip_tags($html->find('div[class=battle-pl] > a[class=ch-link]', 0)->innertext);
		$info2 = strip_tags($html->find('div[class=battle-pl] > a[class=ch-link]', 1)->innertext);
		$winner = strip_tags($html->find('div[class=battle-result] > a[class=ch-link]', 0)->innertext);

		$player1['name'] = substr($info1, 0, -1);
		$player1['level'] = intval(substr($info1, -1));

		if ($info2 == '') {
			$player2['name'] = 'Железный птиц';
			$player2['level'] = $player1['level'];
		}
		else {
			$player2['name'] = substr($info2, 0, -1);
			$player2['level'] = intval(substr($info2, -1));
		}

		$stats1 = $html->find('div[class=stats]', 0)->innertext;
		$stats2 = $html->find('div[class=stats]', 1)->innertext;

		$stats_html->load($stats1);
		$player1['strength']  = intval(trim($stats_html->find('div[class=pl-stat-count]', 0)->innertext));
		$player1['accuracy']  = intval(trim($stats_html->find('div[class=pl-stat-count]', 1)->innertext));
		$player1['defense']   = intval(trim($stats_html->find('div[class=pl-stat-count]', 2)->innertext));
		$player1['agility']   = intval(trim($stats_html->find('div[class=pl-stat-count]', 3)->innertext));
		$player1['intuition'] = intval(trim($stats_html->find('div[class=pl-stat-count]', 4)->innertext));

		$stats_html->load($stats2);
		$player2['strength']  = intval(trim($stats_html->find('div[class=pl-stat-count]', 0)->innertext));
		$player2['accuracy']  = intval(trim($stats_html->find('div[class=pl-stat-count]', 1)->innertext));
		$player2['defense']   = intval(trim($stats_html->find('div[class=pl-stat-count]', 2)->innertext));
		$player2['agility']   = intval(trim($stats_html->find('div[class=pl-stat-count]', 3)->innertext));
		$player2['intuition'] = intval(trim($stats_html->find('div[class=pl-stat-count]', 4)->innertext));

		$num = 0;

		for ($num = 0; $num < 8; $num++) {
			$round_text = $html->find('div[class=log-round]', $num);
			$chances_text = substr($round_text, strpos($round_text, 'showQ(\'Шансы\',\'')+20);
			$chances_text = substr($chances_text, 0, strpos($chances_text, '\',\'none\''));
			$chances_text = str_replace('<br />', '|', $chances_text);
			$chances_text = str_replace('<hr />', '|', $chances_text);
			$chances_text = strip_tags($chances_text);
			$chances_text = str_replace('Шанс уворота: ', '', $chances_text);
			$chances_text = str_replace('Шанс критического удара: ', '', $chances_text);
			$chances_text = str_replace('Заблокированный урон: ', '', $chances_text);
			$chances_text = str_replace('%', '', $chances_text);
			$chances = explode('|', $chances_text);
			//$round[$num+1]['chances_text'] = $chances_text;
			$round[$num+1]['chances_1']['evade_percent'] = $chances[1];
			$round[$num+1]['chances_1']['crit_percent'] = $chances[2];
			$round[$num+1]['chances_1']['block_percent'] = $chances[3];
			$round[$num+1]['chances_2']['evade_percent'] = $chances[5];
			$round[$num+1]['chances_2']['crit_percent'] = $chances[6];
			$round[$num+1]['chances_2']['block_percent'] = $chances[7];

			for ($p = 1; $p <= 2; $p++) {
				$round_text = $html->find("div[class=log-round] > div[class=log-f{$p}]", $num)->innertext;
				$round_text = str_replace("<b>{$player1['name']}</b>", "", $round_text);
				$round_text = str_replace("<b>{$player2['name']}</b>", "", $round_text);
				$round_text = str_replace("<div style=\"float: right;\">", "|", $round_text);
				$round_text = str_replace("<div class=\"log-r", "|<div class=\"log-r", $round_text);
				$round_text = str_replace(chr(13), "", $round_text);
				$round_text = str_replace(chr(10), "", $round_text);
				$round_text = trim(strip_tags($round_text));
				$round_text = str_replace("- ", "-", $round_text);
				while ($round_text != str_replace("  ", " ", $round_text)) $round_text = str_replace("  ", " ", $round_text);
				$action = explode('|', $round_text);
				$attacker_text = trim($action[0]); // нанес 37 урона|пытался атаковать|нанес крит на 70 урона
				$defender_text = trim($action[1]); // заблокировал 4|увернулся
				$round[$num+1]['f'.$p]['hit_damage'] = 0;
				$round[$num+1]['f'.$p]['blocked'] = 0;
				$round[$num+1]['f'.$p]['is_evaded'] = 0;
				$round[$num+1]['f'.$p]['is_crit'] = 0;
				if (strstr($attacker_text, "пытал")) {
					$round[$num+1]['f'.$p]['is_evaded'] = 1;
				}
				if (strstr($attacker_text, "крит")) {
					$round[$num+1]['f'.$p]['is_crit'] = 1;
				}
				$attacker_arr = explode(' ', $attacker_text);
				$defender_arr = explode(' ', $defender_text);
				if (sizeof($attacker_arr) == 3) {
					$round[$num+1]['f'.$p]['hit_damage'] = $attacker_arr[1];
				}
				else
				if (sizeof($attacker_arr) == 5) {
					$round[$num+1]['f'.$p]['hit_damage'] = intval($attacker_arr[3]);
				}
				if (sizeof($defender_arr) == 2) {
					$round[$num+1]['f'.$p]['blocked'] = intval($defender_arr[1]);
				}
				$round[$num+1]['f'.$p]['total_damage'] = intval(trim($action[2]));
				//$round[$num+1][$p] = $round_text;
			}
		}

		$info = array(
			'player1' => $player1,
			'player2' => $player2,
			'winner' => $winner,
			'log' => $round,
		);

		return $info;
	}
}
