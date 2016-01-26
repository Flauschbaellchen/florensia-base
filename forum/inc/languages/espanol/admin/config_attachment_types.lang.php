<?php
/**
 * MyBB 1.6 Spanish Language Pack
 * Copyright Œ 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: config_attachment_types.lang.php 4941 2010-08-11 12:32:33Z Anio_pke  $
 */

$l['attachment_types'] = "Tipos de adjuntos";
$l['attachment_types_desc'] = "Aquí puedes crear y configurar los tipos de archivos adjuntos que los usuarios pueden adjuntar a sus mensajes.";
$l['add_new_attachment_type'] = "Nuevo tipo de adjunto";
$l['add_attachment_type'] = "Agregar tipo de adjunto";
$l['add_attachment_type_desc'] = "Agregar un nuevo tipo de archivos adjuntos permitirá a los miembros adjuntar archivos de este tipo en sus mensajes. Tienes la posibilidad de configurar la extensión, clases MIME, máximo tamaño y mostrar un pequeño icono para cada tipo de adjunto.";
$l['edit_attachment_type'] = "Editar tipo de adjunto";
$l['edit_attachment_type_desc'] = "Tienes la posibilidad de configurar la extensión, clases MIME, máximo tamaño y mostrar un pequeño icono para este tipo de adjunto.";

$l['extension'] = "Extensión";
$l['maximum_size'] = "Máximo tamaño";
$l['no_attachment_types'] = "No hay ningún tipo de archivo adjunto en tu foro.";

$l['file_extension'] = "Extensión";
$l['file_extension_desc'] = "Introducir la extensión que quieres permitir subir aquí (No incluir nada más que la extensión sin punto) (Ejemplo: txt)";
$l['mime_type'] = "Clase MIME";
$l['mime_type_desc'] = "Introduce la clase MIME que se enviará al servidor cuando se descargan archivos de este tipo (<a href=\"http://www.webmaster-toolkit.com/mime-types.shtml\">Ver la lista aquí</a>)";
$l['maximum_file_size'] = "Tamaño máximo de archivo (Kilobytes)";
$l['maximum_file_size_desc'] = "Tamaño máximo de archivo para las subidas de los tipos de archivos adjuntos en Kilobytes (1 MB = 1024 KB)";
$l['limit_intro'] = "Asegúrate que el máximo tamaño de archivo sea inferior a los límites del PHP:";
$l['limit_post_max_size'] = "Máximo tamaño post: {1}";
$l['limit_upload_max_filesize'] = "Máximo tamaño de archivo: {1}";
$l['attachment_icon'] = "Icono de adjunto";
$l['attachment_icon_desc'] = "Si quieres mostrar un pequeño icono con los adjuntos de este tipo introduce la ruta aquí. {theme} se reemplazará por el directorio de imágenes del usuario permitiéndote tener diferentes iconos por cada estilo.";
$l['save_attachment_type'] = "Guardar tipo de adjunto";

$l['error_invalid_attachment_type'] = "Has seleccionado un tipo de adjuntos inválido.";
$l['error_missing_mime_type'] = "No has introducido una clase MIME para este tipo de archivo adjunto";
$l['error_missing_extension'] = "No has introducido una extensión para este tipo de archivo adjunto";

$l['success_attachment_type_created'] = "El tipo de archivo adjunto se ha creado correctamente.";
$l['success_attachment_type_updated'] = "El tipo de archivo adjunto se ha actualizado correctamente.";
$l['success_attachment_type_deleted'] = "El tipo de archivo adjunto se ha eliminado correctamente.";

$l['confirm_attachment_type_deletion'] = "¿Estás seguro de querer borrar este tipo de archivo adjunto?";

?>
