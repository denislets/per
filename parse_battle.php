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
	$params = 'world/battle/log/id/1828174/r/72b035d7ac51a205e23d1d154fcffd0e';
}

$battle = new perBattle;
$info = $battle->getBattleByURL($params);

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
