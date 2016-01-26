<?php
/**
 * Idioma Português do Brasil para MyBB 1.6 - Versão 1.0
 * Tradutor: Speeder
 * Copyright © 2008 - 2010 MyBB Brasil - Todos os Direitos Reservados
 * http://www.mybb.com.br
 *
 * $Id: user_admin_permissions.lang.php 1000 03/08/2010 16:25:00 Speeder $
 */

$l['admin_permissions'] = "Permissões de Admin";
$l['user_permissions'] = "Permissões de Usuário";
$l['user_permissions_desc'] = "Aqui você pode gerenciar as permissões de administrador para usuários individuais. Isto lhe permite bloquear certas áreas do Admin CP para certos administradores.";
$l['group_permissions'] = "Permissões de Grupo";
$l['group_permissions_desc'] = "Permissões de administrador também podem ser aplicadas a grupos que tem permissão de acesso ao Admin CP. Isto lhe permite bloquear certas áreas do Admin CP para certos grupos.";
$l['default_permissions'] = "Permissões Padrão";
$l['default_permissions_desc'] = "As permissões administrativas padrão são aquelas aplicadas a usuários que não tem permissões administrativas customizadas definidas ou herdadas de um grupo administrativo.";

$l['admin_permissions_updated'] = "As permissões de admin foram atualizadas com sucesso.";
$l['revoke_permissions'] = "Revogar Permissões";
$l['edit_permissions'] = "Editar Permissões";
$l['set_permissions'] = "Definir Permissões";
$l['edit_permissions_desc'] = "Aqui você pode restringir o acesso a abas inteiras ou páginas individuais. Saiba apenas que a aba \"Início\" é acessível a todos os administradores.";
$l['update_permissions'] = "Atualizar Permissões";
$l['view_log'] = "Visualizar Log";
$l['permissions_type_group'] = "Tipo de Permissão do grupo";
$l['permissions_type_user'] = "Tipo de Permissão do usuário";
$l['no_group_perms'] = "Não existem permissões de grupo definidas.";
$l['no_user_perms'] = "Não existem permissões de usuário definidas.";
$l['edit_user'] = "Editar Perfil do Usuário";
$l['using_individual_perms'] = "Usando Permissões Individuais";
$l['using_custom_perms'] = "Usando Permissões Customizadas";
$l['using_group_perms'] = "Usuando Permissões de Grupo";
$l['using_default_perms'] = "Usando Permissões Padrão";
$l['last_active'] = "Última Vez Ativo";
$l['user'] = "Usuário";
$l['edit_group'] = "Editar Grupo";
$l['default'] = "Padrão";
$l['group'] = "Grupo";

$l['error_delete_super_admin'] = 'Lamentamos, mas você não pode executar esta ação por que o usuário especificado é um super administrador.<br /><br />Para executar esta ação, solicite a um super administrador que adicione seu usuário na lista de super admins.';
$l['error_delete_no_uid'] = 'Você não informou uma id de admin de usuário/grupo';
$l['error_delete_invalid_uid'] = 'Você não informou um id de admin de usuário/grupo válida';

$l['success_perms_deleted'] = 'As permissões de admin de usuário/grupo foram revogadas com sucesso.';

$l['confirm_perms_deletion'] = "Tem certeza que deseja revogar as permissões de admin de usuário/grupo?";
$l['confirm_perms_deletion2'] = "Tem certeza que deseja revogar as permissões deste usuário?";

?>