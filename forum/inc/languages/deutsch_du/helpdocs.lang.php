<?php
#########################################################
# Deutsches Sprachpaket (Informell)                     #
# Version 1.6                                           #
# Datum: 03.08.2010                                     #
# MyBB-Version 1.6                                      #
# Autor: MyBBoard.de | Webseite: http://www.mybboard.de #
# (c) 2005-2010 MyBBoard.de | Alle Rechte vorbehalten!  #
#                                                       #
# Die Lizenz/Nutzungsbedingungen finden Sie in der      #
# beiliegenden Readme.                                  #
#########################################################

// Help Document 1
$l['d1_name'] = "Benutzerregistrierung";
$l['d1_desc'] = "Nutzen und Vorteile der Registrierung.";
$l['d1_document'] = "Einige Bereiche dieses Forums können voraussetzen, dass du registriert und eingeloggt bist. Die Registrierung kostet nichts und ist in wenigen Minuten durchgeführt.
<br /><br />Eine Registrierung bringt dir Vorteile: Wenn du registriert bist, kannst du Nachrichten schreiben, deine eigenen Einstellungen festlegen und ein Profil anlegen.
<br /><br />Einige Features, die dir nur nach einer Registrierung zur Verfügung stehen, sind z.B. Wechseln des Anzeigestils, persönlicher Notizblock und das Versenden von E-Mails an andere Benutzer.";

// Help Document 2
$l['d2_name'] = "Profil aktualisieren";
$l['d2_desc'] = "Ändern deiner momentanen Daten.";
$l['d2_document'] = "Vielleicht musst du zwischendurch deine Angaben aktualisieren, wie z. B. Messengeridentitäten, dein Passwort oder deine E-Mail-Adresse. Du kannst alle Angaben über das Control Panel ändern. Um zum Control Panel zu gelangen, klicke einfach auf den Link \"Benutzer-CP\" im oberen Menü. Wähle dann \"Profil ändern\" und ändere oder aktualisiere deine Daten. Danach musst du unten auf der Seite auf den Button klicken, um deine Daten zu senden.";

// Help Document 3
$l['d3_name'] = "Verwendung von Cookies";
$l['d3_desc'] = "Das Forum speichert Cookies auf deinem Computer, die verschiedene Angaben deiner Registrierung beinhalten.";
$l['d3_document'] = "Die Forensoftware verwendet Cookies, um deine Logininformationen und den Zeitpunkt deines letzten Besuchs zu speichern (nur bei registrierten Benutzern).
<br /><br />Cookies sind kleine Textdokumente, die auf deinem Computer gespeichert werden; die Cookies dieses Forums können nur auf dieser Webseite verwendet werden und stellen kein Sicherheitsrisiko dar.
<br /><br />In den Cookies wird auch gespeichert, welche Beiträge du wann gelesen hast.
<br /><br />Um alle Cookies dieses Forums von deinem Computer zu löschen, <a href=\"misc.php?action=clearcookies&amp;key={1}\">klicke hier</a>.";

// Help Document 4
$l['d4_name'] = "An- und Abmeldung";
$l['d4_desc'] = "Wie du dich an- und abmelden kannst.";
$l['d4_document'] = "Wenn du dich anmeldest, werden die Informationen der Anmeldung in einem Cookie auf deinem PC gespeichert, so dass du dich nicht jedes Mal neu anmelden musst. Bei der Abmeldung wird dieses Cookie gelöscht, so dass sich niemand anderes über deinen Account einloggen kann.
<br /><br />Um dich anzumelden, klicke einfach auf \"Anmelden\" oben auf der Seite. Solltest du dich nicht abmelden können, kannst du auch die Cookies auf deinem Computer löschen.";

// Help Document 5
$l['d5_name'] = "Ein neues Thema beginnen";
$l['d5_desc'] = "Wie man ein neues Thema in einem Forum startet.";
$l['d5_document'] = "Wenn du dich in einem Forum befindest, das dich interessiert und in dem du ein neues Thema erstellen willst, wähle den Button \"Neues Thema\", den du oben und unten auf der Seite findest. Bitte beachte, dass du vielleicht nicht die Berechtigung hast, ein neues Thema zu starten.";

// Help Document 6
$l['d6_name'] = "Antworten";
$l['d6_desc'] = "Auf einen Beitrag in einem Forum antworten.";
$l['d6_document'] = "Während deines Besuchs könntest du auf ein Thema stoßen, auf das du gerne antworten möchtest. Klicke dafür auf den Button \"Antworten\" oben oder unten auf der Seite. Bitte beachte, dass du dazu vielleicht nicht die nötige Berechtigung hast.
<br /><br />Ein Moderator könnte das Thema auch geschlossen haben, so dass keine weiteren Antworten möglich sind. Ein Thema kann nur von einem Moderator oder einem Administrator wieder geöffnet werden.";

// Help Document 7
$l['d7_name'] = "MyCode";
$l['d7_desc'] = "Wie man MyCode benutzt.";
$l['d7_document'] = "Du kannst in deinen Beiträgen MyCode benutzen, eine vereinfachte Version von HTML, um verschiedene Effekte zu erzielen.
<p><br />[b]Dieser Text ist fett[/b]<br />&nbsp;&nbsp;&nbsp;<b>Dieser Text ist fett</b>
<p>[i]Dieser Text ist kursiv[/i]<br />&nbsp;&nbsp;&nbsp;<i>Dieser Text ist kursiv</i>
<p>[u]Dieser Text ist unterstrichen[/u]<br />&nbsp;&nbsp;&nbsp;<u>Dieser Text ist unterstrichen</u>
<p>[s]Dieser Text ist durchgestrichen[/s]<br>&nbsp;&nbsp;&nbsp;<strike>Dieser Text ist durchgestrichen</strike>
<p><br />[url]http://www.example.org[/url]<br />&nbsp;&nbsp;&nbsp;<a href=\"http://www.example.org\" target=\"_blank\">http://www.example.org</a>
<p>[url=http://www.example.org]Example.org[/url]<br />&nbsp;&nbsp;&nbsp;<a href=\"http://www.example.org\" target=\"_blank\">Example.org</a>
<p>[email]beispiel@example.org[/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:beispiel@example.org\">beispiel@example.org</a>
<p>[email=beispiel@example.org]Schreib mir![/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:beispiel@example.org\">Schreib mir!</a>
<p>[email=beispiel@example.org?subject=Spam]E-Mail mit Betreff[/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:beispiel@example.org?subject=Spam\">E-Mail mit Betreff</a>
<p><br />[quote]Das ist zitierter Text[/quote]<br />&nbsp;&nbsp;&nbsp;<quote>Das ist zitierter Text</quote>
<p>[code]Text mit vorgegebener Formatierung[/code]<br />&nbsp;&nbsp;&nbsp;<code>Text mit vorgegebener Formatierung</code>
<p><br />[img]http://www.php.net/images/php.gif[/img]<br />&nbsp;&nbsp;&nbsp;<img src=\"http://www.php.net/images/php.gif\" alt=\"\" />
<p>[img=50x50]http://www.php.net/images/php.gif[/img]<br />&nbsp;&nbsp;&nbsp;<img src=\"http://www.php.net/images/php.gif\" width=\"50\" height=\"50\" alt=\"\" />
<p><br />[color=red]Dieser Text ist rot[/color]<br />&nbsp;&nbsp;&nbsp;<font color=\"red\">Dieser Text ist rot</font>
<p>[size=3]Dieser Text ist Größe 3[/size]<br />&nbsp;&nbsp;&nbsp;<font size=\"3\">Dieser Text ist Größe 3</font>
<p>[font=Tahoma]Diese Schriftart ist Tahoma[/font]<br />&nbsp;&nbsp;&nbsp;<font face=\"Tahoma\">Diese Schriftart ist Tahoma</font>
<p><br />[align=center]Dieser Text ist zentriert[/align]<div align=\"center\">Dieser Text ist zentriert</div>
<p>[align=right]Dieser Text ist rechtsbündig[/align]<div align=\"right\">Dieser Text ist rechtsbündig</div>
<p><br />[list]<br />[*]Listeneintrag #1<br />[*]Listeneintrag #2<br />[*]Listeneintrag #3<br />[/list]<br /><ul><li>Listeneintrag #1</li><li>Listeneintrag #2</li><li>Listeneintrag #3</li></ul>
<p>Du kannst eine geordnete Liste erstellen, indem du [list=1] für nummerierte und [list=a] für alphabetisch sortierte Listen verwendest.";

?>
