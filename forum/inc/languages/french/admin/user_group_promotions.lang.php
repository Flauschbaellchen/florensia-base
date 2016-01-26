<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright � 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: user_group_promotions.lang.php 4941 2010-05-15 18:17:38Z RyanGordon $
 */

$l['user_group_promotions'] = "Promotions de groupe d'utilisateurs";
$l['user_group_promotions_desc'] = "Ici vous pouvez gérer les promotions de groupe d'utilisateurs.";
$l['edit_promotion'] = "Éditer la promotion";
$l['edit_promotion_desc'] = "Ici vous pouvez éditer les promotions, qui seront automatiquement appliquées sur votre forum.";
$l['add_new_promotion'] = "Ajouter une promotion";
$l['add_new_promotion_desc'] = "Ici vous pouvez créer une nouvelle promotion qui sera automatiquement appliquée sur votre forum.";
$l['title'] = "Titre";
$l['short_desc'] = "Courte description";
$l['post_count'] = "Nombre de messages";
$l['reputation'] = "Réputation";
$l['referrals'] = "Parrainages";
$l['time_registered'] = "Temps passé en ligne";
$l['promo_requirements'] = "Critères requis pour une promotion";
$l['promo_requirements_desc'] = "Sélectionnez les critères requis pour cette promotion. Maintenez CTRL enfoncé pour sélectionner plusieurs critères.";
$l['greater_than_or_equal_to'] = "Supérieur ou égal à";
$l['greater_than'] = "Supérieur à";
$l['equal_to'] = "Égal à";
$l['less_than_or_equal_to'] = "Inférieur ou égal à";
$l['less_than'] = "Inférieur à";
$l['reputation_count'] = "Réputation";
$l['reputation_count_desc'] = "Entrez le seuil de réputation requis. La réputation doit être sélectionnée comme étant une valeur requise pour être prise en compte. Sélectionnez le type de comparaison pour la réputation.";
$l['referral_count'] = "Nombre de parrainages";
$l['referral_count_desc'] = "Entrez le nombre de parrainages requis. Le nombre de parrainages est nécessaire pour que cela soit inclus. Sélectionnez le type de comparaison pour les parrainages.";
$l['post_count'] = "Nombre de messages";
$l['post_count_desc'] = "Entrez le nombre de messages requis. Le nombre de messages doit être sélectionné comme étant une valeur requise pour être pris en compte. Sélectionnez le type de comparaison pour les messages.";
$l['hours'] = "Heures";
$l['days'] = "Jours";
$l['weeks'] = "Semaines";
$l['months'] = "Mois";
$l['years'] = "Années";
$l['time_registered'] = "Temps passé en ligne";
$l['time_registered_desc'] = "Entrez le nombre d'heures, de jours, de semaines, de mois ou d'années que l'utilisateur doit passer en ligne. Le temps passé en ligne doit être sélectionné comme étant une valeur requise pour être pris en compte. Choisissez si le temps passé en ligne devrait être compté en heures, jours, semaines, mois ou années.";
$l['all_user_groups'] = "Tous les groupes d'utilisateurs";
$l['orig_user_group'] = "Groupe d'utilisateur d'origine";
$l['orig_user_group_desc'] = "Sélectionnez sur quel(s) groupe(s) d'utilisateur(s) doit s'appliquer la promotion. Maintenez CTRL enfoncé pour sélectionner plusieurs groupes.";
$l['new_user_group'] = "Nouveau groupe d'utilisateurs";
$l['new_user_group_desc'] = "Sélectionnez le groupe d'utilisateurs dans lequel l'utilisateur sera déplacé suite à cette promotion.";
$l['primary_user_group'] = "Groupe d'utilisateurs primaire";
$l['secondary_user_group'] = "Groupe d'utilisateurs secondaire";
$l['user_group_change_type'] = "Changer le type de groupe d'utilisateurs";
$l['user_group_change_type_desc'] = "Sélectionnez 'Groupe d'utilisateurs primaire' si l'utilisateur devrait voir son groupe d'utilisateur primaire remplacé par son nouveau groupe d'utilisateurs. Sélectionnez 'Groupe d'utilisateurs secondaire' si l'utilisateur devrait avoir le nouveau groupe d'utilisateur ajouté en tant que groupe d'utilisateurs secondaire dans son profil.";
$l['enabled'] = "Activer ?";
$l['enable_logging'] = "Activer le suivi ?";
$l['promotion_logs'] = "Suivis de promotions";
$l['view_promotion_logs'] = "Voir les suivis de promotions";
$l['view_promotion_logs_desc'] = "Ici vous pouvez visualiser les suivis de promotions lancés précédemment.";
$l['promoted_user'] = "Utilisateur promu";
$l['time_promoted'] = "Durée de promotion";
$l['no_promotion_logs'] = "Il n'y a actuellement aucune promotion archivée.";
$l['promotion_manager'] = "Gestionnaire de promotions";
$l['promotion'] = "Promotion";
$l['edit_promotion'] = "Éditer une promotion";
$l['disable_promotion'] = "Désactiver une promotion";
$l['enable_promotion'] = "Activer une promotion";
$l['delete_promotion'] = "Supprimer une promotion";
$l['no_promotions_set'] = "Il n'y a actuellement aucune promotion définie.";
$l['update_promotion'] = "Enregistrer la promotion";
$l['multiple_usergroups'] = "Groupes d'utilisateurs multiples";
$l['secondary'] = "Secondaire";
$l['primary'] = "Primaire";

$l['error_no_promo_id'] = "Vous n'avez pas entré d'ID de promotion.";
$l['error_invalid_promo_id'] = "Vous n'avez pas entré d'ID de promotion valide.";

$l['error_no_title'] = "Vous n'avez pas entré de titre pour cette promotion.";
$l['error_no_desc'] = "Vous n'avez pas entré de description pour cette promotion.";
$l['error_no_requirements'] = "Vous n'avez pas sélectionné au moins un critère requis pour cette promotion.";
$l['error_no_orig_usergroup'] = "Vous n'avez pas sélectionné au moins un groupe d'utilisateur original pour cette promotion.";
$l['error_no_new_usergroup'] = "Vous n'avez pas sélectionné au moins un nouveau groupe d'utilisateur pour cette promotion.";
$l['error_no_usergroup_change_type'] = "vous n'avez pas sélectionné au moins un changement de type de groupe d'utilisateur pour cette promotion.";

$l['success_promo_disabled'] = "La promotion de groupe sélectionnée a été désactivée.";
$l['success_promo_deleted'] = "La promotion de groupe sélectionnée a été supprimée.";
$l['success_promo_enabled'] = "La promotion de groupe sélectionnée a été activée.";
$l['success_promo_updated'] = "La promotion de groupe sélectionnée a été mise à jour.";
$l['success_promo_added'] = "La promotion a été créée.";

$l['confirm_promo_deletion'] = "Êtes-vous sûr de vouloir supprimer cette promotion ?";

?>