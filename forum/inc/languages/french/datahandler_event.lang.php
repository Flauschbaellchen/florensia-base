<?php
/**
 * MyBB 1.6 English Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 *
 * $Id: datahandler_event.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */

$l['eventdata_missing_name'] = "Le nom de l'évènement est manquant. Veuillez en entrer un.";
$l['eventdata_missing_description'] = "La description de l'évènement est manquante. Veuillez en entrer une.";

$l['eventdata_invalid_start_date'] = "La date de début d'évènement que vous avez entrée est invalide. Vous devez vous assurer de spécifier le jour, le mois et l'année et aussi que le jour entré est valide pour ce mois en particulier.";
$l['eventdata_invalid_start_year'] = "Les évènements peuvent seulement être créés pour les 5 prochaines années. Veuillez choisir une année de début raisonnable à partir de la liste.";
$l['eventdata_invalid_start_month'] = "Le mois de début que vous avez entré n'est pas un mois valide. Veuillez entrer un mois de début valide.";

$l['eventdata_invalid_end_date'] = "L'évènement et la date que vous avez entrés sont invalides. Vous devez vous assurer de spécifier le jour, le mois et l'année et aussi que le jour entré est valide pour ce mois en particulier.";
$l['eventdata_invalid_end_year'] = "Les évènements peuvent seulement être créés pour les 5 prochaines années. Veuillez choisir une année de fin raisonnable à partir de la liste.";
$l['eventdata_invalid_end_month'] = "Le mois de fin que vous avez entré n'est pas un mois valide. Veuillez entrer un mois de fin valide.";
$l['eventdata_invalid_end_day'] = "Le jour de fin que vous avez entré n'est pas un jour valide. Le jour que vous avez choisi est probablement plus grand que le nombre de jours de ce mois.";

$l['eventdata_cant_specify_one_time'] = "Si vous entrez l'heure de début d'un évènement, vous devez entrer l'heure de fin de cet évènement.";
$l['eventdata_start_time_invalid'] = "L'heure de début que vous avez entrée est invalide. Des exemples valides sont 12am, 12:01am, 00:01.";
$l['eventdata_end_time_invalid'] = "L'heure de fin que vous avez entrée est invalide. Des exemples valides sont 12am, 12:01am, 00:01.";
$l['eventdata_invalid_timezone'] = "Le fuseau horaire que vous avez choisi pour cet évènement est invalide.";
$l['eventdata_end_in_past'] = "La date ou l'heure de fin pour votre évènement est antérieure à la date ou l'heure de début.";

$l['eventdata_only_ranged_events_repeat'] = "Seulement des évènements étendus (évènements  avec une date de début et de fin) peuvent être répétés.";
$l['eventdata_invalid_repeat_day_interval'] = "Vous avez entré un intervalle de répétition de jour invalide.";
$l['eventdata_invalid_repeat_week_interval'] = "Vous avez entré un intervalle de répétition de semaine invalide.";
$l['eventdata_invalid_repeat_weekly_days'] = "Vous n'avez choisi aucun jour de la semaine pour déclencher cet évènement.";
$l['eventdata_invalid_repeat_month_interval'] = "Vous avez entré un intervalle de répétition de mois invalide.";
$l['eventdata_invalid_repeat_year_interval'] = "Vous avez entré un intervalle de répétition d'année invalide.";
$l['eventdata_event_wont_occur'] = "En utilisant les heures de début et de fin avec les paramètres de répétition d'évènement, cet évènement ne se produira pas.";

$l['eventdata_no_permission_private_event'] = "Vous n'avez pas la permission de poster des évènements privés.";
?>