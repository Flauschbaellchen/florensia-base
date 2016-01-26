<?php
/**
 * MyBB 1.6 Dutch Language Pack
 * Zie dutch.php voor versieinformatie
 *
 * Nederlands taalpakket voor MyBB
 * Nederlandse vertaling door Tom Huls (Tochjo)
 */

$l['cache'] = "Buffer:";
$l['cache_manager'] = "Buffers beheren";
$l['cache_manager_description'] = "U kunt hier de buffers beheren. Een buffer (in het Engels cache genoemd) wordt gebruikt om MyBB te optimaliseren. Een buffer opnieuw opbouwen zal de buffer opnieuw maken en synchroniseren. Een buffer opnieuw laden zal de buffer opnieuw in de gekozen bufferafhandelaar laden (disk, eaccelerator, memcache et cetera). Opnieuw laden is handig als u van database of bestandssysteem bent gewisseld.";
$l['rebuild_cache'] = "Buffer opnieuw opbouwen";
$l['reload_cache'] = "Buffer opnieuw laden";

$l['error_cannot_rebuild'] = "Deze buffer kan niet opnieuw worden opgebouwd.";
$l['error_empty_cache'] = "Buffer is leeg.";
$l['error_incorrect_cache'] = "U hebt een ongeldige buffer opgegeven.";
$l['error_no_cache_specified'] = "U hebt geen buffer opgegeven.";

$l['success_cache_rebuilt'] = "De buffer is opnieuw opgebouwd.";
$l['success_cache_reloaded'] = "De buffer is opnieuw geladen.";

?>