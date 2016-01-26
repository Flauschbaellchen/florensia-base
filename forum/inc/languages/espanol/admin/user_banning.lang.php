<?php
/**
 * MyBB 1.6 Spanish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: user_banning.lang.php 5016 2010-06-12 12:32:33Z Anio_pke $
 */

// Tabs
$l['banning'] = "Suspensiones";
$l['banned_accounts'] = "Cuentas suspendidas";
$l['banned_accounts_desc'] = "Aquí puedes ver, levantar o editar las suspensiones de los usuarios de tu foro.";
$l['ban_a_user'] = "Suspender usuario";
$l['ban_a_user_desc'] = "Aquí puedes suspender un usuario.";
$l['edit_ban'] = "Editar suspensión";
$l['edit_ban_desc'] = "Aquí puedes cambiar la razón y la duración de los usuarios suspendidos.";
$l['banned_ips'] = "IPs suspendidas";
$l['disallowed_usernames'] = "Deshabilitar nombres";
$l['disallowed_email_addresses'] = "Deshabilitar emails";

// Errors
$l['error_invalid_ban'] = "Has seleccionado una suspensión inválida para editar.";
$l['error_invalid_username'] = "El nombre de usuario introducido es inválido o no existe.";
$l['error_no_perm_to_ban'] = "No tienes permisos para suspender a este usuario.";
$l['error_already_banned'] = "Este usuario ya está suspendido.";
$l['error_ban_self'] = "No puedes suspenderte a ti mismo.";
$l['error_no_reason'] = "No has introducido una razón para suspender a este usuario.";

// Success
$l['success_ban_lifted'] = "La suspensión se ha levantado correctamente.";
$l['success_banned'] = "El usuario ha sido suspendido correctamente.";
$l['success_ban_updated'] = "La suspensión se ha actualizado correctamente.";
$l['success_pruned'] = "Los temas y mensajes del usuario seleccionado se han borrado correctamente.";

// Confirm
$l['confirm_lift_ban'] = "¿Estás seguro de querer levantar esta suspensión?";
$l['confirm_prune'] = "¿Estás seguro de querer borrar todos los temas y mensajes de este usuario?";

//== Pages
//= Add / Edit
$l['ban_username'] = "Nombre de usuario <em>*</em>";
$l['autocomplete_enabled'] = "Autocompletar activado en este campo.";
$l['ban_reason'] = "Razón";
$l['ban_group'] = "Grupo de suspensión <em>*</em>";
$l['ban_group_desc'] = "Si quieres, puedes mover este usuario a otro grupo de suspendidos.";
$l['ban_time'] = "Duración <em>*</em>";

//= Index
$l['user'] = "Usuario";
$l['moderation'] = "Moderación";
$l['ban_lifts_on'] = "Termina el";
$l['time_left'] = "Tiempo restante";
$l['permenantly'] = "permanentemente";
$l['na'] = "N/A";
$l['for'] = "durante";
$l['bannedby_x_on_x'] = "<strong>{1}</strong><br /><small>Suspendido por {2} {3} {4}</small>";
$l['lift'] = "Levantar";
$l['no_banned_users'] = "Actualmente no hay ningún usuario suspendido.";
$l['prune_threads_and_posts'] = "Borrar temas y mensajes";

// Buttons
$l['ban_user'] = "Suspender usuario";
$l['update_ban'] = "Actualizar suspensión";

?>