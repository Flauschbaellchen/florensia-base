<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: user_admin_permissions.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */

$l['admin_permissions'] = "Permissions d'administrateur";
$l['user_permissions'] = "Permissions de l'utilisateur";
$l['user_permissions_desc'] = "Ici vous pouvez gérer les permissions d'administrateur pour chaque utilisateur. Cette fonction vous permet de ne permettre l'accès qu'à certaines parties de l'administration aux utilisateurs sélectionnés.";
$l['group_permissions'] = "Permissions de groupe";
$l['group_permissions_desc'] = "Les permissions d'administrateur peuvent aussi être appliquées aux groupes d'utilisateurs qui ont la permission d'accéder à l'administration. De même, vous pouvez utiliser cette fonction pour ne restreindre l'accès de groupes administrateurs qu'à certaines parties de l'administration.";
$l['default_permissions'] = "Permissions par défaut";
$l['default_permissions_desc'] = "Les permissions d'administration par défaut sont celles qui sont appliquées aux utilisateurs qui n'ont pas de permissions personnalisées d'administration, ou qui n'ont pas hérité de permissions.";

$l['admin_permissions_updated'] = "Les permissions d'administrateur ont été mises à jour.";
$l['revoke_permissions'] = "Révoquer les permissions";
$l['edit_permissions'] = "Modifier les permissions";
$l['set_permissions'] = "Définir les permissions";
$l['edit_permissions_desc'] = "Ici vous pouvez restreindre l'accès à des onglets ou à des pages individuelles. Attention, l'onglet \"Accueil\" est accessible à tous les administrateurs.";
$l['update_permissions'] = "Mettre à jour les permissions";
$l['view_log'] = "Voir le suivi";
$l['permissions_type_group'] = "Type de permission du groupe";
$l['permissions_type_user'] = "Type de permission de l'utilisateur";
$l['no_group_perms'] = "Il n'y a actuellement aucune permission définie de groupe.";
$l['no_user_perms'] = "Il n'y actuellement aucune permission définie d'utilisateur.";
$l['edit_user'] = "Éditer le profil de l'utilisateur";
$l['using_individual_perms'] = "Utilisant les permissions individuelles";
$l['using_custom_perms'] = "Utilisant les permissions personnalisées";
$l['using_group_perms'] = "Utilisant les permissions de groupe";
$l['using_default_perms'] = "Utilisant les permissions par défaut";
$l['last_active'] = "Dernière visite";
$l['user'] = "Utilisateur";
$l['edit_group'] = "Éditer le groupe";
$l['default'] = "Par défaut";
$l['group'] = "Groupe";

$l['error_delete_super_admin'] = "Désolé, mais vous ne pouvez pas effectuer cette action sur l'utilisateur spécifié car il appartient au groupe Super Administrateur.<br /><br />Pour avoir l'autorisation d'effectuer cette action, vous devez ajouter votre ID utilisateur à la liste des Super Administrateurs dans inc/config.php.";
$l['error_delete_no_uid'] = "Vous n'avez pas entré d'ID d'administrateur, d'utilisateur ou de groupe d'utilisateurs.";
$l['error_delete_invalid_uid'] = "Vous n'avez pas entré d'ID valide d'administrateur, d'utilisateur ou de groupe d'utilisateurs.";

$l['success_perms_deleted'] = "Les permissions d'administrateur d'utilisateur/groupe d'utilisateurs ont été révoquées.";

$l['confirm_perms_deletion'] = "Êtes-vous sûr de vouloir révoquer les permissions d'administrateur pour cet utilisateur/groupe d'utilisateurs ?";
$l['confirm_perms_deletion2'] = "Êtes-vous sûr de vouloir révoquer les permissions de cet utilisateur ?";

?>