<?php 
include('config.php');
include('perBot.php');

$m = new perBot;
$m->setLoginData(GAME_USER, GAME_PASS);

if(!$m->isLoged()) {
	$m->loginToper();
}

echo "Getting player info.<br>\n";
$info = new perInfo;
$playerInfo = $info->getInfo();
echo "<pre>".print_r($playerInfo)."</pre>";

echo "Getting player war log list.<br>\n";
$mail = new perMail;
$battlesList = $mail->getBattlesList();
echo "<pre>".print_r($battlesList)."</pre>";
