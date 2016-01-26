<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: tools_system_health.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */

$l['system_health'] = "État du système";
$l['system_health_desc'] = "Vous pouvez suivre l'état de votre système.";
$l['utf8_conversion'] = "Conversion UTF-8";
$l['utf8_conversion_desc'] = "Vous êtes en train de convertir une base de données au format UTF-8. Cette opération peut prendre plusieurs heures, en fonction de la taille de votre forum et de ses tables. Quand le processus sera terminé, vous serez dirigé vers la page principale de conversion UTF-8.";
$l['utf8_conversion_desc2'] = "Cet outil vérifie que la base de données est au format UTF-8 et vous permet de la convertir si elle n'est pas encodée ainsi.";

$l['convert_all'] = "Tout convertir";
$l['converting_to_utf8'] = "MyBB convertit la table \"{1}\" en UTF-8 à partir d'un encodage {2}.";
$l['convert_to_utf8'] = "Vous êtes sur le point de convertir la table \"{1}\" en encodage UTF-8 à partir de l'encodage {2}.";
$l['convert_all_to_utf'] = "Vous êtes sur le point de convertir TOUTES les tables en encodage UTF-8 à prter de l'encodage {1}.";
$l['please_wait'] = "Veuillez patienter...";
$l['converting_table'] = "Conversion de la table:";
$l['convert_table'] = "Convertir table";
$l['convert_tables'] = "Convertir toutes les tables";
$l['convert_database_table'] = "Convertir la table de la la base de données";
$l['convert_database_tables'] = "Convertir toutes les tables de la la base de données";
$l['table'] = "Table";
$l['status'] = "État";
$l['convert_now'] = "Convertir maintenant";
$l['totals'] = "Total";
$l['attachments'] = "Pièces jointes";
$l['total_database_size'] = "Taille totale de la base";
$l['attachment_space_used'] = "Espace utilisé par les pièces jointes";
$l['total_cache_size'] = "Taille totale du cache";
$l['estimated_attachment_bandwidth_usage'] = "Utilisation estimée de la bande passante pour les pièces jointes";
$l['max_upload_post_size'] = "Upload max/Taille de MESSAGE";
$l['average_attachment_size'] = "Taille moyenne des pièces jointes";
$l['stats'] = "Statistiques";
$l['task'] = "Tâche";
$l['run_time'] = "Heure de lancement";
$l['next_3_tasks'] = "3 tâches suivantes";
$l['backup_time'] = "Heure de sauvegarde";
$l['no_backups'] = "Il n'existe actuellement aucune sauvegarde déjà faite.";
$l['existing_db_backups'] = "Sauvegardes de base de données existantes";
$l['writable'] = "Écriture possible";
$l['not_writable'] = "Écriture impossible";
$l['please_chmod_777'] = "CHMODez à 777.";
$l['chmod_info'] = "Changez l'attribut CHMOD des fichiers/dossiers ci-dessous. Pour de plus amples renseignements sur le CHMOD, consultez le";
$l['file'] = "Fichier";
$l['location'] = "Emplacement";
$l['settings_file'] = "Fichier de paramètres";
$l['config_file'] = "Fichier de configuration";
$l['file_upload_dir'] = "Répertoire d'envoi des fichiers";
$l['avatar_upload_dir'] = "Répertoire d'envoi des avatars";
$l['language_files'] = "Fichiers de langue";
$l['backup_dir'] = "Répertoire des sauvegardes";
$l['cache_dir'] = "Répertoire du cache";
$l['themes_dir'] = "Répertoire de thèmes";

$l['chmod_files_and_dirs'] = "CHMOD des fichiers et répertoires";

$l['notice_process_long_time'] = "Ce processus peut prendre jusqu'à plusieurs heures en fonction de la taille de votre forum et ses tables.";

$l['error_chmod'] = "des fichiers et des répertoires n'ont pas le bon réglage CHMOD.";
$l['error_invalid_table'] = "La table spécifiée n'existe pas.";
$l['error_db_encoding_not_set'] = "Votre configuration actuelle de MyBB n'est pas encore configurée pour utiliser cet outil. Consultez <a href=\"http://wiki.mybboard.net/index.php/UTF8_Setup\">le wiki</a> pour de plus amples renseignements sur la façon de configurer.";
$l['error_not_supported'] = "Votre moteur de base de données n'est pas pris en charge par l'outil de conversion UTF-8.";

$l['success_all_tables_already_converted'] = "Toutes les tables ont déjà été converties ou sont déjà au format UTF-8.";
$l['success_table_converted'] = "La table sélectionnée \"{1}\" a été convertie en UTF-8.";
$l['success_chmod'] = "Tous les fichiers et répertoires ont le bon réglage CHMOD."; 

?>