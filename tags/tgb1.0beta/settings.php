<?php

/* settings.php - legt die Einstellungen fest (c) 2002 Tritanium Scripts */


/*** Allgemeine Einstellungen ***/

// Gästebuch aktivieren? (1 = ja, 0 = nein; Standard: 1)
$config['activate_gb'] = 1;

// Name des Besitzers des Gästebuchs
$config['gb_owner_name'] = "Besitzer";

// Ein Bild, dass über dem Gästebuch angezeigt werden soll (kann weggelassen werden; eventuell http:// nicht vergessen!)
$config['banner_pic'] = "";

// Ein Text, der über dem Gästebuch angezeigt werden soll (kann weggelassen werden)
$config['banner_text'] = "Mein Gästebuch";

// Legt fest, ob IPs nach dem Eintragen automatisch für eine gewisse Zeit gesperrt werden sollen (1 = ja, 0 = nein; Standard: 1)
$config['auto_ban'] = 1;

// Legt fest, wie lange IPs, falls aktiviert, automatisch gesperrt werden sollen (in Minuten; Standard: 10)
$config['auto_ban_time'] = 10;

// Anzahl der Einträge, die pro Seite angezeigt werden sollen
$config['entries_per_page'] = 7;

// Legt fest, ob Einträge automatisch zensiert werden sollen (1 = ja, 0 = nein; Standard: 0)
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

// Email des Besitzers des Gästebuchs
$config['gb_owner_email'] = "kontakt@meinedomain.de";

// Über neue Beiträge per Mail benachrichtigen (1 = ja, 0 = nein; Standard: 1)
$config['notify_new_entries'] = 1;

// Danke-Email an Gäste schicken (1 = ja, 0 = nein; Standard: 1)
$config['notify_guests'] = 1;

// Betreff der Dankesemail
$config['thx_email_subject'] = 'Danke für deinen Eintrag!';

// Inhalt der Dankesemail (Neue Zeilen werden mit \n gekennzeichnet!)
$config['thx_email_message'] = "Vielen Danke für deinen Eintrag in meinem Gästebuch!\nIch würde mich freuen dich bald wieder auf meiner Homepage begrüßen zu dürfen!";



/*** Die einzelnen Felder beim Formular zum Eintragen ***/
//
// muss = User müssen in diesem Feld etwas eingeben
// kann = User können in diesem Feld etwas eingeben, zwingend notwendig ist es aber nicht
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
$config['act_icq'] = 0; // ACHTUNG: In dieser Version noch nicht verfügbar!

// AIM-Nick (2 = muss, 1 = kann, 0 = deaktiviert; Standard: 1)
$config['act_aim'] = 0; // ACHTUNG: In dieser Version noch nicht verfügbar!

// Y!-Nick (2 = muss, 1 = kann, 0 = deaktiviert; Standard: 1)
$config['act_yahoo'] = 0; // ACHTUNG: In dieser Version noch nicht verfügbar!

?>