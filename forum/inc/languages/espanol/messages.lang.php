<?php
/**
 * MyBB 1.6 Spanish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: messages.lang.php 5016 2010-08-10 12:32:33Z Anio_pke $
 */

$l['click_no_wait'] = "Haz clic aquí si no quieres esperar más.";
$l['redirect_return_forum'] = "<br /><br />Alternativamente, <a href=\"{1}\">volver al foro</a>.";
$l['redirect_emailsent'] = "Tu mensaje de email se ha enviado correctamente.";
$l['redirect_loggedin'] = "Has iniciado sesión correctamente.<br />Ahora regresarás a la página de donde venías.";

$l['error_invalidpworusername'] = "Has introducido una combinación de usuario/contraseña inválida. <br /><br />Si has olvidado tu contraseña por favor <a href=\"member.php?action=lostpw\">pide una nueva</a>.";
$l['error_incompletefields'] = "Parece que tienes algún campo en blanco. Por favor, regresa y rellena los campos necesarios.";
$l['error_alreadyuploaded'] = "Parece que ya estás subiendo el mismo archivo (juzgando por el nombre y tamaño) a este mensaje. Escoge otro archivo para adjuntar.";
$l['error_nomessage'] = "Lo sentimos, no se puede proceder porque no has introducido un mensaje válido. Por favor, regresa e inténtalo de nuevo.";
$l['error_invalidemail'] = "No has introducido una dirección de email válida.";
$l['error_nomember'] = "El miembro que especificaste es inválido o no existe.";
$l['error_maxposts'] = "Lo sentimos, pero tu límite máximo de mensajes ha sido excedido. Por favor espera hasta mañana para enviar mensajes o contacta a un administrador.<br /><br />El máximo número de mensajes por día es de {1}";
$l['error_nohostname'] = "No se ha encontrado ningún servidor en la IP que has introducido.";
$l['error_invalidthread'] = "El tema especificado no existe.";
$l['error_invalidpost'] = "El mensaje especificado no existe.";
$l['error_invalidattachment'] = "El archivo adjunto especificado no existe.";
$l['error_invalidforum'] = "Foro inválido";
$l['error_closedinvalidforum'] = "No puedes escribir mensajes en este foro porque o el foro esta cerrado o es una categoría.";
$l['error_attachtype'] = "El tipo de archivo que adjuntaste no está permitido. Quita ese archivo o escoge alguno otro de diferente tipo.";
$l['error_attachsize'] = "El archivo que adjuntaste es demasiado grande. El tamaño máximo para ese tipo de archivos es de {1} kilobytes.";
$l['error_uploadsize'] = "El tamaño del archivo a subir es muy grande.";
$l['error_uploadfailed'] = "La subida del archivo ha fallado. Escoge un archivo válido y trata de nuevo.";
$l['error_uploadfailed_detail'] = "Detalles del error: ";
$l['error_uploadfailed_php1'] = "PHP: El archivo subido excede la directiva upload_max_filesize en php.ini. Por favor, contacta con un administrador con este error.";
$l['error_uploadfailed_php2'] = "El archivo subido excede el límite de tamaño especificado.";
$l['error_uploadfailed_php3'] = "El archivo se ha subido sólo parcialmente.";
$l['error_uploadfailed_php4'] = "No se ha subido archivo.";
$l['error_uploadfailed_php6'] = "PHP: Carpeta temporal desconocida. Por favor, contacta con un administrador con este error.";
$l['error_uploadfailed_php7'] = "PHP: Fallo al escribir el archivo en el disco. Por favor, contacta con un administrador con este error.";
$l['error_uploadfailed_phpx'] = "PHP código de error: {1}. Por favor, contacta con un administrador con este error.";
$l['error_uploadfailed_nothingtomove'] = "El archivo espedificado es inválido, por lo que el archivo subido no se moverá a su destino.";
$l['error_uploadfailed_movefailed'] = "Ha ocurrido un problema al mover el archivo subido a su destino.";
$l['error_uploadfailed_lost'] = "El archivo adjunto no se encuentra en el servidor.";
$l['error_emailmismatch'] = "Las direcciones de email que has introducido no coinciden. Por favor, regresa e inténtalo de nuevo";
$l['error_nopassword'] = "No escribiste una contraseña válida.";
$l['error_usernametaken'] = "El nombre de usuario que escribiste ya esta registrado.";
$l['error_nousername'] = "No escribiste un nombre de usuario.";
$l['error_invalidusername'] = "El nombre de usuario que escribiste parece ser no válido.";
$l['error_invalidpassword'] = "La contraseña que escribiste es incorrecta. Si la olvidaste, haz click <a href=\"member.php?action=lostpw\">aquí</a>, o regresa e intenta de nuevo.";
$l['error_postflooding'] = "Lo sentimos pero no podemos procesar tu mensaje. El administrador ha especificado que puedes enviar mensajes cada {1} segundos.";
$l['error_nopermission_guest_1'] = "No iniciaste sesión o no tienes permiso para ver esta página. Esto puede ser por una de las siguientes razones:";
$l['error_nopermission_guest_2'] = "No iniciaste sesión o no estás registrado. Usa el formulario al final de esta página para iniciar sesión.";
$l['error_nopermission_guest_3'] = "No tienes permiso para acceder a esta página. ¿Estás tratando de entrar en páginas administrativas en las cuales no deberías de estar? Revisa las reglas del foro para ver si te esta permitido realizar esta acción.";
$l['error_nopermission_guest_4'] = "Tu cuenta pudo haber sido desactivada por un administrador, o puede que estés esperando activación.";
$l['error_nopermission_guest_5'] = "Has accedido a esta página directamente en vez de usar los formularios o links adecuados.";
$l['login'] = "Iniciar sesión";
$l['need_reg'] = "¿Necesitas registrarte?";
$l['forgot_password'] = "¿Olvidaste tu contraseña?";
$l['error_nopermission_user_1'] = "No tienes permiso de acceder a esta página. Esto puede ser por una de las siguientes razones:";
$l['error_nopermission_user_ajax'] = "No tienes permiso para acceder a esta página.";
$l['error_nopermission_user_2'] = "Tu cuenta esta suspendida o no tiene autorización del acceso a este recurso.";
$l['error_nopermission_user_3'] = "No tienes permiso de ver esta página. ¿Estás tratando de entrar en páginas administrativas en las cuales no deberías estar? Revisa las reglas del foro para ver si te está permitido realizar esta acción.";
$l['error_nopermission_user_4'] = "Tu cuenta está esperando ser activada o moderada.";
$l['error_nopermission_user_5'] = "Has accedido a esta página directamente en vez de usar los formularios o links adecuados.";
$l['error_nopermission_user_resendactivation'] = "Reenviar código de activación";
$l['error_nopermission_user_username'] = "Sesión iniciada con el nombre de usuario: '{1}'";
$l['logged_in_user'] = "Usuario en sesión";
$l['error_too_many_images'] = "Demasiadas imágenes.";
$l['error_too_many_images2'] = "Lo sentimos, pero no podemos procesar tu mensaje porque contiene demasiadas imágenes. Por favor, quita algunas imágenes de tu mensaje para continuar.";
$l['error_too_many_images3'] = "<b>Nota:</b> La máxima cantidad de imágenes por mensaje es";
$l['error_attach_file'] = "Error adjuntando archivo";
$l['please_correct_errors'] = "Corrige los siguientes errores antes de continuar:";
$l['error_reachedattachquota'] = "Lo sentimos, pero no puedes adjuntar este archivo porque ya has llegado a la cuota de {1}";
$l['error_invaliduser'] = "El usuario especificado es inválido o no existe.";
$l['error_invalidaction'] = "Acción inválida";
$l['error_messagelength'] = "Lo sentimos, pero tu mensaje es muy largo y no puede publicarse. Por favor, intenta cortar tu mensaje e intenta de nuevo.";
$l['error_message_too_short'] = "Lo sentimos, tu mensaje es un corto y no puede publicarse.";
$l['failed_login_wait'] = "Ya has fallado el número de intentos requeridos para iniciar sesión. Debes esperar {1}h {2}m {3}s antes de volver a intentarlo.";
$l['failed_login_again'] = "<br/>Tienes <strong>{1}</strong> intentos de inicio de sesión más.";
$l['error_max_emails_day'] = "No puedes usar 'Enviar tema a un amigo' o 'Enviar email' porque ya has utilizado tu cuota de {1} mensajes en las últimas 24 horas.";

$l['emailsubject_lostpw'] = "Contraseña reiniciada {1}";
$l['emailsubject_passwordreset'] = "Nueva contraseña en {1}";
$l['emailsubject_subscription'] = "Nueva respuesta en {1}";
$l['emailsubject_randompassword'] = "Tu contraseña en {1}";
$l['emailsubject_activateaccount'] = "Activación de cuenta en {1}";
$l['emailsubject_forumsubscription'] = "Nuevo tema en {1}";
$l['emailsubject_reportpost'] = "Mensaje reportado en {1}";
$l['emailsubject_reachedpmquota'] = "Límite máximo de mensajes privados alcanzado en {1}";
$l['emailsubject_changeemail'] = "Cambio de dirección de email en {1}";
$l['emailsubject_newpm'] = "Mensaje privado nuevo en {1}";
$l['emailsubject_sendtofriend'] = "Una página web interesante en {1}";
$l['emailbit_viewthread'] = "... (visita el tema para leer más...)";

$l['email_lostpw'] = "{1},

Para completar la reiniciación de tu contraseña en {2}, visita la siguiente dirección en tu navegador:

{3}/member.php?action=resetpassword&uid={4}&code={5}

Si el enlace de arriba no funciona adecuadamente, ve a

{3}/member.php?action=resetpassword

Necesitarás la siguiente información:
Nombre de usuario: {1}
Código de activación: {5}

Gracias,
El equipo de {2}.";


$l['email_reportpost'] = "{1} de {2} ha reportado este mensaje:

{3}
{4}/{5}

Razón para reportar este mensaje:
{7}

Este mensaje ha sido enviado a todos los moderadores o a todos los administradores y super moderadores si no hay moderadores.

Por favor revisen este mensaje tan pronto como sea posible.";

$l['email_passwordreset'] = "{1},

Tu contraseña en {2} ha sido reiniciada.

Tu nueva contraseña es: {3}

Necesitarás esta contraseña para iniciar sesión en los foros, una vez ahí puedes cambiarla en el panel de control del usuario.

Gracias,
El equipo de {2}.";

$l['email_randompassword'] = "{1},

Gracias por registrarte en {2}. Abajo están tu nombre de usuario y contraseña generada al azar. Para iniciar sesión en {2}, necesitarás la siguiente información.

Nombre de usuario: {3}
Contraseña: {4}

Es recomendable que cambies de contraseña inmediatamente después de iniciar sesión. Puedes hacerlo en el panel de control del usuario, luego haciendo clic en 'Cambiar contraseña'.

Gracias,
El equipo de {2}.";

$l['email_sendtofriend'] = "Hola,

{1} de {2} te envía este email porque cree que puedes estar interesado en leer esta página:

{3}

{1} incluye además el siguiente mensaje:
------------------------------------------
{4}
------------------------------------------

Gracias,
El equipo de {2}.
";

$l['email_forumsubscription'] = "{1},

{2} ha iniciado un nuevo tema en {3}. Este es un foro al que estas suscito en {4}.

El tema está titulado {5}

Aquí esta un párrafo del mensaje:
--
{6}
--

Para ver el tema, visita la siguiente dirección:
{7}/{8}

Tal vez haya más nuevos temas y respuestas pero no recibirás más notificaciones hasta que visites el foro.

Gracias,
El equipo de {4}.

------------------------------------------
Información para borrar la suscripción:

Si ya no quieres recibir más notificaciones de nuevos temas en este foro, visita la siguiente dirección en tu navegador:
{7}/usercp2.php?action=removesubscription&type=forum&fid={9}&my_post_key={10}

------------------------------------------";

$l['email_activateaccount'] = "{1},

Para completar el proceso de registro en {2}, visita la siguiente dirección en tu navegador:

{3}/member.php?action=activate&uid={4}&code={5}

Si el enlace de arriba no funciona adecuadamente, ve a

{3}/member.php?action=activate

Necesitarás la siguiente información:
Nombre de usuario: {1}
Código de activación: {5}

Gracias,
El equipo de {2}.";

$l['email_subscription'] = "{1},

{2} ha respondido a un tema al que estas suscrito en {3}. El tema está titulado {4}.

Aquí esta un párrafo del mensaje:
------------------------------------------
{5}
------------------------------------------

Para ver el tema, visita la siguiente dirección:
{6}/{7}

Tal vez haya más respuestas a este tema pero no recibirás más notificaciones hasta que visites el foro.

Gracias,
El equipo de {3}.

------------------------------------------
Información para borrar la suscripción:

Si ya no quieres recibir más notificaciones de respuestas a este tema, visita la siguiente dirección en tu navegador:
{6}/usercp2.php?action=removesubscription&tid={8}&key={9}&my_post_key={10}

------------------------------------------";
$l['email_reachedpmquota'] = "{1},

Este es un mensaje automático de {2} para hacerte saber que tu bandeja de entrada de mensajes privados ha alcanzado su máxima capacidad.

Uno ó más usuarios han tratado de enviarte mensajes privados y no les fue posible por esta situación.

Por favor borra uno ó más mensajes de los que tienes almacenados, recuerda también borrarlos de la 'Papelera'.

Gracias,
El equipo de {2}
{3}";
$l['email_changeemail'] = "{1},

Hemos recibido una solicitud en {2} para cambiar tu dirección de email (Detalles abajo).

Antigua dirección de email: {3}
Nueva dirección de email: {4}

Si estos cambios son correctos, por favor completa el proceso de validación en {2} visitando la siguiente dirección en tu navegador.

{5}/member.php?action=activate&uid={8}&code={6}

Si el enlace de arriba no funciona adecuadamente, ve a

{5}/member.php?action=activate

Necesitarás la siguiente información:
Nombre de usuario: {7}
Código de activación: {6}

Si escoges no validar tu dirección de email tu perfil no será actualizado y contendrá tu antigua dirección.

Gracias,
El equipo de {2}
{5}";

$l['email_newpm'] = "{1},
		
Has recibido un nuevo mensaje privado en {3} de {2}. Para ver este mensaje, ve al siguiente enlace:

{4}/private.php

Se te hace saber que ya no recibirás más notificaciones de nuevos mensajes hasta que visites {3}.

Puedes desactivar las notificaciones de mensajes nuevos en la página de opciones de tu cuenta:

{4}/usercp.php?action=options

Gracias,
El equipo de {3}
{4}";

$l['email_emailuser'] = "{1},

{2} de {3} te ha enviado el siguiente mensaje:
------------------------------------------
{5}
------------------------------------------

Gracias,
El equipo de {3}
{4}

------------------------------------------
¿No quieres recibir mensajes de email de otros miembros?

Si no quieres que otros miembros puedan enviarte emails, debes visitar tu panel de control y activar la opción 'Ocultar mi email a otros miembros':
{4}/usercp.php?action=options

------------------------------------------";
?>
