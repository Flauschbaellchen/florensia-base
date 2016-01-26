<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: helpdocs.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */

// Help Document 1
$l['d1_name'] = "Enregistrement de l'utilisateur";
$l['d1_desc'] = "Avantages et privilèges de l'enregistrement";
$l['d1_document'] = "Quelques parties de ce forum peuvent demander une inscription, et de vous connecter. L'enregistrement est gratuit et prend seulement quelques minutes.
<br /><br />Vous êtes encouragé à vous enregistrer ; une fois enregistré, vous pourrez poster des messages, définir vos préférences, et mettre à jour votre profil.
<br /><br />Les éléments qui demandent un enregistrement sont les abonnements, la gestion des favoris, le changement de style et de thème, l'accès au bloc-notes personnel et le contact par email des membres du forum.";

// Help Document 2
$l['d2_name'] = "Mise à jour du profil";
$l['d2_desc'] = "Modifier vos données enregistrées.";
$l['d2_document'] = "À certains moments, vous pourrez décider de mettre à jour certaines informations, comme par exemple les informations de messagerie instantanée, votre mot de passe, ou encore le changement d'adresse email. Vous pouvez changer ces informations depuis le panneau de configuration de l'utilisateur. Pour accéder à ce panneau, cliquez simplement sur le lien en haut de la page. Depuis cet endroit, choisissez de modifier votre profil et changez ou mettez à jour toutes les données que vous désirez, validez ensuite vos changements avec le bouton adéquat en bas de la page pour qu'ils prennent effet.";

// Help Document 3
$l['d3_name'] = "Usage des cookies avec MyBB";
$l['d3_desc'] = "MyBB utilise les cookies pour stocker vos informations d'enregistrement.";
$l['d3_document'] = "MyBB utilise les cookies pour stocker vos informations de connexion, si vous êtes enregistré, et votre dernière visite, si vous ne l'êtes pas.
<br /><br />Les cookies sont des petits documents de texte stockés sur votre ordinateur ; les cookies de ce forum ne sont utilisés que par ce forum et ne posent aucun problème de sécurité.
<br /><br />Les cookies de ce forum suivent vos lectures et le moment où vous lisez certains messages.
<br /><br />Pour supprimer tous les cookies de ce forum, vous pouvez cliquer <a href=\"misc.php?action=clearcookies&amp;key={1}\">ici</a>.";

// Help Document 4
$l['d4_name'] = "Connexion et déconnexion";
$l['d4_desc'] = "Comment se connecter et se déconnecter.";
$l['d4_document'] = "Quand vous vous connectez, vous créez un cookie sur votre ordinateur, ce qui vous permet de visiter le forum sans avoir à redonner votre nom d'utilisateur et votre mot de passe à chaque fois. La déconnexion supprime les cookies, pour s'assurer que personne ne navigue sur le forum avec votre compte.
<br /><br />Pour vous connecter, cliquez simplement sur le bouton 'Identification' en haut. Pour vous déconnecter, cliquez sur le bouton qui est au même endroit. Enfin, si vous supprimez les cookies de votre ordinateur, vous serez déconnecté.";

// Help Document 5
$l['d5_name'] = "Poster une nouvelle discussion";
$l['d5_desc'] = "Créer une nouvelle discussion dans un forum";
$l['d5_document'] = "Quand vous êtes intéressé par un forum, vous pouvez créer une nouvelle discussion, cliquez simplement le bouton situé en haut et en bas intitulé \"Nouvelle discussion\". N'oubliez pas que vous n'aurez peut-être pas les permissions de poster dans tous les forums, en fonction des restrictions imposées par l'Administration du forum.";

// Help Document 6
$l['d6_name'] = "Poster une réponse";
$l['d6_desc'] = "Répondre à un message.";
$l['d6_document'] = "Vous pouvez répondre à un message, dans une discussion. Pour faire ceci, cliquez sur le bouton situé en haut et en bas intitulé \"Répondre\". N'oubliez pas que vous n'aurez peut-être pas les permissions de poster dans tous les forums, en fonction des restrictions imposées par l'Administration du forum.
<br /><br />En outre, un modérateur d'un forum peut verrouiller une discussion, ce qui fait qu'il est impossible de poster à la suite. Un utilisateur ne peut pas réouvrir une discussion verrouillée sans l'intervention d'un modérateur ou d'un administrateur.";

// Help Document 7
$l['d7_name'] = "MyCode";
$l['d7_desc'] = "Utiliser le MyCode, pour agrémenter vos messages.";
$l['d7_document'] = "Vous pouvez utiliser le MyCode, une version simplifiée du HTML, dans vos messages pour créer certains effets.
<p><br />[b]Ce texte est gras[/b]<br />&nbsp;&nbsp;&nbsp;<b>Ce texte est gras</b>
<p>[i]Ce texte est en italique[/i]<br />&nbsp;&nbsp;&nbsp;<i>Ce texte est en italique</i>
<p>[u]Ce texte est souligné[/u]<br />&nbsp;&nbsp;&nbsp;<u>Ce texte est souligné</u>
<p>[s]Ce texte est barré[/s]<br />&nbsp;&nbsp;&nbsp;<strike>Ce texte est barré</strike>
<p><br />[url]http://www.exemple.com/[/url]<br />&nbsp;&nbsp;&nbsp;<a href=\"http://www.exemple.com/\">http://www.exemple.com/</a>
<p>[url=http://www.exemple.com/]Exemple.com[/url]<br />&nbsp;&nbsp;&nbsp;<a href=\"http://www.exemple.com/\">Exemple.com</a>
<p>[email]exemple@exemple.com[/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:exemple@exemple.com\">exemple@exemple.com</a>
<p>[email=exemple@exemple.com]Envoyez moi un email ![/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:exemple@exemple.com\">Envoyez moi un email !</a>
<p>[email=exemple@exemple.com?subject=spam]E-mail avec sujet[/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:exemple@exemple.com?subject=spam\">E-mail avec sujet</a>
<p><br />[quote]Texte cité[/quote]<br />&nbsp;&nbsp;&nbsp;<quote>Texte cité</quote>
<p>[code]Texte avec formatage préservé[/code]<br />&nbsp;&nbsp;&nbsp;<code>Texte avec formatage préservé</code>
<p><br />[img]http://www.php.net/images/php.gif[/img]<br />&nbsp;&nbsp;&nbsp;<img src=\"http://www.php.net/images/php.gif\">
<p>[img=50x50]http://www.php.net/images/php.gif[/img]<br />&nbsp;&nbsp;&nbsp;<img src=\"http://www.php.net/images/php.gif\" width=\"50\" height=\"50\">
<p><br />[color=red]Ce texte est rouge[/color]<br />&nbsp;&nbsp;&nbsp;<font color=\"red\">Ce texte est rouge</font>
<p>[size=3]Ce texte est de taille 3[/size]<br />&nbsp;&nbsp;&nbsp;<font size=\"3\">Ce texte est de taille 3</font>
<p>[font=Tahoma]Cette police est Tahoma[/font]<br />&nbsp;&nbsp;&nbsp;<font face=\"Tahoma\">Cette police est Tahoma</font>
<p><br />[align=center]C'est centré[/align]<div align=\"center\">C'est centré</div>
<p>[align=right]C'est aligné à droite[/align]<div align=\"right\">C'est aligné à droite</div>
<p><br />[list]<br />[*]Objet de la liste #1<br />[*]Objet de la liste #2<br />[*]Objet de la liste #3<br />[/list]<br /><ul><li>Objet de la liste #1</li><li>Objet de la liste #2</li><li>Objet de la liste #3</li>
</ul>
<p>Vous pouvez faire une liste hiérarchisée en utilisant [list=1] pour une numérotation, et [list=a] pour un classement alphabétique.";
?>