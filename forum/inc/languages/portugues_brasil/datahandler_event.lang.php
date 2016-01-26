<?php
/**
 * Idioma Português do Brasil para MyBB 1.6 - Versão 1.0
 * Tradutor: Speeder
 * Copyright © 2008 - 2010 MyBB Brasil - Todos os Direitos Reservados
 * http://www.mybb.com.br
 *
 * $Id: datahandler_event.lang.php 1000 03/08/2010 16:25:00 Speeder $
 */

$l['eventdata_missing_name'] = "O nome do evento está faltando. Por favor informe um nome para o evento.";
$l['eventdata_missing_description'] = "A descrição do evento está faltando. Por favor informe uma descrição para o evento.";

$l['eventdata_invalid_start_date'] = "A data de início do evento é inválida. Tenha certeza de informar um dia, mês e ano e que o dia informado seja válido para o mês em questão.";
$l['eventdata_invalid_start_year'] = "Eventos podem ser criados somente para os próximos 5 anos. Por favor escolha um ano válido na lista.";
$l['eventdata_invalid_start_month'] = "O mês de início escolhido não é válido. Por favor informe um mês válido.";

$l['eventdata_invalid_end_date'] = "A data de término do evento não é válida. Tenha certeza de informar um dia, mês e ano e que o dia informado seja válido para o mês em questão.";
$l['eventdata_invalid_end_year'] = "Eventos podem ser criados somente para os próximos 5 anos. Por favor escolha um ano válido na lista.";
$l['eventdata_invalid_end_month'] = "O mês de término escolhido não é válido. Por favor informe um mês válido.";
$l['eventdata_invalid_end_day'] = "O dia de término escolhido não é válido. O dia selecionado provalmente é maior que o número de dias do mês em questão.";

$l['eventdata_cant_specify_one_time'] = "Se você está especificando um horário de início você precisa especificar um horário de término para o evento.";
$l['eventdata_start_time_invalid'] = "O horário de início é inválido. Exemplos válidos: 12am, 12:01am, 00:01.";
$l['eventdata_end_time_invalid'] = "O horário de término é inválido. Exemplos válidos: 12am, 12:01am, 00:01.";
$l['eventdata_invalid_timezone'] = "O fuso horário para este evento é inválido.";
$l['eventdata_end_in_past'] = "A data de término ou horário para o evento é antes da data ou horário de início do mesmo.";

$l['eventdata_only_ranged_events_repeat'] = "Somente eventos com data de início e fim podem repetir.";
$l['eventdata_invalid_repeat_day_interval'] = "Você informou um intervalo de repetição de dias inválido.";
$l['eventdata_invalid_repeat_week_interval'] = "Você informou um intervalo de repetição de semanas inválido.";
$l['eventdata_invalid_repeat_weekly_days'] = "Você não selecionou nenhuma semana para este evento ocorrer.";
$l['eventdata_invalid_repeat_month_interval'] = "Você informou um intervalo de repetição de meses inválido.";
$l['eventdata_invalid_repeat_year_interval'] = "Você informou um intervalo de repetição de anos inválido.";
$l['eventdata_event_wont_occur'] = "Usando as configurações de início/fim e repetição, este evento não irá ocorrer.";

$l['eventdata_no_permission_private_event'] = "Você não tem permissão para postar eventos privados.";
?>