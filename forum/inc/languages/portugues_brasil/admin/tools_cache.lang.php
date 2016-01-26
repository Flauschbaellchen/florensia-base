<?php
/**
 * Idioma Português do Brasil para MyBB 1.6 - Versão 1.0
 * Tradutor: Speeder
 * Copyright © 2008 - 2010 MyBB Brasil - Todos os Direitos Reservados
 * http://www.mybb.com.br
 *
 * $Id: tools_cache.lang.php 1000 03/08/2010 16:25:00 Speeder $
 */

$l['cache'] = "Cache:";
$l['cache_manager'] = "Gerenciar Cache";
$l['cache_manager_description'] = "Aqui você pode gerenciar os caches que são usados para otimizar o MyBB. Reconstruir o cache fará com que todos os dados necessários sejam obtidos novamente e ressincronizados. Recarregar o cache fará com que o cache seja carregado novamente pelo gerenciador usado (disco, eaccelerator, memcache, etc). Recarregar é útil quando se troca do banco de dados ou disco para o xcache, eaccelerator ou outro gerenciador de cache.";
$l['rebuild_cache'] = "Reconstruir Cache";
$l['reload_cache'] = "Recarregar Cache";

$l['error_cannot_rebuild'] = "Este cache não pode ser reconstruído.";
$l['error_empty_cache'] = "O cache está vazio.";
$l['error_incorrect_cache'] = "Cache incorreto especificado.";
$l['error_no_cache_specified'] = "Você não especificou um cache para visualizar.";

$l['success_cache_rebuilt'] = "O cache foi reconstruído com sucesso.";
$l['success_cache_reloaded'] = "O cache foi recarregado com sucesso.";

?>