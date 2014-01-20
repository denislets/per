<?php 
include('config.php');
include('perBot.php');

$m = new perBot;
$m->setLoginData(GAME_USER, GAME_PASS);

if(!$m->isLoged()) {
	$m->loginToper();
}

$player1 = array();
$player2 = array();

$wb = new cUrlClass;
$params = @$_GET['url'];
if ($params == '') { 
	$params = 'world/battle/log/id/397716/r/6ace9a89491df9be4530a11a0b86dfe4';
}
$raw_page = $wb->goToPage($params);

$html->load($raw_page);
$stats_html = new simple_html_dom;

$info1 = strip_tags($html->find('div[class=battle-pl] > a[class=ch-link]', 0)->innertext);
$info2 = strip_tags($html->find('div[class=battle-pl] > a[class=ch-link]', 1)->innertext);

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
	$temp=substr($round_text, strpos($round_text, 'showQ(\'Шансы\',\'')+20);
	$temp=substr($temp, 0, strpos($temp, '\',\'none\''));
	$round[$num+1]['chances'] = $temp;

	for ($p = 1; $p <= 2; $p++) {
		$round_text = $html->find("div[class=log-round] > div[class=log-f{$p}]", $num)->innertext;
		$round_text = str_replace("<b>{$player1['name']}</b>", "", $round_text);
		$round_text = str_replace("<b>{$player2['name']}</b>", "", $round_text);
		$round_text = str_replace("<div style=\"float: right;\">", "|", $round_text);
		$round_text = str_replace("<div class=\"log-r", "|<div class=\"log-r", $round_text);
		$round_text = str_replace(chr(13), "", $round_text);
		$round_text = str_replace(chr(10), "", $round_text);
		$round_text = trim(strip_tags($round_text));
		while ($round_text != str_replace("  ", " ", $round_text)) $round_text = str_replace("  ", " ", $round_text);
		$round[$num+1][$p] = $round_text;
	}
}

$info = array(
	'player1' => $player1,
	'player2' => $player2,
	'log' => $round,
);

$format = @$_GET['format'];
if ($format == '') {
	$format = 'json';
}

switch ($format) {
	case 'text': {
		header('Content-type: text/plain');
		echo print_r($info, 1);
		break;
	}

	case 'json': {
		header('Content-type: application/json');
		echo json_encode_utf8($info);
		break;
	}

	default: {
		header('Content-type: application/xml');
		$converter = new Array2XML();
		echo $converter->convert($info);
		break;
	}
}
