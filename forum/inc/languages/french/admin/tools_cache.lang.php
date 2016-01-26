<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: tools_cache.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */

$l['cache'] = "Cache :";
$l['cache_manager'] = "Gestion du cache";
$l['cache_manager_description'] = "Ici vous pouvez gérer les caches qui sont utilisés comme méthode d'optimisation de MyBB. Reconstruire le cache va prendre toutes les données nécessaires à la création de celui-ci et les re-synchroniser. Recharger le cache va le recharger dans le gestionnaire de cache choisi (disque, eaccelerator, memcache, etc). Le rechargement est utile lors d'un changement depuis la base de données ou les fichiers systèmes vers xcache, eaccelerator, ou n'importe quel autre gestionnaire de cache.";
$l['rebuild_cache'] = "Reconstruire le cache";
$l['reload_cache'] = "Recharger le cache";

$l['error_cannot_rebuild'] = "Ce cache ne peut pas être reconstruit.";
$l['error_empty_cache'] = "Le cache est vide.";
$l['error_incorrect_cache'] = "Cache spécifié incorrect.";
$l['error_no_cache_specified'] = "Vous n'avez pas spécifié de cache à voir.";

$l['success_cache_rebuilt'] = "Le cache a été reconstruit.";
$l['success_cache_reloaded'] = "Le cache a été rechargé.";

?>