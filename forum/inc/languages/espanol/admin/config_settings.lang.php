<?php
/**
 * MyBB 1.6 Spanish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: config_settings.lang.php 5016 2010-08-11 12:32:33Z Anio_pke $
 */
 
$l['board_settings'] = "Ajustes del foro";
$l['change_settings'] = "Cambiar ajuste";
$l['change_settings_desc'] = "Esta sección te permite configurar algunos de los ajustes en relación a tu foro. Para empezar, selecciona un grupo de los de abajo para configurar sus posibles ajustes.";
$l['add_new_setting'] = "Agregar ajuste";
$l['add_new_setting_desc'] = "Esta sección te permite añadir un nuevo ajuste a tu foro.";
$l['modify_existing_settings'] = "Editar ajustes";
$l['modify_existing_settings_desc'] = "Esta sección te permite editar los ajustes existentes en tu foro.";
$l['add_new_setting_group'] = "Agregar un grupo de ajustes";
$l['add_new_setting_group_desc'] = "Esta sección te permite crear un nuevo grupo para organizar ajustes individuales.";
$l['edit_setting_group'] = "Editar grupo de ajustes";
$l['edit_setting_group_desc'] = "Esta sección te permite editar un grupo de ajustes existente.";

$l['title'] = "Título";
$l['description'] = "Descripción";
$l['group'] = "Grupo";
$l['display_order'] = "Orden";
$l['name'] = "Identificador";
$l['name_desc'] = "Este identificador único se usa para referenciar este ajuste (En plugins, traducciones y plantillas).";
$l['group_name_desc'] = "Este identificador único se usa para el sistema de traducción.";
$l['text'] = "Texto";
$l['textarea'] = "Área de texto";
$l['yesno'] = "Elección Sí / No";
$l['onoff'] = "Elección On / Off";
$l['select'] = "Caja de selección";
$l['radio'] = "Botones de radio";
$l['checkbox'] = "Cajas de validación";
$l['language_selection_box'] = "Caja de selección de idioma";
$l['adminlanguage'] = "Caja de selección de idioma de administración";
$l['cpstyle'] = "Caja de selección de estilo del panel de control";
$l['php'] = "PHP";
$l['type'] = "Tipo";
$l['extra'] = "Extra";
$l['extra_desc'] = "Si este ajuste es una caja de selección, radio o caja de validación introduce las posibles elecciones. Separa cada elección con una nueva línea. Si es PHP, introduce el PHP que va a ser ejecutado.";
$l['value'] = "Valor";
$l['insert_new_setting'] = "Insertar nuevo ajuste";
$l['edit_setting'] = "Editar ajuste";
$l['delete_setting'] = "Eliminar ajuste";
$l['setting_configuration'] = "Configuración del ajuste";
$l['update_setting'] = "Actualizar ajuste";
$l['save_settings'] = "Guardar ajuste";
$l['setting_groups'] = "Grupos de ajustes";
$l['bbsettings'] = "Ajustes";
$l['insert_new_setting_group'] = "Insertar nuevo grupo de ajustes";
$l['setting_group_setting'] = "Grupo de ajustes / Ajuste";
$l['order'] = "Orden";
$l['edit_setting_group'] = "Editar grupo de ajustes";
$l['delete_setting_group'] = "Eliminar grupo de ajustes";
$l['save_display_orders'] = "Guardar orden de muestra";
$l['update_setting_group'] = "Actualizar grupo de ajustes";
$l['modify_setting'] = "Modificar ajuste";
$l['search'] = "Buscar";

$l['show_all_settings'] = "Mostrar todos los ajustes";
$l['settings_search'] = "Buscar ajustes";

$l['confirm_setting_group_deletion'] = "¿Estás seguro de querer eliminar este grupo de ajustes?";
$l['confirm_setting_deletion'] = "¿Estás seguro de querer eliminar este ajuste?";

$l['error_missing_title'] = "No has introducido un título para este ajuste";
$l['error_missing_group_title'] = "No has introducido un título para este grupo de ajustes";
$l['error_invalid_gid'] = "No has seleccionado un grupo válido para este ajuste";
$l['error_invalid_gid2'] = "El enlace introducido lleva a un grupo de ajustes inválido. Asegúrate de que existe.";
$l['error_missing_name'] = "No has introducido un identificador para este ajuste";
$l['error_missing_group_name'] = "No has introducido un identificador para este grupo de ajustes";
$l['error_invalid_type'] = "No has seleccionado un tipo válido para este ajuste";
$l['error_invalid_sid'] = "El ajuste especificado no existe";
$l['error_duplicate_name'] = "El identificador especificado ya está en uso por el ajuste \"{1}\" -- debe ser único";
$l['error_duplicate_group_name'] = "El identificador especificado ya está en uso por el grupo de ajustes \"{1}\" -- debe ser único";
$l['error_no_settings_found'] = "No se han encontrado ajustes con la búsqueda especificada.";
$l['error_cannot_edit_default'] = "Los ajustes y grupos de ajustes por defecto no se pueden editar o eliminar.";
$l['error_cannot_edit_php'] = "Este es un tipo de ajuste especial que no puede ser editado.";
$l['error_ajax_search'] = "Ha ocurrido un error al buscar ajustes:";
$l['error_ajax_unknown'] = "Ha ocurrido un error desconicido al buscar ajustes.";

$l['success_setting_added'] = "El ajuste se ha creado correctamente.";
$l['success_setting_updated'] = "El ajuste se ha actualizado correctamente.";
$l['success_setting_deleted'] = "El ajuste se ha eliminado correctamente.";
$l['success_settings_updated'] = "Los ajustes se han actualizado correctamente.";
$l['success_display_orders_updated'] = "El orden de muestra de los ajustes se ha actualizado correctamente.";
$l['success_setting_group_added'] = "El grupo de ajustes se ha creado correctamente.";
$l['success_setting_group_updated'] = "El grupo de ajustes se ha actulizado correctamente.";
$l['success_setting_group_deleted'] = "El grupo de ajustes se ha eliminado correctamente.";
$l['success_duplicate_settings_deleted'] = "Todos los grupos de ajustes duplicados se han eliminado correctamente.";

//SETTINGS
// Grupo 1 calendar
$l['setting_group_calendar'] = "Calendario";
$l['setting_group_calendar_desc'] = "El calendario del foro permite listar eventos públicos o privados y los cumpleaños de los miembros. Esta sección permite configurar los parámetros para el Calendario.";
//Ajustes
$l['setting_enablecalendar'] = "Activar calendario";
$l['setting_enablecalendar_desc'] = "Si quieres desactivar el calendario en tu foro, establece esta opción a no.";
/**************************************************************************************************************************************************/

// Grupo 2 clickablecode
$l['setting_group_clickablecode'] = "Iconos gestuales y código BB";
$l['setting_group_clickablecode_desc'] = "Esta sección te permite cambiar cómo aparece la lista de los iconos gestuales.";
//Ajustes
$l['setting_smilieinserter'] = "Lista de iconos gestuales";
$l['setting_smilieinserter_desc'] = "La lista de iconos gestuales aparece en las webs de publicación de mensajes si esta opción se marca como 'On'.";
$l['setting_smilieinsertertot'] = "Nº. de iconos gestuales que se muestran";
$l['setting_smilieinsertertot_desc'] = "Introduce el número total de iconos gestuales que se mostrarán en la Lista de Emoticonos.";
$l['setting_smilieinsertercols'] = "Nº. de columnas para los iconos gestuales";
$l['setting_smilieinsertercols_desc'] = "Introduce el número de columnas que deseas para la lista de iconos gestuales.";
$l['setting_bbcodeinserter'] = "Editor MyCode";
$l['setting_bbcodeinserter_desc'] = "Establece esta opción como 'On' para mostrar el editor MyCode en las páginas de envío de mensajes.";
/**************************************************************************************************************************************************/

// Grupo 3 cpprefs
$l['setting_group_cpprefs'] = "Opciones del panel de control (Global)";
$l['setting_group_cpprefs_desc'] = "Esta sección te permite configurar las preferencias globales del panel de administración.";
//Ajustes
$l['setting_cplanguage'] = "Idioma del panel de control";
$l['setting_cplanguage_desc'] = "El idioma del panel de control.";
$l['setting_cpstyle'] = "Estilo del panel de control";
$l['setting_cpstyle_desc'] = "Estilo por defecto para el panel de control. Los estilos están dentro de la carpeta 'styles'. El nombre de la carpeta dentro de 'styles' será el título del estilo, dentro de la carpeta del estilo estará el archivo de estilos css.";
$l['setting_maxloginattempts'] = "Numero máximo de Intentos de Inicio de sesión";
$l['setting_maxloginattempts_desc'] = "El número máximo de intentos para iniciar sesión antes de bloquearlo. Establece 0 para desactivar.";
$l['setting_loginattemptstimeout'] = "Tiempo de espera tras fallar el número máximo de intentos";
$l['setting_loginattemptstimeout_desc'] = "Si la persona revasa el número máximo de intentos para iniciar sesión, ¿Cuanto tiempo tiene que esperar antes de poder intentarlo de nuevo? (En minutos)";
/**************************************************************************************************************************************************/

// Grupo 4 datetime
$l['setting_group_datetime'] = "Formato de fecha y hora";
$l['setting_group_datetime_desc'] = "Aquí puedes especificar un formato diferente de fecha y hora para las fechas y horas mostradas en los foros.";
//Ajustes
$l['setting_dateformat'] = "Formato de fecha";
$l['setting_dateformat_desc'] = "Formato de las fechas que se usan en este foro. Este formato se usa en la función de PHP 'date()'. Recomendamos no cambiarlo si no estás seguro de lo que estás haciendo.";
$l['setting_timeformat'] = "Formato de hora";
$l['setting_timeformat_desc'] = "Formato de las horas que se usan en este foro. Este formato se usa en la función de PHP 'date()'. Recomendamos no cambiarlo si no estás seguro de lo que estás haciendo.";
$l['setting_regdateformat'] = "Formato de la fecha de registro";
$l['setting_regdateformat_desc'] = "Este formato se usa en los mensajes y muestra la fecha en la que se registro el usuario.";
$l['setting_timezoneoffset'] = "Zona horaria por defecto";
$l['setting_timezoneoffset_desc'] = "Aquí puedes establecer la zona horaria por defecto para los invitados y los miebros que hayan elegido la Zona Horaria por defecto.";
$l['setting_dstcorrection'] = "Corrección del horario de verano";
$l['setting_dstcorrection_desc'] = "Si tienes una hora más y tu zona horaria está seleccionada correctamente, activa la corrección del horario de verano.";
/**************************************************************************************************************************************************/

// Grupo 5 forumdisplay
$l['setting_group_forumdisplay'] = "Opciones de visualización del foro";
$l['setting_group_forumdisplay_desc'] = "Esta sección permite configurar varios parámetros que se utilizan al visualizar el foro (forumdisplay.php) de tus foros activando y desactivando diferentes opciones.";
//Ajustes
$l['setting_threadsperpage'] = "Temas por página";
$l['setting_threadsperpage_desc'] = "Número de temas que se mostrarán por página en el foro";
$l['setting_hottopic'] = "Respuestas para tema concurrido";
$l['setting_hottopic_desc'] = "Número de respuestas para que un tema sea considerado 'Concurrido'.";
$l['setting_hottopicviews'] = "Vistas para tema concurrido";
$l['setting_hottopicviews_desc'] = "Número de vistas para que un tema sea considerado 'Concurrido'.";
$l['setting_usertppoptions'] = "Opciones seleccionables de temas por página";
$l['setting_usertppoptions_desc'] = "Si quieres permitir a los usuarios elegir cuántos temas quieren ver por página que se muestran en el foro, introduce las opciones que quieras permitir separadas con comas. Si prefires no permitir que escojan el número de temas por página, déjalo en blanco.";
$l['setting_dotfolders'] = "Usar iconos de mensaje";
$l['setting_dotfolders_desc'] = "Si quieres usar un punto en el indicador en los temas cuando el usuario ha participado en ese tema.";
$l['setting_browsingthisforum'] = "Usuarios navegando este foro";
$l['setting_browsingthisforum_desc'] = "Aquí puedes desactivar la característica 'Usuarios navegando este foro'.";
$l['setting_announcementlimit'] = "Límite de anuncios";
$l['setting_announcementlimit_desc'] = "Número de anuncios que se mostrán en el foro en la lista de temas. Introduce 0 para desactivar el límite de anuncios.";
/**************************************************************************************************************************************************/

// Grupo 6 forumhome
$l['setting_group_forumhome'] = "Opciones de la página de inicio del foro";
$l['setting_group_forumhome_desc'] = "Esta sección te permite configurar varios parámetros que se utilizan en la página de inicio (index.php) de tus foros activando y desactivando diferentes opciones.";
//Ajustes
$l['setting_showdescriptions'] = "¿Mostrar descripciones de los foros?";
$l['setting_showdescriptions_desc'] = "Esta opción te permite desactivar las descripciones que se muestran para los foros.";
$l['setting_subforumsindex'] = "Subforos que se muestran en el inicio";
$l['setting_subforumsindex_desc'] = "Número de subforos que quieres mostrar en el inicio. Introduce 0 para no mostrar la lista de subforos";
$l['setting_subforumsstatusicons'] = "¿Mostrar iconos de estado de los subforos?";
$l['setting_subforumsstatusicons_desc'] = "¿Mostrar iconos que indican si un subforo tiene o no nuevos mensajes? Esta opción no afectará si está desactivado el listado de subforos en el inicio.";
$l['setting_hideprivateforums'] = "¿Ocultar foros privados?";
$l['setting_hideprivateforums_desc'] = "Puesdes ocultar los foros privados activando esta opción. Esta opción también oculta los foros en el salto de foro y todos sus subforos.";
$l['setting_modlist'] = "Mostrar lista de moderadores";
$l['setting_modlist_desc'] = "Aquí puedes activar o desactivar la lista de moderadores que se muestra en index.php y en forumdisplay.php";
$l['setting_showbirthdays'] = "¿Mostrar cumpleaños de hoy?";
$l['setting_showbirthdays_desc'] = "¿Quieres mostrar los cumpleaños de hoy en la página de inicio del foro?";
$l['setting_showwol'] = "¿Mostrar Quién está en línea?";
$l['setting_showwol_desc'] = "Muestra los usuarios activos en la página de inicio del foro.";
$l['setting_showindexstats'] = "¿Mostrar sección de estadísticas sencilla?";
$l['setting_showindexstats_desc'] = "¿Quieres mostrar el número total de temas, mensajes, miembros, y el último miembro registrado en la página de inicio del foro?";
$l['setting_showforumviewing'] = "¿Mostrar usuarios visitando un foro?";
$l['setting_showforumviewing_desc'] = "Muestra los usuarios activos viendo un foro en la página de inicio.";
/**************************************************************************************************************************************************/

// Grupo 7 general
$l['setting_group_general'] = "Ajustes generales";
$l['setting_group_general_desc'] = "Esta sección contiene varios parámetros como el nombre y URL de tu foro y también nombre y URL de tu sitio web.";
//Ajustes
$l['setting_bbname'] = "Nombre del foro";
$l['setting_bbname_desc'] = "El nombre de tu foro. Es recomendable no exceder de 75 caracteres.";
$l['setting_bburl'] = "URL del foro";
$l['setting_bburl_desc'] = "La url de tus foros.<br />Incluir el http://. NO incluir la barra (/) al final.";
$l['setting_homename'] = "Nombre de la web";
$l['setting_homename_desc'] = "Nombre de tu página de inicio. Esto aparecerá en el pie con un enlace.";
$l['setting_homeurl'] = "URL de la web";
$l['setting_homeurl_desc'] = "La URL completa de tu web. Esto estará de enlace en el pie si se ha puesto el nombre de la web.";
$l['setting_adminemail'] = "Email de administrador";
$l['setting_adminemail_desc'] = "La dirección de email del administrador. Este email se usará para los emails enviados desde el foro.";
$l['setting_returnemail'] = "Email de respuesta";
$l['setting_returnemail_desc'] = "Dirección de email para las respuestas de los usuarios para enviar emails desde el foro con cuentas  'No responder (no-reply)'. Dejar en blanco para usar el email de administrador.";
$l['setting_contactlink'] = "Enlace contáctanos";
$l['setting_contactlink_desc'] = "La dirección se utilizará en el enlace contáctanos al pie de todas las páginas del foro. Puedes usar una dirección de email (mailto:email@website.com) o un enlace.";
$l['setting_bblanguage'] = "Idioma por defecto";
$l['setting_bblanguage_desc'] = "El idioma por defecto de MyBB para invitados y usuarios que no han seleccionado un idioma en su panel de control.";
$l['setting_cookiedomain'] = "Dominio de las cookies";
$l['setting_cookiedomain_desc'] = "El dominio en el que las cookies se deben establecer. Se puede dejar en blanco. Puedes también iniciar con un . y así cubrir todos los subdominios.";
$l['setting_cookiepath'] = "Ruta de las cookies";
$l['setting_cookiepath_desc'] = "Ruta en la que se estableceran las cookies; recomendamos configurarlo para todos tus foros con una barra inclinada '/'.";
$l['setting_cookieprefix'] = "Prefijo para cookie";
$l['setting_cookieprefix_desc'] = "Añadir un prefijo a las cookies del MyBB. Esto es recomendable cuando deseas instalar múltiples copias de MyBB en un dominio o tienes otro software instalado que podría crear conflictos con los nombres de las cookies de MyBB. Si no se especifica uno, no se usará ningún prefijo.";
$l['setting_showvernum'] = "Mostrar número de versión";
$l['setting_showvernum_desc'] = "Te permite deshabilitar el número de versión que se muestra en el MyBB.";
$l['setting_captchaimage'] = "Imágenes CAPTCHA para registros y publicaciones";
$l['setting_captchaimage_desc'] = "Si lo activas y la librería GD está instalada, se mostrará una imagen durante el registro y las publicaciones, requiriendo de los usuarios que introduzcan el texto contenido en la imagen para continuar. Esto ayuda a prevenir registros y publicaciones automáticas.";
$l['setting_reportmethod'] = "Método de aviso para mensajes reportados";
$l['setting_reportmethod_desc'] = "Por favor, selecciona de la lista el método a utilizar. Probablemente guardar en la base de datos sea la mejor opción de las mostradas.";
$l['setting_reportmethod_db'] = "Guardar en la base de datos";
$l['setting_reportmethod_pms'] = "Enviar un mensaje privado";
$l['setting_reportmethod_email'] = "Enviar un email informando";
$l['setting_statslimit'] = "Límite de estadísticas";
$l['setting_statslimit_desc'] = "Número de temas que se mostrarán en la página de estadísticas para los de más respuestas y los más vistos.";
$l['setting_decpoint'] = "Punto decimal";
$l['setting_decpoint_desc'] = "El punto decimal que se usa en tu región (Por ejemplo, configurar ',' con el número 0,1 dará como resultado el número 0,1).";
$l['setting_thousandssep'] = "Separador de miles";
$l['setting_thousandssep_desc'] = "El separador de millares que se usa en tu región (Por ejemplo, configurar '.' con el número 1200 dará como resultado el número 1.200).";
$l['setting_showlanguageselect'] = "Mostrar selección de idioma en el pie";
$l['setting_showlanguageselect_desc'] = "Establece como 'No' si no quieres que se muestre el selector de idioma en el pie de todas las páginas de tu foro.";
$l['setting_maxmultipagelinks'] = "Enlaces máximos de paginación";
$l['setting_maxmultipagelinks_desc'] = "Aquí puedes establecer el número de enlaces (Anterior o siguiente) que se mostrarán en la paginación de los temas con más de una página de resultados.";
$l['setting_mailingaddress'] = "Dirección de correo";
$l['setting_mailingaddress_desc'] = "Si tienes una dirección de correo ordinaria, introdúcela aquí. Esto se mostrará con el formulario COPPA.";
$l['setting_faxno'] = "Número de fax";
$l['setting_faxno_desc'] = "Si tienes un número de fax, introdúcelo aquí. Esto se mostrará con el formulario COPPA.";
/**************************************************************************************************************************************************/

//Grupo 8 mailsettings
$l['setting_group_mailsettings'] = "Ajustes de correo (Mailing)";
$l['setting_group_mailsettings_desc'] = "Esta sección te permite configurar varios aspectos del sistema de emails del MyBB, como enviar los emails con la función 'mail' del PHP o otro servidor SMTP.";
//Ajustes
$l['setting_mail_handler'] = "Tipo de envío";
$l['setting_mail_handler_desc'] = "Aquí seleccionas el tipo de envío de emails.";
$l['setting_smtp_host'] = "Servidor SMTP";
$l['setting_smtp_host_desc'] = "El servidor SMTP que se usará para enviar los emails a través de él.<br />Solo necesario si has seleccionado 'SMTP mail' en tipo de envío.";
$l['setting_smtp_port'] = "Puerto SMTP";
$l['setting_smtp_port_desc'] = "Puerto que utiliza el servidor SMTP para enviar los emails a través de él.<br />Solo necesario si has seleccionado 'SMTP mail' en tipo de envío.";
$l['setting_smtp_user'] = "Usuario SMTP";
$l['setting_smtp_user_desc'] = "Nombre de usuario para autentificarse con el servidor SMTP.<br />Solo necesario si has seleccionado 'SMTP mail' en tipo de envío y el servidor SMTP necesita autentificación.";
$l['setting_smtp_pass'] = "Contraseña SMTP";
$l['setting_smtp_pass_desc'] = "Contraseña para autentificarse con el servidor SMTP.<br />Solo necesario si has seleccionado 'SMTP mail' en tipo de envío y el servidor SMTP necesita autentificación.";
$l['setting_secure_smtp'] = "Modo de Encriptación SMTP";
$l['setting_secure_smtp_desc'] = "Selecciona la encriptación necesaria para comunicarse con el servidor SMTP.<br />Solo necesario si has seleccionado 'SMTP mail' en tipo de envío.";
$l['setting_secure_smtp_0'] = "Sin encriptación";
$l['setting_secure_smtp_1'] = "Encriptación SSL";
$l['setting_secure_smtp_2'] = "Encriptación TLS";
$l['setting_mail_parameters'] = "Parámetros adicionales para mail() de PHP";
$l['setting_mail_parameters_desc'] = "Este ajuste te permite establecer otros parámetros para la función mail() de PHP. <br />Solo necesario si has seleccionado 'PHP mail' en tipo de envío. <a href=\"http://php.net/function.mail\" target=\"_blank\">Más información</a>";
$l['setting_mail_logging'] = "Guardar envíos de correo";
$l['setting_mail_logging_desc'] = "Este ajuste te permite almacenar todos los correos salientes enviados a través de la función 'Enviar tema a un amigo'. En muchos países es ilegal almacenar todo el contenido.";
$l['setting_mail_logging_0'] = "Desactivar guardado";
$l['setting_mail_logging_1'] = "Almacenar correos sin contenido";
$l['setting_mail_logging_2'] = "Almacenar siempre";
$l['setting_mail_message_id'] = "Agregar un ID al mensaje en las cabeceras del email";
$l['setting_mail_message_id_desc'] = "Desactivando esta opción en muchos servidores compartidos resuelve problemas con los emails del foro que son marcados como spam.";
/**************************************************************************************************************************************************/

//Grupo 9 member
$l['setting_group_member'] = "Opciones de registro de usuario y perfil";
$l['setting_group_member_desc'] = "Aquí controlas varios parámetros relacionados con el registro de usuarios y el mantenimiento de cuentas.";
//Ajustes
$l['setting_disableregs'] = "Desactivar registros";
$l['setting_disableregs_desc'] = "Permite desactivar los registros de nuevos usuarios con un solo clic.";
$l['setting_regtype'] = "Método de registro";
$l['setting_regtype_desc'] = "Por favor, selecciona el método que se utilizará en los resgitros de nuevos usuarios.";
$l['setting_regtype_instant'] = "Activación instantánea";
$l['setting_regtype_verify'] = "Enviar email de verificación";
$l['setting_regtype_randompass'] = "Enviar contraseña aleatoria";
$l['setting_regtype_admin'] = "Activación por administración";
$l['setting_minnamelength'] = "Longitud mínima de nombre de usuario";
$l['setting_minnamelength_desc'] = "Mínimo número de caracteres para un nombre de usuario.";
$l['setting_maxnamelength'] = "Longitud máxima de nombre de usuario";
$l['setting_maxnamelength_desc'] = "Máximo número de caracteres para un nombre de usuario.";
$l['setting_minpasswordlength'] = "Longitud mínima para la contraseña";
$l['setting_minpasswordlength_desc'] = "Mínimo número de caracteres para una contraseña.";
$l['setting_requirecomplexpasswords'] = "¿Requerir contraseña compleja?";
$l['setting_requirecomplexpasswords_desc'] = "¿Quieres que tus usuarios usen contraseñas complejas? Las contraseñas complejas requieren una letra mayúscula, una minúscula y un número.";
$l['setting_maxpasswordlength'] = "Longitud máxima para la contraseña";
$l['setting_maxpasswordlength_desc'] = "Máximo número de caracteres para una contraseña.";
$l['setting_customtitlemaxlength'] = "Longitud máxima para el título personalizado";
$l['setting_customtitlemaxlength_desc'] = "Máximo número de caracteres para el título de usuario personalizado.";
$l['setting_betweenregstime'] = "Tiempo entre registros";
$l['setting_betweenregstime_desc'] = "Tiempo (En horas) que deben esperar los usuarios para volver a registrarse desde la misma IP.";
$l['setting_allowmultipleemails'] = "¿Permitir usar el mismo email varias veces?";
$l['setting_allowmultipleemails_desc'] = "Selecciona 'Sí' si quieres permitir que los usuarios se puedan registrar varias veces con un mismo email, en otro caso selecciona 'No'.";
$l['setting_maxregsbetweentime'] = "Máximos registros por IP";
$l['setting_maxregsbetweentime_desc'] = "Esta opción te permite establecer el número máximo de registros desde una misma IP.";
$l['setting_failedcaptchalogincount'] = "Número de fallos al iniciar sesión antes de pedir una verificación";
$l['setting_failedcaptchalogincount_desc'] = "El número de veces que se permite fallar a alguien al iniciar sesión antes de requerir introducir una verificación CAPTCHA. 0 para desactivar";
$l['setting_failedlogincount'] = "Número de fallos permitidos al iniciar sesión";
$l['setting_failedlogincount_desc'] = "Número de veces que se permite fallar cuando se intenta iniciar sesión. 0 para desactivar.";
$l['setting_failedlogintime'] = "Tiempo entre fallos al iniciar sesión";
$l['setting_failedlogintime_desc'] = "Tiempo (En horas) que deben esperar los usuarios para volver a intentar iniciar sesión de nuevo. Solo si la opción de arriba es distinta de 0";
$l['setting_failedlogintext'] = "Mostrar número de fallos";
$l['setting_failedlogintext_desc'] = "¿Quieres mostrar una línea de texto al usuario que diga cuántos intentos de inicio de sesión le quedan?";
$l['setting_usereferrals'] = "Usar recomendantes";
$l['setting_usereferrals_desc'] = "¿Quieres que los usuarios puedan usar el sistema de recomendantes en estos foros?";
$l['setting_sigmycode'] = "Permitir MyCode en las firmas";
$l['setting_sigmycode_desc'] = "¿Quieres que los usuarios puedan utilizar MyCode en sus firmas?";
$l['setting_maxsigimages'] = "Máximo número de imágenes por firma";
$l['setting_maxsigimages_desc'] = "Introduce el máximo número de imágenes (Incluidos iconos gestuales) que un usuario puede poner en su firma. Introduce 0 para no permitir ninguna imagen en las firmas.";
$l['setting_sigsmilies'] = "Permitir emoticonos en las firmas";
$l['setting_sigsmilies_desc'] = "¿Quieres que los usuarios puedan utilizar iconos gestuales en sus firmas?";
$l['setting_sightml'] = "Permitir HTML en las firmas";
$l['setting_sightml_desc'] = "¿Quieres que los usuarios puedan utilizar HTML en sus firmas?";
$l['setting_sigimgcode'] = "Permitir Código [img] en las firmas";
$l['setting_sigimgcode_desc'] = "¿Quieres que los usuarios puedan utilizar el código [img] en sus firmas?";
$l['setting_siglength'] = "Longitud máxima de las firmas";
$l['setting_siglength_desc'] = "Máximo número de caracteres que un usuario puede utilizar en su firma.";
$l['setting_sigcountmycode'] = "¿MyCode afecta a la longitud de las firmas?";
$l['setting_sigcountmycode_desc'] = "¿Quieres que el MyCode cuente como parte del límite para las firmas?";
$l['setting_maxavatardims'] = "Dimensiones máximas de avatar";
$l['setting_maxavatardims_desc'] = "Dimensiones máximas para un avatar, con el formato de ancho<b>x</b>alto. Si lo dejas en blanco no habrá restricciones en la dimensión.";
$l['setting_avatarsize'] = "Tamaño máximo del avatar";
$l['setting_avatarsize_desc'] = "Tamaño máximo (En kilobytes) que se permite para subir un avatar desde el PC.";
$l['setting_avatarresizing'] = "Redimensionar avatar";
$l['setting_avatarresizing_desc'] = "Si quieres puedes configurar que todos los avatares muy grandes se redimensionen automáticamente, permitir a los usuarios redimensionar su avatar o no redimensionar su avatar con este ajuste.";
$l['setting_avatarresizing_auto'] = "Redimensionar automáticamente los avatares grandes";
$l['setting_avatarresizing_user'] = "Dejar al usuario elegir si redimensiona los avatares grandes";
$l['setting_avatarresizing_disabled'] = "Desactivar esta función";
$l['setting_avatardir'] = "Directorio del avatar";
$l['setting_avatardir_desc'] = "Directorio en el que se almacenarán los avatares. Se utilizarán en la lista de avatares del panel de control del usuario.";
$l['setting_avataruploadpath'] = "Ruta de avatares subidos";
$l['setting_avataruploadpath_desc'] = "Ruta en la que se almacenarán los avatares subidos por los usuarios. Este directorio <b>debe tener chmod 777</b> (Escribible) para que las subidas funcionen.";
$l['setting_emailkeep'] = "Conservar email";
$l['setting_emailkeep_desc'] = "Si un usuario actualmente tiene un email que está en la lista de suspensiones, ¿se permite que lo conserve?";
$l['setting_coppa'] = "Formulario COPPA";
$l['setting_coppa_desc'] = "Si quieres activar el soporte <a href=\"http://www.coppa.org/comply.htm\">COPPA</a> en tus foros, por favor, selecciona el tipo de registro permitido.";
$l['setting_coppa_enabled'] = "Activada";
$l['setting_coppa_deny'] = "Denegar registros a menores de 13 años";
$l['setting_coppa_disabled'] = "Desactivar esta función";
$l['setting_allowaway'] = "¿Permitir el estado 'Ausente'?";
$l['setting_allowaway_desc'] = "¿Permitir que los usuarios puedan establecer su estado como 'Ausente' con una razón y una fecha de regreso?";
$l['setting_allowbuddyonly'] = "¿Permitir mensajes privados solo entre amigos?";
$l['setting_allowbuddyonly_desc'] = "¿Permitir a los usuarios recibir mensajes privados solo de usuarios que estén en su lista de amigos?";
/**************************************************************************************************************************************************/

// Grupo 10 memberlist
$l['setting_group_memberlist'] = "Lista de miembros";
$l['setting_group_memberlist_desc'] = "Esta sección permite configurar varios aspectos en la lista de miembros (memberlist.php), como por ejemplo cuántos miembros se muestran por página y el orden en el que se muestran.";
//Ajustes
$l['setting_enablememberlist'] = "Activar lista de miembros";
$l['setting_enablememberlist_desc'] = "Si quieres desactivar la lista de miembros en tu foro, marca la opción no.";
$l['setting_membersperpage'] = "Miembros por página";
$l['setting_membersperpage_desc'] = "Número de miembros que se mostrarán por página en la lista de miembros.";
$l['setting_default_memberlist_sortby'] = "Campo de ordenación";
$l['setting_default_memberlist_sortby_desc'] = "Selecciona el campo por el que serán ordenados los usuarios.";
$l['setting_default_memberlist_sortby_regdate'] = "Fecha de registro";
$l['setting_default_memberlist_sortby_postnum'] = "Número de mensajes";
$l['setting_default_memberlist_sortby_username'] = "Nombre de usuario";
$l['setting_default_memberlist_sortby_lastvisit'] = "Última visita";
$l['setting_default_memberlist_order'] = "Orden para ordenación";
$l['setting_default_memberlist_order_desc'] = "Seleccionar el orden por el que se mostrarán los usuarios.<br />Ascendente: A-Z / principio-fin<br />Descendente: Z-A / fin-principio";
$l['setting_default_memberlist_order_ascending'] = "Ascendente";
$l['setting_default_memberlist_order_descending'] = "Descendente";
$l['setting_memberlistmaxavatarsize'] = "Máxima dimensión de avatar";
$l['setting_memberlistmaxavatarsize_desc'] = "Aquí puedes configurar el tamaño máximo para los avatares que se mostrarán en la lista de miembros. Si un avatar es muy grande, se redimensionará automáticamente.";
/**************************************************************************************************************************************************/

// Grupo 11 onlineoffline
$l['setting_group_onlineoffline'] = "Foro abierto / cerrado";
$l['setting_group_onlineoffline_desc'] = "Estos parámetros permiten abrir o cerrar tus foros y en caso de cierre especificar el motivo.";
//Ajustes
$l['setting_boardclosed'] = "Foro cerrado";
$l['setting_boardclosed_desc'] = "Si necesitas cerrar tus foros para hacer cambios o realizar una actualización, este es un interruptor global. Los visitantes no podrán ver tus foros; sin embargo, verán un mensaje con la razón especificada debajo.<br /><br /><b>Los administradores podrán ver los foros.</b>";
$l['setting_boardclosed_reason'] = "Razón del cierre";
$l['setting_boardclosed_reason_desc'] = "Si tus foros están cerrados, puedes escribir un mensaje aquí que se mostrará a los visitantes cuando entren a tus foros.";
/**************************************************************************************************************************************************/

// Grupo 12 portal
$l['setting_group_portal'] = "Opciones del portal";
$l['setting_group_portal_desc'] = "La página del portal mezcla muchas piezas de información sobre el foro, incluyendo los últimos mensajes, quién está en línea, estadísticas del foro, anuncios y más. Esta sección tiene ajustes que controlan la página del portal (portal.php).";
//Ajustes
$l['setting_portal_announcementsfid'] = "ID del foro para mostrar los anuncios";
$l['setting_portal_announcementsfid_desc'] = "Por favor, introduce las ids (fid) de los foros de los que quieres mostrar los anuncios. Separarlos por comas (,).";
$l['setting_portal_numannouncements'] = "Número de anuncios a mostrar";
$l['setting_portal_numannouncements_desc'] = "Por favor, introduce el número de anuncios que se mostarán en la página principal.";
$l['setting_portal_showwelcome'] = "Mostrar caja de bienvenida";
$l['setting_portal_showwelcome_desc'] = "Muestra la caja de bienvenida a invitados / usuarios.";
$l['setting_portal_showpms'] = "Mostrar el número de MPs a los usuarios";
$l['setting_portal_showpms_desc'] = "Muestra el número de mensajes privados que tiene actualmente un usuario en sus carpetas del sistema de mensajería privada.";
$l['setting_portal_showstats'] = "Mostrar estadísticas del foro";
$l['setting_portal_showstats_desc'] = "Muestra el número de mensajes, temas, miembros y el último miembro resgistrado en el portal.";
$l['setting_portal_showwol'] = "Mostrar \"Usuarios en línea\"";
$l['setting_portal_showwol_desc'] = "Muestra los usuarios que están en línea en la página del portal";
$l['setting_portal_showsearch'] = "Mostrar \"Busca en los foros\"";
$l['setting_portal_showsearch_desc'] = "Muestra una caja que permite a usuarios buscar rápidamente en el foro.";
$l['setting_portal_showdiscussions'] = "Mostrar \"Últimos temas\"";
$l['setting_portal_showdiscussions_desc'] = "Muestra los últimos temas que se han discutido en el foro";
$l['setting_portal_showdiscussionsnum'] = "Número de \"Últimos temas\" a mostrar";
$l['setting_portal_showdiscussionsnum_desc'] = "Por favor, introduce el número de los últimos temas que se mostrarán en la página del portal.";
/**************************************************************************************************************************************************/

//Grupo 13 posting
$l['setting_group_posting'] = "Publicando mensajes";
$l['setting_group_posting_desc'] = "Estas opciones controlan varios elementos relacionados con la publicación de mensajes en los foros.";
//Ajustes
$l['setting_minmessagelength'] = "Longitud mínima de mensaje";
$l['setting_minmessagelength_desc'] = "Número mínimo de caracteres para los mensajes.";
$l['setting_maxmessagelength'] = "Longitud máxima de mensaje";
$l['setting_maxmessagelength_desc'] = "Número máximo de caracteres para un mensaje. Introducir 0 permite una longitud ilimitada.";
$l['setting_maxposts'] = "Mensajes Máximos por Día";
$l['setting_maxposts_desc'] = "Número máximo de mensajes que un miembro puede escribir por día. 0 para ilimitados.";
$l['setting_postfloodcheck'] = "Comprobar envíos masivos de mensajes (Flood)";
$l['setting_postfloodcheck_desc'] = "Activa esta opción, si quieres comprobar el envío masivo de mensajes. Especificar el tiempo entre mensajes abajo.";
$l['setting_postfloodsecs'] = "Tiempo entre envío de mensajes";
$l['setting_postfloodsecs_desc'] = "Establece el tiempo (En segundos) que tienen que esperar los miembros entre los envíos de mensajes; para que tenga efecto, la opción anterior debe estar activada.";
$l['setting_postmergemins'] = "Tiempo de autocombinar mensajes";
$l['setting_postmergemins_desc'] = "Cuando está activa, los mensajes enviados antes de x minutos por el mismo autor después de enviar otro, se combinarán. Establece el tiempo límite (En minutos) para combinar mensajes. Escribe un 0 o déjalo en blanco para desactivar esta opción. Por defecto: 60";
$l['setting_postmergefignore'] = "Autocombinar desactivado en algunos foros";
$l['setting_postmergefignore_desc'] = "Introduce las ids (fid) de los foros, separadas por comas (,) para excluir la función autocombinar. Dejar en blanco para desactivar.";
$l['setting_postmergeuignore'] = "Autocombinar desactivado en algunos grupos de usuario";
$l['setting_postmergeuignore_desc'] = "Introduce las ids (fid) de los grupos, separadas por comas (,) para excluir la función autocombinar. Por defecto: 4 (Administrador). Dejar en blanco para desactivar.";
$l['setting_postmergesep'] = "Separador del autocombinar";
$l['setting_postmergesep_desc'] = "El separador que se usará para combinar dos mensajes. Por defecto: '[hr]'";
$l['setting_logip'] = "Guardar IP de los mensajes";
$l['setting_logip_desc'] = "Si quieres almacenar las IP desde las que escriben los miembros, y a quién quieres que se muestren esas direcciones IP.";
$l['setting_logip_no'] = "No almacenar IPs";
$l['setting_logip_hide'] = "Mostrar a administradores y moderadores";
$l['setting_logip_show'] = "Mostrar a todos los usuarios";
$l['setting_showeditedby'] = "Mostrar mensajes 'Modificado por'";
$l['setting_showeditedby_desc'] = "Cuando un usuario edita un post, ¿quieres que se muestre el mensaje 'Modificado por'?";
$l['setting_showeditedbyadmin'] = "Mostrar mensajes 'Modificado por' al equipo del foro";
$l['setting_showeditedbyadmin_desc'] = "¿Quieres que se muestre el mensaje 'Modificado por' cuando editan sus mensajes los miembros del equipo del foro?";
$l['setting_maxpostimages'] = "Número máximo de imágenes por mensaje";
$l['setting_maxpostimages_desc'] = "Introduce el número máximo de imágenes (incluyendo iconos gestuales) que un miembro puede usar en sus mensajes. 0 para deshabilitar el límite.";
$l['setting_maxpostvideos'] = "Número máximo de vídeos por mensaje";
$l['setting_maxpostvideos_desc'] = "Introduce el número máximo de vídeos que un miembro puede usar en sus mensajes. 0 para deshabilitar el límite.";
$l['setting_subscribeexcerpt'] = "Caracteres para las vistas previas de las suscripciones";
$l['setting_subscribeexcerpt_desc'] = "Número de caracteres de un mensaje que se enviarán en las notificaciones por email en las suscripciones.";
$l['setting_maxattachments'] = "Número máximo de adjuntos por mensaje";
$l['setting_maxattachments_desc'] = "Máximo número de archivos adjuntos que se permiten subir con cada mensaje.";
$l['setting_attachthumbnails'] = "Mostrar miniaturas en los mensajes";
$l['setting_attachthumbnails_desc'] = "¿Quieres mostrar las miniaturas generadas de las imágenes adjuntas dentro de los mensajes?";
$l['setting_attachthumbnails_yes'] = "Miniatura";
$l['setting_attachthumbnails_no'] = "Imagen completa";
$l['setting_attachthumbnails_download'] = "Enlace de descarga";
$l['setting_attachthumbh'] = "Altura máxima de las miniaturas";
$l['setting_attachthumbh_desc'] = "Introduce la altura máxima con la que se generarán las miniaturas";
$l['setting_attachthumbw'] = "Ancho máximo de las miniaturas";
$l['setting_attachthumbw_desc'] = "Introduce el ancho máximo con el que se generarán las miniaturas";
$l['setting_edittimelimit'] = "Tiempo permitido para editar";
$l['setting_edittimelimit_desc'] = "Número de minutos permitidos para editar un mensaje; después los miembros normales no podrán editar sus mensajes (si tenían permiso). Introduce 0 (cero) para deshabilitar el límite.";
$l['setting_wordwrap'] = "Caracteres máximos por palabra";
$l['setting_wordwrap_desc'] = "Número máximo de caracteres para una palabra antes de que se corte automáticamente (previene deformaciones en el foro). 0 para deshabilitar.";
$l['setting_maxquotedepth'] = "Nivel máximo de citas";
$l['setting_maxquotedepth_desc'] = "Nivel máximo en las citas. Cuando citas un mensaje, todas las citas antiguas que superen este valor de nivel se eliminarán automáticamente. Este valor solo afecta cuando se cita con el botón ya que siempre esrá posible exceder este límite citando a mano, este valor no afecta a los mensajes enviados con anterioridad. 0 para deshabilitar.";
$l['setting_polloptionlimit'] = "Longitud de una opción de una encuesta";
$l['setting_polloptionlimit_desc'] = "Longitud máxima permitida para una opción de una encuesta (0 para deshabilitar).";
$l['setting_maxpolloptions'] = "Número de opciones para una encuesta";
$l['setting_maxpolloptions_desc'] = "Número máximo de opciones permitidas en una encuesta.";
$l['setting_threadreview'] = "Mostrar resumen del tema";
$l['setting_threadreview_desc'] = "¿Mostrar los mensajes más recientes al crear una nueva respuesta?";
/**************************************************************************************************************************************************/

//Grupo 14 privatemessaging
$l['setting_group_privatemessaging'] = "Mensajería privada";
$l['setting_group_privatemessaging_desc'] = "Varias opciones relacionadas con el sistema de mensajería privada de MyBB (private.php) que se configuran desde aquí.";
//Ajustes
$l['setting_enablepms'] = "Activar mensajería privada";
$l['setting_enablepms_desc'] = "Si quieres deshabilitar el sistema de mensajería privada en tu foro, selecciona no.";
$l['setting_pmsallowhtml'] = "Permitir HTML";
$l['setting_pmsallowhtml_desc'] = "Selecciona sí para permitir el uso de HTML en la mensajería privada.";
$l['setting_pmsallowmycode'] = "Permitir MyCode";
$l['setting_pmsallowmycode_desc'] = "Selecciona sí para permitir el uso de MyCode en la mensajería privada.";
$l['setting_pmsallowsmilies'] = "Permitir iconos gestuales";
$l['setting_pmsallowsmilies_desc'] = "Selecciona sí para permitir el uso de iconos gestuales en la mensajería privada.";
$l['setting_pmsallowimgcode'] = "Permitir el código [img]";
$l['setting_pmsallowimgcode_desc'] = "Selecciona sí para permitir el uso de [img] en la mensajería privada.";
$l['setting_pmsallowvideocode'] = "Permitir el código [video]";
$l['setting_pmsallowvideocode_desc'] = "Selecciona sí para permitir el uso de [video] en la mensajería privada.";
$l['setting_pmfloodsecs'] = "Tiempo de espera entre mensajes privados";
$l['setting_pmfloodsecs_desc'] = "Establece el tiempo (En segundos) que los usuarios deben esperar entre cada envío. 0 para deshabilitar.";
/**************************************************************************************************************************************************/

//Grupo 15 reputation
$l['setting_group_reputation'] = "Sistema de reputaciones";
$l['setting_group_reputation_desc'] = "El sistema de reputación permite a tus usuarios calificar a otros y dejarles un comentario. Esta sección tiene ajustes para desactivar y cambiar otros aspectos de la página de reputación (reputation.php).";
//Ajustes
$l['setting_enablereputation'] = "Activar sistema de reputaciones";
$l['setting_enablereputation_desc'] = "Si quieres deshabilitar el sistema de reputación en tu foro, selecciona no.";
$l['setting_negrep'] = "Permitir reputación negativa";
$l['setting_negrep_desc'] = "¿Permitir a los usuarios dar reputación negativa?";
$l['setting_multirep'] = "Permitir reputación múltiple";
$l['setting_multirep_desc'] = "¿Pueden los usuarios dar múltiples reputaciones a un mismo usuario?<br />Nota: Esto no afecta a la reputación de mensaje";
$l['setting_postrep'] = "Permitir reputación de mensaje";
$l['setting_postrep_desc'] = "¿Activar la reputación enlazada con los mensajes?";
$l['setting_repsperpage'] = "Número de comentarios por página";
$l['setting_repsperpage_desc'] = "Introduce el número de comentarios de las reputaciones que se mostarán por cada página en el sistema de reputación.";
$l['setting_maxreplength'] = "Longitud máxima de la reputación";
$l['setting_maxreplength_desc'] = "Máximo número de caracteres que se pueden usar en una reputación.";
/**************************************************************************************************************************************************/

//Grupo 16 search
$l['setting_group_search'] = "Sistema de búsqueda";
$l['setting_group_search_desc'] = "Esta sección permite configurar varios parámetros que modifican el mecanismo de búsqueda de temas y mensajes en el MyBB.";
//Ajustes
$l['setting_searchtype'] = "Tipo de búsqueda";
$l['setting_searchtype_desc'] = "Por favor, selecciona el tipo de búsqueda que quieres utilizar. Puedes elegir entre \"Estándar\", o \"Completa\" (Dependiendo de tu base de datos). La búsqueda completa es más potente y más rápida que la búsqueda estándar de MyBB.";
$l['setting_searchtype_standard'] = "Estándar";
$l['setting_searchtype_fulltext'] = "Completa";
$l['setting_searchfloodtime'] = "Tiempo de búsqueda (Segundos)";
$l['setting_searchfloodtime_desc'] = "Introduce el tiempo mínimo de espera entre búsquedas. Previene que los usuarios sobrecarguen tu servidor por hacer búsquedas constantemente. 0 para desactivar.";
$l['setting_minsearchword'] = "Longitud mínima para la búsqueda";
$l['setting_minsearchword_desc'] = "Introduce el número de caracteres para una palabra individual en la búsqueda. 0 para desactivar (Y acepta un límite por defecto de 3 para la búsqueda estándar y 4 para la búsqueda completa MySQL). Si usas búsqueda completa de MySQL y estableces un límite inferior a la configuración MySQL - MySQL lo sobreescribirá.";
$l['setting_searchhardlimit'] = "Límite máximo de resultados en una búsqueda";
$l['setting_searchhardlimit_desc'] = "Introduce el máximo de resultados que se procesarán. En foros muy grandes (Más de 1 millón de mensajes) es recomendable establecer un máximo inferior a 1000 (0 para deshabilitar).";
/**************************************************************************************************************************************************/

//Grupo 17 server
$l['setting_group_server'] = "Servidor y opciones de optimización";
$l['setting_group_server_desc'] = "Estas opciones permiten configurar varios parámetros del servidor y de optimización permitiendo reducir la carga de tu servidor y tener un mejor rendimiento en el foro.";
//Ajustes
$l['setting_seourls'] = "Activar sistema de URLs amigables (Engine friendly URLs)";
$l['setting_seourls_desc'] = "El sistema de URLs amigables cambia los enlaces de MyBB por unos más cortos que los motores de búsqueda prefieren y que son más fáciles de escribir. showthread.php?tid=1 se convierte en thread1.html. <strong>Una vez activada esta opción, asegúrate que el archivo .htaccess está en tu directorio raíz de MyBB (O equivalente para tu servidor). La detección automática no funciona en todos los servidores.</strong> Por favor, visita <a href=\"http://wiki.mybboard.net/index.php/SEF_URLS\">El Wiki de MyBB</a> para más información.";
$l['setting_seourls_auto'] = "Detección automática";
$l['setting_seourls_yes'] = "Activada";
$l['setting_seourls_no'] = "Desactivada";
$l['setting_gzipoutput'] = "¿Usar compresión GZip en las páginas?";
$l['setting_gzipoutput_desc'] = "¿Quieres comprimir las páginas con el formato GZip cuando se envían al navegador? Con esto se aceleran las descargas para tus visitantes, y reducen el uso de ancho de banda.";
$l['setting_gziplevel'] = "Nivel de compresión GZip";
$l['setting_gziplevel_desc'] = "Establece el nivel  de compresión GZip en las páginas de 0-9. (0=sin compresión, 9=máxima compresión). Esto únicamente afecta si está activada la compresión GZip en las páginas y tu versión de PHP es 4.2 o superior. Si tu servidor usa una versión más antigua, se utilizará el nivel de compresión predeterminado en la librería zlib.";
$l['setting_standardheaders'] = "Enviar Cabeceras Estándar";
$l['setting_standardheaders_desc'] = "Con muchos servidores web, esta opción puede causar problemas; con otros, puede ser necesaria.";
$l['setting_nocacheheaders'] = "Enviar cabeceras \"No cache\"";
$l['setting_nocacheheaders_desc'] = "Con esta opción evitas que los navegadores guarden páginas en la cache (Archivos temporales).";
$l['setting_redirects'] = "Páginas de redirección";
$l['setting_redirects_desc'] = "Activando esta opción activas las páginas de redireccionamiento en vez de enviar al usuario directamente a la página.";
$l['setting_load'] = "Límite de carga *NIX";
$l['setting_load_desc'] = "Límite de carga máxima del servidor antes de que myBB rechace usuarios. 0 para desactivar. Límite recomendado 5.0.";
$l['setting_tplhtmlcomments'] = "Mostrar comentarios de plantillas (Inicio/Fin)";
$l['setting_tplhtmlcomments_desc'] = "Activa los comentarios de inicio y fin de las plantillas en el código de salida HTML.";
$l['setting_use_xmlhttprequest'] = "¿Activar funciones de XMLHttp request?";
$l['setting_use_xmlhttprequest_desc'] = "Este ajuste activará o desactivará las funciones de XMLHttp request (Funciones AJAX).";
$l['setting_useshutdownfunc'] = "Usar la función de apagado de PHP";
$l['setting_useshutdownfunc_desc'] = "Este ajuste, por norma general, es mejor dejarlo en el valor predefinido que es detectado en la instalación. Si los indicadores de los temas no se están actualizando tan bien como el resto de la información meta, cambie este ajuste a 'No'";
$l['setting_extraadmininfo'] = "Estadísticas avanzadas / Información debug";
$l['setting_extraadmininfo_desc'] = "Mostrar carga del servidor, tiempo de análisis, tiempo de generación, compresión Gzip, etc al final de todas las páginas de la raíz del foro. Esta información solo podrán verla los administradores.";
$l['setting_uploadspath'] = "Ruta de subidas";
$l['setting_uploadspath_desc'] = "Ruta que usa el foro para las subidas. Es <b>necesario chmod 777</b> (en servidores Unix).";
$l['setting_useerrorhandling'] = "Usar sistema de guardado de errores";
$l['setting_useerrorhandling_desc'] = "Si no quieres usar el sistema de guardado de errores de MyBB, debes desactivar esta opción. De todas formas, es recomendable activarlo";
$l['setting_errorlogmedium'] = "Guardar errores";
$l['setting_errorlogmedium_desc'] = "Tipo de guardado de errores.";
$l['setting_errorlogmedium_none'] = "Ninguno";
$l['setting_errorlogmedium_log'] = "Guardar en un archivo log";
$l['setting_errorlogmedium_email'] = "Enviar al email";
$l['setting_errorlogmedium_both'] = "Guardar log y enviar email";
$l['setting_errortypemedium'] = "Tipo de errores";
$l['setting_errortypemedium_desc'] = "Tipo de errores que se mostrarán";
$l['setting_errortypemedium_warning'] = "Advertencias";
$l['setting_errortypemedium_error'] = "Errores";
$l['setting_errortypemedium_both'] = "Advertencias y errores";
$l['setting_errortypemedium_none'] = "Ocultar errores y advertencias";
$l['setting_errorloglocation'] = "Archivo en el que se guardan los errores";
$l['setting_errorloglocation_desc'] = "Localización del archivo en el que se guadarán, si se activa el sistema es necesario indicar el archivo que se utilizará.";
$l['setting_enableforumjump'] = "¿Activar el menú salto de foro?";
$l['setting_enableforumjump_desc'] = "El menú \"Salto de foro\" se muestra en las páginas de foros y mensajes. Añade una significativa carga a tus foros si tienes una cantidad considerable de foros. Selecciona 'No' para desactivarlo.";
/**************************************************************************************************************************************************/

//Grupo 18 showthread
$l['setting_group_showthread'] = "Opciones de visualización de temas";
$l['setting_group_showthread_desc'] = "Esta sección permite configurar varios parámetros que se utilizan al visualizar los temas (showthread.php) de tus foros activando y desactivando diferentes opciones.";
//Ajustes
$l['setting_postlayout'] = "Vista de mensajes";
$l['setting_postlayout_desc'] = "Este ajuste te permite cambiar entre la vista clásica y la nueva horizontal. La vista clásica muestra la información del autor a la izquierda del post; la horizontal muestra la información encima del mensaje.";
$l['setting_postlayout_horizontal'] = "Mostrar mensajes usando la nueva vista horizontal";
$l['setting_postlayout_classic'] = "Mostrar mensajes usando la vista clásica";
$l['setting_postsperpage'] = "Mensajes por página";
$l['setting_postsperpage_desc'] = "Número de mensajes que se muestran por cada página. Se recomienda no utilizar más de 20 para la gente con conexiones lentas.";
$l['setting_userpppoptions'] = "Opciones seleccionables de mensajes por página";
$l['setting_userpppoptions_desc'] = "Si quieres permitir a los usuarios elegir cuántos mensajes quieren ver por página que se muestran en un tema, introduce las opciones que quieras permitir separadas con comas. Si prefires no permitir que escojan el número de mensajes por página, déjalo en blanco.";
$l['setting_postmaxavatarsize'] = "Dimensión máxima del avatar en los mensajes";
$l['setting_postmaxavatarsize_desc'] = "La dimensión máxima que pueden tener los avatares cuando se muestran en los mensajes. Si un avatar es muy grande se redimensionará automáticamente.";
$l['setting_threadreadcut'] = "Temas leídos en la base de datos (Días)";
$l['setting_threadreadcut_desc'] = "Número de días que quieres almacenar los temas leídos en la base de datos. Para foros grandes, se recomienda no utilizar un número muy grande o el foro se ralentizará. Introducir 0 para desactivar.";
$l['setting_threadusenetstyle'] = "Usar modo compacto";
$l['setting_threadusenetstyle_desc'] = "Si seleccionas sí, el foro utilizará el modo compacto al mostrar los temas. No influye en la vista de los mensajes.";
$l['setting_quickreply'] = "Mostrar respuesta rápida";
$l['setting_quickreply_desc'] = "Permite activar un formulario de respuesta rápida que se mostrará en la parte de debajo de los temas.";
$l['setting_multiquote'] = "Mostrar botones de multi-cita";
$l['setting_multiquote_desc'] = "La multi-cita permite a los usuarios seleccionar varios mensajes y al pulsar responder tendrán todos los mensajes citados en sus mensajes.";
$l['setting_showsimilarthreads'] = "Mostrar la tabla 'Temas similares'";
$l['setting_showsimilarthreads_desc'] = "La tabla de 'Temas similares' muestra otros temas que posiblemente traten lo mismo que el que estás leyendo. Puedes establecer la exactitud abajo.";
$l['setting_similarityrating'] = "Exactitud en los temas similares";
$l['setting_similarityrating_desc'] = "Permite limitar la exactitud para la tabla de 'Temas Similares' (0 mínima exactitud). Este número no debe ser mayor de 10 y en foros grandes (<5) para que no se relentice.";
$l['setting_similarlimit'] = "Número de temas similares";
$l['setting_similarlimit_desc'] = "Aquí puedes cambiar el número de temas similares que se mostrarán en la tabla. Se recomienda no usar más de 15 para los usuarios de 56k.";
$l['setting_browsingthisthread'] = "Usuarios navegando por este tema";
$l['setting_browsingthisthread_desc'] = "Aquí puedes desactivar la opción 'usuarios navegando por este tema'.";
$l['setting_delayedthreadviews'] = "Actualizar número de vistas con Retraso";
$l['setting_delayedthreadviews_desc'] = "Si este ajuste está activado, el número de veces que se ha visto un tema se actualizará en segundo plano por el sistema de tareas. Si no está activa, se actualizará instantáneamente.";
/**************************************************************************************************************************************************/

//Grupo 19 warning
$l['setting_group_warning'] = "Sistema de advertencias";
$l['setting_group_warning_desc'] = "El sistema de advertencias permite al equipo del foro advertir a usuarios que violen las normas. Aquí puedes configurar los ajustes de control del sistema de advertencias.";
//Ajustes
$l['setting_enablewarningsystem'] = "¿Activar sistema de advertencias?";
$l['setting_enablewarningsystem_desc'] = "Establecer en 'No' para desactivar completamente el sistema de advertencias.";
$l['setting_allowcustomwarnings'] = "¿Permitir advertencias personalizadas?";
$l['setting_allowcustomwarnings_desc'] = "Permite personalizar la razón y el número de puntos que serán especificados por sus permisos para advertir a los usuarios.";
$l['setting_canviewownwarning'] = "¿Permitir a los Usuarios ver sus advertencias?";
$l['setting_canviewownwarning_desc'] = "Establcer este ajuste como 'Sí' permite a los usuarios ver en su panel de control las últimas advertencias y mostrar su nivel de advertencia en su perfil.";
$l['setting_maxwarningpoints'] = "Máximo número de advertencias";
$l['setting_maxwarningpoints_desc'] = "El máximo número de advertencias necesarias antes de que un usuario tenga el nivel de advertencia al 100% (Ej: 10 puntos = 100% de advertencia)";
/**************************************************************************************************************************************************/

//Grupo 20 whosonline
$l['setting_group_whosonline'] = "Quién está en línea";
$l['setting_group_whosonline_desc'] = "Esta sección permite configurar varios parámetros que afectan a la funcionalidad \"Quién está en línea\".";
//Ajustes
$l['setting_wolcutoffmins'] = "Tiempo de desconexión (Minutos)";
$l['setting_wolcutoffmins_desc'] = "Minutos que se esperan antes de marcar a un usuario como desconectado. Recomendado: 15 mins.";
$l['setting_refreshwol'] = "Actualizar quién está en línea (Minutos)";
$l['setting_refreshwol_desc'] = "Tiempo entre actualizaciones de la página \"Quién está en línea\", en minutos. 0 para desactivar.";
/**************************************************************************************************************************************************/

//Grupo 21 userpruning
$l['setting_group_userpruning'] = "Borrado de usuarios";
$l['setting_group_userpruning_desc'] = "Borrado de usuarios te permite eliminar usuarios siguiendo un criterio. Aquí puedes configurar este criterio."; 
//Ajustes
$l['setting_enablepruning'] = "¿Activar borrado de usuarios?";
$l['setting_enablepruning_desc'] = "Establece 'On' para borrar usuarios usando el criterio especificado abajo.";
$l['setting_enableprunebyposts'] = "¿Borrar usuarios usando el contador de mensajes?";
$l['setting_enableprunebyposts_desc'] = "Establece 'On' para borrar usuarios usando el número de mensajes en el foro.";
$l['setting_prunepostcount'] = "Número de mensajes";
$l['setting_prunepostcount_desc'] = "Ajusta para que se borren los usuarios con menos del número de mensajes especificado.";
$l['setting_dayspruneregistered'] = "Días registrados antes de borrar por número de mensajes";
$l['setting_dayspruneregistered_desc'] = "Ajusta el número de días que debe tener un usuario para ser borrado.";
$l['setting_pruneunactived'] = "¿Borrar usuarios inactivos?";
$l['setting_pruneunactived_desc'] = "Establece que se eliminen los usuarios que estén inactivos.";
$l['setting_dayspruneunactivated'] = "Días registrados antes de borrar un usuario inactivo";
$l['setting_dayspruneunactivated_desc'] = "Ajusta el número de días que debe tener un usuario para ser borrado.";
$l['setting_prunethreads'] = "Borras mensajes/temas";
$l['setting_prunethreads_desc'] = "Para los usuarios eliminados, ¿quieres eliminar todos sus mensajes/temas?";

//Grupo 22 akismet
$l['setting_group_akismet'] = "Akismet";
$l['setting_group_akismet_desc'] = "Opciones para configurar y personalizar Akismet.";
//Ajustes
$l['setting_akismetswitch'] = "¿Activar Akismet?";
$l['setting_akismetswitch_desc'] = "Activa o desactiva Akismet.";
$l['setting_akismetapikey'] = "API Key para usar con Akismet";
$l['setting_akismetapikey_desc'] = "La API Key se usa para conectar con Akismet. Leer para más detalles: <a href=\"http://wordpress.com/api-keys/\" target=\"_blank\">http://wordpress.com/api-keys/</a>";
$l['setting_akismetnumtillban'] = "Mensajes de spam para suspensión";
$l['setting_akismetnumtillban_desc'] = "Número de mensajes spam detectados por Akismet antes de que el usuario sea suspendido (0 para deshabilitar).";
$l['setting_akismetfidsignore'] = "Foros ignorados";
$l['setting_akismetfidsignore_desc'] = "Foros, separados por comas, ignorados. Usa la ID del foro, <strong>no el nombre</strong>.";
$l['setting_akismetuidsignore'] = "Grupos de usuario ignorados";
$l['setting_akismetuidsignore_desc'] = "Grupos de usuario, separados por comas, ignorados. Usa la ID del grupo de usuarios, <strong>no el nombre</strong>.";
$l['setting_akismetuserstoignore'] = "Usuarios ignorados";
$l['setting_akismetuserstoignore_desc'] = "Usuarios, separados por comas, ignorados. Usa la ID del usuario, <strong>no el nombre</strong>.";
/**************************************************************************************************************************************************/
?>