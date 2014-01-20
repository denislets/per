<?php
//for debug
//error_reporting (E_ALL | E_STRICT);
//ini_set("display_errors", 1); 

error_reporting (0);
ini_set("display_errors", 0); 

define("BOT_DEBUG", 0);

include('botCore/cUrlClass.php');
include('botCore/simple_html_dom.php');
include('botCore/json.php');
include('botCore/xml.php');
include('botCore/perUtils.class.php');
include('botCore/perBot.class.php');
include('botCore/perInfo.class.php');
include('botCore/perMail.class.php');

$html = new simple_html_dom;
