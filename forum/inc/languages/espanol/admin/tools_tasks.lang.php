<?php
/**
 * MyBB 1.6 Spanish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: tools_tasks.lang.php 5016 2010-08-12 12:32:33Z Anio_pke $
 */

$l['task_manager'] = "Tareas programadas";
$l['add_new_task'] = "Agregar tarea";
$l['add_new_task_desc'] = "Aquí puedes crear una nueva tarea programada que se ejeutarán automáticamente en tu foro.";
$l['edit_task'] = "Editar tarea";
$l['edit_task_desc'] = "Abajo puedes editar varios ajustes de esta tarea programada.";
$l['task_logs'] = "Historial de tareas";
$l['view_task_logs'] = "Ver historial de tareas";
$l['view_task_logs_desc'] = "Cuando una tarea se ejecuta y el historial está activado, cualquier resultado o error se mostrarán abajo. Las entradas de más de 30 días se eliminan automáticamente.";
$l['scheduled_tasks'] = "Tareas programadas";
$l['scheduled_tasks_desc'] = "Aquí puedes configurar las tareas que se ejecutarán automáticamente en tu foro. Para ejecutar una tarea haz clic en el icono de la derecha (despertador) en la tarea.";

$l['title'] = "Título";
$l['short_description'] = "Descripción corta";
$l['task_file'] = "Archivo de tarea";
$l['task_file_desc'] = "Selecciona el archivo de tarea que deseas ejecutar.";
$l['time_minutes'] = "Tiempo: minutos";
$l['time_minutes_desc'] = "Introduce una coma para separar una lista de minutos (0-59) en los que se ejecuta esta tarea. Introduce '*' para que esta tarea se ejecute en todos los minutos.";
$l['time_hours'] = "Tiempo: horas";
$l['time_hours_desc'] = "Introduce una coma para separar una lista de horas (0-23) en los que se ejecuta esta tarea. Introduce '*' para que esta tarea se ejecute todas las horas.";
$l['time_days_of_month'] = "Tiempo: días del mes";
$l['time_days_of_month_desc'] = "Introduce una coma para separar una lista de días (1-31) en los que se ejecuta esta tarea. Introduce '*' para que esta tarea se ejecute todos los días o puedes especificar que día de la semana.";
$l['every_weekday'] = "Todos los días";
$l['sunday'] = "Domingo";
$l['monday'] = "Lunes";
$l['tuesday'] = "Martes";
$l['wednesday'] = "Miércoles";
$l['thursday'] = "Jueves";
$l['friday'] = "Viernes";
$l['saturday'] = "Sábado";
$l['time_weekdays'] = "Tiempo: días de la semana";
$l['time_weekdays_desc'] = "Selecciona que días de la semana se ejecuta esta tarea. Presiona CTRL para seleccionar múltiple días. Selecciona 'Todos los días' si quieres que se ejecute todos los dias de la semana o introduce un día predefinido arriba.";
$l['every_month'] = "Todos los meses";
$l['time_months'] = "Tiempo: meses";
$l['time_months_desc'] = "Selecciona los meses en los que se ejecuta esta tarea. Presiona CTRL para seleccionar múltiple meses. Selecciona 'Todos los meses' si quieres que se ejecute todos los meses del año.";
$l['enabled'] = "¿Activar tarea?";
$l['enable_logging'] = "¿Activar historial?";
$l['save_task'] = "Guardar tarea";
$l['task'] = "Tarea";
$l['date'] = "Fecha";
$l['data'] = "Datos";
$l['no_task_logs'] = "Actualmente no hay historial de tareas para ninguna de las tareas programadas.";
$l['next_run'] = "Próxima ejecución";
$l['run_task_now'] = "Ejecutar ahora";
$l['run_task'] = "Ejecutar tarea";
$l['disable_task'] = "Desactivar tarea";
$l['enable_task'] = "Activar tarea";
$l['delete_task'] = "Eliminar tarea";
$l['alt_enabled'] = "Activada";
$l['alt_disabled'] = "Desactivada";

$l['error_invalid_task'] = "La tarea especificada no existe.";
$l['error_missing_title'] = "No has introducido un título para esta tarea programada";
$l['error_missing_description'] = "No has introducido una descripción para esta tarea programada";
$l['error_invalid_task_file'] = "Al archivo de tarea seleccionado no existe.";
$l['error_invalid_minute'] = "El minuto que has introducido es inválido.";
$l['error_invalid_hour'] = "La hora que has introducido es inválida.";
$l['error_invalid_day'] = "El día que has introducido es inválido.";
$l['error_invalid_weekday'] = "El día de la semana que has seleccionado es inválido.";
$l['error_invalid_month'] = "El mes que has seleccionado es inválido.";

$l['success_task_created'] = "La tarea se ha creado correctamente.";
$l['success_task_updated'] = "La tarea seleccionada se ha actualizado correctamente.";
$l['success_task_deleted'] = "La tarea seleccionada se ha eliminado correctamente.";
$l['success_task_enabled'] = "La tarea seleccionada se ha activado correctamente.";
$l['success_task_disabled'] = "La tarea seleccionada se ha desactivado correctamente.";
$l['success_task_run'] = "La tarea seleccionada se ha ejecutado correctamente.";

$l['confirm_task_deletion'] = "¿Estás seguro de querer eliminar esta tarea programada?";
$l['confirm_task_enable'] = "<strong>ADVERTENCIA:</strong> Estás activando una tarea que solo se puede ejecutar a través de cron (Por favor, visita el <a href=\"http://wiki.mybboard.net/\" target=\"_blank\">MyBB Wiki</a> para más información). ¿Continuar?";

?>