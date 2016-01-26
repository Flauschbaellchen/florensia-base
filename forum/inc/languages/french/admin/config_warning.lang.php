<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright Œ 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: config_warning.lang.php 4941 2010-05-15 18:17:38Z RyanGordon $
 */
 
$l['warning_system'] = "Système d'avertissements";
$l['warning_types'] = "Types d'avertissements";
$l['warning_types_desc'] = "Ici vous pouvez gérer la liste des différents types d'avertissements que l'équipe est autorisée à délivrer à l'utilisateur.";
$l['add_warning_type'] = "Ajouter un type d'avertissement";
$l['add_warning_type_desc'] = "Ici vous pouvez créer un nouveau type d'avertissement prédéfini. Les types d'avertissements sont sélectionnables lorsque vous avertissez les utilisateurs et vous pouvez déterminer le nombre de points à ajouter pour ce type ainsi que le délai avant qu'un avertissement de ce type n'expire.";
$l['edit_warning_type'] = "Éditer le type d'avertissement";
$l['edit_warning_type_desc'] = "Ici vous pouvez éditer ce type d'avertissement. Les types d'avertissements sont sélectionnables lorsque vous avertissez les utilisateurs et vous pouvez déterminer le nombre de points à ajouter pour ce type ainsi que le délai avant qu'un avertissement de ce type n'expire.";
$l['warning_levels'] = "Niveaux d'avertissements";
$l['warning_levels_desc'] = "Les niveaux d'avertissements définissent ce qui se passe quand les utilisateurs ont  atteint un niveau d'avertissement particulier (pourcentage du maximum de points d'avertissement). Vous pouvez bannir des utilisateurs ou suspendre leurs privilèges.";
$l['add_warning_level'] = "Ajouter un niveau d'avertissement";
$l['add_warning_level_desc'] = "Ici vous pouvez créer un nouveau niveau d'avertissement. Les niveaux d'avertissement sont les mesures à prendre contre les utilisateurs lorsqu'ils atteignent un certain pourcentage du niveau d'avertissement maximal.";
$l['edit_warning_level'] = "Éditer un niveau d'avertissement";
$l['edit_warning_level_desc'] = "Les niveaux d'avertissement sont les mesures à prendre contre les utilisateurs lorsqu'ils atteignent un certain pourcentage du niveau d'avertissement maximal.";

$l['percentage'] = "Pourcentage";
$l['action_to_take'] = "Mesure à prendre";
$l['move_banned_group'] = "Déplacer dans le groupe bannis ({1}) pour {2} {3}";
$l['move_banned_group_permanent'] = "Déplacer dans le groupe bannis ({1}) Définitivement";
$l['suspend_posting'] = "Suspendre les privilèges de messages pour {1} {2}";
$l['suspend_posting_permanent'] = "Suspendre définitivement les privilèges de messages";
$l['moderate_new_posts'] = "Modérer les nouveaux messages pour {1} {2}";
$l['moderate_new_posts_permanent'] = "Modérer définitivement les nouveaux messages";
$l['no_warning_levels'] = "Il n'y a actuellement aucun niveau d'avertissement sur votre forum.";

$l['warning_type'] = "Type d'avertissement";
$l['points'] = "Points";
$l['expires_after'] = "Expire après";
$l['no_warning_types'] = "Il n'y a actuellement aucun type d'avertissement sur votre forum.";

$l['warning_points_percentage'] = "Pourcentage maximum de points d'avertissements";
$l['warning_points_percentage_desc'] = "Veuillez entrer une valeur numérique entre 1 et 100.";
$l['action_to_be_taken'] = "Mesure à prendre";
$l['action_to_be_taken_desc'] = "Sélectionnez la mesure que vous voulez prendre quand des utilisateurs ont atteint le niveau ci-dessus.";
$l['ban_user'] = "Bannir l'utilisateur";
$l['banned_group'] = "Groupe de Bannis :";
$l['ban_length'] = "Durée du bannissement :";
$l['suspend_posting_privileges'] = "Suspendre les privilèges de messages";
$l['suspension_length'] = "Durée de suspension :";
$l['moderate_posts'] = "Modérer les messages";
$l['moderation_length'] = "Durée de modération :";
$l['save_warning_level'] = "Enregistrer le niveau d'avertissement";

$l['title'] = "Titre";
$l['points_to_add'] = "Points à ajouter";
$l['points_to_add_desc'] = "Le nombre de points à ajouter au niveau d'avertissement des utilisateurs.";
$l['warning_expiry'] = "Expiration de l'avertissement";
$l['warning_expiry_desc'] = "Une fois que l'avertissement a été délivré, après combien de temps voulez-vous qu'il expire ?";
$l['save_warning_type'] = "Enregistrer le type d'avertissement";

$l['expiration_hours'] = "Heure(s)";
$l['expiration_days'] = "Jour(s)";
$l['expiration_weeks'] = "Semaine(s)";
$l['expiration_months'] = "Mois(s)";
$l['expiration_never'] = "Jamais";
$l['expiration_permanent'] = "Permanent";

$l['error_invalid_warning_level'] = "Le niveau d'avertissement spécifié n'existe pas.";
$l['error_invalid_warning_percentage'] = "Vous n'avez pas entré une valeur de pourcentage correcte pour ce niveau d'avertissement. La valeur de votre pourcentage doit être entre 1 et 100.";
$l['error_invalid_warning_type'] = "Le type d'avertissement spécifié n'existe pas.";
$l['error_missing_type_title'] = "Vous n'avez pas entré de titre pour ce type d'avertissement.";
$l['error_missing_type_points'] = "Vous n'avez pas entré un nombre de points correct à ajouter quand on délivre un avertissement de ce type. Vous devez entrer un nombre supérieur à 0 mais inférieur à {1}.";

$l['success_warning_level_created'] = "Le niveau d'avertissement a été créé.";
$l['success_warning_level_updated'] = "Le niveau d'avertissement a été mis à jour.";
$l['success_warning_level_deleted'] = "Le niveau d'avertissement sélectionné a été supprimé.";
$l['success_warning_type_created'] = "Le type d'avertissement a été créé.";
$l['success_warning_type_updated'] = "Le type d'avertissement a été mis à jour.";
$l['success_warning_type_deleted'] = "Le type d'avertissement sélectionné a été supprimé.";

$l['confirm_warning_level_deletion'] = "Êtes-vous sûr de vouloir supprimer ce niveau d'avertissement ?";
$l['confirm_warning_type_deletion'] = "Êtes-vous sûr de vouloir supprimer ce type d'avertissement ?";

?>