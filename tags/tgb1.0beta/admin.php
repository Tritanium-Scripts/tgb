<?

/* admin.php - verwaltet das Gästebuch (c) 2002 Tritanium Scripts */

require_once("settings.php");
require_once("auth.php");

if($authed != TRUE) {
	$showformular = TRUE;
	$error = '';
	if(isset($HTTP_GET_VARS['verify'])) {
		if(mycrypt($HTTP_POST_VARS['post_password']) != $pw[0]) $error = "Falscher Code!";
		else {
			$showformular = FALSE;
			$HTTP_SESSION_VARS['tgbpw'] = mycrypt($HTTP_POST_VARS['post_password']);
			header("Location: admin.php?$MYSID"); exit;
		}
	}
	if($showformular == TRUE) {
		include("a_pheader.php");
		?>
			<form method="post" action="admin.php?verify=yes&<?=$MYSID?>">
			<table style="border:black 2px solid" border="0" cellpadding="4" cellspacing="0" width="100%">
				<tr><td bgcolor="#616387"><font face="verdana" size="2" color="white"><b>Bitte geben sie den Zugangscode an:</b></font><br /><? if($error != '') echo "<font face=\"verdana\" size=\"2\" color=\"red\"><b>$error</b></font><br />"; ?><input type="password" name="post_password" /><br /><br /><input type="submit" value="Einloggen" /></td></tr>
			<table></form>
		<?
	}
}
else {
	switch(@$HTTP_GET_VARS['mode']) {
		default:
			include("a_pheader.php");
			echo '<form method="post" action="admin.php?mode=deleteentrys&'.$MYSID.'"><table style="background-color:black; border:black 1px solid" border="0" cellpadding="4" cellspacing="1" width="100%">';
			if(!$entrys = myfile("data/entries.dat")) echo "<tr><td bgcolor=\"#696387\" align=\"center\"><span class=\"tgbanorm\"><b>--Keine Einträge vorhanden--</td></tr>";
			else {
				$entrys = array_reverse($entrys);
				while(list(,$akt_value) = each($entrys)) {
					$akt_entry = myexplode($akt_value);
					$akt_entry[2] = killbr($akt_entry[2]);
					if(strlen($akt_entry[2]) > 103) $akt_entry[2] = substr($akt_entry[2],0,100).'...';
					?>
						<tr>
						 <td bgcolor="#696387" align="center"><input type="checkbox" name="delete_entry[<?=$akt_entry[0]?>]" /></td>
						 <td bgcolor="#696387"><span class="tgbanorm"><?=$akt_entry[2]?></span></td>
						 <td bgcolor="#696387"><span class="tgbasmall"><a class="tgbasmall" href="admin.php?mode=viewentry&entry_id=<?=$akt_entry[0]?>">&#187;&nbsp;details</a></span></td>
						</tr>
					<?
				}
			}
			echo '</table><br /><input type="submit" value="gewählte Einträge löschen" /></form>';
		break;

		case 'deleteentrys':
			if(isset($HTTP_POST_VARS['delete_entry'])) {
				if($entrys = myfile("data/entries.dat")) {
					while(list($akt_id,$akt_value) = each($entrys)) {
						$akt_entry = myexplode($akt_value);
						if(isset($HTTP_POST_VARS['delete_entry'][$akt_entry[0]])) unset($entrys[$akt_id]);
					}
				}
				myfwrite("data/entries.dat",$entrys,'w');
			}
			header("Location: admin.php?$MYSID"); exit;
		break;

		case 'logout':
			session_destroy();
			header("Location: admin.php?$MYSID"); exit;
		break;

		case 'delete_many_ips':
			if(isset($HTTP_POST_VARS['delete_iplog'])) {
				$iplogs = myfile("data/iplog.dat");
				while(list($akt_id,$akt_value) = each($iplogs)) {
					$akt_iplog = myexplode($akt_value);
					if(isset($HTTP_POST_VARS['delete_iplog'][str_replace('.','',$akt_iplog[0])])) unset($iplogs[$akt_id]);
				}
				myfwrite('data/iplog.dat',$iplogs,'w');
			}
			header("Location: admin.php?mode=viewiplogs&$MYSID"); exit;
		break;

		case 'addcword':
			$showformular = TRUE;
			if(isset($HTTP_POST_VARS['add'])) {
				$showformular = FALSE;
				while($akt_value = each($HTTP_POST_VARS['word'])) {
					if($akt_value[1] == '') unset($HTTP_POST_VARS['word'][$akt_value[0]]);
					else $HTTP_POST_VARS['word'][$akt_value[0]] = strtolower($HTTP_POST_VARS['word'][$akt_value[0]]);
				}
				$HTTP_POST_VARS['word'] = array_unique($HTTP_POST_VARS['word']);
				if(sizeof($HTTP_POST_VARS['word'] > 0)) {
					$akt_cwords = myfile("data/cwords.dat");
					$last_cword = myexplode($akt_cwords[sizeof($akt_cwords)-1]);
					$akt_id = $last_cword[0]+1;
					reset($HTTP_POST_VARS['word']);
					$towrite = '';
					while($akt_value = each($HTTP_POST_VARS['word'])) {
						$towrite .= $akt_id."\t".mutate($akt_value[1])."\t".mutate($HTTP_POST_VARS['repl'][$akt_value[0]])."\t\t\t\n";
						$akt_id++;
					}
					myfwrite("data/cwords.dat",$towrite,'a');
				}
				header("Location: admin.php?mode=viewcwords&$MYSID"); exit;
			}
			if($showformular == TRUE) {
				include("a_pheader.php");
				?>
					<table style="background-color:black; border:black 1px solid" border="0" cellpadding="2" cellspacing="1" width="100%">
					<tr><td bgcolor="#696387"><span class="tgbanorm"><b>Info:</b> Hier haben sie die Möglichkeit bis zu 4 Zensuren hinzuzufügen. Falls sie weniger hinzufügen wollen, lassen sie die entsprechenden Felder einfach leer.<br>Groß-/Kleinschreibung wird nicht beachtet!</span></td></tr>
					</table>
					<form method="post" action="admin.php?mode=addcword&<?=$MYSID?>"><input type="hidden" name="add" value="1" />
					<table style="background-color:black; border:black 1px solid" border="0" cellpadding="2" cellspacing="1" width="100%">
					<tr>
					 <td width="50%" bgcolor="#696387" align="center"><span class="tgbanorm"><b>Wort</b></span></td>
					 <td width="50%" bgcolor="#696387" align="center"><span class="tgbanorm"><b>Ersetzung</b></span></td>
					</tr>
					<tr>
					 <td bgcolor="#696387" align="center"><input type="text" name="word[0]" /></td>
					 <td bgcolor="#696387" align="center"><input type="text" name="repl[0]" /></td>
					</tr>
					<tr>
					 <td bgcolor="#696387" align="center"><input type="text" name="word[1]" /></td>
					 <td bgcolor="#696387" align="center"><input type="text" name="repl[1]" /></td>
					</tr>
					<tr>
					 <td bgcolor="#696387" align="center"><input type="text" name="word[2]" /></td>
					 <td bgcolor="#696387" align="center"><input type="text" name="repl[2]" /></td>
					</tr>
					<tr>
					 <td bgcolor="#696387" align="center"><input type="text" name="word[3]" /></td>
					 <td bgcolor="#696387" align="center"><input type="text" name="repl[3]" /></td>
					</tr>
					</table><br><input type="submit" value="hinzufügen" /></form>
				<?
			}
		break;

		case 'delete_many_cwords':
			if(isset($HTTP_POST_VARS['delete_cword'])) {
				$cwords = myfile("data/cwords.dat");
				while(list($akt_id,$akt_value) = each($cwords)) {
					$akt_cword = myexplode($akt_value);
					if(isset($HTTP_POST_VARS['delete_cword'][$akt_cword[0]])) unset($cwords[$akt_id]);
				}
				myfwrite("data/cwords.dat",$cwords,'w');
			}
			header("Location: admin.php?mode=viewcwords&$MYSID"); exit;
		break;

		case 'editcword':
			$cwords = myfile("data/cwords.dat");
			while(list($akt_id,$akt_value) = each($cwords)) {
				$akt_cword = myexplode($akt_value);
				if($akt_cword[0] == $HTTP_GET_VARS['cword_id']) {
					$showformular = TRUE;
					if(isset($HTTP_POST_VARS['update'])) {
						$showformular = FALSE;
						$akt_cword[1] = mutate($HTTP_POST_VARS['post_word']);
						$akt_cword[2] = mutate($HTTP_POST_VARS['post_repl']);
						$cwords[$akt_id] = myimplode($akt_cword);
						myfwrite("data/cwords.dat",$cwords,'w');
						header("Location: admin.php?mode=viewcwords&$MYSID"); exit;
					}
					if($showformular == TRUE) {
						include("a_pheader.php");
						?>
							<form method="post" action="admin.php?mode=editcword&cword_id=<?=$akt_cword[0]?>&<?=$MYSID?>"><input type="hidden" name="update" value="1" />
							<table style="background-color:black; border:black 1px solid" border="0" cellpadding="2" cellspacing="1" width="100%">
							<tr>
							 <td bgcolor="#696387"><span class="tgbanorm">Wort:</span></td>
							 <td bgcolor="#696387"><input type="text" name="post_word" value="<?=$akt_cword[1]?>" /></td>
							</tr>
							<tr>
							 <td bgcolor="#696387"><span class="tgbanorm">Ersetzung:</a></td>
							 <td bgcolor="#696387"><input type="text" name="post_repl" value="<?=$akt_cword[2]?>" /></td>
							</tr>
							</table><br><input type="submit" value="updaten" /></form>
						<?
					}
				}
			}
		break;

		case 'viewcwords':
			$cwords = myfile("data/cwords.dat");
			include("a_pheader.php");
			?>
				<form method="post" action="admin.php?mode=delete_many_cwords&<?=$MYSID?>">
				<table style="background-color:black; border:black 1px solid" border="0" cellpadding="2" cellspacing="1" width="100%">
			<?
			if(sizeof($cwords) > 0) {
				while(list(,$akt_value) = each($cwords)) {
					$akt_cword = myexplode($akt_value);
					?>
						<tr>
						 <td width="1%" align="center" bgcolor="#696387"><input type="checkbox" name="delete_cword[<?=$akt_cword[0]?>]" /></td>
						 <td bgcolor="#696387"><span class="tgbanorm"><?=$akt_cword[1]?></span></td>
						 <td bgcolor="#696387"><span class="tgbanorm"><?=$akt_cword[2]?></span></td>
						 <td width="1%" bgcolor="#696387"><span class="tgbasmall"><a class="tgbasmall" href="admin.php?mode=editcword&cword_id=<?=$akt_cword[0]?>&<?=$MYSID?>">bearbeiten</a></span></td>
						</tr>
					<?
				}
				echo '</table><br><input type="submit" value="gewählte zensierte Wörter löschen">';
			}
			else {
				?>
					<tr><td bgcolor="#696387" align="center"><span class="tgbanorm"><b>--keine zensierten Wörter vorhanden--</b></span></td></table>
				<?
			}
			$config['censor_text'] == 1 ? $c_status = '<font color="00FF7F">aktiviert</font>' : $c_status = '<font color="#FF4500">deaktiviert</font>';
			?>
				<br><br><table style="background-color:black; border:black 1px solid" border="0" cellpadding="2" cellspacing="1" width="100%">
				<tr><td bgcolor="#696387"><span class="tgbanorm"><a class="tgbanorm" href="admin.php?mode=addcword&<?=$MYSID?>">Wörter hinzufügen</a> | Aktueller Zensur-Status: <b><?=$c_status?></b></span></td></tr>
				</table>
			<?
		break;

		case 'viewiplogs':
			delete_old_ips();
			include("a_pheader.php");
			?>
				<form method="post" action="admin.php?mode=delete_many_ips&<?=$MYSID?>">
				<table style="background-color:black; border:black 1px solid" border="0" cellpadding="2" cellspacing="1" width="100%">
			<?
			$iplogs = myfile("data/iplog.dat");
			if(sizeof($iplogs) > 0) {
				while(list(,$akt_value) = each($iplogs)) {
					$akt_iplog = myexplode($akt_value);
					if($akt_iplog[2] == -1) $xyz = 'für immer gesperrt';
					else {
						$xyz = 'noch für '.round(( ($akt_iplog[1] + (60*$akt_iplog[2])) -time())/60).' Minuten gesperrt';
					}
					?>
						<tr>
						 <td bgcolor="#696387"><input type="checkbox" name="delete_iplog[<?=str_replace(".","",$akt_iplog[0])?>]"></td>
						 <td bgcolor="#696387"><span class="tgbanorm"><b><?=$akt_iplog[0]?></b></span></td>
						 <td bgcolor="#696387"><span class="tgbanorm"><?=$xyz?></span></td>
						 <td bgcolor="#696387"><span class="tgbasmall"><a class="tgbasmall" href="admin.php?mode=editiplog&ip=<?=$akt_iplog[0]?>&<?=$MYSID?>">bearbeiten</a></span></td>
						</tr>
					<?
				}
				echo '</table><br><input type="submit" value="gewählte Sperren löschen">';
			}
			else {
				?>
					<tr><td bgcolor="#696387" align="center"><span class="tgbanorm"><b>--keine IP-Sperren vorhanden--</b></span></td></table>
				<?
			}
			echo "</form>";
		break;

		case 'editiplog':
			$iplogs = myfile("data/iplog.dat");
			while(list($akt_id,$akt_value) = each($iplogs)) {
				$akt_iplog = myexplode($akt_value);
				if($akt_iplog[0] == $HTTP_GET_VARS['ip']) {
					$showformular = TRUE;
					if(isset($HTTP_POST_VARS['update'])) {
						$showformular = FALSE;
						$akt_iplog[2] = $HTTP_POST_VARS['post_duration'];
						$iplogs[$akt_id] = myimplode($akt_iplog);
						myfwrite("data/iplog.dat",$iplogs,'w');
						header("Location: admin.php?mode=viewiplogs&$MYSID"); exit;
					}
					if($showformular == TRUE) {
						include("a_pheader.php");
						?>
							<form method="post" action="admin.php?mode=editiplog&ip=<?=$akt_iplog[0]?>&<?=$MYSID?>"><input type="hidden" name="update" value="1">
							<table style="background-color:black; border:black 1px solid" border="0" cellpadding="2" cellspacing="1" width="100%">
							<tr>
							 <td bgcolor="#696387"><span class="tgbanorm"><b>IP:</b></span></td>
							 <td bgcolor="#696387"><span class="tgbanorm"><b><?=$akt_iplog[0]?></b></span></td>
							</tr>
							<tr>
							 <td bgcolor="#696387"><span class="tgbanorm"><b>Beginn der Sperre:</b></span></td>
							 <td bgcolor="#696387"><span class="tgbanorm"><b><?=makedate($akt_iplog[1])?></b></span></td>
							</tr>
							<tr>
							 <td bgcolor="#696387"><span class="tgbanorm"><b>Sperrdauer:</b></span></td>
							 <td bgcolor="#696387"><input type="text" name="post_duration" value="<?=$akt_iplog[2]?>">&nbsp;<span class="tgbasmall">(in Minuten; -1 = für immer)</span></td>
							</tr>
							</table><br><input type="submit" value="bearbeiten"></form>
						<?
					}
					break;
				}
			}
		break;

		case 'addipban':
			$entries = myfile("data/entries.dat");
			while(list(,$akt_value) = each($entries)) {
				$akt_entry = myexplode($akt_value);
				if($akt_entry[0] == $HTTP_GET_VARS['entry_id']) {
					$showformular = TRUE;
					if(isset($HTTP_POST_VARS['add'])) {
						$showformular = FALSE;
						add_ip($akt_entry[3],$HTTP_POST_VARS['post_duration']);
						header("Location: admin.php?mode=viewiplogs&$MYSID"); exit;
					}
					if($showformular == TRUE) {
						include("a_pheader.php");
						?>
							<form method="post" action="admin.php?mode=addipban&entry_id=<?=$HTTP_GET_VARS['entry_id']?>&<?=$MYSID?>"><input type="hidden" name="add" value="1">
							<table style="background-color:black; border:black 1px solid" border="0" cellpadding="2" cellspacing="1" width="100%">
							<tr>
							 <td bgcolor="#696387"><span class="tgbanorm"><b>IP:</b></span></td>
							 <td bgcolor="#696387"><span class="tgbanorm"><b><?=$akt_entry[3]?></b></span></td>
							</tr>
							<tr>
							 <td bgcolor="#696387"><span class="tgbanorm"><b>Sperrdauer:</b></span></td>
							 <td bgcolor="#696387"><input type="text" name="post_duration" value="10">&nbsp;<span class="tgbasmall">(in Minuten; -1 = für immer)</span></td>
							</tr>
							</table><br><input type="submit" value="Sperre hinzufügen"></form>
						<?
					}
				}
			}
		break;

		case 'viewentry':
			$entrys = myfile("data/entries.dat");
			while(list($akt_id,$akt_value) = each($entrys)) {
				$akt_entry = myexplode($akt_value);
				if($akt_entry[0] == $HTTP_GET_VARS['entry_id']) {
					$showformular = TRUE;

					if(isset($HTTP_POST_VARS['update'])) {
						$showformular = FALSE;
						$akt_entry[1] = mutate($HTTP_POST_VARS['post_name']);
						$akt_entry[2] = nlbr(mutate($HTTP_POST_VARS['post_entry']));
						$akt_entry[4] = mutate($HTTP_POST_VARS['post_email']);
						$akt_entry[5] = mutate($HTTP_POST_VARS['post_hp']);
						$akt_entry[7] = mutate($HTTP_POST_VARS['post_icq']);
						$akt_entry[8] = mutate($HTTP_POST_VARS['post_aim']);
						$akt_entry[9] = mutate($HTTP_POST_VARS['post_yahoo']);
						$akt_entry[10] = mutate($HTTP_POST_VARS['post_place']);
						$akt_entry[11] = nlbr(mutate($HTTP_POST_VARS['post_comment']));
						$entrys[$akt_id] = myimplode($akt_entry);
						myfwrite("data/entries.dat",$entrys,'w');
						header("Location: admin.php?$MYSID"); exit;
					}

					if($showformular == TRUE) {
						$date = makedate($akt_entry[6]);
						include("a_pheader.php");
						?>
							<form method="post" action="admin.php?mode=viewentry&entry_id=<?=$akt_entry[0]?>&<?=$MYSID?>"><input type="hidden" name="update" value="yes" />
							<table style="background-color:black; border:black 1px solid" border="0" cellpadding="2" cellspacing="1" width="100%">
							<tr>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>Name:</b></span></td>
							 <td bgcolor="#696387" valign="top"><input type="text" name="post_name" value="<?=$akt_entry[1]?>" /></td>
							</tr>
							<tr>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>Datum:</b></span></td>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><?=$date?></span></td>
							</tr>
							<tr>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>IP-Adresse:</b></span></td>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><?=$akt_entry[3]?>&nbsp;(<a class="tgbanorm" href="admin.php?mode=addipban&entry_id=<?=$akt_entry[0]?>&<?=$MYSID?>">IP sperren</a>)</span></td>
							</tr>
							<tr>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>Emailadresse:</b></span></td>
							 <td bgcolor="#696387" valign="top"><input type="text" name="post_email" value="<?=$akt_entry[4]?>" /></td>
							</tr>
							<tr>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>Homepage:</b></span></td>
							 <td bgcolor="#696387" valign="top"><input type="text" name="post_hp" value="<?=$akt_entry[5]?>" /></td>
							</tr>
							<tr>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>Wohnort:</b></span></td>
							 <td bgcolor="#696387" valign="top"><input type="text" name="post_place" value="<?=$akt_entry[10]?>" /></td>
							</tr>
							<tr>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>ICQ:</b></span></td>
							 <td bgcolor="#696387" valign="top"><input type="text" name="post_icq" value="<?=$akt_entry[7]?>" /></td>
							</tr>
							<tr>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>AIM:</b></span></td>
							 <td bgcolor="#696387" valign="top"><input type="text" name="post_aim" value="<?=$akt_entry[8]?>" /></td>
							</tr>
							<tr>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>Y!:</b></span></td>
							 <td bgcolor="#696387" valign="top"><input type="text" name="post_yahoo" value="<?=$akt_entry[9]?>" /></td>
							</tr>
							<tr>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>Eintrag:</b></span></td>
							 <td bgcolor="#696387" valign="top"><textarea cols="60" name="post_entry" rows="13"><?=brnl($akt_entry[2])?></textarea></td>
							</tr>
							<tr>
							 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>Kommentar:</b></span></td>
							 <td bgcolor="#696387" valign="top"><textarea name="post_comment" cols="60" rows="13"><?=brnl($akt_entry[11])?></textarea></td>
							</tr>
							<tr><td bgcolor="#696387" colspan="2"><input type="submit" value="speichern" /></td></tr>
							</table>
							</form>
						<?
					}
				}
			}
		break;

		case 'changecode':
			$showformular = TRUE;
			$error = '';
			if(isset($HTTP_POST_VARS['verify'])) {
				$akt_code = myfile("data/pw.dat"); $akt_code = $akt_code[0];
				if($akt_code != mycrypt($HTTP_POST_VARS['post_oldcode'])) $error = 'Der aktuelle Zugangscode ist falsch!';
				elseif($HTTP_POST_VARS['post_newcode1'] == '') $error = 'Bitte geben sie einen neuen Zugangscode ein!';
				elseif($HTTP_POST_VARS['post_newcode1'] != $HTTP_POST_VARS['post_newcode2']) $error = 'Der neue Zugangscode und die Wiederholung stimmen nicht überein!';
				else {
					$showformular = FALSE;
					$new_code = mycrypt($HTTP_POST_VARS['post_newcode1']);
					myfwrite("data/pw.dat",$new_code,'w');
					$HTTP_SESSION_VARS['tgbpw'] = $new_code;
					include("a_pheader.php");
					?>
						<table style="background-color:black; border:black 1px solid" border="0" cellpadding="2" cellspacing="1" width="100%">
						<tr><td bgcolor="#696387" valign="top"><span class="tgbanorm">Der neue Zugangscode wurde erfolgreich übernommen!</span></td></tr>
						</table>
					<?
				}
			}
			if($showformular == TRUE) {
				include("a_pheader.php");
				?>
					<form method="post" action="admin.php?mode=changecode&entry_id=<?=$akt_entry[0]?>&<?=$MYSID?>"><input type="hidden" name="verify" value="yes" />
					<table style="background-color:black; border:black 1px solid" border="0" cellpadding="2" cellspacing="1" width="100%">
				<?

				if($error != '') echo '<tr><td colspan="2" bgcolor="#696387"><span class="tgbanorm"><b><font color="red">'.$error.'</font></b></span></td></tr>';

				?>
					<tr>
					 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>Aktueller Zugangscode:</b></span></td>
					 <td bgcolor="#696387" valign="top"><input type="password" name="post_oldcode" /></td>
					</tr>
					<tr>
					 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>Neuer Zugangscode:</b></span></td>
					 <td bgcolor="#696387" valign="top"><input type="password" name="post_newcode1" /></td>
					</tr>
					<tr>
					 <td bgcolor="#696387" valign="top"><span class="tgbanorm"><b>Neuer Zugangscode wiederholen:</b></span></td>
					 <td bgcolor="#696387" valign="top"><input type="password" name="post_newcode2" /></td>
					</tr>
					<tr><td bgcolor="#696387" colspan="2"><input type="submit" value="speichern" /></td></tr>
					</table>
					</form>
				<?
			}
		break;

	}
}

include("a_ptail.php");

?>