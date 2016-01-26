<?php
/**
 * Idioma Português do Brasil para MyBB 1.6 - Versão 1.0
 * Tradutor: Speeder
 * Copyright © 2008 - 2010 MyBB Brasil - Todos os Direitos Reservados
 * http://www.mybb.com.br
 *
 * $Id: forum_attachments.lang.php 1000 03/08/2010 16:25:00 Speeder $
 */

// Tabs
$l['attachments'] = "Anexos";
$l['stats'] = "Estatísticas";
$l['find_attachments'] = "Encontrar Anexos";
$l['find_attachments_desc'] = "Use o sistema de pesquisa de anexos para encontrar arquivos específicos anexados em seu fórum. Inicie digitando algum termo de busca abaixo. Todos os campos são opcionais e não serão incluídos na busca até que sejam preenchidos.";
$l['find_orphans'] = "Encontrar Anexos Orfãos";
$l['find_orphans_desc'] = "Anexos Orfãos são aqueles que por algum motivo estão faltando no banco de dados ou no sistema de arquivos. Esta ferramente permite localizar os mesmos e excluí-los.";
$l['attachment_stats'] = "Estatísticas de Anexos";
$l['attachment_stats_desc'] = "Abaixo são mostradas estatísticas gerais sobre os anexos do seu fórum.";

// Errors
$l['error_nothing_selected'] = "Por favor selecione um ou mais anexos para excluir.";
$l['error_no_attachments'] = "Não existem anexos no seu fórum no momento. Assim que um anexo for postado você poderá acessar esta seção.";
$l['error_not_all_removed'] = "Somente alguns anexos orfãos foram excluídos com sucesso. Outros não foram excluídos do diretório de uploads devido a um problema desconhecido.";
$l['error_invalid_username'] = "O nome de usuário que informou é inválido.";
$l['error_invalid_forums'] = "Um ou mais fóruns selecinados são inválidos.";
$l['error_no_results'] = "Nenhum anexo encontrado com o critério usado.";
$l['error_not_found'] = "O arquivo anexo não foi encontrado no diretório de uploads.";
$l['error_not_attached'] = "O anexo foi enviado a mais de 24 horas mas não foi anexado a nenhuma resposta.";
$l['error_does_not_exist'] = "O tópico ou resposta para este anexo não existe mais.";

// Success
$l['success_deleted'] = "O anexo selecionado foi excluído com sucesso.";
$l['success_orphan_deleted'] = "Os anexos orfãos selecinados foram excluídos com sucesso.";
$l['success_no_orphans'] = "Não existem anexos orfãos no seu fórum.";

// Confirm
$l['confirm_delete'] = "Tem certeza que deseja excluir os anexos selecionados?";

// == Pages
// = Stats
$l['general_stats'] = "Estatísticas Gerais";
$l['stats_attachment_stats'] = "Anexos - Estatísticas";
$l['num_uploaded'] = "<strong>Total de Anexos Enviados</strong>";
$l['space_used'] = "<strong>Armazenamento Utilizado</strong>";
$l['bandwidth_used'] = "<strong>Uso Estimado de Banda</strong>";
$l['average_size'] = "<strong>Tamanho Médio dos Anexos</strong>";
$l['size'] = "Tamanho";
$l['posted_by'] = "Postado Por";
$l['thread'] = "Tópico";
$l['downloads'] = "Downloads";
$l['date_uploaded'] = "Data de Envio";
$l['popular_attachments'] = "5 Anexos Mais Populares";
$l['largest_attachments'] = "5 Maiores Anexos";
$l['users_diskspace'] = "5 Usuários Usando Mais Armazenamento";
$l['username'] = "Usuário";
$l['total_size'] = "Tamanho Total";

// = Orphans
$l['orphan_results'] = "Pesquisa de Anexos Orfãos - Resultados";
$l['orphan_attachments_search'] = "Pesquisa de Anexos Orfãos";
$l['reason_orphaned'] = "Motivo";
$l['reason_not_in_table'] = "Inexistente na tabela de anexos";
$l['reason_file_missing'] = "Arquivo do anexo faltando";
$l['reason_thread_deleted'] = "Tópico excluído";
$l['reason_post_never_made'] = "Resposta nunca postada";
$l['unknown'] = "Desconhecido";
$l['results'] = "Resultados";
$l['step1'] = "Passo 1";
$l['step2'] = "Passo 2";
$l['step1of2'] = "Passo 1 de 2 - Pesquisa no Sistema de Arquivos";
$l['step2of2'] = "Passo 2 de 2 - Pesquisa no Banco de Dados";
$l['step1of2_line1'] = "Por favor aguarde. Escaneando o sistema de arquivos em busca de anexos orfãos.";
$l['step2of2_line1'] = "Por favor aguarde. Escaneando o banco de dados em busca de anexos orfãos.";
$l['step_line2'] = "Você será redirecionado para o próximo passo assim que este processo estiver concluído.";

// = Attachments / Index
$l['index_find_attachments'] = "Anexos - Pesquisar";
$l['find_where'] = "Encontrar anexos onde...";
$l['name_contains'] = "Nome do Arquivo contem";
$l['name_contains_desc'] = "Para pesquisar por tipo de arquivo use *.[extensão do arquivo]. Examplo: *.zip.";
$l['type_contains'] = "Tipo de arquivo contem";
$l['forum_is'] = "Forum é";
$l['username_is'] = "Nome de Usuário é";
$l['more_than'] = "Mais que";
$l['greater_than'] = "Maior que";
$l['is_exactly'] = "é exatamente";
$l['less_than'] = "Menos que";
$l['date_posted_is'] = "Data de postagem é";
$l['days_ago'] = "dias atrás";
$l['file_size_is'] = "Tamanho do arquivo é";
$l['kb'] = "KB";
$l['download_count_is'] = "Total de Downloads é";
$l['display_options'] = "Opções de Visualização";
$l['filename'] = "Nome do Arquivo";
$l['filesize'] = "Tamanho do Arquivo";
$l['download_count'] = "Downloads";
$l['post_username'] = "Usuário";
$l['asc'] = "Ascendente";
$l['desc'] = "Descendente";
$l['sort_results_by'] = "Organizar Resultados por";
$l['results_per_page'] = "Resultados por página";
$l['in'] = "in";

// Buttons
$l['button_delete_orphans'] = "Excluir Orfãos Marcados";
$l['button_delete_attachments'] = "Excluir Anexos Marcados";
$l['button_find_attachments'] = "Encontrar Anexos";

?>