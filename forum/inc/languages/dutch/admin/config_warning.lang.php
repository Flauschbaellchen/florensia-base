<?php
/**
 * MyBB 1.6 Dutch Language Pack
 * Zie dutch.php voor versieinformatie
 *
 * Nederlands taalpakket voor MyBB
 * Nederlandse vertaling door Tom Huls (Tochjo)
 */
 
$l['warning_system'] = "Waarschuwingssysteem";
$l['warning_types'] = "Voorgedefinieerde waarschuwingen";
$l['warning_types_desc'] = "U kunt hier de voorgedefinieerde waarschuwingen beheren die kunnen worden toegekend. Als u een gebruiker wilt waarschuwen, kunt u snel een voorgedefinieerde waarschuwing kiezen. De reden voor de waarschuwing, het aantal punten dat zal worden uitgedeeld en wanneer de waarschuwing zal verlopen zijn dan al ingevuld. U hoeft bij het waarschuwen van een gebruiker niet te kiezen uit de voorgedefinieerde waarschuwingen; u kunt altijd per geval een andere, niet-voordefinieerde reden voor de waarschuwing invullen en bepalen hoeveel punten u wilt uitdelen en wanneer de waarschuwing zal verlopen.";
$l['add_warning_type'] = "Toevoegen";
$l['add_warning_type_desc'] = "U kunt hier een voorgedefinieerde waarschuwing toevoegen. Als u een gebruiker wilt waarschuwen, kunt u snel zo'n voorgedefinieerde waarschuwing kiezen. De reden voor de waarschuwing, het aantal punten dat zal worden uitgedeeld en wanneer de waarschuwing zal verlopen zijn dan al ingevuld. U hoeft bij het waarschuwen van een gebruiker niet te kiezen uit de voorgedefinieerde waarschuwingen; u kunt altijd per geval een andere, niet-voordefinieerde reden voor de waarschuwing invullen, bepalen hoeveel punten u wilt uitdelen en wanneer de waarschuwing zal verlopen.";
$l['edit_warning_type'] = "Voorgedefinieerde aarschuwing bewerken";
$l['edit_warning_type_desc'] = "U kunt hier deze voorgedefinieerde waarschuwing bewerken. Als u een gebruiker wilt waarschuwen, kunt u snel zo'n voorgedefinieerde waarschuwing kiezen. De reden voor de waarschuwing, het aantal punten dat zal worden uitgedeeld en wanneer de waarschuwing zal verlopen zijn dan al ingevuld.";
$l['warning_levels'] = "Waarschuwingsniveaus";
$l['warning_levels_desc'] = "Als een gebruiker een waarschuwingsniveau (percentage van het maximale aantal uit te delen punten) bereikt, kan hij automatisch worden verbannen of kunnen zijn rechten worden beperkt.";
$l['add_warning_level'] = "Niveau toevoegen";
$l['add_warning_level_desc'] = "U kunt hier een waarschuwingsniveau toevoegen. Als een gebruiker zo'n niveau (percentage van het maximale aantal uit te delen punten) bereikt, kan hij automatisch worden verbannen of kunnen zijn rechten worden beperkt.";
$l['edit_warning_level'] = "Niveau bewerken";
$l['edit_warning_level_desc'] = "Als een gebruiker een waarschuwingsniveau (percentage van het maximale aantal uit te delen punten) bereikt, kan hij automatisch worden verbannen of kunnen zijn rechten worden beperkt.";

$l['percentage'] = "Percentage";
$l['action_to_take'] = "Actie die wordt ondernomen";
$l['move_banned_group'] = "De gebruiker {1} {2} naar de verbannen groep {1} verplaatsen";
$l['move_banned_group_permanent'] = "De gebruiker voor altijd naar de verbannen groep {1} verplaatsen";
$l['suspend_posting'] = "De gebruiker {1} {2} het recht om berichten te plaatsen ontnemen";
$l['suspend_posting_permanent'] = "De gebruiker het recht om berichten te plaatsen voor altijd ontnemen";
$l['moderate_new_posts'] = "Berichten van deze gebruiker moeten {1} {2} worden goedgekeurd voordat ze zichtbaar zijn";
$l['moderate_new_posts_permanent'] = "Berichten van deze gebruiker moeten voor altijd worden goedgekeurd";
$l['no_warning_levels'] = "Er zijn momenteel geen waarschuwingsniveaus.";

$l['warning_type'] = "Voorgedefinieerde waarschuwing";
$l['points'] = "Punten";
$l['expires_after'] = "Verloopt na";
$l['no_warning_types'] = "Er zijn momenteel geen voorgedefinieerde waarschuwingen.";

$l['warning_points_percentage'] = "Percentage";
$l['warning_points_percentage_desc'] = "U kunt hier invullen bij welk percentage van het maximale aantal te behalen punten er actie moet worden ondernomen. Dit percentage moet een geheel getal zijn dat minstens 1 en maximaal 100 is.";
$l['action_to_be_taken'] = "Actie die moet worden ondernomen";
$l['action_to_be_taken_desc'] = "U kunt hier aangeven welke actie moet worden ondernomen als een gebruiker het bovenstaande percentage bereikt.";
$l['ban_user'] = "Gebruiker verbannen";
$l['banned_group'] = "Verbannen groep:";
$l['ban_length'] = "Voor de duur van:";
$l['suspend_posting_privileges'] = "Gebruiker het recht om berichten te plaatsen ontnemen";
$l['suspension_length'] = "Voor de duur van:";
$l['moderate_posts'] = "Berichten van gebruiker eerst laten goedkeuren";
$l['moderation_length'] = "Voor de duur van:";
$l['save_warning_level'] = "Waarschuwingsniveau opslaan";

$l['title'] = "Reden";
$l['points_to_add'] = "Punten";
$l['points_to_add_desc'] = "U kunt hier invoeren hoeveel punten er moeten worden uitgedeeld voor deze waarschuwing.";
$l['warning_expiry'] = "Verlooptermijn";
$l['warning_expiry_desc'] = "U kunt hier aangeven hoe lang nadat de waarschuwing is gegeven deze moet verlopen.";
$l['save_warning_type'] = "Voorgedefinieerde waarschuwing opslaan";

$l['expiration_hours'] = "uur";
$l['expiration_days'] = "dagen";
$l['expiration_weeks'] = "weken";
$l['expiration_months'] = "maanden";
$l['expiration_never'] = "voor altijd";
$l['expiration_permanent'] = "voor altijd";

$l['error_invalid_warning_level'] = "Het opgegeven waarschuwingsniveau bestaat niet.";
$l['error_invalid_warning_percentage'] = "U hebt geen geldig percentage ingevoerd. Uw percentage moet een geheel getal zijn dat minstens 1 en maximaal 100 is.";
$l['error_invalid_warning_type'] = "De opgegeven voorgedefinieerde waarschuwing bestaat niet.";
$l['error_missing_type_title'] = "U hebt geen reden voor deze waarschuwing ingevoerd.";
$l['error_missing_type_points'] = "U hebt geen geldig aantal punten ingevoerd. Het aantal moet een geheel getal zijn dat groter is dan 0 en kleiner dan {1}.";

$l['success_warning_level_created'] = "Het waarschuwingsniveau is toegevoegd.";
$l['success_warning_level_updated'] = "Het waarschuwingsniveau is bijgewerkt.";
$l['success_warning_level_deleted'] = "Het waarschuwingsniveau is verwijderd.";
$l['success_warning_type_created'] = "De voorgedefinieerde waarschuwing is toegevoegd.";
$l['success_warning_type_updated'] = "De voorgedefinieerde waarschuwing is bijgewerkt.";
$l['success_warning_type_deleted'] = "De voorgedefinieerde waarschuwing is verwijderd.";

$l['confirm_warning_level_deletion'] = "Weet u zeker dat u dit waarschuwingsniveau wilt verwijderen?";
$l['confirm_warning_type_deletion'] = "Weet u zeker dat u deze voorgedefinieerde waarschuwing wilt verwijderen?";

?>