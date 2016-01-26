<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: tools_recount_rebuild.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */

$l['recount_rebuild'] = "Recompter et reconstruire";
$l['recount_rebuild_desc'] = "Vous pouvez recompter et reconstruire les données pour corriger les problèmes de synchronisation sur votre forum.";

$l['data_per_page'] = "Nombre de données par page";
$l['recount_stats'] = "Recompter les statistiques";
$l['recount_stats_desc'] = "Cette option va recompter et mettre à jour les statistiques du forum sur l'index et sur la page de statistiques.";
$l['rebuild_forum_counters'] = "Reconstruire les compteurs des forums";
$l['rebuild_forum_counters_desc'] = "Cette option mettra à jour le nombre de messages et de discussions de chaque forum.";
$l['rebuild_thread_counters'] = "Reconstruire les compteurs des discussions";
$l['rebuild_thread_counters_desc'] = "Les compteurs des messages/affichages et les derniers messages de chaque discussion seront mis à jour afin de refléter les valeurs actuelles.";
$l['recount_user_posts'] = "Recompter le nombre de messages des utilisateurs";
$l['recount_user_posts_desc'] = "Les compteurs de messages pour chaque utilisateur seront mis à jour afin de refléter les valeurs actuelles directement basées sur les messages dans la base de donnée, et les forums ayant leur compteur de messages désactivé.";
$l['rebuild_attachment_thumbs'] = "Reconstruire les miniatures de pièces jointes";
$l['rebuild_attachment_thumbs_desc'] = "Ceci reconstruira les miniatures de pièces jointes pour s'assurer qu'elles utilisent les dimensions de largeur et de hauteur actuelles et reconstruira aussi les miniatures manquantes.";

$l['success_rebuilt_forum_counters'] = "Les compteurs du forum ont été reconstruits.";
$l['success_rebuilt_thread_counters'] = "Les compteurs de discussions ont été reconstruits.";
$l['success_rebuilt_user_counters'] = "Le nombre de messages des utilisateurs a été recompté.";
$l['success_rebuilt_attachment_thumbnails'] = "Les miniatures de pièces jointes ont été reconstruites.";
$l['success_rebuilt_forum_stats'] = "Les statistiques du forum ont été reconstruites.";

$l['confirm_proceed_rebuild'] = "Cliquez sur \"Continuer\" pour poursuivre le processus de recomptage et de reconstruction.";
?>