<?php 
include('config.php');
include('perBot.php');

$m = new perBot;
$m->setLoginData(GAME_USER, GAME_PASS);

if(!$m->isLoged()) {
	$m->loginToper();
}

echo "Getting player war log list.<br>\n";
$mail = new perMail;
$battlesList = $mail->getBattlesList();
echo "<pre>".print_r($battlesList)."</pre>";

$battle = new perBattle;

$converter = new Array2XML();

foreach ($battlesList as $battle_url) {
	$battle_url_parsed = explode('/', $battle_url);
	$id = (int)$battle_url_parsed[4];
	$info = $battle->getBattleByURL($battle_url);
	file_put_contents('battle_'.$id.'.xml', $converter->convert($info));
	file_put_contents('battle_'.$id.'.json', json_encode_utf8($info));
	file_put_contents('battle_'.$id.'.txt', print_r($info, 1));
	usleep(1000000); // sleep for 1 sec.
}
		

