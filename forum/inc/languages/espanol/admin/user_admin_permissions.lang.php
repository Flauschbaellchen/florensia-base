<?php
/**
 * MyBB 1.6 Spanish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: user_admin_permissions.lang.php 5016 2010-08-12 12:32:33Z Anio_pke $
 */

$l['admin_permissions'] = "Permisos de los administradores";
$l['user_permissions'] = "Permisos de los usuarios";
$l['user_permissions_desc'] = "Aquí puedes configurar los permisos de los administradores. Esto te permite bloquear ciertas áreas a ciertos administradores.";
$l['group_permissions'] = "Permisos de los grupos";
$l['group_permissions_desc'] = "Los permisos de los grupos te permiten establecer los permisos de los grupos de usuarios con acceso al panel de administración. Puedes usar esta herramienta para bloquear ciertas áreas a los distintos grupos con acceso al panel de administración.";
$l['default_permissions'] = "Permisos predefinidos";
$l['default_permissions_desc'] = "Los permisos predefinidos se aplican a los administradores que no tengan sus permisos modificados o que no pertenezcan a ningún grupo de usuarios con los permisos modificados.";

$l['admin_permissions_updated'] = "Los permisos del administrador se han actualizado correctamente.";
$l['revoke_permissions'] = "Restablecer permisos";
$l['edit_permissions'] = "Editar permisos";
$l['set_permissions'] = "Establacer permisos";
$l['edit_permissions_desc'] = "Aquí puedes restringir el acceso a algunas pestañas o a páginas individuales. Ten en cuenta que la pestaña \"Portada\" es accesible por todos los administradores.";
$l['update_permissions'] = "Actualizar permisos";
$l['view_log'] = "Ver historial";
$l['permissions_type_group'] = "Tipo de permiso para el grupo";
$l['permissions_type_user'] = "Tipo de permiso para el usuario";
$l['no_group_perms'] = "Actualmente no hay establecido ningún permiso de grupo.";
$l['no_user_perms'] = "Actualmente no hay establecido ningún permiso de usuario.";
$l['edit_user'] = "Editar perfil";
$l['using_individual_perms'] = "Usando permisos individuales";
$l['using_custom_perms'] = "Usando permisos personalizados";
$l['using_group_perms'] = "Usando permisos del grupo";
$l['using_default_perms'] = "Usando permisos predefinidos";
$l['last_active'] = "Última visita";
$l['user'] = "Usuario";
$l['edit_group'] = "Editar grupo";
$l['default'] = "Predeterminado";
$l['group'] = "Grupo";

$l['error_delete_super_admin'] = 'Lo siento, pero no puedes realizar esta acción porque el usuario especificado es un super administrador.<br /><br />Para realizar esta acción, debes añadir tu ID de usuario a la lista de super administradores en inc/config.php.';
$l['error_delete_no_uid'] = 'No has introducido ninguna id de un permiso de administrador';
$l['error_delete_invalid_uid'] = 'No has introducido una id válida de un permiso de administrador';

$l['success_perms_deleted'] = 'Los permisos de administrador se han restablecido correctamente.';

$l['confirm_perms_deletion'] = "¿Estás seguro de querer restablecer estos permisos de administrador?";
$l['confirm_perms_deletion2'] = "¿Estás seguro de querer restablecer los permisos de administrador a este usuario?";

?>