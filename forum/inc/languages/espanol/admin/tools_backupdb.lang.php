<?php
/**
 * MyBB 1.6 Spanish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: tools_backupdb.lang.php 5016 2010-08-12 12:32:33Z Anio_pke $
 */


$l['database_backups'] = "Copias de seguridad";
$l['database_backups_desc'] = "Aquí puedes encontrar un listado de las copias de seguridad de tu base de datos que están alojadas en tu servidor web en la carpeta 'Backups' del MyBB.";
$l['new_database_backup'] = "Nueva copia de seguridad de la base de datos";
$l['new_backup'] = "Nueva copia de seguridad";
$l['new_backup_desc'] = "Aquí puedes realizar una copia de seguridad de tu base de datos";
$l['backups'] = "Copias de seguridad";
$l['existing_database_backups'] = "Copias de seguridad existentes";

$l['backup_saved_to'] = "La copia se ha guardado en:";
$l['download'] = "Descargar";
$l['table_selection'] = "Selección de tablas";
$l['backup_options'] = "Opciones de la copia";
$l['table_select_desc'] = "Puedes seleccionar las tablas de las que quieres realizar la copia. Pulsa CTRL para seleccionar múltiples tablas.";
$l['select_all'] = "Seleccionar TODO";
$l['deselect_all'] = "Deseleccionar TODO";
$l['select_forum_tables'] = "Seleccionar tablas del foro";
$l['file_type'] = "Tipo de archivo";
$l['file_type_desc'] = "Selecciona el tipo de archivo en el que quieres que se guarde la copia de seguridad.";
$l['gzip_compressed'] = "Comprimir con GZIP";
$l['plain_text'] = "Texto plano (txt)";
$l['save_method'] = "Método de almacenamiento";
$l['save_method_desc'] = "Selecciona el método que prefieres para guardar la copia.";
$l['backup_directory'] = "Carpeta 'backup'";
$l['backup_contents'] = "Contenido de la copia";
$l['backup_contents_desc'] = "Selecciona la información que quieres incluir en la copia de seguridad.";
$l['structure_and_data'] = "Estructura y datos";
$l['structure_only'] = "Solo estructura";
$l['data_only'] = "Solo datos";
$l['analyze_and_optimize'] = "Analizar y optimizar las tablas seleccionadas";
$l['analyze_and_optimize_desc'] = "¿Quieres que las tablas seleccionadas se analicen y se optimicen mientras se realiza la copia?";
$l['perform_backup'] = "Realizar copia";
$l['backup_filename'] = "Nombre de la copia";
$l['file_size'] = "Tamaño";
$l['creation_date'] = "Fecha de creación";
$l['no_backups'] = "Actualmente no hay copias de seguridad.";

$l['error_file_not_specified'] = "No has seleccionado ninguna copia para descargar.";
$l['error_invalid_backup'] = "La copia de seguridad seleccionada es inválida o no existe.";
$l['error_backup_doesnt_exist'] = "La copia de seguridad seleccionada no existe";
$l['error_backup_not_deleted'] = "La copia de seguridad no se ha eliminado.";
$l['error_tables_not_selected'] = "No has seleccionado ninguna tabla para la copia.";
$l['error_no_zlib'] = "La librería zlib de PHP no esta activada - no puedes crear copias comprimidas con GZIP.";

$l['alert_not_writable'] = "La carpeta 'backups' (dentro de la carpeta admin) no es de escritura. No puedes guardar copias en este servidor.";

$l['confirm_backup_deletion'] = "¿Estás seguro de querer eliminar esta copia?";

$l['success_backup_deleted'] = "La copia de seguridad se ha eliminado correctamente.";
$l['success_backup_created'] = "La copia de seguridad se ha creado correctamente.";

?>