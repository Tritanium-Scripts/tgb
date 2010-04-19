<?php

/* index.php - Zeigt die Beiträge an (c) 2002 Tritanium Scripts */

require_once("auth.php");

if($config['activate_gb'] != 1) {
	include("pheader.php");
	?>
		<table class="tgbform" border="0" cellpadding="2" cellspacing="1" width="80%">
		<tr><td class="tgberrorcell"><span class="tgberrorcell">Das Gästebuch wurde vorübergehend deaktiviert!</span></td></tr>
		</table>
	<?php
	include("ptail.php");
	exit;
}


if(!isset($HTTP_GET_VARS['mode'])) $mode = 'viewentrys';
else $mode = $HTTP_GET_VARS['mode'];

delete_old_ips();

switch($mode) {
	default:
		include("pheader.php");

		$epp = $config['entries_per_page'];

		$size = 0;
		if($entries = myfile("data/entries.dat")) {
			$size = sizeof($entries);
			$entries = array_reverse($entries);
			$x = ceil($size / $epp);
		}
		if(!isset($HTTP_GET_VARS['page'])) $HTTP_GET_VARS['page'] = 1;
		elseif($HTTP_GET_VARS['page'] == 'last') $HTTP_GET_VARS['page'] = $x;
		echo "<span class=\"tgbnewentrytext\"><a class=\"tgbnewentrytext\" href=\"index.php?mode=newentry\">Eintragen</a></span><br /><br /><br />";
		if($size == 0) echo '<table class="tgbform" border="0" cellpadding="2" cellspacing="1" width="80%"><tr><td class="tgberrorcell"><span class="tgberrorcell">Es sind bisher keine Einträge vorhanden.</span></td></tr></table><br>';
		else {
			echo '<table class="tgbtable" border="0" cellpadding="'.$config['tpadding'].'" cellspacing="'.$config['tspacing'].'" width="'.$config['twidth'].'">';
			$startentry = ($HTTP_GET_VARS['page']-1)*$epp;
			$endentry = $startentry+$epp;
			if($endentry > $size) $endentry = $size;
			for($i = $startentry; $i < $endentry; $i++) {
				$akt_entry = myexplode($entries[$i]);
				if($config['censor_text'] == 1) $akt_entry[2] = censor($akt_entry[2]);
				if($akt_entry[4] != '') $akt_entry[4] = "&nbsp;<a href=\"mailto:".$akt_entry[4]."\"><img alt=\"".$akt_entry[4]."\" border=\"0\" src=\"images/email.gif\"></a>&nbsp;";
				if($akt_entry[5] != '') {
					if(strtolower(substr($akt_entry[5],0,7)) != 'http://') $akt_entry[5] = 'http://'.$akt_entry[5];
					$akt_entry[5] = '<br /><a class="tgbleftcellothertext" href="'.$akt_entry[5].'" target="_blank">'.$akt_entry[5].'</a>';
				}
				if($akt_entry[11] != '') $akt_entry[11] = '<br /><br /><br /><div class="tgbcomment">Kommentar von '.$config['gb_owner_name'].':<br />'.$akt_entry[11].'</div>';
				if($akt_entry[10] != '') $akt_entry[10] = '<br />Ort: '.$akt_entry[10];
				?>
						<tr>
						 <td width="30%" class="tgbleftcell" valign="top"><span class="tgbleftcellnametext"><?=$akt_entry[1].$akt_entry[4]?></span><span class="tgbleftcellothertext"><br /><?=makedate($akt_entry[6])?><?=$akt_entry[10].$akt_entry[5]?></span></td>
						 <td width="70%" class="tgbrightcell" valign="top"><span class="tgbrightcelltext"><?=$akt_entry[2].'</span>'.$akt_entry[11]?></td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>

				<?php
			}
			echo '</table><br />';
		}
		echo "<span class=\"tgbnewentrytext\"><a class=\"tgbnewentrytext\" href=\"index.php?mode=newentry\">Eintragen</a></span><br /><br />";

		$array = array();

		if($x > 0) {
			if($x > 5) {
				if($HTTP_GET_VARS['page'] > 2 && $HTTP_GET_VARS['page'] < $x - 2) {
					$array = array($HTTP_GET_VARS['page']-2,$HTTP_GET_VARS['page']-1,$HTTP_GET_VARS['page'],$HTTP_GET_VARS['page']+1,$HTTP_GET_VARS['page']+2);
				}
				elseif($HTTP_GET_VARS['page'] <= 2) {
					$array = array(1,2,3,4,5);
				}
				elseif($HTTP_GET_VARS['page'] >= $x-2) {
					$array = array($x-4,$x-3,$x-2,$x-1,$x);
				}
			}
			else {
				for($i = 1; $i < $x+1; $i++) {
					$array[] = $i;
				}
			}
		}
		for($i = 0; $i < sizeof($array); $i++) {
			if($array[$i] != $HTTP_GET_VARS['page']) $array[$i] = "<a class=\"tgbpagechange\" href=\"index.php?page=".$array[$i]."\">".$array[$i]."</a>";
		}
		$pre = '&#171;&nbsp;&#8249;&nbsp;&nbsp;';
		$suf = '&nbsp;&nbsp;&#8250;&nbsp;&#187;';

		if($HTTP_GET_VARS['page'] > 1) $pre = '<a class="tgbpagechange" href="index.php?page=1">&#171;</a>&nbsp;<a class="tgbpagechange" href="index.php?page='.($HTTP_GET_VARS['page']-1).'">&#8249;</a>&nbsp;&nbsp;';
		if($HTTP_GET_VARS['page'] < $x) $suf = '&nbsp;&nbsp;<a class="tgbpagechange" href="index.php?page='.($HTTP_GET_VARS['page']+1).'">&#8250;</a>&nbsp;<a class="tgbpagechange" href="index.php?page=last">&#187;</a>';


		echo '<span class="tgbpagechange">'.$pre.implode(' | ',$array).$suf.'</span>';
		echo '';


		include("ptail.php");
	break;

	case 'newentry':
		if(check_ip() == TRUE) {
			include("pheader.php");
			?>
				<table class="tgbform" border="0" cellpadding="2" cellspacing="1" width="80%">
				<tr><td class="tgberrorcell"><span class="tgberrorcell">Sie wurden für gewisse Zeit gesperrt. Dies kann mehrere Gründe haben:<br />1: Sie haben sich innerhalb der letzten <?=$config['auto_ban_time']?> Minuten schon ein mal eingetragen<br />2: Sie haben dieses Gästebuch durch z.B. Spammen missbraucht.</span></td></tr>
				</table><br /><span class="tgbnewentrytext"><a class="tgbnewentrytext" href="index.php">Hier gelangen sie zurück zum Gästebuch</a>
			<?php
			include("ptail.php");
		}
		else {
			$showformular = TRUE;
			$error = '';
			if(isset($HTTP_POST_VARS['addentry'])) {
				if(trim($HTTP_POST_VARS['post_name']) == '' && $config['act_name'] == 2) $error = 'Bitte geben sie einen Namen ein!';
				elseif(trim($HTTP_POST_VARS['post_email']) == '' && $config['act_email'] == 2) $error = 'Bitte geben sie eine Emailadresse ein!';
				elseif(trim($HTTP_POST_VARS['post_hp']) == '' && $config['act_hp'] == 2) $error = 'Bitte geben sie eine Homepage ein!';
				elseif(trim($HTTP_POST_VARS['post_place']) == '' && $config['act_from'] == 2) $error = 'Bitte geben sie einen Wohnort ein!';
				elseif(trim($HTTP_POST_VARS['post_icq']) == '' && $config['act_icq'] == 2) $error = 'Bitte geben sie eine ICQ-Nummer ein!';
				elseif(trim($HTTP_POST_VARS['post_aim']) == '' && $config['act_aim'] == 2) $error = 'Bitte geben sie einen AIM-Nick ein!';
				elseif(trim($HTTP_POST_VARS['post_yahoo']) == '' && $config['act_yahoo'] == 2) $error = 'Bitte geben sie einen Y!-Nick ein!';
				elseif(trim($HTTP_POST_VARS['post_entry']) == '') $error = 'Bitte geben sie einen Text ein!';
				else {
					$showformular = FALSE;
					$towrite = array();
					$entry_id = myfile("data/last_id.dat"); $entry_id = $entry_id[0]+1; myfwrite("data/last_id.dat",$entry_id,'w');
					$towrite[] = $entry_id;
					$config['act_name'] > 0 ? $towrite[] = mutate($HTTP_POST_VARS['post_name']) : $towrite[] = '';
					$towrite[] = nlbr(mutate($HTTP_POST_VARS['post_entry']));
					$towrite[] = $HTTP_SERVER_VARS['REMOTE_ADDR'];
					$config['act_email'] > 0 ? $towrite[] = mutate($HTTP_POST_VARS['post_email']) : $towrite[] = '';
					$config['act_hp'] > 0 ? $towrite[] = mutate($HTTP_POST_VARS['post_hp']) : $towrite[] = '';
					$towrite[] = time();
					$config['act_icq'] > 0 ? $towrite[] = mutate($HTTP_POST_VARS['post_icq']) : $towrite[] = '';
					$config['act_aim'] > 0 ? $towrite[] = mutate($HTTP_POST_VARS['post_aim']) : $towrite[] = '';
					$config['act_yahoo'] > 0 ? $towrite[] = mutate($HTTP_POST_VARS['post_yahoo']) : $towrite[] = '';
					$config['act_from'] > 0 ? $towrite[] = mutate($HTTP_POST_VARS['post_place']) : $towrite[] = '';
					$towrite[] = ''; // später Kommentar
					$towrite[] = get_rand_string();
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = '';
					$towrite[] = "\n";
					$towrite = myimplode($towrite);
					myfwrite("data/entries.dat",$towrite,'a');
					if($config['auto_ban'] == 1) add_ip($HTTP_SERVER_VARS['REMOTE_ADDR'],$config['auto_ban_time']);

					if($config['notify_new_entries'] == 1) {
						$send_data['name'] = mysslashes($HTTP_POST_VARS['post_name']);

						if($HTTP_POST_VARS['post_email'] != '') $send_data['email'] = " <".mysslashes($HTTP_POST_VARS['post_email']).">";
						else $send_data['email'] = '';

						$send_data['entry'] = mysslashes($HTTP_POST_VARS['post_entry']);

						$tosend = "Folgender Eintrag wurde von ".$send_data['name'].$send_data['email']." abgegeben:\n".$send_data['entry'];

						mymail($config['gb_owner_email'],"Neuer Eintrag in Ihrem Gästebuch",$tosend);
					}

					if($config['notify_guests'] == 1 && $HTTP_POST_VARS['post_email'] != '') {
						mymail($HTTP_POST_VARS['post_email'],$config['thx_email_subject'],$config['thx_email_message']);
					}

					header("Location: index.php"); exit;
				}
			}

			if($showformular == TRUE) {
				$star = array('name','email','hp','from','icq','aim','yahoo');
				if($config['act_name'] == 2) $star['name'] = '*';
				if($config['act_email'] == 2) $star['email'] = '*';
				if($config['act_hp'] == 2) $star['hp'] = '*';
				if($config['act_from'] == 2) $star['from'] = '*';
				if($config['act_icq'] == 2) $star['icq'] = '*';
				if($config['act_aim'] == 2) $star['aim'] = '*';
				if($config['act_yahoo'] == 2) $star['yahoo'] = '*';

				isset($HTTP_POST_VARS['post_name']) ? $HTTP_POST_VARS['post_name'] = mutate($HTTP_POST_VARS['post_name']) : $HTTP_POST_VARS['post_name'] = '';
				isset($HTTP_POST_VARS['post_email']) ? $HTTP_POST_VARS['post_email'] = mutate($HTTP_POST_VARS['post_email']) : $HTTP_POST_VARS['post_email'] = '';
				isset($HTTP_POST_VARS['post_hp']) ? $HTTP_POST_VARS['post_hp'] = mutate($HTTP_POST_VARS['post_hp']) : $HTTP_POST_VARS['post_hp'] = '';
				isset($HTTP_POST_VARS['post_place']) ? $HTTP_POST_VARS['post_place'] = mutate($HTTP_POST_VARS['post_place']) : $HTTP_POST_VARS['post_place'] = '';
				isset($HTTP_POST_VARS['post_icq']) ? $HTTP_POST_VARS['post_icq'] = mutate($HTTP_POST_VARS['post_icq']) : $HTTP_POST_VARS['post_icq'] = '';
				isset($HTTP_POST_VARS['post_aim']) ? $HTTP_POST_VARS['post_aim'] = mutate($HTTP_POST_VARS['post_aim']) : $HTTP_POST_VARS['post_aim'] = '';
				isset($HTTP_POST_VARS['post_yahoo']) ? $HTTP_POST_VARS['post_yahoo'] = mutate($HTTP_POST_VARS['post_yahoo']) : $HTTP_POST_VARS['post_yahoo'] = '';
				isset($HTTP_POST_VARS['post_entry']) ? $HTTP_POST_VARS['post_entry'] = mutate($HTTP_POST_VARS['post_entry']) : $HTTP_POST_VARS['post_entry'] = '';

				include("pheader.php");
				?>
					<form method="post" action="index.php?mode=newentry"><input type="hidden" name="addentry" value="1" />
					<table class="tgbform" border="0" cellpadding="2" cellspacing="1" width="60%">
				<?php
				if($error != '') echo "<tr><td colspan=\"2\" class=\"tgberrorcell\"><span class=\"tgberrorcell\">Fehler: $error</span></td></tr>";

				if($config['act_name'] > 0) {
					?>
						<tr>
						 <td class="tgbformleftcell"><span class="tgbformleftcell">Name:<?=$star['name']?></span></td>
						 <td class="tgbformrightcell"><span class="tgbformrightcell"><input class="tgbform" type="text" name="post_name" value="<?=$HTTP_POST_VARS['post_name']?>" /></span></td>
						</tr>
					<?php
				}
				if($config['act_hp'] > 0) {
					?>
						<tr>
						 <td class="tgbformleftcell"><span class="tgbformleftcell">Emailadresse:<?=$star['email']?></span></td>
						 <td class="tgbformrightcell"><span class="tgbformrightcell"><input class="tgbform" type="text" name="post_email" value="<?=$HTTP_POST_VARS['post_email']?>" /></span></td>
						</tr>
					<?php
				}
				if($config['act_hp'] > 0) {
					?>
						<tr>
						 <td class="tgbformleftcell"><span class="tgbformleftcell">Homepage:<?=$star['hp']?></span></td>
						 <td class="tgbformrightcell"><span class="tgbformrightcell"><input class="tgbform" type="text" name="post_hp" value="<?=$HTTP_POST_VARS['post_hp']?>" /></span></td>
						</tr>
					<?php
				}
				if($config['act_from'] > 0) {
					?>
						<tr>
						 <td class="tgbformleftcell"><span class="tgbformleftcell">Wohnort:<?=$star['from']?></span></td>
						 <td class="tgbformrightcell"><span class="tgbformrightcell"><input class="tgbform" type="text" name="post_place" value="<?=$HTTP_POST_VARS['post_place']?>" /></span></td>
						</tr>
					<?php
				}
				if($config['act_icq'] > 0) {
					?>
						<tr>
						 <td class="tgbformleftcell"><span class="tgbformleftcell">ICQ:<?=$star['icq']?></span></td>
						 <td class="tgbformrightcell"><span class="tgbformrightcell"><input class="tgbform" type="text" name="post_icq" value="<?=$HTTP_POST_VARS['post_icq']?>" /></span></td>
						</tr>
					<?php
				}
				if($config['act_aim'] > 0) {
					?>
						<tr>
						 <td class="tgbformleftcell"><span class="tgbformleftcell">AIM:<?=$star['aim']?></span></td>
						 <td class="tgbformrightcell"><span class="tgbformrightcell"><input class="tgbform" type="text" name="post_aim" value="<?=$HTTP_POST_VARS['post_aim']?>" /></span></td>
						</tr>
					<?php
				}
				if($config['act_yahoo'] > 0) {
					?>
						<tr>
						 <td class="tgbformleftcell"><span class="tgbformleftcell">Y!:<?=$star['yahoo']?></span></td>
						 <td class="tgbformrightcell"><span class="tgbformrightcell"><input class="tgbform" type="text" name="post_yahoo" value="<?=$HTTP_POST_VARS['post_yahoo']?>" /></span></td>
						</tr>
					<?php
				}
				?>
					<tr><td colspan="2" class="tgbformleftcell"><span class="tgbformleftcell">Eintrag:*</span></td></tr>
					<tr><td colspan="2" class="tgbformrightcell"><span class="tgbformrightcell"><textarea class="tgbform" name="post_entry" cols="80" rows="13"><?=$HTTP_POST_VARS['post_entry']?></textarea></span></td></tr>
					</table><br /><button type="submit" class="tgbform" onfocus="this.blur()"><span class="tgbform">Eintragen</span></button></form>
				<?php
				include("ptail.php");
			}
		}
	break;

}
?>