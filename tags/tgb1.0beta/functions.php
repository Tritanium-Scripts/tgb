<?php

/* functions.php - einige Funktionen (c) 2002 Tritanium Scripts */

function myfwrite($file,$towrite,$mode) {
	$set_chmod = 0;
	if(!file_exists($file)) $set_chmod = 1;
	$fp = fopen($file,$mode.'b'); flock($fp,LOCK_EX);
	if(!is_array($towrite)) {
		fwrite($fp,$towrite);
	}
	else {
		while($akt_value = each($towrite)) {
			if($akt_value[1] != '') fwrite($fp,$akt_value[1]."\n"); // Nur schreiben, wenn der Arrayteil nicht nichts ist
		}
	}
	flock($fp,LOCK_UN); fclose($fp);
	if($set_chmod == 1) {
		@chmod($file,0777);
	}
}

function myexplode($data) {
	return explode("\t",$data);
}

function makedate($timestamp) {
	return strftime("%d.%m.%Y %H:%M",$timestamp);
}

function myimplode($data) {
	return implode("\t",$data);
}

function myfile($file) {
	if(!$fp = @fopen($file,'rb')) return FALSE;
	$data = fread($fp,filesize($file)); flock($fp,LOCK_SH);
	flock($fp,LOCK_UN); fclose($fp);
	$data = explode("\n",$data);
	if(sizeof($data) > 1) {
		end($data);
		unset($data[key($data)]);
	}
	elseif($data[0] == '') return array();
	reset($data);
	return $data;
}

function mycrypt($text) {
	return md5($text);
}

function mutate($text) {
	$text = htmlspecialchars(mysslashes($text));
	return $text;
}

function mymail($target,$subject,$message) {
	global $config;
	return @mail($target,$subject,$message,"From: \"".$config['gb_owner_name']."\" <".$config['gb_owner_email'].">\nX-Mailer: PHP/".phpversion());
}

function mysslashes($text) {
	$text = str_replace("\\\"","\"",$text);
	$text = str_replace("\\\\","\\",$text);
	$text = str_replace("\\'","'",$text);
	$text = str_replace("\t","",$text);
	return $text;
}

function nlbr($text) {
	$text = str_replace("\r",'',$text);
	return str_replace("\n", "<br />", $text);
}

function brnl($text) {
	$text = str_replace("<br />", "\n", $text);
	return str_replace("<br>", "\n", $text);
}

function killbr($text) {
	$text = str_replace("<br />"," ", $text);
	return str_replace("<br>"," ", $text);
}

function check_ip() {
	global $HTTP_SERVER_VARS;
	$found = FALSE;
	$ip_file = myfile("data/iplog.dat");
	while(list($akt_id,$akt_value) = each($ip_file)) {
		$akt_ip = myexplode($akt_value);
		if($akt_ip[0] == $HTTP_SERVER_VARS['REMOTE_ADDR']) {
			if($akt_ip[1]+$akt_ip[2]*60 >= time() || $akt_ip[2] == -1) $found = TRUE;
			else {
				unset($ip_file[$akt_id]);
				myfwrite("data/iplog.dat",$ip_file,'w');
			}
			break;
		}
	}
	return $found;
}

function delete_old_ips() {
	$ip_file = myfile("data/iplog.dat");
	while(list($akt_id,$akt_value) = each($ip_file)) {
		$akt_ip = myexplode($akt_value);
		if($akt_ip[1]+$akt_ip[2]*60 < time() && $akt_ip[2] != -1) {
			unset($ip_file[$akt_id]);
			myfwrite("data/iplog.dat",$ip_file,'w');
		}
	}
}

function add_ip($ip,$duration) {
	$found = FALSE;
	$ip_file = myfile("data/iplog.dat");
	while(list($akt_id,$akt_value) = each($ip_file)) {
		$akt_ip = myexplode($akt_value);
		if($akt_ip[0] == $ip) {
			$akt_ip[1] = time();
			$akt_ip[2] = $duration;
			$ip_file[$akt_id] = myimplode($akt_ip);
			myfwrite("data/iplog.dat",$ip_file,'w');
			$found = TRUE; break;
		}
	}
	if($found == FALSE) {
		myfwrite("data/iplog.dat",$ip."\t".time()."\t".$duration."\t\t\t\t\n",'a');
	}
}

function get_rand_string() {
	mt_srand((double)microtime()*1000000);
	return md5(mt_rand());
}

function censor($text) {
	$cwords = myfile("data/cwords.dat");
	while(list(,$akt_cword) = each($cwords)) {
		$akt_cword = myexplode($akt_cword);
		$text = eregi_replace($akt_cword[1],$akt_cword[2],$text);
	}
	return $text;
}

?>