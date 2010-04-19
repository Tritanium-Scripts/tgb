<?

/* a_pheader.php - Pageheader der Administration (c) Tritanium Scripts */

require_once("auth.php");

?>
<html>
<head>
	<title>Tritanium Guestbook 1.0 Beta - Administration</title>
	<style>
		.tgbanorm {
			font-size:10pt;
			font-family:verdana;
			color:white;
		}
		.tgbasmall {
			font-family:verdana;
			font-size:10px;
			color:white;
		}
		a.tgbasmall:link {
			color:white;
			text-decoration:none;
		}
		a.tgbasmall:visited {
			color:white;
			text-decoration:none;
		}
		a.tgbasmall:hover {
			text-decoration:underline;
		}
	</style>
</head>
<body bgcolor="#A9A9A9">
<table border="0" cellpadding="4" cellspacing="0" width="100%">
<tr><td width="20%" valign="top">
<table style="border:black 2px solid" border="0" width="100%" cellpadding="2" cellspacing="0">
<?
if($authed != TRUE) {
	?>
		<tr><td bgcolor="#616387" align="right"><span class="tgbasmall"><a class="tgbasmall" href="admin.php"><b>Einloggen</b></a></span></td></tr>
	<?
}
else {
	?>
		<tr><td bgcolor="#616387" align="right"><span class="tgbasmall"><a class="tgbasmall" href="admin.php?mode=logout&<?=$MYSID?>"><b>Ausloggen</b></a></span></td></tr>
		<tr><td bgcolor="#616387" align="right"><span class="tgbasmall"><a class="tgbasmall" href="admin.php?mode=viewentrys&<?=$MYSID?>"><b>Einträge ansehen</b></span></td></tr>
		<tr><td bgcolor="#616387" align="right"><span class="tgbasmall"><a class="tgbasmall" href="admin.php?mode=viewiplogs&<?=$MYSID?>"><b>IP-Sperren ansehen</b></span></td></tr>
		<tr><td bgcolor="#616387" align="right"><span class="tgbasmall"><a class="tgbasmall" href="admin.php?mode=viewcwords&<?=$MYSID?>"><b>zens. Wörter ansehen</b></span></td></tr>
		<tr><td bgcolor="#616387" align="right"><span class="tgbasmall"><a class="tgbasmall" href="admin.php?mode=changecode&<?=$MYSID?>"><b>Zugangscode ändern</b></a></span></td></tr>
		<tr><td bgcolor="#616387" align="right"><span class="tgbasmall"><a class="tgbasmall" href="index.php"><b>Zum Gästebuch</b></a></span></td></tr>
	<?
}
?>
</table>
</td>
<td width="80%" valign="top">
