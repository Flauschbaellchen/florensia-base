<?php
/**
 * MyBB 1.6 Spanish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: helpdocs.lang.php 5016 2010-08-10 15:50:28Z Anio_pke $
 */

// Help Document 1
$l['d1_name'] = "Registro de usuarios";
$l['d1_desc'] = "Ventajas y privilegios de estar registrado.";
$l['d1_document'] = "Algunas partes de estos foros requieren que estés registrado. Es gratis y solo cuesta un par de minutos.
<br /><br />Se te anima a que te registres. Una vez registrado, podrás escribir mensajes, poner tus propias preferencias y tener un perfil.
<br /><br />Muchas de las características requieren que estés registrado, como las subscripciones, la lista de favoritos, cambiar el estilo, acceder a la libreta personal y mandar emails a otros miembros.";

// Help Document 2
$l['d2_name'] = "Actualizar el perfil";
$l['d2_desc'] = "Cómo actualizar tus datos.";
$l['d2_document'] = "En algún momento puedes querer actualizar alguna información, como tu dirección de messenger, tu contraseña, o quizá quieras cambiar tu dirección de correo. Puedes hacer todo eso y más desde el \"panel de control del usuario\".<br /><br />Para acceder al panel de control, haz clic en el enlace de arriba de la página. Desde allí simplemente elige \"Editar perfil\" y cambia o actualiza las opciones, después pulsa \"Actualiza perfil\" al final de la página para que los cambios tengan efecto.";

// Help Document 3
$l['d3_name'] = "Uso de cookies de los foros";
$l['d3_desc'] = "MyBB usa cookies para guardar cierta información sobre tu registro.";
$l['d3_document'] = "MyBB (los foros que usamos aquí) usan cookies para guardar información de si estás registrado o de cuándo nos visitaste por última vez.
<br /><br />Las cookies son pequeños ficheros de texto que se guardan en tu ordenador. Las cookies de estos foros solo pueden ser vistas en esta página web y no ofrecen ningún riesgo de seguridad.
<br /><br />Nuestras cookies también llevan la cuenta de los temas que has leído y de cuándo los leíste por última vez.
<br /><br />Para borrar todas las cookies de estos foros, puedes hacer clic <a href=\"misc.php?action=clearcookies&amp;key={1}\">aquí</a>.";

// Help Document 4
$l['d4_name'] = "Conectarse y desconectarse";
$l['d4_desc'] = "Cómo conectarse y desconectarse en los foros.";
$l['d4_document'] = "Cuando te conectas, se crea una cookie en tu PC para que puedas visitar los foros sin tener que poner tu nick y contraseña cada vez. Al desconectarse, se borra esa cookie para que nadie más pueda navegar los foros como si fueras tú.
<br /><br />Para conectarte, simplemente haz clic en el enlace de arriba de la página. Para desconectarte, haz clic en el enlace de salir en el mismo sitio. En caso de que no puedas desconectarte, puedes borrar las cookies de tu ordenador. Hará el mismo efecto.";

// Help Document 5
$l['d5_name'] = " Crear un nuevo tema";
$l['d5_desc'] = " Cómo empezar un nuevo tema en un foro.";
$l['d5_document'] = "Cuando vayas a un foro en el que estés interesado y quieras crear un nuevo tema, simplemente oprime  el botón de \"Nuevo tema\" que está al principio y al final de la página. Ten en cuenta que puede ser que no tengas permisos para crear un nuevo tema, ya que los administradores lo han decidido así.";

// Help Document 6
$l['d6_name'] = "Responder a un tema";
$l['d6_desc'] = "Cómo responder a un tema.";
$l['d6_document'] = "Mientras estés en los foros, te encontrarás con temas a los cuales te gustaría añadir un comentario o simplemente dar tu opinión. Para hacer eso tienes que escribir tu respuesta en el cuadro de casi al final de la página o pulsar el botón de \"Responder\". Ten en cuenta que los administradores pueden haber restringido el derecho de participar a algunos usuarios en algunos foros o temas.
<br /><br /> Por otra parte, un moderador puede cerrar un tema. Eso significa que los usuarios no pueden responder a él. No hay ninguna manera de abrir ese tema sin la ayuda de un moderador o administrador.";

// Help Document 7
$l['d7_name'] = "MyCode";
$l['d7_desc'] = "Aprende cómo mejorar tus mensajes con MyCode.";
$l['d7_document'] = "En tus posts puedes usar MyCode, una variante simplificada del HTML, para crear ciertos efectos:
<p><br />[b]Este texto está en negrita[/b]<br />&nbsp;&nbsp;&nbsp;<b>Este texto está en negrita</b>
<p>[i]Este texto está en cursiva[/i]<br />&nbsp;&nbsp;&nbsp;<i>Este texto está en cursiva</i>
<p>[u]Este texto está subrayado[/u]<br />&nbsp;&nbsp;&nbsp;<u>Este texto está subrayado</u>
<p>[s]Este texto está tachado[/s]<br />&nbsp;&nbsp;&nbsp;<del>Este texto está tachado</del>
<p><br />[url]http://www.example.com/[/url]<br />&nbsp;&nbsp;&nbsp;<a href=\"http://www.example.com/\">http://www.example.com/</a>
<p>[url=http://www.example.com/]Example.com[/url]<br />&nbsp;&nbsp;&nbsp;<a href=\"http://www.example.com/\">Example.com</a>
<p>[email]example@example.com[/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:example@example.com\">example@example.com</a>
<p>[email=example@example.com]¡Mándame un email![/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:example@example.com\">¡Mándame un email!</a>
<p>[email=example@example.com?subject=spam]Email con tema[/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:example@example.com?subject=spam\">Email con tema</a>
<p><br />[quote]El texto citado irá aquí[/quote]<br />&nbsp;&nbsp;&nbsp;<blockquote>El texto citado irá aquí</blockquote>
<p>[code]Texto formateado[/code]<br />&nbsp;&nbsp;&nbsp;<code>Texto formateado</code>
<p><br />[img]http://www.php.net/images/php.gif[/img]<br />&nbsp;&nbsp;&nbsp;<img alt=\"[Imagen: php.gif]\" src=\"http://www.php.net/images/php.gif\">
<p>[img=50x50]http://www.php.net/images/php.gif[/img]<br />&nbsp;&nbsp;&nbsp;<img alt=\"[Imagen: php.gif]\" src=\"http://www.php.net/images/php.gif\" width=\"50\" height=\"50\">
<p><br />[color=red]Este texto es rojo[/color]<br />&nbsp;&nbsp;&nbsp;<span style=\"color:red\">Este texto es rojo</span>
<p>[size=3]Este texto es de tamaño 3[/size]<br />&nbsp;&nbsp;&nbsp;<span style=\"font-size:13pt\">Este texto es de tamaño 3</span>
<p>[font=Tahoma]Esta fuente es Tahoma[/font]<br />&nbsp;&nbsp;&nbsp;<span style=\"font-family:Tahoma\">Esta fuente es Tahoma</span>
<p><br />[align=center]Esto está centrado[/align]<div style=\"text-align:center\">Esto está centrado</div>
<p>[align=right]Esto está alineado a la derecha[/align]<div style=\"text-align:right\">Esto está alineado a la derecha</div>
<p><br />[list]<br />[*]Objeto de lista #1<br />[*]Objeto de lista #2<br />[*]Objeto de lista #3<br />[/list]<br /><ul><li>Objeto de lista #1</li><li>Objeto de lista #2</li><li>Objeto de lista #3</li>
</ul>
<p>Para listas ordenadas, usa [list=1] para lista numerada o [list=a] para lista alfabética.</p>";
?>
