<?php
/**
 * MyBB 1.6 Spanish Language Pack
 * Copyright Œ 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: config_warning.lang.php 4941 2010-08-11 12:32:33Z Anio_pke $
 */
 
$l['warning_system'] = "Sistema de advertencias";
$l['warning_types'] = "Tipos de advertencias";
$l['warning_types_desc'] = "Aquí puedes configurar una lista con diferentes tipos advertencias que el equipo del foro tiene permitido usar para advertir a los usuarios.";
$l['add_warning_type'] = "Agregar advertencia";
$l['add_warning_type_desc'] = "Aquí puedes crear un nuevo tipo de advertencia. Los tipos de advertencias son seleccionables cuando se advierten usuarios. Puedes definir el número de puntos que agregas y el tiempo que debe pasar para que la advertencia caduque.";
$l['edit_warning_type'] = "Editar advertencia";
$l['edit_warning_type_desc'] = "Aquí puedes editar un tipo de advertencia. Los tipos de advertencias son seleccionables cuando se advierten usuarios. Puedes definir el número de puntos que agregas y el tiempo que debe pasar para que la advertencia caduque.";
$l['warning_levels'] = "Niveles de advertencia";
$l['warning_levels_desc'] = "Los niveles de advertencia definen que pasa cuando un usuario supera un cierto nivel (porcentaje del máximo número de puntos). Puedes suspender usuarios o suspender privilegios.";
$l['add_warning_level'] = "Agregar nuevo nivel";
$l['add_warning_level_desc'] = "Aquí puedes crear un nuevo nivel de advertencia. Los niveles de advertencia son acciones que se realizarán cuando los usuarios superen un porcentaje especificado del máximo número de puntos.";
$l['edit_warning_level'] = "Editar nivel de advertencia";
$l['edit_warning_level_desc'] = "Los niveles de advertencia son acciones que se realizarán cuando los usuarios superen un porcentaje especificado del máximo número de puntos.";

$l['percentage'] = "Porcentaje";
$l['action_to_take'] = "Acción a realizar";
$l['move_banned_group'] = "Mover al grupo de suspensión ({1}) durante {2} {3}";
$l['move_banned_group_permanent'] = "Mover al grupo suspendido ({1}) - Permanentemente";
$l['suspend_posting'] = "Suspender privilegios de escritura durante {1} {2}";
$l['suspend_posting_permanent'] = "Suspender privilegio de envio de mensajes - Permanentemente";
$l['moderate_new_posts'] = "Moderar sus nuevos mensajes durante {1} {2}";
$l['moderate_new_posts_permanent'] = "Moderar nuevos mensajes - Permanentemente";
$l['no_warning_levels'] = "Actualmente no hay niveles de advertencia en tu foro.";

$l['warning_type'] = "Tipo de advertencia";
$l['points'] = "Puntos";
$l['expires_after'] = "Caduca después de";
$l['no_warning_types'] = "Actualmente no hay tipos de advertencias en tu foro.";

$l['warning_points_percentage'] = "Porcentaje del máximo de puntos de advertencia";
$l['warning_points_percentage_desc'] = "Por favor, introduce un número entre 1 y 100.";
$l['action_to_be_taken'] = "Acción a realizar";
$l['action_to_be_taken_desc'] = "Selecciona la acción que deseas realizar cuando un usuario llegao supera el nivel.";
$l['ban_user'] = "Suspender usuario";
$l['banned_group'] = "Grupo de suspensión:";
$l['ban_length'] = "Duración de la suspensión:";
$l['suspend_posting_privileges'] = "Suspender privilegios de envío de mensajes";
$l['suspension_length'] = "Duración de la suspensión:";
$l['moderate_posts'] = "Moderar nuevos mensajes";
$l['moderation_length'] = "Duración de la moderación:";
$l['save_warning_level'] = "Guardar nivel de advertencia";

$l['title'] = "Título";
$l['points_to_add'] = "Puntos a agregar";
$l['points_to_add_desc'] = "Número de puntos que se le agregarán al nivel de advertencia del usuario.";
$l['warning_expiry'] = "Caducidad";
$l['warning_expiry_desc'] = "¿Cuanto tiempo debe pasar para que esta advertencia caduque?";
$l['save_warning_type'] = "Guardar tipo de advertencia";

$l['expiration_hours'] = "Hora(s)";
$l['expiration_days'] = "Día(s)";
$l['expiration_weeks'] = "Semana(s)";
$l['expiration_months'] = "Mes(s)";
$l['expiration_never'] = "Nunca";
$l['expiration_permanent'] = "Permanentemente";

$l['error_invalid_warning_level'] = "El nivel de advertencia especificado no existe.";
$l['error_invalid_warning_percentage'] = "No has introducido un porcentaje válido para este nivel de advertencia. El porcentaje debe estar entre 1 y 100.";
$l['error_invalid_warning_type'] = "El tipo de advertencia especificado no existe.";
$l['error_missing_type_title'] = "No has introducido un título para este tipo de advertencia";
$l['error_missing_type_points'] = "No has introducido un número de puntos válido para agregar con esta advertencia. Debe ser mayor de 0 pero menor de {1}";

$l['success_warning_level_created'] = "El nivel de advertencia se ha creado correctamente.";
$l['success_warning_level_updated'] = "El nivel de advertencia seleccionado se ha actualizado correctamente.";
$l['success_warning_level_deleted'] = "El nivel de advertencia seleccionado se ha eliminado correctamente.";
$l['success_warning_type_created'] = "El tipo de advertencia se ha creado correctamente.";
$l['success_warning_type_updated'] = "El nivel de advertencia seleccionado se ha actualizado correctamente.";
$l['success_warning_type_deleted'] = "El nivel de advertencia seleccionado se ha eliminado correctamente.";

$l['confirm_warning_level_deletion'] = "¿Estás seguro de querer eliminar este nivel de advertencia?";
$l['confirm_warning_type_deletion'] = "¿Estás seguro de querer eliminar este tipo de advertencia?";

?>