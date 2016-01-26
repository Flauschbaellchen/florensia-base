<?php
/**
 * MyBB 1.6 Spanish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 *
 * $Id: forum_attachments.lang.php 5016 2010-08-11 12:32:33Z Anio_pke $
 */

// Tabs
$l['attachments'] = "Archivos adjuntos";
$l['stats'] = "Estadísticas";
$l['find_attachments'] = "Buscar adjuntos";
$l['find_attachments_desc'] = "Usando el sistema de búsqueda de adjuntos puedes buscar archivos específicos que los usuarios tengan subidos en el foro. Introduce los criterios abajo. Todos los campos son opcionales y los criterios que estén vacíos no se aplicarán en la búsqueda.";
$l['find_orphans'] = "Buscar adjuntos huérfanos";
$l['find_orphans_desc'] = "Los adjuntos huérfanos son archivos adjuntos que por alguna razón se perdieron en la base de datos o en el sistema de ficheros. Esta utilidad te permite localizarlos y borrarlos.";
$l['attachment_stats'] = "Estadísticas";
$l['attachment_stats_desc'] = "Abajo hay una estadística general de los archivos adjuntos que están actualmente en tu foro.";

// Errors
$l['error_nothing_selected'] = "Por favor, selecciona uno o más adjuntos para borrar.";
$l['error_no_attachments'] = "Actualmente no hay adjuntos en tu foro. En cuanto se suba algún archivo podrás acceder a esta sección.";
$l['error_not_all_removed'] = "Solo se han podido eliminar algunos adjuntos correctamente, los demás no se han eliminado de la carpeta 'uploads'.";
$l['error_invalid_username'] = "El nombre de usuario introducido es inválido.";
$l['error_invalid_forums'] = "Uno o más de los foros seleccionados son inválidos.";
$l['error_no_results'] = "No se han encontrado adjuntos con los criterios de búsqueda especificados.";
$l['error_not_found'] = "El archivo adjunto no se ha encontrado en el directorio 'uploads'.";
$l['error_not_attached'] = "El adjunto se ha subido hace 24 horas pero no se ha adjuntado al mensaje.";
$l['error_does_not_exist'] = "El tema o el mensaje de este adjuntos no existe.";

// Success
$l['success_deleted'] = "Los adjuntos seleccionados se han eliminado correctamente";
$l['success_orphan_deleted'] = "Los adjuntos huérfanos seleccionados se han eliminado correctamente";
$l['success_no_orphans'] = "Actualmente no hay ningún adjunto huérfano en tu foro";

// Confirm
$l['confirm_delete'] = "¿Estás seguro de querer eliminar los ajduntos seleccionados?";

// == Pages
// = Stats
$l['general_stats'] = "Estadísticas generales";
$l['stats_attachment_stats'] = "Adjuntos - Estadísticas";
$l['num_uploaded'] = "<strong>Nº ajuntos subidos</strong>";
$l['space_used'] = "<strong>Espacio en disco</strong>";
$l['bandwidth_used'] = "<strong>Transferecia estimada</strong>";
$l['average_size'] = "<strong>Tamaño medio de los adjuntos</strong>";
$l['size'] = "Tamaño";
$l['posted_by'] = "Subido por";
$l['thread'] = "Tema";
$l['downloads'] = "Descargas";
$l['date_uploaded'] = "Fecha de subida";
$l['popular_attachments'] = "Top 5 - Los adjuntos más populares";
$l['largest_attachments'] = "Top 5 - Los adjuntos más grandes";
$l['users_diskspace'] = "Top 5 - Usuarios que usan más espacio de disco";
$l['username'] = "Nombre de usuario";
$l['total_size'] = "Tamaño";

// = Orphans
$l['orphan_results'] = "Búsqueda de adjuntos huérfanos - Resultados";
$l['orphan_attachments_search'] = "Búsqueda de adjuntos huérfanos";
$l['reason_orphaned'] = "Razón";
$l['reason_not_in_table'] = "No está en la tabla 'adjuntos'";
$l['reason_file_missing'] = "Falta el archivo";
$l['reason_thread_deleted'] = "Tema borrado";
$l['reason_post_never_made'] = "Mensaje no creado";
$l['unknown'] = "Desconocido";
$l['results'] = "Resultados";
$l['step1'] = "Paso 1";
$l['step2'] = "Paso 2";
$l['step1of2'] = "Paso 1 de 2 - Escaneando el sistema de ficheros";
$l['step2of2'] = "Paso 2 de 2 - Escaneando base de datos";
$l['step1of2_line1'] = "Por favor, espere, el sistema de ficheros está siendo escaneado buscando adjuntos huérfanos.";
$l['step2of2_line1'] = "Por favor, espere, la base de datos está siendo escaneada buscando adjuntos huérfanos.";
$l['step_line2'] = "Cuando el proceso se complete se saltará al siguiente paso automáticamente.";

// = Attachments / Index
$l['index_find_attachments'] = "Adjuntos - Buscar adjuntos";
$l['find_where'] = "Buscar adjuntos aquí...";
$l['name_contains'] = "El nombre del archivo contiene";
$l['name_contains_desc'] = "Para buscar con una sustitución introducir *.[extensión]. Ejemplo: *.zip.";
$l['type_contains'] = "El tipo de archivo contiene";
$l['forum_is'] = "El foro es";
$l['username_is'] = "El nombre de usuario es";
$l['more_than'] = "Más de";
$l['greater_than'] = "Mayor que";
$l['is_exactly'] = "Exactamente";
$l['less_than'] = "Menor que";
$l['date_posted_is'] = "La fecha de subida es";
$l['days_ago'] = "días";
$l['file_size_is'] = "El tamaño del archivo es";
$l['kb'] = "KB";
$l['download_count_is'] = "El número de descargas es";
$l['display_options'] = "Opciones";
$l['filename'] = "Nombre";
$l['filesize'] = "Tamaño";
$l['download_count'] = "Número de descargas";
$l['post_username'] = "Nombre de usuario";
$l['asc'] = "Ascendente";
$l['desc'] = "Descendente";
$l['sort_results_by'] = "Ordenar resultados por";
$l['results_per_page'] = "Resultados por página";
$l['in'] = "en orden";

// Buttons
$l['button_delete_orphans'] = "Eliminar huérfanos seleccionados";
$l['button_delete_attachments'] = "Eliminar adjuntos seleccionados";
$l['button_find_attachments'] = "Buscar adjuntos";

?>