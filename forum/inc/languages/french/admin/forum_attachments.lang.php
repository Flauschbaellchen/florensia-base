<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 *
 * $Id: forum_attachments.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */

// Tabs
$l['attachments'] = "Pièces jointes";
$l['stats'] = "Statistiques";
$l['find_attachments'] = "Rechercher des pièces jointes";
$l['find_attachments_desc'] = "En utilisant le système de recherche de pièces jointes, vous pouvez chercher des fichiers spécifiques que les utilisateurs ont joint dans vos forums. Commencez par entrer quelques termes de recherche ci-dessous. Tous les champs sont optionnels et ne seront pas inclus à moins qu'ils ne contiennent une valeur.";
$l['find_orphans'] = "Rechercher des pièces jointes orphelines";
$l['find_orphans_desc'] = "Les pièces jointes orphelines sont des pièces jointes qui pour une raison quelconque sont absentes de la base de données ou du système de fichiers. Cet utilitaire vous aidera à les localiser et à les supprimer.";
$l['attachment_stats'] = "Statistiques de pièces jointes";
$l['attachment_stats_desc'] = "Ci-dessous vous trouverez quelques statistiques sur les pièces jointes actuellement sur votre forum.";

// Errors
$l['error_nothing_selected'] = "Sélectionnez une ou plusieurs pièces jointes à supprimer";
$l['error_no_attachments'] = "Il n'y a pas encore de pièce jointe sur votre forum. Dès qu'une pièce jointe aura été postée vous pourrez accéder à cette section.";
$l['error_not_all_removed'] = "Seules quelques pièces jointes orphelines ont été supprimées , les autres ne peuvent pas ête supprimées du répertoire uploads.";
$l['error_invalid_username'] = "Le nom d'utilisateur que vous avez entré est incorrect.";
$l['error_invalid_forums'] = "Un ou plusieurs forums que vous avez sélectionnés sont incorrects.";
$l['error_no_results'] = "Aucune pièce jointe n'a été trouvée avec le critère de recherche spécifié.";
$l['error_not_found'] = "La pièce jointe n'a pu être trouvée dans le répertoire uploads.";
$l['error_not_attached'] = "La pièce jointe a été uploadée il y a plus de 24 heures mais pas jointe à un message.";
$l['error_does_not_exist'] = "La discussion ou le message pour cette pièce jointe n'existe plus.";

// Success
$l['success_deleted'] = "Les pièces jointes sélectionnées ont été supprimées.";
$l['success_orphan_deleted'] = "Les pièces jointes orphelines sélectionnées ont été supprimées.";
$l['success_no_orphans'] = "Il n'y a aucune pièce jointe orpheline sur votre forum.";

// Confirm
$l['confirm_delete'] = "Êtes-vous sûr de vouloir supprimer les pièces jointes sélectionnées ?";

// == Pages
// = Stats
$l['general_stats'] = "Statistiques générales";
$l['stats_attachment_stats'] = "Pièces jointes - Statistiques pièces jointes";
$l['num_uploaded'] = "<strong>Nb. de pièces jointes uploadées</strong>";
$l['space_used'] = "<strong>Espace utilisé par les pièces jointes</strong>";
$l['bandwidth_used'] = "<strong>Utilisation de bande passante estimée</strong>";
$l['average_size'] = "<strong>Taille moyenne des pièces jointes</strong>";
$l['size'] = "Taille";
$l['posted_by'] = "Postée par";
$l['thread'] = "Discussion";
$l['downloads'] = "Téléchargements";
$l['date_uploaded'] = "Date d'upload";
$l['popular_attachments'] = "Top 5 des pièces jointes les plus populaires";
$l['largest_attachments'] = "Top 5 des pièces jointes les plus volumineuses";
$l['users_diskspace'] = "Top 5 des utilisateurs occupant le plus d'espace disque";
$l['username'] = "Nom d'utilisateur";
$l['total_size'] = "Taille totale";

// = Orphans
$l['orphan_results'] = "Recherche de pièces jointes orphelines - Résultats";
$l['orphan_attachments_search'] = "Recherche de pièces jointes orphelines";
$l['reason_orphaned'] = "Raison de l'orphelinat";
$l['reason_not_in_table'] = "Pas dans la table des pièces jointes.";
$l['reason_file_missing'] = "Fichier de pièce jointe manquant.";
$l['reason_thread_deleted'] = "La discussion a été supprimée.";
$l['reason_post_never_made'] = "Le message n'a jamais été créé.";
$l['unknown'] = "Inconnu";
$l['results'] = "Résultats";
$l['step1'] = "Étape 1";
$l['step2'] = "Étape 2";
$l['step1of2'] = "Étape 1 de 2 - Analyse du système de fichiers";
$l['step2of2'] = "Étape 2 de 2 - Analyse de la base de données";
$l['step1of2_line1'] = "Veuillez patienter, le système de fichiers est actuellement analysé pour les pièces jointes orphelines.";
$l['step2of2_line1'] = "Veuillez patienter, la base de données est actuellement analysée pour les pièces jointes orphelines.";
$l['step_line2'] = "Vous allez être automatiquement redirigé vers l'étape suivante quand ce processus sera terminé.";

// = Attachments/Index
$l['index_find_attachments'] = "Pièces jointes - Recherche de pièces jointes";
$l['find_where'] = "Rechercher pièces jointes où...";
$l['name_contains'] = "Le nom de fichier contient";
$l['name_contains_desc'] = "Pour rechercher avec joker *.[extension fichier]. Exemple : *.zip.";
$l['type_contains'] = "Le type de fichier contient";
$l['forum_is'] = "Le forum est";
$l['username_is'] = "Le nom d'utilisateur de l'auteur est";
$l['more_than'] = "Plus de";
$l['greater_than'] = "Supérieur à";
$l['is_exactly'] = "Est exactement";
$l['less_than'] = "Inférieur à";
$l['date_posted_is'] = "Le message a été posté il y a";
$l['days_ago'] = "jours";
$l['file_size_is'] = "La taille du fichier est";
$l['kb'] = "Ko";
$l['download_count_is'] = "Le nb. de téléchargements est";
$l['display_options'] = "Options d'affichage";
$l['filename'] = "Nom du fichier";
$l['filesize'] = "Taille du fichier";
$l['download_count'] = "Nb. téléchargements";
$l['post_username'] = "Nom d'utilisateur du message";
$l['asc'] = "Croissant";
$l['desc'] = "Décroissant";
$l['sort_results_by'] = "Trier résultats par";
$l['results_per_page'] = "Résultats par page";
$l['in'] = "dans";

// Buttons
$l['button_delete_orphans'] = "Supprimer les orphelines cochées";
$l['button_delete_attachments'] = "Supprimer les pièces jointes cochées";
$l['button_find_attachments'] = "Rechercher des pièces jointes";

?>