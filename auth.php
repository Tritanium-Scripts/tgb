<?

/* auth.php - stellt fest, ob User Admin ist (c) 2002 Tritanium Scripts */

require_once("functions.php");
require_once("settings.php");

$authed = FALSE;

session_start();
session_name("sid");
$MYSID = 'sid='.session_id();

if(!isset($HTTP_SESSION_VARS['tgbpw'])) {
	$HTTP_SESSION_VARS['tgbpw'] = '';
	session_register('tgbpw');
}

$pw = myfile("data/pw.dat");

if($pw[0] == $HTTP_SESSION_VARS['tgbpw']) $authed = TRUE;


?>