<?php
/**
 * MyBB 1.6 Dutch Language Pack
 * Zie dutch.php voor versieinformatie
 *
 * Nederlands taalpakket voor MyBB
 * Nederlandse vertaling door Tom Huls (Tochjo)
 */

$l['task_manager'] = "Taken beheren";
$l['add_new_task'] = "Taak toevoegen";
$l['add_new_task_desc'] = "U kunt hier een nieuwe taak toevoegen. Geplande taken worden automatisch uitgevoerd.";
$l['edit_task'] = "Taak bewerken";
$l['edit_task_desc'] = "U kunt hieronder de instellingen voor deze taak wijzigen.";
$l['task_logs'] = "Logboek";
$l['view_task_logs'] = "Logboek bekijken";
$l['view_task_logs_desc'] = "U kunt hier bekijken wat de resultaten van een taak zijn of welke fouten zich hebben voorgedaan als het bijhouden van een logboek is ingeschakeld. Vermeldingen ouder dan 30 dagen worden automatisch verwijderd.";
$l['scheduled_tasks'] = "Geplande taken";
$l['scheduled_tasks_desc'] = "U kunt hier de taken beheren die automatisch worden uitgevoerd. Om een taak nu uit te voeren klikt u op het icoon rechts van de taak.";

$l['title'] = "Naam";
$l['short_description'] = "Omschrijving";
$l['task_file'] = "Taakbestand";
$l['task_file_desc'] = "U kunt hier het taakbestand selecteren dat u wilt uitvoeren.";
$l['time_minutes'] = "Tijd: minuten";
$l['time_minutes_desc'] = "U kunt hier aangeven op welke minuten van het uur (0-59) deze taak moet worden uitgevoerd. U kunt meerdere waarden scheiden door een komma. U kunt * invoeren als deze taak elke minuut moet worden uitgevoerd.";
$l['time_hours'] = "Tijd: uren";
$l['time_hours_desc'] = "U kunt hier aangeven op welke uren van de dag (0-23) deze taak moet worden uitgevoerd. U kunt meerdere waarden scheiden door een komma. U kunt * invoeren als deze taak elk uur moet worden uitgevoerd.";
$l['time_days_of_month'] = "Tijd: dagen van de maand";
$l['time_days_of_month_desc'] = "U kunt hier aangeven op welke dagen van de maand (1-31) deze taak moet worden uitgevoerd. U kunt meerdere waarden scheiden door een komma. U kunt * invoeren als deze taak elke dag of elke week op dezelfde dag (zie hieronder) moet worden uitgevoerd.";
$l['every_weekday'] = "Elke werkdag";
$l['sunday'] = "Zondag";
$l['monday'] = "Maandag";
$l['tuesday'] = "Dinsdag";
$l['wednesday'] = "Woensdag";
$l['thursday'] = "Donderdag";
$l['friday'] = "Vrijdag";
$l['saturday'] = "Zaterdag";
$l['time_weekdays'] = "Tijd: dagen van de week";
$l['time_weekdays_desc'] = "U kunt hier aangeven op welke dagen van de week deze taak moet worden uitgevoerd. U kunt de Control-toets gebruiken om meerdere dagen te selecteren. Houd dan de toets ingedrukt en klik meerdere dagen aan. Selecteer 'Elke werkdag' als deze taak elke dag van de week of op een beperkt aantal dagen van de maand (zie hierboven) moet worden uitgevoerd.";
$l['every_month'] = "Elke maand";
$l['time_months'] = "Time: maanden";
$l['time_months_desc'] = "U kunt hier aangeven op welke maanden van het jaar deze taak moet worden uitgevoerd. U kunt de Control-toets gebruiken om meerdere maanden te selecteren. Houd dan de toets ingedrukt en klik meerdere maanden aan. Selecteer 'Elke maand' als deze taak elke maand moet worden uitgevoerd.";
$l['enabled'] = "Inschakelen";
$l['enable_logging'] = "Logboek bijhouden";
$l['save_task'] = "Taak opslaan";
$l['task'] = "Taak";
$l['date'] = "Datum";
$l['data'] = "Gegevens";
$l['no_task_logs'] = "Er zijn momenteel geen vermeldingen voor de opgegeven taken.";
$l['next_run'] = "Volgende keer uitvoeren op";
$l['run_task_now'] = "Deze taak nu uitvoeren";
$l['run_task'] = "Taak uitvoeren";
$l['disable_task'] = "Taak uitschakelen";
$l['enable_task'] = "Taak inschakelen";
$l['delete_task'] = "Taak verwijderen";
$l['alt_enabled'] = "Ingeschakeld";
$l['alt_disabled'] = "Uitgeschakeld";

$l['error_invalid_task'] = "U hebt een ongeldige taak opgegeven";
$l['error_missing_title'] = "U hebt geen naam ingevoerd.";
$l['error_missing_description'] = "U hebt geen omschrijving ingevoerd.";
$l['error_invalid_task_file'] = "U hebt een ongeldig taakbestand opgegeven.";
$l['error_invalid_minute'] = "U hebt een ongeldige minuut ingevoerd.";
$l['error_invalid_hour'] = "U hebt een ongeldig uur ingevoerd.";
$l['error_invalid_day'] = "U hebt een ongeldige dag van de maand ingevoerd.";
$l['error_invalid_weekday'] = "U hebt een ongeldige dag van de week opgegeven.";
$l['error_invalid_month'] = "U hebt een ongeldige maand opgegeven.";

$l['success_task_created'] = "De taak is toegevoegd.";
$l['success_task_updated'] = "De taak is bijgewerkt.";
$l['success_task_deleted'] = "De taak is verwijderd.";
$l['success_task_enabled'] = "De taak is ingeschakeld.";
$l['success_task_disabled'] = "De taak is uitgeschakeld.";
$l['success_task_run'] = "De taak is uitgevoerd.";

$l['confirm_task_deletion'] = "Weet u zeker dat u deze taak wilt verwijderen?";
$l['confirm_task_enable'] = "<strong>Waarschuwing:</strong> u staat op het punt een taak in te schakelen die alleen via cron uitgevoerd hoort te worden. Bekijk de <a href=\"http://wiki.mybboard.net/\" target=\"_blank\">MyBB Wiki</a> voor meer informatie. Wilt u doorgaan?";

?>