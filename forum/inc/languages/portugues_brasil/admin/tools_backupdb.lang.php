<?php
/**
 * Idioma Português do Brasil para MyBB 1.6 - Versão 1.0
 * Tradutor: Speeder
 * Copyright © 2008 - 2010 MyBB Brasil - Todos os Direitos Reservados
 * http://www.mybb.com.br
 *
 * $Id: tools_backupdb.lang.php 1000 03/08/2010 16:25:00 Speeder $
 */


$l['database_backups'] = "Backups do Banco de Dados";
$l['database_backups_desc'] = "Aqui você encontra a lista de backups do banco de dados armazenados no seu servidor na pasta de backups do MyBB.";
$l['new_database_backup'] = "Novo Backup do Banco de Dados";
$l['new_backup'] = "Novo Backup";
$l['new_backup_desc'] = "Aqui você pode fazer um backup do seu banco de dados.";
$l['backups'] = "Backups";
$l['existing_database_backups'] = "Backups Existentes";

$l['backup_saved_to'] = "O backup foi salvo em:";
$l['download'] = "Download";
$l['table_selection'] = "Seleção de Tabela";
$l['backup_options'] = "Opções de Backup";
$l['table_select_desc'] = "Você pode selecionar as tabelas do banco de dados em que você deseja que as ações sejam executadas. Segure CTRL para selcionar várias tabelas.";
$l['select_all'] = "Selecionar Tudo";
$l['deselect_all'] = "Desmarcar Tudo";
$l['select_forum_tables'] = "Selecionar Tabelas do Fórum";
$l['file_type'] = "Tipo de Arquivo";
$l['file_type_desc'] = "Selecione o tipo de arquivo para salvar o backup do banco de dados.";
$l['gzip_compressed'] = "GZIP Compactado";
$l['plain_text'] = "Texto Puro";
$l['save_method'] = "Salvar Método";
$l['save_method_desc'] = "Selecione o método que você deseja usar para salvar o backup.";
$l['backup_directory'] = "Backup de Diretório";
$l['backup_contents'] = "Backup de Conteúdo";
$l['backup_contents_desc'] = "Selecione a informação que você deseja incluir no backup.";
$l['structure_and_data'] = "Estrutura e Dados";
$l['structure_only'] = "Somente Estrutura";
$l['data_only'] = "Somente Dados";
$l['analyze_and_optimize'] = "Analisar e Otimizar as Tabelas Selecionadas";
$l['analyze_and_optimize_desc'] = "Deseja que as tabelas sejam analisadas e otimizadas durante o backup?";
$l['perform_backup'] = "Executar Backup";
$l['backup_filename'] = "Arquivo de Backup";
$l['file_size'] = "Tamanho";
$l['creation_date'] = "Data de Criação";
$l['no_backups'] = "Não existem backups no momento.";

$l['error_file_not_specified'] = "Você não especificou um backup do banco de dados para baixar.";
$l['error_invalid_backup'] = "O arquivo de backup selecionado é invalído ou não existe.";
$l['error_backup_doesnt_exist'] = "O backup especificado não existe.";
$l['error_backup_not_deleted'] = "O backup não foi excluído.";
$l['error_tables_not_selected'] = "Você não selecionou nenhuma tabela para backup.";
$l['error_no_zlib'] = "A biblioteca zlib do PHP do seu servidor não está habilitada - você não pode criar arquivos de bakcup GZIP.";

$l['alert_not_writable'] = "Seu diretório de backup não permite gravação. Você não pode salvar backups no servidor.";

$l['confirm_backup_deletion'] = "Tem certeza que deseja excluir este backup?";

$l['success_backup_deleted'] = "O backup foi excluído com sucesso.";
$l['success_backup_created'] = "O backup foi criado com sucesso.";

?>