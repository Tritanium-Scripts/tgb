<?php

/* pheader.php - zeigt den HTML-Kopf des Gästebuchs an */

require_once("auth.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>Gästebuch</title>
	<link rel="stylesheet" href="<?=$config['css_file']?>" type="text/css" />
</head>
<body>
<div style="text-align:center;">
<?php

if($config['banner_pic'] != '') {
	echo '<span class="tgbbannertext"><img border="0" src="'.$config['banner_pic'].'" /><br /><br /></span>';
}

if($config['banner_text'] != '') {
	echo '<span class="tgbbannertext">'.$config['banner_text'].'<br /><br /></span>';
}

?>