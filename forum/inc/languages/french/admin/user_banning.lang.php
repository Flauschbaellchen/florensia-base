<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: user_banning.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */

// Tabs
$l['banning'] = "Bannissement";
$l['banned_accounts'] = "Comptes bannis";
$l['banned_accounts_desc'] = "Ici vous pouvez gérer les comptes d'utilisateurs qui ont étés bannis de l'accès au forum.";
$l['ban_a_user'] = "Bannir un utilisateur";
$l['ban_a_user_desc'] = "Ici vous pouvez bannir un utilisateur.";
$l['edit_ban'] = "Éditer un bannissement";
$l['edit_ban_desc'] = "Ici vous pouvez éditer la raison, et la durée du bannissement pour les utilisateur actuellement bannis.";
$l['banned_ips'] = "IPs bannies";
$l['disallowed_usernames'] = "Noms d'utilisateur rejetés";
$l['disallowed_email_addresses'] = "Adresses email rejetées";

// Errors
$l['error_invalid_ban'] = "Vous avez sélectionné un bannissement à éditer incorrect.";
$l['error_invalid_username'] = "Le nom d'utilisateur que vous avez entré est incorrect et n'existe pas.";
$l['error_no_perm_to_ban'] = "Vous n'avez pas la permission de bannir cet utilisateur.";
$l['error_already_banned'] = "Cet utilisateur appartient déjà à un groupe banni, et ne peut donc pas être ajouté à un nouveau groupe.";
$l['error_ban_self'] = "Vous ne pouvez pas vous bannir vous même.";
$l['error_no_reason'] = "Vous n'avez pas spécifié de raison au bannissement de cet utilisateur.";

// Success
$l['success_ban_lifted'] = "Le bannissement sélectionné a été levé.";
$l['success_banned'] = "L'utilisateur sélectionné a été banni.";
$l['success_ban_updated'] = "Le bannissement sélectionné a été mis à jour.";
$l['success_pruned'] = "Les messages et discussions sélectionnés de l'utilisateur ont été purgés."; 

// Confirm
$l['confirm_lift_ban'] = "Êtes-vous sûr de vouloir lever ce bannissement ?";
$l['confirm_prune'] = "Êtes-vous sûr de vouloir supprimer toutes les discussions et tous les messages créés par cet utilisateur ?";

//== Pages
//= Add/Edit
$l['ban_username'] = "Nom d'utilisateur <em>*</em>";
$l['autocomplete_enabled'] = "L'auto-complétion est activée pour ce champ.";
$l['ban_reason'] = "Raison du bannissement";
$l['ban_group'] = "Groupe banni <em>*</em>";
$l['ban_group_desc'] = "Afin de pouvoir bannir cet utilisateur, il doit être déplacé dans un groupe banni.";
$l['ban_time'] = "Durée du bannissement <em>*</em>";
//= Index
$l['user'] = "Utilisateur";
$l['moderation'] = "Modération";
$l['ban_lifts_on'] = "Bannissements levés";
$l['time_left'] = "Temps restant";
$l['permenantly'] = "de façon permanente";
$l['na'] = "N/A";
$l['for'] = "pour";
$l['bannedby_x_on_x'] = "<strong>{1}</strong><br /><small>Banni par {2} le {3} {4}</small>";
$l['lift'] = "Levé";
$l['no_banned_users'] = "Vous n'avez aucun utilisateur banni pour le moment.";
$l['prune_threads_and_posts'] = "Purger les discussions et messages";
// Buttons
$l['ban_user'] = "Bannir un utilisateur";
$l['update_ban'] = "Mettre à jour le bannissement";

?>