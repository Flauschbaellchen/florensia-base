<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: tools_tasks.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */

$l['task_manager'] = "Gestion des tâches";
$l['add_new_task'] = "Ajouter une tâche";
$l['add_new_task_desc'] = "Vous pouvez créer des tâches planifiées qui s'exécuteront automatiquement sur le forum.";
$l['edit_task'] = "Modifier la tâche";
$l['edit_task_desc'] = "Ci-dessous, vous pouvez modifier les paramètres pour cette tâche planifiée.";
$l['task_logs'] = "Suivis de tâches";
$l['view_task_logs'] = "Voir les suivis de tâches";
$l['view_task_logs_desc'] = "Quand une tâche est en cours d'exécution et que les suivis sont activés, tous les résultats ou erreurs seront affichés plus bas. Les entrées qui dépasseront 30 jours seront automatiquement supprimées.";
$l['scheduled_tasks'] = "Tâches planifiées";
$l['scheduled_tasks_desc'] = "Gestion des tâches qui s'exécutent automatiquement sur le forum. Pour exécuter une tâche immédiatement, cliquez sur la petite icône à droite de la tâche.";

$l['title'] = "Titre";
$l['short_description'] = "Description";
$l['task_file'] = "Fichier de tâche";
$l['task_file_desc'] = "Sélectionnez le fichier qui sera utilisé par la tâche.";
$l['time_minutes'] = "Temps : Minutes";
$l['time_minutes_desc'] = "Mettez une virgule pour séparer les listes de minutes (0-59) durant lesquelles cette tâche devra s'exécuter. Mettez '*' si cette tâche doit s'exécuter toutes les minutes.";
$l['time_hours'] = "Temps : Heures";
$l['time_hours_desc'] = "Mettez une virgule pour séparer les listes d'heures (0-23) durant lesquelles cette tâche devra s'exécuter. Mettez '*' si cette tâche doit s'exécuter toutes les heures.";
$l['time_days_of_month'] = "Temps : Jours du mois";
$l['time_days_of_month_desc'] = "Mettez une virgule pour séparer les listes de jours (1-31) durant lesquels cette tâche devra s'exécuter. Mettez '*' si cette tâche doit s'exécuter tous les jours, ou si vous spécifiez un jour dans la liste dessous.";
$l['every_weekday'] = "Tous les jours";
$l['sunday'] = "Dimanche";
$l['monday'] = "Lundi";
$l['tuesday'] = "Mardi";
$l['wednesday'] = "Mercredi";
$l['thursday'] = "Jeudi";
$l['friday'] = "Vendredi";
$l['saturday'] = "Samedi";
$l['time_weekdays'] = "Temps : Jours de la semaine";
$l['time_weekdays_desc'] = "Sélectionnez tous les jours durant lesquels cette tâche devra s'exécuter. Appuyez sur CTRL pour sélectionner plusieurs jours. Sélectionnez 'Tous les jours de la semaine' si vous voulez que cette tâche s'exécute tous les jours, ou si vous avez défini une journée précise dans le champ précédent.";
$l['every_month'] = "Tous les mois";
$l['time_months'] = "Temps : Mois";
$l['time_months_desc'] = "Sélectionnez tous les mois durant lesquels cette tâche devra s'exécuter. Appuyez sur CTRL pour sélectionner plusieurs mois. Sélectionnez 'Tous les mois' pour que cette tâche s'exécute tous les mois.";
$l['enabled'] = "Tâche activée ?";
$l['enable_logging'] = "Activer le suivi ?";
$l['save_task'] = "Sauvegarder la tâche";
$l['task'] = "Tâche";
$l['date'] = "Date";
$l['data'] = "Données";
$l['no_task_logs'] = "Il n'y a pas de suivi enregistré pour l'une des tâches planifiées.";
$l['next_run'] = "Prochaine exécution";
$l['run_task_now'] = "Lancer cette tâche maintenant";
$l['run_task'] = "Lancer la tâche";
$l['disable_task'] = "Désactiver la tâche";
$l['enable_task'] = "Activer la tâche";
$l['delete_task'] = "Supprimer la tâche";
$l['alt_enabled'] = "Activé";
$l['alt_disabled'] = "Désactivé";

$l['error_invalid_task'] = "La tâche spécifiée n'existe pas.";
$l['error_missing_title'] = "Vous n'avez pas entré de titre pour la tâche planifiée";
$l['error_missing_description'] = "Vous n'avez pas entré de description pour cette tâche planifiée";
$l['error_invalid_task_file'] = "Le fichier de tâche sélectionné n'existe pas.";
$l['error_invalid_minute'] = "Le nombre de minutes proposé est incorrect.";
$l['error_invalid_hour'] = "Le nombre d'heures proposé est incorrect.";
$l['error_invalid_day'] = "Le jour proposé est incorrect.";
$l['error_invalid_weekday'] = "Le jour de la semaine proposé est incorrect.";
$l['error_invalid_month'] = "Le mois sélectionné est incorrect.";

$l['success_task_created'] = "La tâche a été créée.";
$l['success_task_updated'] = "La tâche sélectionnée a été mise à jour.";
$l['success_task_deleted'] = "La tâche sélectionnée a été supprimée.";
$l['success_task_enabled'] = "La tâche sélectionnée a été activée.";
$l['success_task_disabled'] = "La tâche sélectionnée a été désactivée.";
$l['success_task_run'] = "La tâche sélectionnée a été exécutée.";

$l['confirm_task_deletion'] = "Êtes-vous sûr de vouloir supprimer cette tâche planifiée ?";
$l['confirm_task_enable'] = "<strong>ATTENTION :</strong> Vous êtes sur le point d'activer une tâche qui est destinée à être activée via un planificateur (Rendez-vous sur le <a href=\"http://wiki.mybboard.net/\" target=\"_blank\">MyBB Wiki</a> pour plus d'informations en anglais). Continuer ?";

?>