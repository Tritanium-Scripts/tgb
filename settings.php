<?php

/* settings.php - legt die Einstellungen fest (c) 2002 Tritanium Scripts */


/*** Allgemeine Einstellungen ***/

// G�stebuch aktivieren? (1 = ja, 0 = nein; Standard: 1)
$config['activate_gb'] = 1;

// Name des Besitzers des G�stebuchs
$config['gb_owner_name'] = "Besitzer";

// Ein Bild, dass �ber dem G�stebuch angezeigt werden soll (kann weggelassen werden; eventuell http:// nicht vergessen!)
$config['banner_pic'] = "";

// Ein Text, der �ber dem G�stebuch angezeigt werden soll (kann weggelassen werden)
$config['banner_text'] = "Mein G�stebuch";

// Legt fest, ob IPs nach dem Eintragen automatisch f�r eine gewisse Zeit gesperrt werden sollen (1 = ja, 0 = nein; Standard: 1)
$config['auto_ban'] = 1;

// Legt fest, wie lange IPs, falls aktiviert, automatisch gesperrt werden sollen (in Minuten; Standard: 10)
$config['auto_ban_time'] = 10;

// Anzahl der Eintr�ge, die pro Seite angezeigt werden sollen
$config['entries_per_page'] = 7;

// Legt fest, ob Eintr�ge automatisch zensiert werden sollen (1 = ja, 0 = nein; Standard: 0)
$config['censor_text'] = 0;

// CSS-Datei
$config['css_file'] = "styles/standard.css";

// Rahmenabstand
$config['tpadding'] = 4;

// Zellenabstand
$config['tspacing'] = 1;

// Tabellenweite
$config['twidth'] = "80%";




/*** Emaileinstellungen ***/

// Email des Besitzers des G�stebuchs
$config['gb_owner_email'] = "kontakt@meinedomain.de";

// �ber neue Beitr�ge per Mail benachrichtigen (1 = ja, 0 = nein; Standard: 1)
$config['notify_new_entries'] = 1;

// Danke-Email an G�ste schicken (1 = ja, 0 = nein; Standard: 1)
$config['notify_guests'] = 1;

// Betreff der Dankesemail
$config['thx_email_subject'] = 'Danke f�r deinen Eintrag!';

// Inhalt der Dankesemail (Neue Zeilen werden mit \n gekennzeichnet!)
$config['thx_email_message'] = "Vielen Danke f�r deinen Eintrag in meinem G�stebuch!\nIch w�rde mich freuen dich bald wieder auf meiner Homepage begr��en zu d�rfen!";



/*** Die einzelnen Felder beim Formular zum Eintragen ***/
//
// muss = User m�ssen in diesem Feld etwas eingeben
// kann = User k�nnen in diesem Feld etwas eingeben, zwingend notwendig ist es aber nicht
// deaktiviert = Feld wird nicht angezeigt
//

// Name (2 = muss, 1 = kann, 0 = deaktiviert; Standard: 2)
$config['act_name'] = 2;

// Email (2 = muss, 1 = kann, 0 = deaktiviert; Standard: 1)
$config['act_email'] = 1;

// Homepage (2 = muss, 1 = kann, 0 = deaktiviert; Standard: 1)
$config['act_hp'] = 1;

// Wohnort (2 = muss, 1 = kann, 0 = deaktiviert; Standard: 1)
$config['act_from'] = 1;

// ICQ-Nummer (2 = muss, 1 = kann, 0 = deaktiviert; Standard: 1)
$config['act_icq'] = 0; // ACHTUNG: In dieser Version noch nicht verf�gbar!

// AIM-Nick (2 = muss, 1 = kann, 0 = deaktiviert; Standard: 1)
$config['act_aim'] = 0; // ACHTUNG: In dieser Version noch nicht verf�gbar!

// Y!-Nick (2 = muss, 1 = kann, 0 = deaktiviert; Standard: 1)
$config['act_yahoo'] = 0; // ACHTUNG: In dieser Version noch nicht verf�gbar!

?>