<?php
/**
 * Idioma Português do Brasil para MyBB 1.6 - Versão 1.0
 * Tradutor: Speeder
 * Copyright © 2008 - 2010 MyBB Brasil - Todos os Direitos Reservados
 * http://www.mybb.com.br
 *
 * $Id: tools_tasks.lang.php 1000 03/08/2010 16:25:00 Speeder $
 */

$l['task_manager'] = "Gerenciar Tarefas";
$l['add_new_task'] = "Adicionar Nova Tarefa";
$l['add_new_task_desc'] = "Aqui você pode criar novas tarefas agendadas que rodarão automáticamente no fórum.";
$l['edit_task'] = "Editar Tarefa";
$l['edit_task_desc'] = "Abaixo você pode editar as várias configurações para esta tarefa.";
$l['task_logs'] = "Logs de Tarefas";
$l['view_task_logs'] = "Ver Logs de Tarefas";
$l['view_task_logs_desc'] = "Quando uma tarefa é executada e o log estiver habilitado, quaisquer resultados e erros serão listados abaixo. Entradas mais antigas que 30 dias são automáticamente excluídas.";
$l['scheduled_tasks'] = "Tarefas Agendadas";
$l['scheduled_tasks_desc'] = "Aqui você pode gerenciar as tarefas que são executadas automáticamente no fórum. Para executar uma tarefa agora, clique no ícone ao lado direito do nome da tarefa.";

$l['title'] = "Título";
$l['short_description'] = "Descrição Curta";
$l['task_file'] = "Arquivo de Tarefa";
$l['task_file_desc'] = "Selecione o arquivo de tarefa que você deseja executar.";
$l['time_minutes'] = "Tempo: Minutos";
$l['time_minutes_desc'] = "Informe um conjunto de minutos separados por vírgula (0-59) nos quais a tarefa será executada. Digite '*' se esta tarefa deverá rodar a cada minuto.";
$l['time_hours'] = "Tempo: Horas";
$l['time_hours_desc'] = "Informe um conjunto de horas separadas por vírgula (0-23) nas quais a tarefa será executada. Digite '*' se esta tarefa deverá rodar a cada hora.";
$l['time_days_of_month'] = "Tempo: Dias do Mês";
$l['time_days_of_month_desc'] = "Informe um conjunto de dias separados por vírgula (1-31) nos quais a tarefa será executada. Digite '*' se esta tarefa deverá rodar a cada dia ou se desejar especificar um dia abaixo.";
$l['every_weekday'] = "Toda Semana";
$l['sunday'] = "Domingo";
$l['monday'] = "Segunda";
$l['tuesday'] = "Terça";
$l['wednesday'] = "Quarta";
$l['thursday'] = "Quinta";
$l['friday'] = "Sexta";
$l['saturday'] = "Sábado";
$l['time_weekdays'] = "Tempo: Dias da Semana";
$l['time_weekdays_desc'] = "Selecione os dias da semana que esta tarefa deverá executar. Segure CTRL para selecionoar mais de um dia. Selecione 'Todos os dias' se você desejar executar a tarefa todos os dias ou se informou um dia pré-definido acima.";
$l['every_month'] = "Todo Mês";
$l['time_months'] = "Tempo: Meses";
$l['time_months_desc'] = "Selecione os meses que esta tarefa deverá executar. Segure CTRL para selecionoar mais de um mês. Selecione 'Todos os meses' se desejar executar a tarefa todos os meses.";
$l['enabled'] = "Tarefa habilitada?";
$l['enable_logging'] = "Habilitar Log?";
$l['save_task'] = "Salvar Tarefa";
$l['task'] = "Tarefa";
$l['date'] = "Data";
$l['data'] = "Dados";
$l['no_task_logs'] = "Não existem logs existentes para nenhuma das tarefas agendadas.";
$l['next_run'] = "Próxima Execução";
$l['run_task_now'] = "Executar tarefa agora";
$l['run_task'] = "Executar";
$l['disable_task'] = "Desativar Tarefa";
$l['enable_task'] = "Habilitar Tarefa";
$l['delete_task'] = "Excluir Tarefa";
$l['alt_enabled'] = "Habilitada";
$l['alt_disabled'] = "Desabilitada";

$l['error_invalid_task'] = "A tarefa especificada não existe.";
$l['error_missing_title'] = "Você não informou um título para esta tarefa agendada";
$l['error_missing_description'] = "Você não informou uma descrição para esta tarefa a agendada";
$l['error_invalid_task_file'] = "O arquivo de tarefa selecionado não existe.";
$l['error_invalid_minute'] = "O minuto informado é inválido.";
$l['error_invalid_hour'] = "A hora informada é inválida.";
$l['error_invalid_day'] = "O dia informado é inválido.";
$l['error_invalid_weekday'] = "O dia da semana informado é inválido.";
$l['error_invalid_month'] = "O mês selecionado é inválido.";

$l['success_task_created'] = "A tarefa foi criada com sucesso.";
$l['success_task_updated'] = "A tarefa foi atualizada com sucesso.";
$l['success_task_deleted'] = "A tarefa foi excluída com sucesso.";
$l['success_task_enabled'] = "A tarefa selecionada foi habilitada com sucesso.";
$l['success_task_disabled'] = "A tarefa selecionada foi desabilitada com sucesso.";
$l['success_task_run'] = "A tarefa selecionada foi executada com sucesso.";

$l['confirm_task_deletion'] = "Tem certeza que deseja excluir esta tarefa agendada?";
$l['confirm_task_enable'] = "<strong>AVISO:</strong> Você está a ponto de habilitar uma tarefa que só poderia ser executada via cron (Por favor, acesse o <a href=\"http://wiki.mybboard.net/\" target=\"_blank\">Wiki</a> para mais informações). Continuar?";

?>