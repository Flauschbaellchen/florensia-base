<?php
/**
 * MyBB 1.6 Dutch Language Pack
 * Zie dutch.php voor versieinformatie
 *
 * Nederlands taalpakket voor MyBB
 * Nederlandse vertaling door Tom Huls (Tochjo)
 */

$l['users'] = "Gebruikers";

$l['search_for_user'] = "Naar gebruiker zoeken";
$l['browse_users'] = "Door gebruikers bladeren";
$l['browse_users_desc'] = "U kunt hier alle gebruikers bekijken. U kunt alle gebruikers weergeven, maar ook alleen de gebruikers die aan een bepaald criterium voldoen.";
$l['find_users'] = "Naar gebruikers zoeken";
$l['find_users_desc'] = "U kunt hier naar gebruikers zoeken. Als u meer zoektermen invoert, dan zullen de resultaten specifieker zijn.";
$l['create_user'] = "Toevoegen";
$l['create_user_desc'] = "U kunt hier een gebruiker toevoegen.";
$l['merge_users'] = "Gebruikers samenvoegen";
$l['merge_users_desc'] = "U kunt hier twee gebruikers samenvoegen. De \"brongebruiker\" zal worden samengevoegd met de \"doelgebruiker\", waarbij de doelgebruiker overblijft. Alle berichten, discussies, persoonlijke berichten, afspraken, het aantal geplaatste berichten en de vriendenlijst van de brongebruiker zullen worden samengevoegd met die van de doelgebruiker. Daarna zal de brongebruiker worden verwijderd.<br /><b>Deze handeling kan niet ongedaan worden gemaakt.</b>";
$l['edit_user'] = "Gebruiker bewerken";
$l['edit_user_desc'] = "U kunt hier onder andere het profiel, de instellingen en de handtekening van deze gebruiker bewerken, evenals statistieken en andere gegevens van deze gebruiker bekijken.";
$l['show_referrers'] = "Doorverwijzers weergeven";
$l['show_referrers_desc'] = "U kunt hier uw zoekresultate bekijken. U kunt kiezen tussen een weergave met een tabel of met visitekaartjes.";
$l['show_ip_addresses'] = "IP-adressen weergeven";
$l['show_ip_addresses_desc'] = "U kunt hier bekijken met welk IP-adres de onderstaande gebruikers zich hebben geregistreerd en met welke IP-adressen zij berichten hebben geplaatst. Het eerste IP-adres is het IP-adres waarmee een gebruiker zich heeft geregistreerd. Alle andere IP-adressen zijn IP-adressen waarmee een gebruiker berichten heeft geplaatst.";

$l['error_avatartoobig'] = "De opgegeven avatar is te groot. Een avatar mag maximaal {1} pixels breed en {2} pixels hoog zijn.";
$l['error_invalidavatarurl'] = "U hebt een ongeldige locatie voor de avatar ingevoerd.";
$l['error_invalid_user'] = "U hebt een ongeldige gebruiker opgegeven.";
$l['error_no_perms_super_admin'] = "U mag deze gebruiker niet bewerken, omdat u geen superbeheerder bent.";
$l['error_invalid_user_source'] = "De brongebruiker bestaat niet.";
$l['error_invalid_user_destination'] = "De doelgebruiker bestaat niet.";
$l['error_cannot_merge_same_account'] = "De brongebruiker en doelgebruiker mogen niet hetzelfde zijn.";
$l['error_no_users_found'] = "Er zijn geen gebruikers gevonden die voldoen aan de opgegeven criteria.";
$l['error_invalid_admin_view'] = "U hebt een ongeldig weergavecriterium opgegeven.";
$l['error_missing_view_title'] = "U hebt geen naam voor dit weergavecriterium ingevoerd.";
$l['error_no_view_fields'] = "U hebt geen velden geselecteerd om weer te geven.";
$l['error_invalid_view_perpage'] = "U hebt een ongeldig aantal resultaten per pagina ingevoerd.";
$l['error_invalid_view_sortby'] = "U hebt een ongeldig veld geselecteerd om op te sorteren.";
$l['error_invalid_view_sortorder'] = "U hebt een ongeldige sorteervolgorde opgegeven.";
$l['error_invalid_view_delete'] = "U hebt een ongeldig weergavecriterium geselecteerd om te verwijderen.";
$l['error_cannot_delete_view'] = "U kunt dit weergavecriterium niet verwijderen, omdat er minstens &eacute;&eacute;n weergavecriterium aanwezig moet zijn.";

$l['user_deletion_confirmation'] = "Weet u zeker dan u deze gebruiker wilt verwijderen?";

$l['success_coppa_activated'] = "De COPPA-gebruiker is geactiveerd.";
$l['success_activated'] = "De gebruiker is geactiveerd.";
$l['success_user_created'] = "De gebruiker is toegevoegd.";
$l['success_user_updated'] = "De gebruiker is bijgewerkt.";
$l['success_user_deleted'] = "De gebruiker is verwijderd.";
$l['success_merged'] = "is samengevoegd met";
$l['succuss_view_set_as_default'] = "Het weergavecriterium is ingesteld als standaard weergavecriterium.";
$l['success_view_created'] = "Het weergavecriterum is toegevoegd.";
$l['success_view_updated'] = "Het weergavecriterium is bijgewerkt.";
$l['success_view_deleted'] = "Het weergavecriterium is verwijderd.";

$l['confirm_view_deletion'] = "Weet u zeker dat u dit weergavecriterium wilt verwijderen?";

$l['warning_coppa_user'] = "<p class=\"alert\"><strong>Let op: </strong> deze gebruiker wacht op COPPA-validatie. <a href=\"index.php?module=user/users&amp;action=activate_user&amp;uid={1}\">Deze gebruiker activeren</a></p>";

$l['required_profile_info'] = "Verplichte informatie";
$l['password'] = "Wachtwoord";
$l['confirm_password'] = "Wachtwoord bevestigen";
$l['email_address'] = "E-mailadres";
$l['use_primary_user_group'] = "Eerste gebruikersgroep";
$l['primary_user_group'] = "Eerste gebruikersgroep";
$l['additional_user_groups'] = "Extra gebruikersgroepen";
$l['additional_user_groups_desc'] = "U kunt de Control-toets gebruiken om meerdere groepen te selecteren. Houd dan de toets ingedrukt en klik meerdere groepen aan.";
$l['display_user_group'] = "Zichtbare gebruikersgroep";
$l['save_user'] = "Gebruiker opslaan";

$l['overview'] = "Overzicht";
$l['profile'] = "Profiel";
$l['account_settings'] = "Instellingen";
$l['signature'] = "Handtekening";
$l['avatar'] = "Avatar";
$l['general_account_stats'] = "Statistieken";
$l['local_time'] = "Plaatselijke tijd";
$l['posts'] = "Aantal berichten";
$l['age'] = "Leeftijd";
$l['posts_per_day'] = "Aantal berichten per dag";
$l['percent_of_total_posts'] = "Percentage van alle berichten";
$l['user_overview'] = "Overzicht";

$l['new_password'] = "Nieuw wachtwoord";
$l['new_password_desc'] = "Alleen van toepassing als u het wachtwoord wilt wijzigen.";
$l['confirm_new_password'] = "Nieuw wachtwoord bevestigen";

$l['optional_profile_info'] = "Optionele informatie";
$l['custom_user_title'] = "Eigen gebruikerstitel";
$l['custom_user_title_desc'] = "Als u dit veld leeg laat, dan zal de standaard gebruikerstitel worden gebruikt.";
$l['website'] = "Website";
$l['icq_number'] = "ICQ-nummer";
$l['aim_handle'] = "AIM-schermnaam";
$l['yahoo_messanger_handle'] = "Yahoo! ID";
$l['msn_messanger_handle'] = "Windows Live-adres"; // was MSN Messenger-adres

$l['hide_from_whos_online'] = "Deze gebruiker van de Wie is er online-lijst verbergen";
$l['remember_login_details'] = "Aanmeldgegevens van deze gebruiker voor toekomstige bezoeken bewaren";
$l['login_cookies_privacy'] = "Aanmelden, cookies en privacy";
$l['recieve_admin_emails'] = "Beheerders mogen deze gebruiker e-mailberichten sturen";
$l['hide_email_from_others'] = "Andere gebruikers mogen deze gebruiker geen e-mailberichten sturen";
$l['recieve_pms_from_others'] = "Andere gebruikers mogen deze gebruiker persoonlijke berichten sturen";
$l['alert_new_pms'] = "Een waarschuwing weergeven zodra deze gebruiker een nieuw persoonlijk bericht heeft ontvangen";
$l['email_notify_new_pms'] = "Deze gebruiker een e-mailbericht sturen zodra hij een nieuw persoonlijk bericht heeft ontvangen";
$l['default_thread_subscription_mode'] = "Standaard methode voor abonneren op discussies";
$l['do_not_subscribe'] = "Niet abonneren";
$l['no_email_notification'] = "Wel abonneren, geen e-mailbericht ontvangen";
$l['instant_email_notification'] = "Wel abonneren, ook e-mailbericht ontvangen";
$l['messaging_and_notification'] = "Berichten en meldingen";
$l['use_default'] = "Standaard gebruiken";
$l['date_format'] = "Datumweergave";
$l['time_format'] = "Tijdweergave";
$l['time_zone'] = "Tijdzone";
$l['daylight_savings_time_correction'] = "Zomertijdcorrectie";
$l['automatically_detect'] = "Instellingen voor zomertijd automatisch aanpassen";
$l['always_use_dst_correction'] = "Altijd zomertijdcorrectie toepassen";
$l['never_use_dst_correction'] = "Nooit zomertijdcorrectie toepassen";
$l['date_and_time_options'] = "Datum- en tijdinstellingen voor deze gebruiker";
$l['show_threads_last_day'] = "Alleen discussies van de afgelopen 24 uur weergeven";
$l['show_threads_last_5_days'] = "Alleen discussies van de afgelopen 5 dagen weergeven";
$l['show_threads_last_10_days'] = "Alleen discussies van de afgelopen 10 dagen weergeven";
$l['show_threads_last_20_days'] = "Alleen discussies van de afgelopen 20 dagen weergeven";
$l['show_threads_last_50_days'] = "Alleen discussies van de afgelopen 50 dagen weergeven";
$l['show_threads_last_75_days'] = "Alleen discussies van de afgelopen 75 dagen weergeven";
$l['show_threads_last_100_days'] = "Alleen discussies van de afgelopen 100 dagen weergeven";
$l['show_threads_last_year'] = "Alleen discussies van het afgelopen jaar weergeven";
$l['show_all_threads'] = "Alle discussies weergeven";
$l['threads_per_page'] = "Aantal discussies per pagina";
$l['default_thread_age_view'] = "Standaard discussieweergave";
$l['forum_display_options'] = "Weergave-instellingen forum voor deze gebruiker";
$l['display_users_sigs'] = "Handtekeningen van gebruikers onder berichten weergeven";
$l['display_users_avatars'] = "Avatars van gebruikers naast berichten weergeven";
$l['show_quick_reply'] = "Het vak Snel reactie plaatsen op de weergavepagina van discussies weergeven";
$l['posts_per_page'] = "Aantal berichten per pagina";
$l['default_thread_view_mode'] = "Standaard discussieweergave";
$l['linear_mode'] = "Lineaire weergave";
$l['threaded_mode'] = "Geneste weergave";
$l['thread_view_options'] = "Weergave-instellingen discussie voor deze gebruiker";
$l['show_redirect'] = "Gebruikersvriendelijke doorverwijspagina's weergeven";
$l['show_code_buttons'] = "De MyCode-opmaakopties op de plaatsingspagina's weergeven";
$l['theme'] = "Thema";
$l['board_language'] = "Taal";
$l['other_options'] = "Overige instellingen voor deze gebruiker";
$l['signature_desc'] = "Emoticons zijn {1}, MyCode is {2}, [img]-tags zijn {3}, HTML-code is {4}";
$l['enable_sig_in_all_posts'] = "Deze handtekening weergeven onder alle berichten van deze gebruiker";
$l['disable_sig_in_all_posts'] = "Geen handtekening weergeven onder alle berichten van deze gebruiker";
$l['do_nothing'] = "Uw huidige handtekeningsinstellingen behouden";
$l['signature_preferences'] = "Handtekeningsinstellingen";

$l['username'] = "Gebruikersnaam";
$l['email'] = "E-mailadres";
$l['primary_group'] = "Eerste gebruikersgroep";
$l['additional_groups'] = "Extra gebruikersgroepen";
$l['registered'] = "Lid sinds";
$l['last_active'] = "Laatst actief";
$l['post_count'] = "Aantal berichten";
$l['reputation'] = "Reputatie";
$l['warning_level'] = "Waarschuwingsniveau";
$l['registration_ip'] = "IP-adres bij registratie";
$l['last_known_ip'] = "Laatst gebruikte IP-adres";
$l['registration_date'] = "Datum van registratie";

$l['avatar_gallery'] = "Avatargalerij";
$l['current_avatar'] = "Huidige avatar";
$l['user_current_using_uploaded_avatar'] = "Deze gebruiker gebruikt momenteel een ge&uuml;ploade avatar.";
$l['user_current_using_gallery_avatar'] = "Deze gebruiker gebruikt momenteel een avatar uit de avatargalerij.";
$l['user_currently_using_remote_avatar'] = "Deze gebruiker gebruikt momenteel een elders geplaatste avatar.";
$l['max_dimensions_are'] = "Maximale grootte in pixels:";
$l['avatar_max_size'] = "Maximale bestandsgrootte:";
$l['remove_avatar'] = "Huidige avatar verwijderen";
$l['avatar_desc'] = "U kunt hier de <a href=\"http://nl.wikipedia.org/wiki/Avatar_(nickname)\" target=\"_blank\">avatar</a> van deze gebruiker beheren.<br /><br /><br />";
$l['avatar_auto_resize'] = "Als een ge&uuml;ploade avatar te groot is, dan zal hij automatisch worden verkleind.";
$l['attempt_to_auto_resize'] = "Proberen deze avatar te verkleinen als hij te groot is";
$l['specify_custom_avatar'] = "Eigen avatar opgeven";
$l['upload_avatar'] = "Avatar uploaden";
$l['or_specify_avatar_url'] = "of locatie voor avatar invoeren";
$l['or_select_avatar_gallery'] = "of een keuze maken uit de avatargalerij";

$l['ip_addresses'] = "IP-adressen";
$l['ip_address'] = "IP-adres";
$l['show_users_regged_with_ip'] = "Alle gebruikers weergeven die zich met dit IP-adres geregistreerd hebben";
$l['show_users_posted_with_ip'] = "Alle gebruikers weergeven die met dit IP-adres berichten geplaatst hebben";
$l['ban_ip'] = "IP-adres verbannen";
$l['ip_address_for'] = "IP-adressen voor";

$l['source_account'] = "Brongebruiker";
$l['source_account_desc'] = "Deze gebruiker zal worden samengevoegd met de doelgebruiker, waarna hij zal worden verwijderd.";
$l['destination_account'] = "Doelgebruiker";
$l['destination_account_desc'] = "Deze gebruiker zal overblijven nadat de twee gebruikers zijn samengevoegd.";
$l['merge_user_accounts'] = "Gebruikers samenvoegen";

$l['display_options'] = "Weergaveopties";
$l['ascending'] = "oplopende volgorde";
$l['descending'] = "aflopende volgorde";
$l['sort_results_by'] = "Resultaten sorteren op";
$l['in'] = "in";
$l['results_per_page'] = "Aantal resultaten per pagina";
$l['display_results_as'] = "Resultaten weergeven met";
$l['business_card'] = "Visitekaartjes";
$l['views'] = "Weergavecriteria";
$l['views_desc'] = "U kunt hier de weergavecriteria beheren. U kunt daarmee snel alle gebruikers weergeven die voldoen aan een bepaald criterium.";
$l['manage_views'] = "Weergavecriteria beheren";
$l['none'] = "Geen";
$l['search'] = "Zoeken";

$l['edit_profile_and_settings'] = "Profiel en instellingen bewerken";
$l['ban_user'] = "Gebruiker verbannen";
$l['approve_coppa_user'] = "COPPA-gebruiker activeren";
$l['approve_user'] = "Gebruiker activeren";
$l['delete_user'] = "Gebruiker verwijderen";
$l['show_referred_users'] = "Doorverwezen gebruikers weergeven";
$l['show_attachments'] = "Bijlagen weergeven";
$l['table_view'] = "Tabel";
$l['card_view'] = "Visitekaartjes";

$l['find_users_where'] = "Naar gebruikers zoeken";
$l['username_contains'] = "Gebruikersnaam bevat";
$l['email_address_contains'] = "E-mailadres bevat";
$l['is_member_of_groups'] = "Is lid van een van de volgende groepen";
$l['website_contains'] = "Locatie van website bevat";
$l['icq_number_contains'] = "ICQ-nummer bevat";
$l['aim_handle_contains'] = "AIM-schermnaam bevat";
$l['yahoo_contains'] = "Yahoo! ID bevat";
$l['msn_contains'] = "Windows Live-adres bevat";
$l['signature_contains'] = "Handtekening bevat";
$l['user_title_contains'] = "Eigen gebruikerstitel bevat";
$l['greater_than'] = "is meer dan";
$l['is_exactly'] = "is precies";
$l['less_than'] = "is minder dan";
$l['post_count_is'] = "Aantal geplaatste berichten";
$l['reg_ip_matches'] = "IP-adres bij registratie";
$l['wildcard'] = "U kunt het jokerteken * gebruiken.";
$l['last_known_ip'] = "Laatst gebruikte IP-adres is";
$l['posted_with_ip'] = "Heeft berichten geplaatst met IP-adres";

$l['view'] = "Weergavecriterium";
$l['create_new_view'] = "Weergavecriterium toevoegen";
$l['create_new_view_desc'] = "U kunt hier een weergavecriterium toevoegen. U kunt aangeven waaraan gebruikers die u wilt weergeven moeten voldoen, welke velden u wilt weergeven en waarop u wilt sorteren.";
$l['view_manager'] = "Weergavecriteria beheren";
$l['set_as_default_view'] = "Als standaard weergavecriterium instellen";
$l['enabled'] = "Weergeven";
$l['disabled'] = "Niet weergeven";
$l['fields_to_show'] = "Velden";
$l['fields_to_show_desc'] = "Velden kiezen en sorteren</label> <div class=\"description\">Velden die u wel wilt weergeven sleept u naar de linkerkolom. Velden die u niet wilt weergeven sleept u naar de rechterkolom. U kunt de volgorde waarin velden worden weergegeven aanpassen door velden in de linkerkolom naar boven of naar beneden te slepen.</div><label>";
$l['edit_view'] = "Weergavecriterium bewerken";
$l['edit_view_desc'] = "U kunt hier aangeven waaraan gebruikers die u wilt weergeven moeten voldoen, welke velden u wilt weergeven en waarop u wilt sorteren.";
$l['private'] = "Persoonlijk";
$l['private_desc'] = "</label>Dit weergavecriterium is alleen zichtbaar voor u<label>";
$l['public'] = "Openbaar";
$l['public_desc'] = "</label>Dit weergavecriterium is zichtbaar voor alle beheerders<label>";
$l['visibility'] = "Zichtbaarheid";
$l['save_view'] = "Weergavecriterium opslaan";
$l['created_by'] = "Gemaakt door";
$l['default'] = "Standaard";
$l['this_is_a_view'] = "Dit is een {1} weergavecriterium";
$l['set_as_default'] = "Als standaard instellen";
$l['delete_view'] = "Weergavecriterium verwijderen";
$l['default_view_desc'] = "Standaard weergavecriterium gemaakt door MyBB. Dit criterium kan niet worden bewerkt of verwijderd.";
$l['public_view_desc'] = "Openbaar weergavecriterium";
$l['private_view_desc'] = "Persoonlijk weergavecriterium";
$l['table'] = "Tabel";
$l['title'] = "Naam";

$l['view_title_1'] = "Alle gebruikers";

?>