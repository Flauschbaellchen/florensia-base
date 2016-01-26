<?php
/**
 * MyBB 1.6 Dutch Language Pack
 * Zie dutch.php voor versieinformatie
 *
 * Nederlands taalpakket voor MyBB
 * Nederlandse vertaling door Tom Huls (Tochjo)
 */
 
$l['board_settings'] = "Bordinstellingen";
$l['change_settings'] = "Instellingen wijzigen";
$l['change_settings_desc'] = "U kunt hier de instellingen van uw discussiebord wijzigen. Kies een groep hieronder om de bijbehorende instellingen te bekijken.";
$l['add_new_setting'] = "Instelling toevoegen";
$l['add_new_setting_desc'] = "U kunt hier een instelling toevoegen.";
$l['modify_existing_settings'] = "Instellingen bewerken";
$l['modify_existing_settings_desc'] = "U kunt hier de bestaande instellingen bewerken.";
$l['add_new_setting_group'] = "Groep toevoegen";
$l['add_new_setting_group_desc'] = "U kunt hier een groep toevoegen waarin instellingen kunnen worden ondergebracht.";
$l['edit_setting_group'] = "Groep bewerken";
$l['edit_setting_group_desc'] = "U kunt hier een bestaande groep bewerken.";

$l['title'] = "Naam";
$l['description'] = "Omschrijving";
$l['group'] = "Groep";
$l['display_order'] = "Volgorde in lijst";
$l['name'] = "Identificator";
$l['name_desc'] = "U kunt hier de identificator invoeren die wordt gebruikt in onder andere scripts, sjablonen en taalbestanden.";
$l['group_name_desc'] = "Deze identificator wordt gebruikt in de taalbestanden.";
$l['text'] = "Tekstveld";
$l['textarea'] = "Tekstvak";
$l['yesno'] = "Keuzerondjes met opties Ja en Nee";
$l['onoff'] = "Keuzerondjes met opties Ingeschakeld en Uitgeschakeld";
$l['select'] = "Keuzelijst";
$l['radio'] = "Keuzerondjes";
$l['checkbox'] = "Keuzevakjes";
$l['language_selection_box'] = "Taalkeuzelijst";
$l['adminlanguage'] = "Taalkeuzelijst voor beheerders";
$l['cpstyle'] = "Keuzelijst in de stijl van het configuratiescherm";
$l['php'] = "Uitgevoerde PHP-code";
$l['type'] = "Type";
$l['extra'] = "Aanvullende informatie";
$l['extra_desc'] = "Als deze instelling een keuzelijst is of keuzerondjes of keuzevakjes zijn, voer dan de mogelijkheden in met paren sleutelwoorden en keuzeopties (sleutelwoord=Keuzeoptie). U moet nieuwe opties op een nieuwe regel plaatsen. Als deze instelling uitgevoerde PHP-code is, voer dan de uit te voeren PHP-code in.";
$l['value'] = "Waarde";
$l['insert_new_setting'] = "Instelling toevoegen";
$l['edit_setting'] = "Instelling bewerken";
$l['delete_setting'] = "Instelling verwijderen";
$l['setting_configuration'] = "Instellingsopties";
$l['update_setting'] = "Instelling bijwerken";
$l['save_settings'] = "Instellingen opslaan";
$l['setting_groups'] = "Groepen";
$l['bbsettings'] = "instellingen";
$l['insert_new_setting_group'] = "Groep toevoegen";
$l['setting_group_setting'] = "Groep of instelling";
$l['order'] = "Volgorde in lijst";
$l['edit_setting_group'] = "Groep bewerken";
$l['delete_setting_group'] = "Groep verwijderen";
$l['save_display_orders'] = "Volgorde opslaan";
$l['update_setting_group'] = "Groep bijwerken";
$l['modify_setting'] = "Instelling bewerken";
$l['search'] = "Zoeken";

$l['show_all_settings'] = "Alle instellingen weergeven";
$l['settings_search'] = "Zoeken naar instellingen";

$l['confirm_setting_group_deletion'] = "Weet u zeker dat u deze groep wilt verwijderen?";
$l['confirm_setting_deletion'] = "Weet u zeker dat u deze instelling wilt verwijderen?";

$l['error_missing_title'] = "U hebt geen naam ingevoerd.";
$l['error_missing_group_title'] = "U hebt geen naam ingevoerd.";
$l['error_invalid_gid'] = "U hebt geen geldige groep gekozen.";
$l['error_invalid_gid2'] = "Deze groep bestaat niet. Controleer de link die u gebruikt heeft.";
$l['error_missing_name'] = "U hebt geen identificator ingevoerd.";
$l['error_missing_group_name'] = "U hebt geen identificator ingevoerd.";
$l['error_invalid_type'] = "U hebt geen geldig type gekozen.";
$l['error_invalid_sid'] = "De opgegeven instelling bestaat niet.";
$l['error_duplicate_name'] = "De opgegeven identificator wordt al gebruikt voor de instelling \"{1}\". Voer een andere identificator in.";
$l['error_duplicate_group_name'] = "De opgegeven identificator wordt al gebruikt voor de groep \"{1}\". Voer een andere identificator in.";
$l['error_no_settings_found'] = "Geen instellingen gevonden.";
$l['error_cannot_edit_default'] = "U kunt standaard instellingen en groepen niet bewerken of verwijderen.";
$l['error_cannot_edit_php'] = "U kunt deze instelling niet bewerken of verwijderen.";
$l['error_ajax_search'] = "Er is een fout opgetreden tijdens het zoeken:";
$l['error_ajax_unknown'] = "Er is een onbekende fout opgetreden tijdens het zoeken.";

$l['success_setting_added'] = "De instelling is toegevoegd.";
$l['success_setting_updated'] = "De instelling is bijgewerkt.";
$l['success_setting_deleted'] = "De instelling is verwijderd.";
$l['success_settings_updated'] = "De instellingen zijn bijgewerkt.";
$l['success_display_orders_updated'] = "De volgordes zijn bijgewerkt.";
$l['success_setting_group_added'] = "De groep is toegevoegd.";
$l['success_setting_group_updated'] = "De groep is bijgewerkt.";
$l['success_setting_group_deleted'] = "De groep is verwijderd.";
$l['success_duplicate_settings_deleted'] = "Alle dubbele groepen zijn verwijderd.";



// Below are the strings for all the settings and setting groups
// Setting Groups
$l['setting_group_onlineoffline'] = "Discussiebord sluiten";
$l['setting_group_onlineoffline_desc'] = "U kunt hier uw discussiebord ontoegankelijk maken voor bezoekers. U kunt ook de reden voor de sluiting aangeven.";

$l['setting_group_general'] = "Algemeen";
$l['setting_group_general_desc'] = "U kunt hier allerlei instellingen van uw discussiebord wijzigen. Denk hierbij aan de naam en de locatie van uw discussiebord en uw website, de e-mailadressen waarop leden u kunnen bereiken en de cookieinstellingen.";

$l['setting_group_server'] = "Server en optimalisatie";
$l['setting_group_server_desc'] = "U kunt hier de server- en optimalisatieinstellingen wijzigen. Daarmee kunt u de serverbelasting verminderen en zorgen voor betere prestaties.";

$l['setting_group_datetime'] = "Datum en tijd";
$l['setting_group_datetime_desc'] = "U kunt hier aangeven hoe data en tijden moeten worden weergegeven.";

$l['setting_group_forumhome'] = "Beginpagina";
$l['setting_group_forumhome_desc'] = "U kunt hier de instellingen voor de beginpagina wijzigen. Denk hierbij aan het weergeven of verbergen van omschrijvingen van forums, moderators, verjaardagen en statistieken.";

$l['setting_group_forumdisplay'] = "Lijst met discussies";
$l['setting_group_forumdisplay_desc'] = "U kunt hier de instellingen voor de lijst met discussies wijzigen. Denk hierbij aan het aantal discussies dat per pagina moet worden weergegeven en wanneer discussies als populair worden beschouwd.";

$l['setting_group_showthread'] = "Discussies";
$l['setting_group_showthread_desc'] = "U kunt hier de instellingen voor discussies wijzigen. Denk hierbij aan het aantal berichten dat per pagina moet worden weergegeven en of het vak Snel reactie plaatsen moet worden weergegeven.";

$l['setting_group_member'] = "Registratieproces en profiel";
$l['setting_group_member_desc'] = "U kunt hier de instellingen voor het registratieproces en de profielen wijzigen. Denk hierbij aan het maximale aantal registraties vanaf &eacute;&eacute;n IP-adres en of hetzelfde e-mailadres door meerdere leden gebruikt mag worden. Ook instellingen van handtekeningen en toegestane avatars behoren tot deze instellingengroep.";

$l['setting_group_posting'] = "Plaatsen van berichten";
$l['setting_group_posting_desc'] = "U kunt hier de instellingen voor het plaatsen van berichten wijzigen. Denk hierbij aan de toegestane lengte van berichten en instellingen voor bijlagen.";

$l['setting_group_memberlist'] = "Ledenlijst";
$l['setting_group_memberlist_desc'] = "U kunt hier de instellingen voor de ledenlijst wijzigen. Denk hierbij aan de standaard sorteervolgorde en hoeveel leden er per pagina moeten worden weergegeven.";

$l['setting_group_reputation'] = "Reputatiesysteem";
$l['setting_group_reputation_desc'] = "U kunt hier het reputatiesysteem in- en uitschakelen en aangeven hoeveel reputaties er per pagina moeten worden weergegeven.";

$l['setting_group_warning'] = "Waarschuwingssysteem";
$l['setting_group_warning_desc'] = "U kunt hier de instellingen voor het waarschuwingssysteem wijzigen. Denk hierbij aan de toegestane redenen en het maximum aantal waarschuwingspunten dat een lid kan ontvangen voordat er actie wordt ondernomen.";

$l['setting_group_privatemessaging'] = "Persoonlijke berichten";
$l['setting_group_privatemessaging_desc'] = "U kunt hier de instellingen voor het systeem van persoonlijke berichten wijzigen. Denk hierbij aan het toestaan of weigeren van afbeeldingen of MyCode in berichten.";

$l['setting_group_calendar'] = "Kalender";
$l['setting_group_calendar_desc'] = "U kunt hier de kalender in- en uitschakelen.";

$l['setting_group_whosonline'] = "Pagina Wie is er online";
$l['setting_group_whosonline_desc'] = "U kunt hier aangeven na hoeveel tijd een lid als offline wordt gezien en na hoeveel tijd de pagina Wie is er Online automatisch wordt vernieuwd.";

$l['setting_group_portal'] = "Portaal";
$l['setting_group_portal_desc'] = "U kunt hier de instellingen voor het portaal wijzigen. Denk hierbij aan het aantal mededelingen dat moet worden weergegeven en of er statistieken en een zoekmogelijkheid moeten worden weergegeven.";

$l['setting_group_search'] = "Zoeksysteem";
$l['setting_group_search_desc'] = "U kunt hier de instellingen voor het zoeksysteem wijzigen. Denk hierbij aan de minimale lengte van zoektermen en hoeveel resultaten er maximaal moeten worden weergegeven.";

$l['setting_group_clickablecode'] = "Emoticons en MyCode";
$l['setting_group_clickablecode_desc'] = "U kunt hier de instellingen voor emoticons en MyCode wijzigen. Denk hierbij aan het weergeven of verbergen van de snelkeuzelijst voor emoticons.";

$l['setting_group_cpprefs'] = "Voorkeuren voor het BeheerdersCS";
$l['setting_group_cpprefs_desc'] = "U kunt hier aangeven in welke taal en welke stijl het BeheerdersCS moet worden weergegeven.";

$l['setting_group_mailsettings'] = "Mailen";
$l['setting_group_mailsettings_desc'] = "U kunt hier aangeven hoe MyBB e-mailberichten moet versturen, aanvullende parameters opgeven voor de PHP-functie mail() en aangeven wat MyBB in de logboeken moet opslaan.";


// Settings (and their options)
// Settings for group "Board Online / Offline"
$l['setting_boardclosed'] = "Discussiebord sluiten";
$l['setting_boardclosed_desc'] = "Als u hier 'Ja' kiest, dan zal uw discussiebord niet toegankelijk zijn voor gebruikers (maar nog wel voor beheerders). Bezoekers zullen een mededeling zien waarin staat dat het discussiebord gesloten is, evenals de reden voor de sluiting. U kunt de reden voor de sluiting hieronder opgeven als u 'Ja' kiest. Als u hier 'Nee' kiest, dan zal iedereen uw discussiebord kunnen bezoeken.";

$l['setting_boardclosed_reason'] = "Reden voor sluiting";
$l['setting_boardclosed_reason_desc'] = "U kunt hier een reden voor de sluiting van uw discussiebord invoeren. Deze reden zal aan bezoekers van uw discussiebord worden weergegeven.";


// Settings for group "General Configuration"
$l['setting_bbname'] = "Naam discussiebord";
$l['setting_bbname_desc'] = "U kunt hier de naam van uw discussiebord invoeren. Het wordt aangeraden een naam te kiezen die niet langer is dan 75 tekens.";

$l['setting_bburl'] = "Locatie discussiebord";
$l['setting_bburl_desc'] = "U kunt hier de locatie van uw discussiebord invoeren. U moet de locatie invoeren met http:// ervoor, maar zonder / erachter.";

$l['setting_homename'] = "Naam website";
$l['setting_homename_desc'] = "U kunt hier de naam van uw website invoeren. Deze zal onderaan uw discussiebord worden weergegeven.";

$l['setting_homeurl'] = "Locatie website";
$l['setting_homeurl_desc'] = "U kunt hier de locatie van uw website invoeren. Een koppeling naar uw website zal onderaan uw discussiebord worden weergegeven.";

$l['setting_adminemail'] = "E-mailadres beheerder";
$l['setting_adminemail_desc'] = "U kunt hier het e-mailadres van de beheerder invoeren. Berichten die het discussiebord verstuurt, zullen dit als afzenderadres hebben.";

$l['setting_returnemail'] = "Reactieadres e-mailberichten van discussiebord";
$l['setting_returnemail_desc'] = "U kunt hier het e-mailadres invoeren waar e-mailberichten naartoe gestuurd moeten worden. Naar dit e-mailadres zullen berichten worden gestuurd als gebruikers bij berichten afkomstig van het discussiebord op Beantwoorden klikken. Als u hier niets invult, zal hiervoor het e-mailadres van de beheerder worden gebruikt.";

$l['setting_contactlink'] = "Koppeling Contact opnemen";
$l['setting_contactlink_desc'] = "U kunt hier aangeven wat er moet gebeuren als gebruikers op de koppeling Contact opnemen onderaan uw discussiebord klikken. Als u hier de locatie van een webpagina invoert, bijvoorbeeld een pagina met een contactformulier, dan zal die pagina worden weergegeven. Als u hier een e-mailadres invult, dan zullen gebruikers een e-mailbericht naar dat adres kunnen sturen. Als u een e-mailadres wilt invoeren, plaats dan mailto: voor het adres.";

$l['setting_bblanguage'] = "Standaard taal";
$l['setting_bblanguage_desc'] = "U kunt hier aangeven in welke taal het discussiebord standaard moet worden weergegeven. Gasten en gebruikers die de instelling niet zelf hebben aangepast in het GebruikersCS zullen het discussiebord in deze taal bekijken.";

$l['setting_cookiedomain'] = "Domein voor cookies";
$l['setting_cookiedomain_desc'] = "U kunt hier aangeven wat het domein voor de cookies moet zijn. Dit veld kan leeggelaten worden. Het domein moet beginnen met . zodat het ook van toepassing is op alle subdomeinen.";

$l['setting_cookiepath'] = "Pad voor cookies";
$l['setting_cookiepath_desc'] = "U kunt hier aangeven wat het pad voor de cookies moet zijn. Het wordt aangeraden hier de volledige mapstructuur van uw discussiebord in te vullen met een / erachter.";

$l['setting_cookieprefix'] = "Voorvoegsel voor cookies";
$l['setting_cookieprefix_desc'] = "U kunt hier aangeven wat het voorvoegsel voor cookies moet zijn. Deze instelling is nuttig als u meerdere exemplaren van MyBB wilt installeren op hetzelfde domein of andere software in gebruik hebt die cookies gebruiken met dezelfde naam. Als u hier niets invult, dan zal geen voorvoegsel worden gebruikt.";

$l['setting_showvernum'] = "Versienummer weergeven";
$l['setting_showvernum_desc'] = "Als u hier 'Aan' kiest, dan zal onderaan uw discussiebord het versienummer van MyBB worden weergegeven. Als u hier 'Uit' kiest, dan zal het versienummer van MyBB niet worden weergegeven.";

$l['setting_captchaimage'] = "Captcha-afbeeldingen gebruiken";
$l['setting_captchaimage_desc'] = "U kunt hier aangeven of u <a href=\"http://nl.wikipedia.org/wiki/Captcha\" target=\"_blank\">captcha</a>-afbeeldingen wilt gebruiken. Als u hier 'Aan' kiest (en GD is ge&iuml;nstalleerd op de server), dan zal bij het registratieproces en het plaatsen van een bericht een afbeelding worden weergegeven met daarin enkele letters en cijfers. Gebruikers moeten deze tekens overnemen om aan te geven dat ze daadwerkelijk een mens zijn en geen computerprogramma. Hiermee worden geautomatiseerde registraties en spamberichten voorkomen. Als u hier 'Uit' kiest, dan hoeven gebruikers geen bevestiging te geven dat ze een mens zijn.";

$l['setting_reportmethod'] = "Wijze van berichten rapporteren";
$l['setting_reportmethod_desc'] = "U kunt hier aangeven hoe gerapporteerde berichten moeten worden afgehandeld. Het wordt aangeraden gerapporteerde berichten in de database op te slaan.";
$l['setting_reportmethod_db'] = "Opslaan in de database";
$l['setting_reportmethod_pms'] = "Per persoonlijk bericht rondsturen aan moderators";
$l['setting_reportmethod_email'] = "Per e-mail rondsturen aan moderators";

$l['setting_statslimit'] = "Aantal discussies bij statistieken";
$l['setting_statslimit_desc'] = "U kunt hier aangeven hoeveel discussies er moeten worden weergegeven op de pagina's met statistieken.";

$l['setting_decpoint'] = "Decimaalteken";
$l['setting_decpoint_desc'] = "U kunt hier aangeven welk teken gebruikt moet worden om decimale getallen te noteren. In het Nederlands maken we hiervoor gebruik van een komma. In het Engels wordt hiervoor een punt gebruikt. Zo zal het getal anderhalf in het Nederlands worden genoteerd als 1,5 en in het Engels als 1.5.";

$l['setting_thousandssep'] = "Cijfergroeperingssymbool";
$l['setting_thousandssep_desc'] = "U kunt hier aangeven welk teken gebruikt moet worden om duizendtallen te scheiden. In het Nederlands maken we hiervoor gebruik van een punt. In het Engels wordt hiervoor een komma gebruikt. Zo zal het getal tienduizend in het Nederlands worden genoteerd als 10.000 en in het Engels als 10,000.";

$l['setting_showlanguageselect'] = "Directe-taalkeuzelijst weergeven";
$l['setting_showlanguageselect_desc'] = "Als u hier 'Ja' kiest, dan zal onderaan uw discussiebord een keuzelijst worden weergegeven waarmee gebruikers snel de taal kunnen veranderen waarin het discussiebord wordt weergegeven. Als u hier 'Nee' kiest, dan zal de keuzelijst niet worden weergegeven. Gasten zullen dan de taal waarin het discussiebord wordt weergegeven, niet kunnen veranderen. Geregistreerde gebruikers kunnen de taal nog veranderen in hun GebruikersCS.";

$l['setting_maxmultipagelinks'] = "Maximale aantal directe paginanummers bij paginanavigatie";
$l['setting_maxmultipagelinks_desc'] = "U kunt hier aangeven hoeveel directe paginanummers bij de paginanavigatie moeten worden weergegeven. Als discussies uit veel pagina's bestaan, dan worden bovenaan en onderaan de discussie koppelingen weergegeven waarmee u direct naar een andere pagina kunt gaan. Hier kunt u aangeven hoeveel van zulke koppelingen er moeten worden weergegeven.";

$l['setting_mailingaddress'] = "Postadres";
$l['setting_mailingaddress_desc'] = "U kunt hier uw postadres invoeren. Dit adres zal worden vermeld op de COPPA-formulieren.";

$l['setting_faxno'] = "Faxnummer";
$l['setting_faxno_desc'] = "U kunt hier uw faxnummer invoeren. Dit nummer zal worden vermeld op de COPPA-formulieren.";


// Settings for group "Server and Optimization Options"
$l['setting_seourls'] = "Zoekmachinevriendelijke adressen gebruiken";
$l['setting_seourls_desc'] = "U kunt hier aangeven of zoekmachinevriendelijke adressen gebruikt moeten worden. Deze adressen zijn makkelijker leesbaar en worden door zoekmachines beter begrepen. Zo wordt showthread.php?tid=1 bijvoorbeeld thread1.html. Om hiervan gebruik te kunnen maken, moet u ervoor zorgen dat het bestand .htaccess in de MyBB-map (of gelijkwaardige map) van uw server staat. Als u hier 'Automatisch bepalen' kiest, dan zal MyBB zoekmachinevriendelijke adressen gebruiken als dit door uw server ondersteunt wordt. Mogelijk kan MyBB niet correct bepalen of deze instelling door uw server ondersteund wordt. Schakel deze functie dan handmatig in (door te kiezen voor 'Inschakelen') of uit (door te kiezen voor 'Uitschakelen'). Voor meer informatie kunt u de <a href=\"http://wiki.mybboard.net/index.php/SEF_URLS\" target=\"_blank\">MyBB Wiki</a> raadplegen.";
$l['setting_seourls_auto'] = "Automatisch bepalen";
$l['setting_seourls_yes'] = "Inschakelen";
$l['setting_seourls_no'] = "Uitschakelen";

$l['setting_gzipoutput'] = "GZip-compressie gebruiken";
$l['setting_gzipoutput_desc'] = "Als u hier 'Ja' kiest, dan worden pagina's met GZip gecomprimeerd voordat ze naar de browser worden gestuurd. Dit betekent dat pagina's sneller kunnen worden gedownload en dat er minder bandbreedte gebruikt wordt. Als u hier 'Nee' kiest, dan zullen pagina's niet worden gecomprimeerd. In sommige gevallen wordt GZip-compressie niet ondersteund.";

$l['setting_gziplevel'] = "GZip-compressieniveau";
$l['setting_gziplevel_desc'] = "U kunt hier het compressieniveau instellen dat gebruikt moet worden op pagina's met GZip te comprimeren. Mogelijke waarden zijn de cijfers 0 tot en met 9, waarbij een hoger getal een hogere compressie betekent. Alleen van toepassing als GZip-compressie gebruikt wordt en een PHP-versie hoger dan 4.2 gebruikt wordt. Als u een oudere PHP-versie gebruikt, dan zal het standaard compressieniveau van de zlib-bibliotheek worden gebruikt.";

$l['setting_standardheaders'] = "Standaard headers versturen";
$l['setting_standardheaders_desc'] = "Als u hier 'Ja' kiest, dan zullen standaard headers verstuurd worden. Als u hier 'Nee' kiest, dan zullen niet-standaard headers gebruikt worden. Op sommige servers moet u wel standaard headers gebruiken, op andere juist niet.";

$l['setting_nocacheheaders'] = "No-Cache headers versturen";
$l['setting_nocacheheaders_desc'] = "Als u hier 'Ja' kiest, dan zullen pagina's niet worden opgeslagen in de buffer van de browser. Als u hier 'Nee' kiest, dan zullen pagina's wel worden opgeslagen in de buffer van de browser.";

$l['setting_redirects'] = "Gebruiksvriendelijke doorverwijspagina's weergeven";
$l['setting_redirects_desc'] = "Als u hier 'Aan' kiest, dan zullen gebruiksvriendelijke doorverwijspagina's worden weergegeven. Als u hier 'Uit' kiest, dan zullen gebruiksvriendelijke doorverwijspagina's niet worden weergegeven en zult u direct worden doorgestuurd zonder een bevestiging te zien.";

$l['setting_load'] = "Maximale serverbelasting";
$l['setting_load_desc'] = "U kunt hier aangeven wat de maximale serverbelasting is. Als de maximale serverbelasting is bereikt, dan zullen sommige gebruikers uw discussiebord niet meer kunnen bereiken. Als u hier 0 invult, dan zal MyBB nooit gebruikers weigeren op basis van de serverbelasting. Het wordt aangeraden een maximale serverbelasting van 5.0 te gebruiken.";

$l['setting_tplhtmlcomments'] = "Sjablonen aangeven in broncode";
$l['setting_tplhtmlcomments_desc'] = "Als u hier 'Ja' kiest, dan zal in de broncode van elke pagina met commentaar worden aangegeven waar sjablonen beginnen en ophouden. Als u hier 'Nee' kiest, dan zal dit commentaar niet worden vermeld in de broncode.";

$l['setting_use_xmlhttprequest'] = "XMLHTTP gebruiken";
$l['setting_use_xmlhttprequest_desc'] = "Als u hier 'Ja' kiest, dan zal XMLHTTP worden gebruikt. Als u hier 'Nee' kiest, dan zal XMLHTTP niet worden gebruikt.";

$l['setting_useshutdownfunc'] = "PHP-functionaliteit Shutdown gebruiken";
$l['setting_useshutdownfunc_desc'] = "Als u hier 'Ja' kiest, dan zal de PHP-functionaliteit Shutdown worden gebruikt. Als u hier 'Nee' kiest, dan zal de PHP-functionaliteit Shutdown niet worden gebruikt. Het wordt aangeraden deze instelling te laten staan zoals bij de installatie is ingesteld. Als niet goed wordt weergegeven of een discussie nieuwe berichten bevat, dan moet u 'Nee' kiezen.";

$l['setting_extraadmininfo'] = "Geavanceerde statistieken en informatie om problemen te verhelpen weergeven";
$l['setting_extraadmininfo_desc'] = "Als u hier 'Ja' kiest, dan zullen geavanceerde statistieken en informatie om problemen te verhelpen worden weergegeven onderaan uw discussiebord. Als u hier 'Nee' kiest, dan zal deze informatie niet worden weergegeven. Als de informatie wel wordt weergegeven, dan zal deze alleen zichtbaar zijn voor beheerders.";

$l['setting_uploadspath'] = "Map voor geüploade bestanden";
$l['setting_uploadspath_desc'] = "U kunt hier aangeven in welke map ge&uuml;ploade bestanden moeten worden opgeslagen. Deze map moet naar 777 geCHMOD worden (beschrijfbaar zijn).";

$l['setting_useerrorhandling'] = "Ingebouwde foutafhandelingsfunctionaliteit gebruiken";
$l['setting_useerrorhandling_desc'] = "Als u hier 'Aan' kiest, dan zal de ingebouwde foutafhandelingsfunctionaliteit van MyBB gebruikt worden. Als u hier 'Uit' kiest, dan zal de ingebouwde foutafhandelingsfunctionaliteit van MyBB niet gebruikt worden. Het wordt aangeraden voor 'Aan' te kiezen.";

$l['setting_errorlogmedium'] = "Wijze van fouten bijhouden";
$l['setting_errorlogmedium_desc'] = "U kunt hier aangeven hoe fouten bijgehouden moet worden.";
$l['setting_errorlogmedium_none'] = "Fouten niet bijhouden";
$l['setting_errorlogmedium_log'] = "Fouten bijhouden in logboek";
$l['setting_errorlogmedium_email'] = "Fouten per e-mail melden";
$l['setting_errorlogmedium_both'] = "Fouten bijhouden in logboek en per e-mail melden";

$l['setting_errortypemedium'] = "Soort fouten om bij te houden";
$l['setting_errortypemedium_desc'] = "U kunt hier aangeven welk soort fouten bijgehouden moet worden.";
$l['setting_errortypemedium_warning'] = "Alleen waarschuwingen";
$l['setting_errortypemedium_error'] = "Alleen foutmeldingen";
$l['setting_errortypemedium_both'] = "Zowel waarschuwingen als foutmeldingen";

$l['setting_errorloglocation'] = "Locatie van foutenlogboek";
$l['setting_errorloglocation_desc'] = "U kunt hier aangeven wat de locatie is van het logboek waarin fouten bijgehouden moeten worden. Alleen van toepassing als u fouten bijhoudt in een logboek.";

$l['setting_enableforumjump'] = "Snelkeuzelijst voor locaties inschakelen";
$l['setting_enableforumjump_desc'] = "Als u hier 'Ja' kiest, dan zal een snelkeuzelijst voor locaties worden weergegeven onderaan de lijsten met discussies en berichten. Als u hier 'Nee' kiest, dan zal geen snelkeuzelijst onderaan de lijsten met discussies en berichten worden weergegeven. De snelkeuzelijst voor locaties stelt u in staat snel naar een ander forum of een ander onderdeel van het discussiebord te springen. Als u veel forums hebt, dan wilt u deze functionaliteit misschien uitschakelen om de serverbelasting te verlagen.";


// Settings for group "Date and Time Formats"
$l['setting_dateformat'] = "Datumweergave";
$l['setting_dateformat_desc'] = "U kunt hier wijzigen hoe data worden weergegeven. De gebruikte syntax is die van de <a href=\"http://nl2.php.net/date\" target=\"_blank\">PHP-functie date()</a>. Het wordt aangeraden deze instelling niet te wijzigen, tenzij u weet hoe deze syntax werkt.";

$l['setting_timeformat'] = "Tijdweergave";
$l['setting_timeformat_desc'] = "U kunt hier wijzigen hoe tijden worden weergegeven. De gebruikte syntax is die van de <a href=\"http://nl2.php.net/date\" target=\"_blank\">PHP-functie date()</a>. Het wordt aangeraden deze instelling niet te wijzigen, tenzij u weet hoe deze syntax werkt.";

$l['setting_regdateformat'] = "Weergave registratiedatum";
$l['setting_regdateformat_desc'] = "U kunt hier aangeven hoe de registratiedatum moet worden weergegeven bij de informatie van auteurs als gebruikers een bericht plaatsen. Als u hier bijvoorbeeld M Y invult, dan kan er bijvoorbeeld staan 'Lid sinds: Jan 2008'.";

$l['setting_timezoneoffset'] = "Standaard tijdzone";
$l['setting_timezoneoffset_desc'] = "U kunt hier de standaard tijdzone instellen. Deze zal worden gebruikt door gasten en gebruikers die de tijdzone niet zelf in hun GebruikersCS hebben gewijzigd.";

$l['setting_dstcorrection'] = "Zomertijd";
$l['setting_dstcorrection_desc'] = "Als u hier 'Ja' kiest, dan zal een zomertijdcorrectie worden toegepast op de tijdzone die u hierboven hebt geselecteerd. U kunt deze instelling wijzigen als de tijdzone wel goed is ingesteld, maar de tijden toch een uur afwijken. Als u hier 'Nee' kiest, dan zal geen zomertijdcorrectie worden toegepast.";


// Settings for group "Forum Home Options"
$l['setting_showdescriptions'] = "Omschrijvingen van forums weergeven";
$l['setting_showdescriptions_desc'] = "U kunt hier aangeven of de omschrijvingen van de forums op de beginpagina moeten worden weergegeven";

$l['setting_subforumsindex'] = "Aantal subforums";
$l['setting_subforumsindex_desc'] = "U kunt hier aangeven hoeveel subforums op de beginpagina moeten worden weergegeven. Als u hier 0 invult, dan zullen er geen subforums worden weergegeven.";

$l['setting_subforumsstatusicons'] = "Berichtindicatoren voor subforums weergeven";
$l['setting_subforumsstatusicons_desc'] = "U kunt hier aangeven of u een icoontje wilt weergeven waaraan gezien kan worden of subforums ongelezen berichten bevatten.";

$l['setting_hideprivateforums'] = "Besloten forums verbergen";
$l['setting_hideprivateforums_desc'] = "U kunt hier aangeven of u besloten forums wilt verbergen van de beginpagina en tevens van de snelkeuzelijst voor locaties. Ook alle subforums zullen worden verborgen.";

$l['setting_modlist'] = "Lijst met moderators weergeven";
$l['setting_modlist_desc'] = "U kunt hier aangeven of op de beginpagina een lijst met moderators moet worden weergegeven bij de forums. Deze instelling is ook van toepassing voor de lijst met discussies.";

$l['setting_showbirthdays'] = "Verjaardagen weergeven";
$l['setting_showbirthdays_desc'] = "U kunt hier aangeven of u verjaardagen op de beginpagina wilt weergeven.";

$l['setting_showwol'] = "Wie is er online weergeven";
$l['setting_showwol_desc'] = "U kunt hier aangeven of u op de beginpagina wilt weergeven welke gebruikers er online zijn.";

$l['setting_showindexstats'] = "Statistieken weergeven";
$l['setting_showindexstats_desc'] = "U kunt hier aangeven of u op de beginpagina enkele statistieken wilt weergeven.";

$l['setting_showforumviewing'] = "Aantal gebruikers in forums weergeven";
$l['setting_showforumviewing_desc'] = "U kunt hier aangeven of op de beginpagina moet worden weergegeven hoeveel gebruikers er in een forum actief zijn.";


// Settings for group "Forum Display Options"
$l['setting_threadsperpage'] = "Standaard aantal discussies per pagina";
$l['setting_threadsperpage_desc'] = "U kunt hier aangeven hoeveel discussies er standaard op een pagina moeten worden weergegeven in discussies. Deze instelling is van toepassing op gasten en gebruikers die zelf geen andere waarde hebben gekozen.";

$l['setting_hottopic'] = "Aantal reacties populaire discussie";
$l['setting_hottopic_desc'] = "U kunt hier aangeven vanaf hoeveel reacties een discussie als populair wordt beschouwd.";

$l['setting_hottopicviews'] = "Aantal keer weergeven populaire discussie";
$l['setting_hottopicviews_desc'] = "U kunt hier aangeven hoeveel keer een discussie moet worden bekeken om als populair te worden beschouwd.";

$l['setting_usertppoptions'] = "Mogelijk aantal discussies per pagina";
$l['setting_usertppoptions_desc'] = "U kunt hier aangeven hoeveel discussies gebruikers per pagina mogen weergeven. Gebruikers kunnen deze instelling zelf aanpassen in hun GebruikersCS. Als u hier niets invult, dan kunnen gebruikers het aantal discussies per pagina niet aanpassen.";

$l['setting_dotfolders'] = "Discussies met eigen reacties markeren";
$l['setting_dotfolders_desc'] = "U kunt hier aangeven of discussies waarin gebruikers zelf een reactie hebben geplaatst, moeten worden gemarkeerd. Als u hier 'Ja' kiest, dan zal een markering worden aangebracht op de afbeeldingen waaraan te zien is of een discussie populair is of nieuwe berichten bevat. Als u hier 'Nee' kiest, dan kunnen gebruikers niet in de lijst met discussies kunnen zien in welke discussies zij een bericht hebben geplaatst.";

$l['setting_browsingthisforum'] = "Aantal gebruikers in forum weergeven";
$l['setting_browsingthisforum_desc'] = "Als u hier 'Aan' kiest, dan zal boven de lijst met discussies worden weergegeven hoeveel gebruikers er in het forum actief zijn. Als u hier 'Uit' kiest, dan zal niet boven de lijst met discussies worden weergegeven hoeveel gebruikers er in het forum actief zijn.";

$l['setting_announcementlimit'] = "Aantal mededelingen";
$l['setting_announcementlimit_desc'] = "U kunt hier aangeven hoeveel mededelingen er in de lijst met discussies moeten worden weergegeven. Als u hier 0 invult, zullen er geen mededelingen worden weergegeven.";


// Settings for group "Show Thread Options"
$l['setting_postlayout'] = "Informatie van auteurs";
$l['setting_postlayout_desc'] = "U kunt hier aangeven of informatie van auteurs boven of naast berichten moet worden weergegeven. Vroeger werd deze informatie veelal naast berichten weergegeven. Tegenwoordig is het vaak gebruikelijk om de informatie boven berichten weer te geven. Gebruikers kunnen deze instelling zelf aanpassen in hun GebruikersCS.";
$l['setting_postlayout_horizontal'] = "Informatie van auteurs boven berichten weergeven";
$l['setting_postlayout_classic'] = "Informatie van auteurs naast berichten weergeven";

$l['setting_postsperpage'] = "Standaard aantal berichten per pagina";
$l['setting_postsperpage_desc'] = "U kunt hier aangeven hoeveel berichten er standaard op een pagina moeten worden weergegeven in discussies. Deze instelling is van toepassing op gasten en gebruikers die zelf geen andere waarde hebben gekozen. Het wordt aangeraden hier maximaal 20 in te vullen in verband met mensen die nog met een langzame internetverbinding werken.";

$l['setting_userpppoptions'] = "Mogelijk aantal berichten per pagina";
$l['setting_userpppoptions_desc'] = "U kunt hier aangeven hoeveel berichten gebruikers per pagina mogen weergeven. Gebruikers kunnen deze instelling zelf aanpassen in hun GebruikersCS. Als u hier niets invult, dan kunnen gebruikers het aantal berichten per pagina niet aanpassen.";

$l['setting_postmaxavatarsize'] = "Maximale afmetingen avatars";
$l['setting_postmaxavatarsize_desc'] = "U kunt hier aangeven wat de maximaal toegestane afmetingen van avatars zijn. U moet een grootte opgeven als breedte<b>x</b>hoogte. Als avatars van gebruikers groter zijn, dan zullen ze automatisch worden verkleind. Als u hier niets invult, dan worden avatars niet automatisch verkleind.";

$l['setting_threadreadcut'] = "Gelezen discussies onthouden in database";
$l['setting_threadreadcut_desc'] = "U kunt hier aangeven hoeveel dagen moet worden bijgehouden welke discussies gebruikers nog niet gelezen hebben. Het wordt aangeraden het aantal dagen niet al te hoog in te stellen, omdat dit uw discussiebord trager kan maken. Als u hier 0 invult, dan zal voor altijd worden onthouden welke discussies gebruikers nog niet gelezen hebben.";

$l['setting_threadusenetstyle'] = "Geneste weergave";
$l['setting_threadusenetstyle_desc'] = "Als u hier 'Ja' kiest, dan zullen discussies standaard worden weergegeven in geneste weergave. Deze weergave is gebruikelijk voor nieuwsgroepen. Als u hier 'Nee' kiest, dan zullen discussies standaard worden weergegeven in lineaire weergave. Tegenwoordig is lineaire weergave het gebruikelijkst.";

$l['setting_quickreply'] = "Vak Snel reactie plaatsen weergeven";
$l['setting_quickreply_desc'] = "Als u hier 'Aan' kiest, dan zal het vak Snel reactie plaatsen onder discussies worden weergegeven. Als u hier 'Uit' kiest, dan moeten gebruikers naar de pagina Reactie plaatsen gaan om een reactie te kunnen plaatsen.";

$l['setting_multiquote'] = "Meerdere berichten citeren";
$l['setting_multiquote_desc'] = "Als u hier 'Aan' kiest, dan kunnen gebruikers meerdere berichten selecteren en die later achter elkaar in een nieuwe reactie citeren. Als u hier 'Uit' kiest, dan kunnen gebruikers maar op &eacute;&eacute;n bericht tegelijk reageren.";

$l['setting_showsimilarthreads'] = "Verwante discussies weergeven";
$l['setting_showsimilarthreads_desc'] = "Als u hier 'Ja' kiest, dan zal onder een discussie een lijst met verwante discussies worden weergegeven. Als u hier 'Nee' kiest, dan zal geen lijst met verwante discussies worden weergegeven.";

$l['setting_similarityrating'] = "Mate van verwantschap";
$l['setting_similarityrating_desc'] = "U kunt hier aangeven welke mate van verwantschap vereist is voor discussies om in de lijst met verwante discussies te worden weergegeven. Hoe lager deze waarde, hoe minder verwant discussies aan elkaar hoeven zijn. Deze waarde moet kleiner dan 10 zijn, en voor grote discussieborden groter dan of gelijk aan 5.";

$l['setting_similarlimit'] = "Aantal verwante discussies";
$l['setting_similarlimit_desc'] = "U kunt hier aangeven hoeveel verwante discussies maximaal in de lijst met verwante discussies moeten worden weergegeven. Het wordt aangeraden hier maximaal 15 in te vullen in verband met mensen die nog met een langzame internetverbinding werken.";

$l['setting_delayedthreadviews'] = "Aantal weergaven later bijwerken";
$l['setting_delayedthreadviews_desc'] = "Als u hier 'Aan' kiest, dan zal het aantal keer dat een discussie is weergegeven op vaste momenten worden bijgewerkt met een taak. Als u hier 'Uit' kiest, dan zal het aantal keer dat een discussie is weergegeven direct worden bijgewerkt.";


// Settings for group "User Registration and Profile Options"
$l['setting_disableregs'] = "Registreren uitschakelen";
$l['setting_disableregs_desc'] = "Als u hier 'Ja' kiest, dan zullen nieuwe gebruikers zich niet kunnen registreren. Als u hier 'Nee' kiest, dan zullen nieuwe gebruikers zich wel kunnen registreren.";

$l['setting_regtype'] = "Registratiemethode";
$l['setting_regtype_desc'] = "U kunt hier aangeven welke registratiemethode gebruikt moet worden.";
$l['setting_regtype_instant'] = "Direct activeren";
$l['setting_regtype_verify'] = "Activatiecode per e-mail";
$l['setting_regtype_randompass'] = "Willekeurig wachtwoord per e-mail";
$l['setting_regtype_admin'] = "Activeren door beheerder";

$l['setting_minnamelength'] = "Minimale lengte gebruikersnaam";
$l['setting_minnamelength_desc'] = "U kunt hier aangeven hoeveel tekens een gebruikersnaam minimaal moet bevatten.";

$l['setting_maxnamelength'] = "Maximale lengte gebruikersnaam";
$l['setting_maxnamelength_desc'] = "U kunt hier aangeven hoeveel tekens een gebruikersnaam maximaal mag bevatten.";

$l['setting_minpasswordlength'] = "Minimale lengte wachtwoord";
$l['setting_minpasswordlength_desc'] = "U kunt hier aangeven hoeveel tekens een wachtwoord minimaal moet bevatten.";

$l['setting_maxpasswordlength'] = "Maximale lengte wachtwoord";
$l['setting_maxpasswordlength_desc'] = "U kunt hier aangeven hoeveel tekens een wachtwoord maximaal mag bevatten.";

$l['setting_customtitlemaxlength'] = "Maximale lengte aangepaste gebruikerstitel";
$l['setting_customtitlemaxlength_desc'] = "U kunt hier aangeven hoeveel tekens een aangepaste gebruikerstitel maximaal mag bevatten.";

$l['setting_betweenregstime'] = "Tijd tussen registraties";
$l['setting_betweenregstime_desc'] = "U kunt hier aangeven hoeveel uur er tussen twee registraties vanaf hetzelfde <a href=\"http://nl.wikipedia.org/wiki/IP_adres\" target=\"_blank\">IP-adres</a> moet zitten.";

$l['setting_allowmultipleemails'] = "Zelfde e-mailadres meerdere malen toestaan";
$l['setting_allowmultipleemails_desc'] = "Als u hier 'Ja' kiest, dan mag hetzelfde e-mailadres door meerdere gebruikers gebruikt worden. Als u hier 'Nee' kiest, dan mag een nieuwe gebruiker zich niet registreren met een e-mailadres dat al door een andere gebruiker gebruikt wordt.";

$l['setting_maxregsbetweentime'] = "Maximale aantal registraties per IP-adres";
$l['setting_maxregsbetweentime_desc'] = "U kunt hier aangeven hoe vaak hetzelfde IP-adres mag worden gebruikt bij het registratieproces. Merk op dat deze registraties niet vlak na elkaar mogen plaatsvinden als u de instelling Tijd tussen registraties hierboven hebt ingesteld op een aantal uur groter dan nul.";

$l['setting_failedlogincount'] = "Aantal aanmeldpogingen";
$l['setting_failedlogincount_desc'] = "U kunt hier aangeven hoeveel aanmeldpogingen gebruikers hebben. Als u hier 0 invult, dan hebben gebruikers een onbeperkt aantal pogingen.";

$l['setting_failedlogintime'] = "Tijd tussen mislukte aanmeldpogingen";
$l['setting_failedlogintime_desc'] = "U kunt hier aangeven hoe lang gebruikers moeten wachten voordat zij zich weer kunnen aanmelden als zij het aantal aanmeldpogingen hierboven zonder succes hebben verbruikt. Alleen van toepassing als de waarde van Aantal aanmeldpogingen niet nul is.";

$l['setting_failedlogintext'] = "Aantal aanmeldpogingen weergeven";
$l['setting_failedlogintext_desc'] = "Als u hier 'Ja' kiest, dan zullen gebruikers kunnen zien hoeveel aanmeldpogingen zij nog over hebben. Als u hier 'Nee' invult, dan zullen gebruikers niet kunnen zien hoeveel aanmeldpogingen zij nog over hebben.";

$l['setting_usereferrals'] = "Doorverwijssysteem gebruiken";
$l['setting_usereferrals_desc'] = "Als u hier 'Ja' kiest, dan kunnen gebruikers tijdens het registratieproces aangeven of zij door een gebruiker van dit discussiebord hierheen zijn verwezen en de naam van die gebruiker invoeren. Als u hier 'Nee' kiest, dan zullen gebruikers niet kunnen aangeven door wie zij naar dit discussiebord verwezen zijn.";

$l['setting_sigmycode'] = "MyCode in handtekeningen toestaan";
$l['setting_sigmycode_desc'] = "U kunt hier aangeven of gebruikers MyCode in hun handtekeningen mogen gebruiken.";

$l['setting_maxsigimages'] = "Maximale aantal afbeeldingen per handtekening";
$l['setting_maxsigimages_desc'] = "U kunt hier aangeven hoeveel afbeeldingen gebruikers in hun handtekening mogen gebruiken. Als u hier 0 invult, dan zullen gebruikers een onbeperkt aantal afbeeldingen in hun handtekening mogen gebruiken.";

$l['setting_sigsmilies'] = "Emoticons in handtekeningen toestaan";
$l['setting_sigsmilies_desc'] = "U kunt hier aangeven of gebruikers emoticons in hun handtekeningen mogen gebruiken.";

$l['setting_sightml'] = "HTML in handtekeningen toestaan";
$l['setting_sightml_desc'] = "U kunt hier aangeven of gebruikers HTML-code in hun handtekeningen mogen gebruiken.";

$l['setting_sigimgcode'] = "[img]-tag in handtekeningen toestaan";
$l['setting_sigimgcode_desc'] = "U kunt hier aangeven of gebruikers de [img]-tag in hun handtekeningen mogen gebruiken.";

$l['setting_siglength'] = "Maximale lengte handtekeningen";
$l['setting_siglength_desc'] = "U kunt hier aangeven hoeveel tekens een handtekening maximaal mag bevatten.";

$l['setting_sigcountmycode'] = "MyCode meetellen in lengte handtekeningen";
$l['setting_sigcountmycode_desc'] = "U kunt hier aangeven of u wilt dat MyCode-tags meetellen voor de maximale lengte van handtekeningen.";

$l['setting_maxavatardims'] = "Maximale afmetingen avatars";
$l['setting_maxavatardims_desc'] = "U kunt hier aangeven wat de maximaal toegestane afmetingen van avatars zijn. U moet een grootte opgeven als breedte<b>x</b>hoogte. Als u hier niets invult, dan mogen gebruikers willekeurig grote avatars gebruiken.";

$l['setting_avatarsize'] = "Maximale bestandsgrootte geüploade avatars";
$l['setting_avatarsize_desc'] = "U kunt hier aangeven wat de maximale bestandsgrootte van ge&uuml;ploade avatars mag zijn (in kilobytes).";

$l['setting_avatarresizing'] = "Avatars automatisch verkleinen";
$l['setting_avatarresizing_desc'] = "U kunt hier aangeven of u avatars met grote afmetingen automatisch wilt verkleinen, gebruikers de mogelijkheid wilt geven hun avatar te verkleinen of avatars niet automatisch wilt verkleinen.";
$l['setting_avatarresizing_auto'] = "Grote avatars automatisch verkleinen";
$l['setting_avatarresizing_user'] = "Gebruikers de mogelijkheid geven hun avatar te verkleinen";
$l['setting_avatarresizing_disabled'] = "Avatars niet automatisch verkleinen";

$l['setting_avatardir'] = "Map voor avatars";
$l['setting_avatardir_desc'] = "U kunt hier aangeven in welke map de avatars staan die worden weergegeven in de avatargalerijen in het GebruikersCS.";

$l['setting_avataruploadpath'] = "Map voor geüploade avatars";
$l['setting_avataruploadpath_desc'] = "U kunt hier aangeven in welke map ge&uuml;ploade avatars moeten worden opgeslagen. Deze map moet naar 777 geCHMOD worden (beschrijfbaar zijn).";

$l['setting_emailkeep'] = "E-mailadres laten behouden";
$l['setting_emailkeep_desc'] = "Als u hier 'Ja' kiest, dan zullen gebruikers die een e-mailadres gebruiken dat u in de lijst met verboden e-mailadressen opneemt mogen behouden. Als u hier 'Nee' kiest, dan zullen gebruikers een ander e-mailadres moeten gaan gebruiken als u hun e-mailadres opneemt in de lijst met verboden e-mailadressen.";

$l['setting_coppa'] = "COPPA";
$l['setting_coppa_desc'] = "U kunt hier aangeven of u ondersteuning voor COPPA wilt inschakelen op uw discussiebord. Onder deze Amerikaanse wet moeten de ouders van kinderen jonger dan 13 jaar hun toestemming geven voor registratie op dit discussiebord. Zie <a href=\"http://nl.wikipedia.org/wiki/Children%27s_Online_Privacy_Protection_Act\" target=\"blank\">Wikipedia</a> voor meer informatie.";
$l['setting_coppa_enabled'] = "COPPA inschakelen (ouders moeten toestemming geven voor registratie)";
$l['setting_coppa_deny'] = "COPPA inschakelen (kinderen jonger dan 13 jaar mogen zich niet registreren)";
$l['setting_coppa_disabled'] = "COPPA uitschakelen (iedereen mag zich registreren, ongeacht leeftijd)";

$l['setting_allowaway'] = "Afwezigheidsberichten toestaan";
$l['setting_allowaway_desc'] = "Als u hier 'Ja' kiest, dan mogen gebruikers hun status op Afwezig instellen, met daarbij een reden en een datum van terugkeer. Als u hier 'Nee' kiest, dan mogen gebruikers hun status niet op Afwezig instellen.";


// Settings for group "Posting"
$l['setting_minmessagelength'] = "Minimale lengte bericht";
$l['setting_minmessagelength_desc'] = "U kunt hier aangeven hoeveel tekens een bericht minimaal moet bevatten.";

$l['setting_maxmessagelength'] = "Maximale lengte bericht";
$l['setting_maxmessagelength_desc'] = "U kunt hier aangeven hoeveel tekens een bericht maximaal mag bevatten.";

$l['setting_maxposts'] = "Maximale aantal berichten per dag";
$l['setting_maxposts_desc'] = "U kunt hier aangeven hoeveel berichten gebruikers maximaal per dag mogen plaatsen.";

$l['setting_postfloodcheck'] = "Controleren op berichtenvloed";
$l['setting_postfloodcheck_desc'] = "U kunt hier aangeven of u het controleren op berichtenvloed wilt inschakelen. Als u hier 'Aan' kiest, dan moeten gebruikers een bepaalde tijd wachten voordat zij een nieuw bericht kunnen plaatsen. U kunt hieronder aangeven hoe lang deze tijd moet zijn. Als u hier 'Uit' kiest, dan mogen gebruikers meerdere berichten direct na elkaar plaatsen.";

$l['setting_postfloodsecs'] = "Tijd tussen berichten";
$l['setting_postfloodsecs_desc'] = "U kunt hier aangeven hoeveel seconden gebruikers moeten wachten na het plaatsen van een bericht voordat ze een nieuw bericht mogen plaatsen. Alleen van toepassing als de instelling Controleren op berichtenvloed hierboven Aan is.";

$l['setting_postmergemins'] = "Tijdsbereik voor automatisch samenvoegen berichten";
$l['setting_postmergemins_desc'] = "U kunt hier aangeven hoeveel minuten na het plaatsen van het eerste bericht volgende berichten van dezelfde gebruiker moeten worden samengevoegd met dat bericht. Bij het automatisch samenvoegen zal de berichtenteller van de gebruiker niet worden opgehoogd bij het maken van volgende berichten en zal het tijdstip van het laatste bericht in de discussie niet worden vernieuwd. Als u hier bijvoorbeeld 60 invult, dan zullen twee berichten van dezelfde gebruiker die 10 minuten na elkaar geplaatst zijn wel automatisch worden samengevoegd en twee berichten van dezelfde gebruiker die 2 uur na elkaar geplaatst zijn, niet. Als u hier niets invult, dan zullen berichten nooit automatisch worden samengevoegd. De standaard instelling is 60 minuten.";

$l['setting_postmergefignore'] = "Forums waarin berichten niet automatisch worden samengevoegd";
$l['setting_postmergefignore_desc'] = "U kunt hier aangeven in welke forums berichten nooit automatisch moeten worden samengevoegd. U moet hier de forum-id's van de forums opgeven, gescheiden door komma's. U kunt het forum-id van een forum vinden door op het tabblad Forums en berichten naar de module Forum beheren te gaan en met de muisaanwijzer over de naam van een forum te bewegen. In de statusbalk onderaan het scherm verschijnt het forum-id achteraan, na &amp;fid=. Als u hier niets invult, dan worden berichten in alle forums automatisch samengevoegd.";

$l['setting_postmergeuignore'] = "Gebruikersgroepen waarvan berichten niet automatisch worden samengevoegd";
$l['setting_postmergeuignore_desc'] = "U kunt hier aangeven van welke gebruikersgroepen berichten nooit automatisch moeten worden samengevoegd. U moet hier de groeps-id's van de gebruikersgroepen opgeven, gescheiden door komma's. U kunt het groeps-id van een gebruikersgroep vinden door op het tabblad Gebruikers en groepen naar de module Groepen te gaan en met de muisaanwijzer over de naam van een gebruikersgroep te bewegen. In de statusbalk onderaan verschijnt het groeps-id achteraan, na &amp;gid=. Als u hier niets invult, dan worden berichten van alle gebruikers automatisch samengevoegd. De standaard instelling is 4 (de gebruikersgroep van beheerders).";

$l['setting_postmergesep'] = "Scheidingsteken bij automatisch samenvoegen";
$l['setting_postmergesep_desc'] = "U kunt hier aangeven hoe berichten die automatisch worden samengevoegd, moeten worden gescheiden. De standaard instelling is [hr]. Daarmee worden berichten gescheiden door een horizontale lijn.";

$l['setting_logip'] = "Opslaan van IP-adressen van berichten";
$l['setting_logip_desc'] = "U kunt hier aangeven of u de IP-adressen van gebruikers wilt opslaan als ze een bericht plaatsen en wie deze gegevens mag bekijken.";
$l['setting_logip_no'] = "IP-adressen niet opslaan";
$l['setting_logip_hide'] = "IP-adressen wel opslaan, alleen beheerders en moderators mogen ze bekijken";
$l['setting_logip_show'] = "IP-adressen wel opslaan, alle gebruikers mogen ze bekijken";

$l['setting_showeditedby'] = "Melding bij bewerken weergeven";
$l['setting_showeditedby_desc'] = "Als u hier 'Ja' kiest, dan zal er een melding worden weergegeven als gebruikers hun bericht hebben gewijzigd. Als u hier 'Nee' kiest, dan zal er geen melding worden weergegeven als gebruikers hun bericht hebben gewijzigd en zal niet zichtbaar zijn dat gebruikers hun bericht hebben aangepast.";

$l['setting_showeditedbyadmin'] = "Melding bij bewerken door forumteam weergeven";
$l['setting_showeditedbyadmin_desc'] = "Als u hier 'Ja' kiest, dan zal er een melding worden weergegeven als een moderator een bericht van een gebruiker heeft gewijzigd. Als u hier 'Nee' kiest, dan zal er geen melding worden weergegeven als een moderator een bericht van een andere gebruiker heeft aangepast.";

$l['setting_maxpostimages'] = "Maximale aantal afbeeldingen per bericht";
$l['setting_maxpostimages_desc'] = "U kunt hier aangeven hoeveel afbeeldingen een bericht maximaal mag bevatten. Emoticons tellen ook mee. Als u hier 0 invult, dan mogen berichten een onbeperkt aantal afbeeldingen bevatten.";

$l['setting_subscribeexcerpt'] = "Aantal tekens bij voorbeelden voor abonnementen";
$l['setting_subscribeexcerpt_desc'] = "U kunt hier aangeven hoeveel tekens uit een nieuw geplaatst bericht moeten worden vermeld in de e-mailberichten die worden gestuurd naar gebruikers die op de discussie of op het forum geabonneerd zijn.";

$l['setting_maxattachments'] = "Maximale aantal bijlagen per bericht";
$l['setting_maxattachments_desc'] = "U kunt hier aangeven hoeveel bijlagen gebruikers maximaal aan een bericht mogen toevoegen.";

$l['setting_attachthumbnails'] = "Afbeeldingen als bijlage";
$l['setting_attachthumbnails_desc'] = "U kunt hier aangeven hoe afbeeldingen die als bijlage aan berichten zijn toegevoegd, moeten worden weergegeven.";
$l['setting_attachthumbnails_yes'] = "Als miniatuurweergave";
$l['setting_attachthumbnails_no'] = "Op volledige grootte";
$l['setting_attachthumbnails_download'] = "Als hyperlink";

$l['setting_attachthumbh'] = "Hoogte miniatuurweergaven van bijlagen";
$l['setting_attachthumbh_desc'] = "U kunt hier aangeven wat de hoogte van miniatuurweergaven moet zijn. U moet een waarde in pixels opgeven.";

$l['setting_attachthumbw'] = "Breedte miniatuurweergaven van bijlagen";
$l['setting_attachthumbw_desc'] = "U kunt hier aangeven wat de breedte van miniatuurweergaven moet zijn. U moet een waarde in pixels opgeven.";

$l['setting_edittimelimit'] = "Tijdsbereik voor bewerken berichten";
$l['setting_edittimelimit_desc'] = "U kunt hier aangeven hoe lang na het plaatsen van een bericht gebruikers hun bericht nog mogen wijzigen, vooropgesteld dat ze in een gebruikersgroep zitten die bewerken van berichten toestaat. U moet het aantal minuten opgeven. Als u hier niets invult, dan mogen gebruikers hun berichten altijd wijzigen.";

$l['setting_wordwrap'] = "Aantal tekens voor automatische woordafbraak";
$l['setting_wordwrap_desc'] = "U kunt hier aangeven na hoeveel tekens een woord automatisch moet worden afgebroken. Dit zal gebeuren door op die plaats in het woord een spatie in te voegen. Hiermee voorkomt u dat lange onafgebroken stukken tekst de opmaak van de pagina verstoren. Als u hier 0 invult, dan zullen woorden niet automatisch worden afgebroken.";

$l['setting_polloptionlimit'] = "Maximale lengte peilingkeuzes";
$l['setting_polloptionlimit_desc'] = "U kunt hier aangeven hoeveel tekens een peilingkeuze maximaal mag bevatten.";

$l['setting_maxpolloptions'] = "Maximale aantal peilingkeuzes";
$l['setting_maxpolloptions_desc'] = "U kunt hier aangeven hoeveel peilingkeuzes maximaal aan een peiling mogen worden toegevoegd.";

$l['setting_threadreview'] = "Discussiegeschiedenis weergeven bij plaatsen nieuwe reactie";
$l['setting_threadreview_desc'] = "Als u hier 'Ja' kiest, dan zullen gebruikers de discussie kunnen teruglezen als ze een nieuwe reactie plaatsen. Als u hier 'Nee' kiest, dan zullen gebruikers de discussie niet kunnen teruglezen als ze een nieuwe reactie plaatsen.";


// Settings for group "Member List"
$l['setting_enablememberlist'] = "Ledenlijst inschakelen";
$l['setting_enablememberlist_desc'] = "Als u hier 'Ja' kiest, dan zullen gebruikers de ledenlijst mogen gebruiken. Als u hier 'Nee' kiest, dan zullen gebruikers de ledenlijst niet mogen gebruiken.";

$l['setting_membersperpage'] = "Aantal leden per pagina";
$l['setting_membersperpage_desc'] = "U kunt hier aangeven hoeveel leden per pagina moeten worden weergegeven in de ledenlijst.";

$l['setting_default_memberlist_sortby'] = "Standaard sorteren op";
$l['setting_default_memberlist_sortby_desc'] = "U kunt hier aangeven op welk veld de ledenlijst standaard gesorteerd moet worden.";
$l['setting_default_memberlist_sortby_regdate'] = "Datum van registratie";
$l['setting_default_memberlist_sortby_postnum'] = "Aantal berichten";
$l['setting_default_memberlist_sortby_username'] = "Gebruikersnaam";
$l['setting_default_memberlist_sortby_lastvisit'] = "Datum van laatste bezoek";

$l['setting_default_memberlist_order'] = "Standaard sorteervolgorde";
$l['setting_default_memberlist_order_desc'] = "U kunt hier aangeven in welke volgorde de ledenlijst standaard gesorteerd moet worden. Als u hier 'Oplopende volgorde' kiest, dan komt de A voor de Z en kleine getallen voor grote getallen. Als u hier 'Aflopende volgorde' kiest, dan komt de Z voor de A en grote getallen voor kleine getallen.";
$l['setting_default_memberlist_order_ASC'] = "Oplopende volgorde";
$l['setting_default_memberlist_order_DESC'] = "Aflopende volgorde";

$l['setting_memberlistmaxavatarsize'] = "Maximale afmetingen avatars";
$l['setting_memberlistmaxavatarsize_desc'] = "U kunt hier aangeven wat de maximaal toegestane afmetingen van avatars zijn. U moet een grootte opgeven als breedte<b>x</b>hoogte. Avatars die grotere afmetingen hebben, zullen verkleind in de ledenlijst worden weergegeven. Als u hier niets invult, dan worden avatars niet verkleind.";


// Settings for group "Reputation"
$l['setting_enablereputation'] = "Reputatiesysteem inschakelen";
$l['setting_enablereputation_desc'] = "Als u hier 'Ja' kiest, dan zullen gebruikers het reputatiesysteem mogen gebruiken. Als u hier 'Nee' kiest, dan zullen gebruikers het reputatiesysteem niet mogen gebruiken.";

$l['setting_repsperpage'] = "Aantal reputaties per pagina";
$l['setting_repsperpage_desc'] = "U kunt hier aangeven hoeveel reputaties per pagina moeten worden weergegeven.";


// Settings for group "Warning System Settings"
$l['setting_enablewarningsystem'] = "Waarschuwingssysteem inschakelen";
$l['setting_enablewarningsystem_desc'] = "Als u hier 'Ja' kiest, dan zullen gebruikers het waarschuwingssysteem mogen gebruiken. Als u hier 'Nee' kiest, dan zullen gebruikers het waarschuwingssysteem niet mogen gebruiken.";

$l['setting_allowcustomwarnings'] = "Eigen redenen toestaan";
$l['setting_allowcustomwarnings_desc'] = "Als u hier 'Ja' kiest, dan mogen gebruikers (die waarschuwingen mogen uitdelen) per waarschuwing een eigen reden geven en een eigen aantal punten uitdelen. Als u hier 'Nee' kiest, dan mogen gebruikers geen eigen reden geven geen eigen aantal punten uitdelen.";

$l['setting_canviewownwarning'] = "Gebruikers mogen eigen waarschuwingen bekijken";
$l['setting_canviewownwarning_desc'] = "Als u hier 'Ja' kiest, dan mogen gebruikers waarschuwingen die aan hen zijn gegeven bekijken in hun GebruikersCS. Als u hier 'Nee' kiest, dan mogen gebruikers waarschuwingen die aan hen zijn gegeven niet bekijken.";

$l['setting_maxwarningpoints'] = "Maximale aantal punten";
$l['setting_maxwarningpoints_desc'] = "U kunt hier aangeven hoeveel punten overeenkomt met een waarschuwingsniveau van 100 procent. Als u hier bijvoorbeeld 10 invult, dan komt elk punt overeen met een waarschuwing van 10 procent.";


// Settings for group "Private Messaging"
$l['setting_enablepms'] = "Systeem voor persoonlijke berichten inschakelen";
$l['setting_enablepms_desc'] = "Als u hier 'Ja' kiest, dan mogen gebruikers elkaar persoonlijke berichten sturen en ontvangen. Als u hier 'Nee' invult, dan mogen gebruikers elkaar geen persoonlijke berichten sturen en ontvangen.";

$l['setting_pmsallowhtml'] = "HTML in persoonlijke berichten toestaan";
$l['setting_pmsallowhtml_desc'] = "U kunt hier aangeven of gebruikers HTML-code in hun persoonlijke berichten mogen gebruiken.";

$l['setting_pmsallowmycode'] = "MyCode in persoonlijke berichten toestaan";
$l['setting_pmsallowmycode_desc'] = "U kunt hier aangeven of gebruikers MyCode in hun persoonlijke berichten mogen gebruiken.";

$l['setting_pmsallowsmilies'] = "Emoticons in persoonlijke berichten toestaan";
$l['setting_pmsallowsmilies_desc'] = "U kunt hier aangeven of gebruikers emoticons in hun persoonlijke berichten mogen gebruiken.";

$l['setting_pmsallowimgcode'] = "[img]-tag in persoonlijke berichten toestaan";
$l['setting_pmsallowimgcode_desc'] = "U kunt hier aangeven of gebruikers de [img]-tag in hun persoonlijke berichten mogen gebruiken.";


// Settings for group "Calendar"
$l['setting_enablecalendar'] = "Kalender inschakelen";
$l['setting_enablecalendar_desc'] = "Als u hier 'Ja' kiest, dan zullen gebruikers de kalender mogen gebruiken. Als u hier 'Nee' kiest, dan zullen gebruikers de kalender niet mogen gebruiken.";


// Settings for group "Who's Online"
$l['setting_wolcutoffmins'] = "Inactief na";
$l['setting_wolcutoffmins_desc'] = "U kunt hier aangeven na hoeveel minuten gebruikers als inactief worden gezien en van de pagina Wie is er online verdwijnen. Het wordt aangeraden hier 15 in te vullen.";

$l['setting_refreshwol'] = "Vernieuwen na";
$l['setting_refreshwol_desc'] = "U kunt hier aangeven na hoeveel minuten de pagina Wie is er online automatisch opnieuw wordt geladen. Als u hier 0 invult, dan zal de pagina niet automatisch opnieuw worden geladen.";


// Settings for group "Portal Settings"
$l['setting_portal_announcementsfid'] = "Bron mededelingen";
$l['setting_portal_announcementsfid_desc'] = "U kunt hier aangeven van welk forum of welke forums u de mededelingen wilt laten zien. U moet hier het forum-id invullen. U kunt het forum-id van een forum vinden door op het tabblad Forums en berichten naar de module Forum beheren te gaan en met de muisaanwijzer over de naam van een forum te bewegen. In de statusbalk onderaan het scherm verschijnt het forum-id achteraan, na &amp;fid=. Als u meerdere forums wilt invoeren, dan moet u de forum-id's scheiden met een komma.";

$l['setting_portal_numannouncements'] = "Aantal mededelingen";
$l['setting_portal_numannouncements_desc'] = "U kunt hier aangeven hoeveel mededelingen er moeten worden weergegeven.";

$l['setting_portal_showwelcome'] = "Welkomstvenster weergeven";
$l['setting_portal_showwelcome_desc'] = "U kunt hier aangeven of u een welkomstvenster wilt weergeven.";

$l['setting_portal_showpms'] = "Informatie over persoonlijke berichten weergeven";
$l['setting_portal_showpms_desc'] = "U kunt hier aangeven of u informatie over persoonlijke berichten wilt weergeven.";

$l['setting_portal_showstats'] = "Statistieken weergeven";
$l['setting_portal_showstats_desc'] = "U kunt hier aangeven of u statistieken wilt weergeven.";

$l['setting_portal_showwol'] = "Wie is er online weergeven";
$l['setting_portal_showwol_desc'] = "U kunt hier aangeven of u informatie wilt weergeven over welke gebruikers online zijn.";

$l['setting_portal_showsearch'] = "Zoekmogelijkheid weergeven";
$l['setting_portal_showsearch_desc'] = "U kunt hier aangeven of u een zoekmogelijkheid wilt weergeven.";

$l['setting_portal_showdiscussions'] = "Nieuwste discussies weergeven";
$l['setting_portal_showdiscussions_desc'] = "U kunt hier aangeven of u de discussies waarin het laatst gereageerd is, wilt weergeven.";

$l['setting_portal_showdiscussionsnum'] = "Aantal discussies";
$l['setting_portal_showdiscussionsnum_desc'] = "U kunt hier aangeven hoeveel discussies waarin het laatst gereageerd is, wilt weergeven.";


// Settings for group "Search System"
$l['setting_searchtype'] = "Soort zoeksysteem";
$l['setting_searchtype_desc'] = "U kunt hier aangeven welk soort zoeksysteem u wilt gebruiken. Het wordt aangeraden hier 'Full text' te kiezen. Dit zoeksysteem is krachtiger dan het standaard zoeksysteem. Mogelijk ondersteunt uw server het echter niet.";
$l['setting_searchtype_standard'] = "Standaard";
$l['setting_searchtype_fulltext'] = "Full text";

$l['setting_searchfloodtime'] = "Tijd tussen zoekopdrachten";
$l['setting_searchfloodtime_desc'] = "U kunt hier aangeven hoeveel tijd in seconden er tussen twee zoekopdrachten van dezelfde gebruiker moet zitten. Hiermee kunt u voorkomen dat gebruikers uw database zwaar belasten. Als u hier 0 invult, dan mogen gebruikers direct een nieuwe zoekopdracht uitvoeren.";

$l['setting_minsearchword'] = "Minimale lengte trefwoord";
$l['setting_minsearchword_desc'] = "U kunt hier aangeven uit hoeveel tekens trefwoorden minimaal moeten bestaan. Als u hier 0 invult, dan moeten trefwoorden minstens uit 3 tekens bestaan als u gebruikmaakt van het standaard zoeksysteem en minstens uit 4 tekens als u gebruikmaakt van het 'full text'-zoeksysteem. Als u het 'full text'-zoeksysteem gebruikt en hier een waarde kleiner dan de MySQL-instelling invoert, dan zal deze waarde genegeerd worden en de MySQL-instelling gebruikt worden.";

$l['setting_searchhardlimit'] = "Aantal zoekresultaten";
$l['setting_searchhardlimit_desc'] = "U kunt hier aangeven hoeveel zoekresultaten maximaal moeten worden weergegeven. Als u hier 0 invult, dan zullen alle resultaten worden weergegeven. Het wordt aangeraden hier een waarde onder de 1000 in te vullen voor grote discussieborden (meer dan een miljoen berichten).";


// Settings for group "Clickable Smilies and BB Code"
$l['setting_bbcodeinserter'] = "MyCode invoegen door klikken";
$l['setting_bbcodeinserter_desc'] = "Als u hier 'Aan' kiest, dan zullen gebruikers MyCode kunnen invoegen met behulp van een werkbalk met opmaakopties. Als u hier 'Uit' kiest, dan zullen gebruikers MyCode niet kunnen invoegen met behulp van een werkbalk met opmaakopties.";

$l['setting_smilieinserter'] = "Emoticons invoegen door klikken";
$l['setting_smilieinserter_desc'] = "Als u hier 'Aan' kiest, dan zullen gebruikers emoticons kunnen invoegen door erop te klikken in een vak met emoticons (de snelkeuzelijst). Als u hier 'Uit' kiest, dan zullen gebruikers emoticons niet kunnen invoegen door erop te klikken.";

$l['setting_smilieinsertertot'] = "Aantal emoticons";
$l['setting_smilieinsertertot_desc'] = "U kunt hier aangeven hoeveel emoticons er in de snelkeuzelijst met emoticons moeten worden weergegeven.";

$l['setting_smilieinsertercols'] = "Aantal kolommen met emoticons";
$l['setting_smilieinsertercols_desc'] = "U kunt hier aangeven hoeveel kolommen met emoticons er in de snelkeuzelijst met emoticons moeten worden weegegeven.";


// Settings for group "Control Panel Preferences (Global)"
$l['setting_cplanguage'] = "Taal BeheerdersCS";
$l['setting_cplanguage_desc'] = "U kunt hier aangeven in welke taal het BeheerdersCS moet worden weergegeven.";

$l['setting_cpstyle'] = "Thema BeheerdersCS";
$l['setting_cpstyle_desc'] = "U kunt hier aangeven welk thema moet worden gebruikt voor het BeheerdersCS. Thema's staan als mappen in de map 'styles'. De naam van het thema is de naam van de map in de map 'styles'. Het bestand style.css in de map van het thema zal als stijlblad gebruikt worden.";


// Settings for group "Mail Settings"
$l['setting_mail_handler'] = "Mailverwerker";
$l['setting_mail_handler_desc'] = "U kunt hier aangeven welke mailverwerker MyBB moet gebruiken om e-mailberichten te versturen.";
$l['setting_mail_handler_mail'] = "PHP-mail";
$l['setting_mail_handler_smtp'] = "SMTP-mail";

$l['setting_mail_parameters'] = "Aanvullende parameters voor de PHP-functie mail()";
$l['setting_mail_parameters_desc'] = "U kunt hier de aanvullende parameters voor de PHP-functie mail() invoeren. Alleen van toepassing als u PHP-mail hebt geselecteerd als mailverwerker. <a href=\"http://nl.php.net/function.mail\" target=\"_blank\">Meer informatie...</a>";

$l['setting_smtp_host'] = "Hostnaam SMTP-server";
$l['setting_smtp_host_desc'] = "U kunt hier de hostnaam van de SMTP-server invoeren waarmee u e-mailberichten wilt verzenden. Alleen van toepassing als u PHP-mail hebt geselecteerd als mailverwerker.";

$l['setting_smtp_port'] = "Poort SMTP-server";
$l['setting_smtp_port_desc'] = "U kunt hier de poort van de SMTP-server invoeren waarmee u e-mailberichten wilt verzenden. Alleen van toepassing als u PHP-mail hebt geselecteerd als mailverwerker.";

$l['setting_smtp_user'] = "Gebruikersnaam SMTP-server";
$l['setting_smtp_user_desc'] = "U kunt hier de gebruikersnaam invoeren waarmee u zich bij de SMTP-server wilt authenticeren. Alleen van toepassing als u PHP-mail hebt geselecteerd als mailverwerker en als de SMTP-server authenticatie vereist.";

$l['setting_smtp_pass'] = "Wachtwoord SMTP-server";
$l['setting_smtp_pass_desc'] = "U kunt hier het wachtwoord invoeren waarmee u zich bij de SMTP-server wilt authenticeren. Alleen van toepassing als u PHP-mail hebt geselecteerd als mailverwerker en als de SMTP-server authenticatie vereist.";

$l['setting_secure_smtp'] = "Versleuteling";
$l['setting_secure_smtp_desc'] = "U kunt hier aangeven welke wijze van versleuteling u wilt toepassen bij de communicatie met de SMTP-server. Alleen van toepassing als u PHP-mail hebt geselecteerd als mailverwerker.";
$l['setting_secure_smtp_0'] = "Geen versleuteling";
$l['setting_secure_smtp_1'] = "Versleutelen met SSL";
$l['setting_secure_smtp_2'] = "Versleutelen met TLS";

$l['setting_mail_logging'] = "Logboeken voor e-mailberichten";
$l['setting_mail_logging_desc'] = "U kunt hier aangeven hoe de e-mailberichten die worden verzonden met de functie 'Discussie aan vriend laten zien' worden opgeslagen. In sommige landen is het illegaal om ook de berichtinhoud op te slaan.";
$l['setting_mail_logging_0'] = "Niets opslaan";
$l['setting_mail_logging_1'] = "E-mailberichten opslaan, maar zonder berichtinhoud";
$l['setting_mail_logging_2'] = "E-mailberichten opslaan, inclusief berichtinhoud";
?>