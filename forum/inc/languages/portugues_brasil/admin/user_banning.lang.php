<?php
/**
 * Idioma Português do Brasil para MyBB 1.6 - Versão 1.0
 * Tradutor: Speeder
 * Copyright © 2008 - 2010 MyBB Brasil - Todos os Direitos Reservados
 * http://www.mybb.com.br
 *
 * $Id: user_banning.lang.php 1000 03/08/2010 16:25:00 Speeder $
 */

// Tabs
$l['banning'] = "Banimento";
$l['banned_accounts'] = "Contas Banidas";
$l['banned_accounts_desc'] = "Aqui você pode gerenciar as contas de usuários banidas.";
$l['ban_a_user'] = "Banir Usuário";
$l['ban_a_user_desc'] = "Aqui você pode banir um usuário.";
$l['edit_ban'] = "Editar Banimento";
$l['edit_ban_desc'] = "Aqui você pode editar o motivo e o tempo de banimento dos usuários.";
$l['banned_ips'] = "IPs Banidos";
$l['disallowed_usernames'] = "Nomes de Usuários Bloqueados";
$l['disallowed_email_addresses'] = "Emails Bloqueados";

// Errors
$l['error_invalid_ban'] = "Você selecionou um banimento inválido para editar.";
$l['error_invalid_username'] = "O nome de usuário selecionado não existe ou é inválido.";
$l['error_no_perm_to_ban'] = "Você não tem permissão para banir este usuário.";
$l['error_already_banned'] = "Este usuário pertence a um grupo banido e não pode ser adicionado a outro.";
$l['error_ban_self'] = "Você não pode banir a si mesmo.";
$l['error_no_reason'] = "Você não informou um motivo para banir este usuário.";

// Success
$l['success_ban_lifted'] = "O banimento selecionado foi cancelado com sucesso.";
$l['success_banned'] = "O usuário selecionado foi banido com sucesso.";
$l['success_ban_updated'] = "O banimento selecionado foi atualizado com sucesso.";
$l['success_pruned'] = "As respostas e tópicos selecionados do usuário foram excluídos com sucesso.";

// Confirm
$l['confirm_lift_ban'] = "Tem certeza que deseja cancelar este banimento?";
$l['confirm_prune'] = "Tem certeza que deseja excluir todos os tópicos e respostas do usuário?";

//== Pages
//= Add / Edit
$l['ban_username'] = "Nome de Usuário <em>*</em>";
$l['autocomplete_enabled'] = "Auto-completar está habilitado neste campo.";
$l['ban_reason'] = "Motivo do Usuário";
$l['ban_group'] = "Grupo Banido <em>*</em>";
$l['ban_group_desc'] = "Para que este usuário seja banido ele deve ser movido para um grupo banido.";
$l['ban_time'] = "Tempo de Banimento <em>*</em>";

//= Index
$l['user'] = "Usuário";
$l['moderation'] = "Moderação";
$l['ban_lifts_on'] = "Fim do Banimento";
$l['time_left'] = "Tempo Restante";
$l['permenantly'] = "permanentemente";
$l['na'] = "N/D";
$l['for'] = "para";
$l['bannedby_x_on_x'] = "<strong>{1}</strong><br /><small>Banido por {2} em {3} {4}</small>";
$l['lift'] = "Termina";
$l['no_banned_users'] = "Você não tem usuários banidos neste momento.";
$l['prune_threads_and_posts'] = "Excluir Tópicos e Respostas";

// Buttons
$l['ban_user'] = "Banir Usuário";
$l['update_ban'] = "Atualizar Banimento";

?>