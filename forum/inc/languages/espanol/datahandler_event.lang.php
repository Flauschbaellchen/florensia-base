<?php
/**
 * MyBB 1.6 Spanish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 *
 * $Id: datahandler_event.lang.php 5016 2010-08-10 12:32:33Z Anio_pke $
 */

$l['eventdata_missing_name'] = 'No has introducido un nombre para este evento. Por favor, introduce un nombre.';
$l['eventdata_missing_description'] = 'No has introducido la descripción para el evento. Por favor, introduce la descripción.';

$l['eventdata_invalid_start_date'] = 'La fecha de inicio que has elegido para el evento es inválida. Por favor, asegúrate que el día, mes y año que has seleccionado son válidos para este mes.';
$l['eventdata_invalid_start_year'] = "Solo puedes crear eventos con 5 años de antelación. Por favor, selecciona un año razonable de la lista.";
$l['eventdata_invalid_start_month'] = 'El mes de inicio que has elegido no es válido. Por favor, selecciona un mes válido.';

$l['eventdata_invalid_end_date'] = 'La fecha de finalización que has elegido para el evento es inválida. Por favor, asegúrate que el día, mes y año que has seleccionado son válidos para este mes.';
$l['eventdata_invalid_end_year'] = "Solo puedes crear events con 5 años de antelación. Por favor, selecciona un año razonable de la lista.";
$l['eventdata_invalid_end_month'] = 'El mes de finalización que has elegido no es válido. Por favor, selecciona un mes válido.';
$l['eventdata_invalid_end_day'] = 'El día que has elegido no es válido. Probablemente el día es mayor que el número de días de este mes.';

$l['eventdata_cant_specify_one_time'] = "Si especificas una fecha de inicio también debes seleccionar una fecha de finalización.";
$l['eventdata_start_time_invalid'] = "La hora de inicio es inválida. Unos ejemplos válidos son 12am, 12:01am, 00:01.";
$l['eventdata_end_time_invalid'] = "La hora de finalización es inválida. Unos ejemplos válidos son 12am, 12:01am, 00:01.";
$l['eventdata_invalid_timezone'] = "La zona horaria seleccionada para este evento es inválida.";
$l['eventdata_end_in_past'] = "La fecha o la hora de finalización es anterior a la fecha o a la hora de inicio.";

$l['eventdata_only_ranged_events_repeat'] = "Solo los eventos recurrentes (Eventos con fecha de inicio y fin) pueden repetirse.";
$l['eventdata_invalid_repeat_day_interval'] = "Has introducido un intevalo de repetición diario inválido.";
$l['eventdata_invalid_repeat_week_interval'] = "Has introducido un intevalo de repetición semanal inválido.";
$l['eventdata_invalid_repeat_weekly_days'] = "No has seleccionado ningún día para que este evento se repita.";
$l['eventdata_invalid_repeat_month_interval'] = "Has introducido un intevalo de repetición mensual inválido.";
$l['eventdata_invalid_repeat_year_interval'] = "Has introducido un intevalo de repetición anual inválido.";
$l['eventdata_event_wont_occur'] = "Si usas una hora de inicio y finalización posterior a la del evento, el evento no ocurrirá.";

$l['eventdata_no_permission_private_event'] = "No tienes permisos para crear eventos privados.";
?>
