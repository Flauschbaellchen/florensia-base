<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: tools_backupdb.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */


$l['database_backups'] = "Sauvegarde de la base de données";
$l['database_backups_desc'] = "Ici vous trouvez une liste des sauvegardes de la base de données qui sont actuellement stockées sur votre serveur web dans le répertoire Backups de MyBB.";
$l['new_database_backup'] = "Nouvelle sauvegarde de la base de données";
$l['new_backup'] = "Nouvelle sauvegarde";
$l['new_backup_desc'] = "Ici vous pouvez sauvegarder une copie de votre base de données.";
$l['backups'] = "Sauvegardes";
$l['existing_database_backups'] = "Sauvegardes existantes de la base de données";

$l['backup_saved_to'] = "La sauvegarde a été enregistrée dans :";
$l['download'] = "Télécharger";
$l['table_selection'] = "Sélection de tables";
$l['backup_options'] = "Options de sauvegarde";
$l['table_select_desc'] = "Vous pouvez choisir les tables que vous souhaitez sauvegarder. Maintenez la touche CTRL pour sélectionner plusieurs tables.";
$l['select_all'] = "Tout sélectionner";
$l['deselect_all'] = "Tout désélectionner";
$l['select_forum_tables'] = "Sélectionner les tables du forum";
$l['file_type'] = "Type de fichier";
$l['file_type_desc'] = "Sélectionnez le type de fichier à utiliser pour la sauvegarde de la base.";
$l['gzip_compressed'] = "GZIP Compressé";
$l['plain_text'] = "Texte brut";
$l['save_method'] = "Méthode de sauvegarde";
$l['save_method_desc'] = "Sélectionnez la méthode que vous voulez utiliser pour la sauvegarde.";
$l['backup_directory'] = "Dossier de sauvegarde";
$l['backup_contents'] = "Contenu de la sauvegarde";
$l['backup_contents_desc'] = "Sélectionnez les informations que vous voulez inclure à la sauvegarde.";
$l['structure_and_data'] = "Structure et données";
$l['structure_only'] = "Structure uniquement";
$l['data_only'] = "Données uniquement";
$l['analyze_and_optimize'] = "Analyser et optimiser les tables sélectionnées";
$l['analyze_and_optimize_desc'] = "Voulez-vous que les tables sélectionnées soient analysées et optimisées durant la sauvegarde ?";
$l['perform_backup'] = "Effectuer la sauvegarde";
$l['backup_filename'] = "Nom de fichier de la sauvegarde";
$l['file_size'] = "Taille du fichier";
$l['creation_date'] = "Date de création";
$l['no_backups'] = "Il n'existe actuellement aucune sauvegarde déjà faite.";

$l['error_file_not_specified'] = "Vous n'avez pas spécifié une sauvegarde de base de données à télécharger.";
$l['error_invalid_backup'] = "Le fichier de sauvegarde que vous avez choisi est invalide ou n'existe pas.";
$l['error_backup_doesnt_exist'] = "La sauvegarde spécifiée n'existe pas.";
$l['error_backup_not_deleted'] = "La sauvegarde n'a pas été supprimée.";
$l['error_tables_not_selected'] = "Vous n'avez pas sélectionné de tables à sauvegarder.";
$l['error_no_zlib'] = "La bibliothèque zlib pour PHP n'est pas activée - vous ne pouvez pas créer des sauvegardes compressées GZIP.";

$l['alert_not_writable'] = "Votre répertoire de sauvegarde (dans le répertoire du panneau d'administration) n'est pas accessible en écriture. Vous ne pouvez pas enregistrer les sauvegardes sur le serveur.";

$l['confirm_backup_deletion'] = "Êtes-vous sûr de vouloir supprimer cette sauvegarde ?";

$l['success_backup_deleted'] = "La sauvegarde a été supprimée.";
$l['success_backup_created'] = "La sauvegarde a été créée.";

?>