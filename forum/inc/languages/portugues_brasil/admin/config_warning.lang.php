<?php
/**
 * Idioma Português do Brasil para MyBB 1.6 - Versão 1.0
 * Tradutor: Speeder
 * Copyright © 2008 - 2010 MyBB Brasil - Todos os Direitos Reservados
 * http://www.mybb.com.br
 *
 * $Id: config_warning.lang.php 1000 03/08/2010 16:25:00 Speeder $
 */

$l['warning_system'] = "Sistema de Alertas";
$l['warning_types'] = "Gerenciar";
$l['warning_types_desc'] = "Aqui você pode gerenciar a lista de diferentes tipos de alertas que a equipe do fórum poderá usar.";
$l['add_warning_type'] = "Adicionar Novo Alerta";
$l['add_warning_type_desc'] = "Aqui você pode criar um novo tipo de alerta pré-definido. Tipos de alerta são selecionáveis quando um usuário é alertado e você pode definir o número de pontos a adicionar e também o período de tempo necessário para o alerta expirar.";
$l['edit_warning_type'] = "Editar Alerta";
$l['edit_warning_type_desc'] = "Aqui você pode editar o tipo de alerta.  Tipos de alerta são selecionáveis quando um usuário é alertado e você pode definir o número de pontos a adicionar e também o período de tempo necessário para o alerta expirar.";
$l['warning_levels'] = "Níveis de Alerta";
$l['warning_levels_desc'] = "Níveis de alerta definem o que acontecerá quando um usuário atingir um determinado nível (porcentagem do máximo de pontos de alerta). Você pode banir ou suspender privilégios.";
$l['add_warning_level'] = "Adicionar Novo Nível de Alerta";
$l['add_warning_level_desc'] = "Aqui você pode criar um novo nível de alerta. Níveis de alerta são ações a serem tomadas contra usuários que atingiram uma porcentagem específica do máximo nível de alerta.";
$l['edit_warning_level'] = "Editar Nível de Alerta";
$l['edit_warning_level_desc'] = "Níveis de alerta são ações a serem tomadas contra usuários que atingiram uma porcentagem específica do máximo nível de alerta.";

$l['percentage'] = "Porcentagem";
$l['action_to_take'] = "Ação a Tomar";
$l['move_banned_group'] = "Mover para o grupo banido ({3}) para {1} {2}";
$l['move_banned_group_permanent'] = "Mover para o grupo banido ({1}) Permanentemente";
$l['suspend_posting'] = "Suspender privilégios de postagem para {1} {2}";
$l['suspend_posting_permanent'] = "Suspender privilégios de postagem permanentemente";
$l['moderate_new_posts'] = "Moderar novas respostas para {1} {2}";
$l['moderate_new_posts_permanent'] = "Moderar novas respostas permanentemente";
$l['no_warning_levels'] = "Não existe nenhum nível de alerta configurado no momento.";

$l['warning_type'] = "Tipo de Alerta";
$l['points'] = "Pontos";
$l['expires_after'] = "Expira Após";
$l['no_warning_types'] = "Não existe nenhum tipo de alerta configurado no momento.";

$l['warning_points_percentage'] = "Porcentagem do Máximo de Pontos de Alerta";
$l['warning_points_percentage_desc'] = "Por favor informe um valor número entre 1 e 100.";
$l['action_to_be_taken'] = "Ação a Tomar";
$l['action_to_be_taken_desc'] = "Selecione a ação que você deseja que seja executada quando os usuários atingirem o nível acima.";
$l['ban_user'] = "Banir Usuário";
$l['banned_group'] = "Grupo banido:";
$l['ban_length'] = "Tempo de banimento:";
$l['suspend_posting_privileges'] = "Suspender Privilégios de Postagem";
$l['suspension_length'] = "Tempo de Suspensão:";
$l['moderate_posts'] = "Moderar Respostas";
$l['moderation_length'] = "Tempo de moderação:";
$l['save_warning_level'] = "Salvar Nível de Alerta";

$l['title'] = "Título";
$l['points_to_add'] = "Pontos a Adicionar";
$l['points_to_add_desc'] = "O número de pontos a adicionar no nível de alerta dos usuários.";
$l['warning_expiry'] = "Expiração do Alerta";
$l['warning_expiry_desc'] = "Quanto tempo deve decorrer para o alerta expirar?";
$l['save_warning_type'] = "Salvar Tipo de Alerta";

$l['expiration_hours'] = "Hora(s)";
$l['expiration_days'] = "Dia(s)";
$l['expiration_weeks'] = "Semana(s)";
$l['expiration_months'] = "Mês(es)";
$l['expiration_never'] = "Nunca";
$l['expiration_permanent'] = "Permanente";

$l['error_invalid_warning_level'] = "O nível de alerta especificado não existe.";
$l['error_invalid_warning_percentage'] = "Você não informou uma porcentagem válida para este nível de alerta. Use um número entre 1 e 100.";
$l['error_invalid_warning_type'] = "O tipo de alerta especificado não existe.";
$l['error_missing_type_title'] = "Você não informou o título para este tipo de alerta";
$l['error_missing_type_points'] = "Você não informou um número válido de pontos a serem adicionados quando este alerta for utilizado. Use um número maior que 0 e menor que {1}";

$l['success_warning_level_created'] = "Este nível de alerta foi criado com sucesso.";
$l['success_warning_level_updated'] = "Este nível de alerta foi atualizado com sucesso.";
$l['success_warning_level_deleted'] = "O nível de alerta selecionado foi excluído com sucesso.";
$l['success_warning_type_created'] = "O tipo de alerta foi criado com sucesso.";
$l['success_warning_type_updated'] = "O tipo de alerta foi atualizado com sucesso.";
$l['success_warning_type_deleted'] = "O tipo de alerta selecionado foi excluído com sucesso.";

$l['confirm_warning_level_deletion'] = "Tem certeza que deseja excluir este nível de alerta?";
$l['confirm_warning_type_deletion'] = "Tem certeza que deseja excluir este tipo de alerta?";

?>