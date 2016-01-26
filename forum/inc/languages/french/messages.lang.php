<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: messages.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */

$l['click_no_wait'] = "Cliquez ici si vous ne voulez pas attendre davantage.";
$l['redirect_return_forum'] = "<br /><br />Sinon, retournez sur <a href=\"{1}\">le forum</a>.";
$l['redirect_emailsent'] = "Votre message a été envoyé par email.";
$l['redirect_loggedin'] = "Vous êtes maintenant connecté.<br />Vous allez maintenant être dirigé vers la page d'où vous provenez.";

$l['error_incompletefields'] = "Vous avez laissé un ou plusieurs champs requis vides. Veuillez retourner en arrière et vérifier vos informations, en complétant les champs requis.";
$l['error_alreadyuploaded'] = "Vous avez déjà envoyé le même fichier (en fonction du nom de fichier et de la taille) dans ce message. Choisissez un fichier différent à attacher.";
$l['error_alreadyuploaded'] = "Ce message contient déjà une pièce jointe de même nom. Renommez le fichier et réuploadez-le. Sinon, vous pouvez cliquer sur \"Mettre à jour la pièce jointe\".";
$l['error_nomessage'] = "Désolé, il est impossible d'accéder à votre requête car vous avez entré un message invalide. Retournez en arrière et vérifiez votre message, puis refaites l'opération.";
$l['error_invalidemail'] = "Vous n'avez pas entré une adresse email valide.";
$l['error_nomember'] = "L'utilisateur que vous avez précisé n'est pas valide ou n'existe pas.";
$l['error_maxposts'] = "Désolé, la limite journalière de messages postés a été atteinte. Attendez demain pour poster davantage ou contactez l'Administration.<br /><br />Le nombre maximal de messages que vous pouvez poster par jour est de {1}.";
$l['error_nohostname'] = "Aucun hôte ne peut être trouvé avec l'adresse IP spécifiée.";
$l['error_invalidthread'] = "La discussion demandée n'existe pas.";
$l['error_invalidpost'] = "Le message demandé n'existe pas.";
$l['error_invalidattachment'] = "La pièce jointe demandée n'existe pas.";
$l['error_invalidforum'] = "Forum invalide";
$l['error_closedinvalidforum'] = "Vous ne pouvez pas poster dans ce forum, car il est fermé, ou alors c'est une catégorie.";
$l['error_attachtype'] = "Le type de fichier que vous avez joint n'est pas autorisé. Veuillez retirer le fichier joint ou choisir un autre format.";
$l['error_attachsize'] = "Le fichier attaché est trop volumineux. Le poids maximal de ce genre de fichier est de {1} kilo-octets.";
$l['error_uploadsize'] = "Le fichier à envoyer est trop volumineux.";
$l['error_uploadfailed'] = "Le téléchargement du fichier a échoué. Veuillez choisir un fichier valide et réessayer.";
$l['error_uploadfailed_detail'] = "Détails de l'Erreur :";
$l['error_uploadfailed_php1'] = "PHP a retourné : Le fichier uploadé a dépassé la directive upload_max_filesize de php.ini. Veuillez contacter l'administrateur du forum et lui transmettre cette erreur.";
$l['error_uploadfailed_php2'] = "Le fichier uploadé a dépassé la taille maximale permise.";
$l['error_uploadfailed_php3'] = "Le fichier uploadé ne l'a été que partiellement.";
$l['error_uploadfailed_php4'] = "Aucun fichier n'a été uploadé.";
$l['error_uploadfailed_php6'] = "PHP a retourné : Un dossier temporaire est manquant. Veuillez contacter l'administrateur du forum et lui transmettre cette erreur.";
$l['error_uploadfailed_php7'] = "PHP a retourné : Impossible d'écrire le fichier sur le disque. Veuillez contacter l'administrateur du forum et lui transmettre cette erreur.";
$l['error_uploadfailed_phpx'] = "PHP a retourné le code d'erreur : {1}. Veuillez contacter l'administrateur du forum et lui transmettre cette erreur.";
$l['error_uploadfailed_nothingtomove'] = "Un fichier invalide a été spécifié, le fichier uploadé n'a donc pas pu être déplacé vers sa destination.";
$l['error_uploadfailed_movefailed'] = "Un problème est survenu lors du déplacement du fichier uploadé vers sa destination.";
$l['error_uploadfailed_lost'] = "La pièce jointe n'a pas pu être trouvée sur le serveur.";
$l['error_emailmismatch'] = "Les adresses email ne correspondent pas. Vérifiez vos données et réessayez.";
$l['error_nopassword'] = "Vous n'avez pas entré un mot de passe valide.";
$l['error_usernametaken'] = "Le nom d'utilisateur que vous avez choisi est déjà pris.";
$l['error_nousername'] = "Vous n'avez pas choisi de nom d'utilisateur.";
$l['error_invalidusername'] = "Le nom d'utilisateur que vous avez choisi est invalide.";
$l['error_invalidpassword'] = "Le mot de passe que vous avez entré est incorrect. Si vous avez perdu votre mot de passe, cliquez <a href=\"member.php?action=lostpw\">ici</a>. Sinon, vérifiez vos données et réessayez.";
$l['error_postflooding'] = "Désolé, impossible de poster votre message. L'administration a spécifié que l'intervalle minimum entre chaque message est de {1} secondes.";
$l['error_nopermission_guest_1'] = "Vous n'êtes pas connecté, ou vous n'avez pas les permissions pour accéder à cette page. C'est peut-être lié à une des raisons suivantes :";
$l['error_nopermission_guest_2'] = "Vous n'êtes pas connecté ou enregistré. Utilisez le formulaire au bas de la page pour vous connecter.";
$l['error_nopermission_guest_3'] = "Vous n'avez pas la permission d'accéder à cette page. Êtes-vous en train d'essayer d'accéder à une page réservée à l'Administration, ou dont l'accès est contrôlé ? Consultez les règles du forum pour voir si vous êtes autorisé à consulter cette page.";
$l['error_nopermission_guest_4'] = "Votre compte a été désactivé par l'Administration, ou est en attente de validation.";
$l['error_nopermission_guest_5'] = "Vous avez accédé à cette page directement au lieu d'utiliser les formulaires ou liens appropriés.";
$l['login'] = "Se connecter";
$l['need_reg'] = "S'enregistrer ?";
$l['forgot_password'] = "Perte de mot de passe ?";
$l['error_nopermission_user_1'] = "Vous n'avez pas la permission d'accéder à cette page. C'est peut-être lié à une des raisons suivantes :";
$l['error_nopermission_user_ajax'] = "Vous n'avez pas la permission d'accéder à cette page.";
$l['error_nopermission_user_2'] = "Votre compte a été suspendu, ou ne peut plus accéder à cette ressource.";
$l['error_nopermission_user_3'] = "Vous n'avez pas la permission d'accéder à cette page. Êtes-vous en train d'essayer d'accéder à une page réservée à l'Administration, ou dont l'accès est contrôlé ? Consultez les règles du forum pour voir si vous êtes autorisé à consulter cette page.";
$l['error_nopermission_user_4'] = "Votre compte est en attente de validation, ou de modération.";
$l['error_nopermission_user_5'] = "Vous avez accédé directement à cette page au lieu d'utiliser les formulaires ou liens appropriés.";
$l['error_nopermission_user_resendactivation'] = "Renvoyer l'email d'activation";
$l['error_nopermission_user_5'] = "Vous êtes connecté avec le nom d'utilisateur : '{1}'";
$l['logged_in_user'] = "Connecté en Utilisateur";
$l['error_too_many_images'] = "Trop d'images";
$l['error_too_many_images2'] = "Désolé, votre requête ne peut pas aboutir, votre message contient trop d'images. Retirez quelques images de votre message, ou faites plusieurs messages pour continuer.";
$l['error_too_many_images3'] = "<b>Attention :</b> Le nombre maximal d'images par message est de";
$l['error_attach_file'] = "Erreur en joignant un fichier";
$l['please_correct_errors'] = "Corrigez ces erreurs suivantes, avant de continuer :";
$l['error_reachedattachquota'] = "Désolé, vous ne pouvez pas joindre ce fichier, car vous avez atteint votre quota de {1}";
$l['error_invaliduser'] = "L'utilisateur demandé est invalide ou n'existe pas.";
$l['error_invalidaction'] = "Action invalide";
$l['error_messagelength'] = "Désolé, votre message est trop long et ne peut pas être posté. Essayez de le réduire et de réessayer.";
$l['error_message_too_short'] = "Désolé, votre message est trop court et n'a pas pu être posté.";
$l['failed_login_wait'] = "Vous n'avez pas réussi à vous connecter avec les tentatives de connexion permises. Vous devez maintenant attendre {1}h {2}m {3}s avant de pouvoir vous reconnecter.";
$l['failed_login_again'] = "<br/>il vous reste <strong>{1}</strong> tentative(s) de connexion.";
$l['error_max_emails_day'] = "Vous ne pouvez pas utiliser les fonctionnalités 'Envoyer la discussion à un ami' ou 'Envoyer un email à un utilisateur' car vous avez déjà atteint votre quota alloué en envoyant {1} messages durant les dernières 24 heures.";

$l['emailsubject_lostpw'] = "Mot de passe réinitialisé sur {1}";
$l['emailsubject_passwordreset'] = "Nouveau mot de passe sur {1}";
$l['emailsubject_subscription'] = "Nouvelle réponse sur {1}";
$l['emailsubject_randompassword'] = "Votre mot de passe pour {1}";
$l['emailsubject_activateaccount'] = "Activation du compte sur {1}";
$l['emailsubject_forumsubscription'] = "Nouvelle discussion sur {1}";
$l['emailsubject_reportpost'] = "Message signalé sur {1}";
$l['emailsubject_reachedpmquota'] = "Quota de messagerie privée atteinte sur {1}";
$l['emailsubject_changeemail'] = "Changer l'adresse email sur {1}";
$l['emailsubject_newpm'] = "Nouveau message privé sur {1}";
$l['emailsubject_sendtofriend'] = "Page web intéressante sur {1}";
$l['emailbit_viewthread'] = "... (lisez la discussion pour en savoir plus)";

$l['email_lostpw'] = "{1},

Pour compléter l'étape de réinitialisation de votre mot de passe sur {2}, vous devez visiter l'URL ci-dessous avec votre navigateur web.

{3}/member.php?action=resetpassword&uid={4}&code={5}

Si le lien précédent ne fonctionne pas, allez sur

{3}/member.php?action=resetpassword

Vous aurez besoin des informations suivantes :
Nom d'utilisateur : {1}
Code d'activation : {5}

Cordialement,
L'équipe de {2}";


$l['email_reportpost'] = "{1} de {2} a signalé ce message :

{3}
{4}/{5}

La raison que l'utilisateur a donnée pour ce signalement :
{7}

Ce message a été envoyé à tous les modérateurs de ce forum, ou tous les administrateurs et super modérateurs, s'il n'y a pas de modérateur.

Veuillez consulter ce message le plus tôt possible.";

$l['email_passwordreset'] = "{1},

Votre mot de passe sur {2} a été réinitialisé.

Votre nouveau mot de passe est : {3}

Vous aurez besoin de ce mot de passe pour vous identifier sur le forum. Une fois que vous serez connecté, vous pourrez le changer depuis le panneau de configuration d'utilisateur.

Cordialement,
L'équipe de {2}";

$l['email_randompassword'] = "{1},

Merci de votre inscription sur {2}. Vous trouverez ci-après votre nom d'utilisateur ainsi que le mot de passe généré. Pour vous connecter sur {2}, vous aurez besoin de ces informations.

Nom d'utilisateur : {3}
Mot de passe : {4}

Il est recommandé de changer votre mot de passe immédiatement après votre connexion. Vous pourrez le faire en allant dans le panneau de configuration d'utilisateur, et en cliquant sur la modification de mot de passe, dans le menu à gauche.

Cordialement,
L'équipe de {2}";

$l['email_sendtofriend'] = "Bonjour,

{1} de {2} pense que vous devriez être intéressé par la lecture de cette page web :

{3}

{1} y compris la discussion suivante :
------------------------------------------
{4}
------------------------------------------

Cordialement,
L'équipe de {2}
";

$l['email_forumsubscription'] = "{1},

{2} a créé une nouvelle discussion sur {3}. C'est le forum auquel vous vous êtes abonné sur {4}.

La discussion se nomme {5}

Voici une partie du message :
--
{6}
--

Pour voir la discussion, vous pouvez aller à cette adresse :
{7}/{8}

Il peut y avoir aussi d'autres nouvelles discussions et réponses, mais vous ne recevrez pas d'informations supplémentaires jusqu'à votre prochaine visite sur le forum.

Cordialement,
L'équipe de {4}

------------------------------------------
Pour se désinscrire :

Si vous ne voulez plus recevoir d'autres informations à propos des nouvelles discussions créées sur le forum auquel vous vous êtes abonné, visitez cette adresse :
{7}/usercp2.php?action=removesubscription&type=forum&fid={9}&my_post_key={10}

------------------------------------------";

$l['email_activateaccount'] = "{1},

Pour achever votre processus d'activation sur {2}, vous devez aller sur l'adresse URL ci-dessous avec votre navigateur web.

{3}/member.php?action=activate&uid={4}&code={5}

Si le lien précédent ne fonctionne pas, allez sur

{3}/member.php?action=activate

Vous aurez besoin des informations suivantes :
Nom d'utilisateur : {1}
Code d'activation : {5}

Cordialement,
L'équipe de {2}";

$l['email_subscription'] = "{1},

{2} a répondu à une discussion auquel vous vous êtes abonné sur {3}. Cette discussion est nommée {4}.

Voici une partie du message :
--
{5}
--

Pour voir la discussion, vous pouvez aller à cette adresse :
{6}/{7}

Il peut y avoir aussi d'autres nouvelles réponses, mais vous ne recevrez pas d'informations supplémentaires jusqu'à votre prochaine visite sur le forum.

Cordialement,
L'équipe de {3}

------------------------------------------
Pour se désinscrire :

Si vous ne voulez plus recevoir d'autres informations à propos des nouvelles réponses créées sur la discussion auquelle vous vous êtes abonnée, visitez cette adresse :
{6}/usercp2.php?action=removesubscription&tid={8}&key={9}&my_post_key={10}

------------------------------------------";
$l['email_reachedpmquota'] = "{1},

Ceci est un email automatique de la part de {2} pour vous informer que votre messagerie privée a atteint sa capacité maximale.

Un ou plusieurs utilisateurs ont tenté de vous envoyer un message privé, mais il s'avère que ces messages n'ont pas pu être transmis.

Veuillez s'il vous plaît supprimer quelques messages de votre messagerie privée, et rappelez-vous de valider la suppression depuis la Corbeille.

Cordialement,
L'équipe de {2}
{3}";
$l['email_changeemail'] = "{1},

Nous avons reçu une demande de {2} pour changer votre adresse email (voir les détails ci-dessous).

Ancienne adresse email : {3}
Nouvelle adresse email : {4}

Si ces changements sont corrects, achevez la procédure de validation sur {2} en allant sur le lien donné avec votre navigateur web.

{5}/member.php?action=activate&uid={8}&code={6}

Si le lien précédent ne fonctionne pas, allez sur

{5}/member.php?action=activate

Vous aurez besoin de ces informations :
Nom d'utilisateur : {7}
Code d'activation : {6}

Si vous avez fait le choix de ne pas valider votre nouvelle adresse email, votre profil ne sera pas mis à jour et continuera à contenir votre adresse email existante.

Cordialement,
L'équipe de {2}
{5}";
$l['email_newpm'] = "{1},
		
Vous avez reçu un nouveau message privé sur {3} de la part de {2}. Pour voir ce message, vous pouvez suivre ce lien :

{4}/private.php

Notez que vous ne recevrez pas de nouvelles informations si vous recevez d'autres messages privés tant que vous n'aurez pas visité le forum {3}.

Vous pouvez désactiver l'alerte par email de la réception de message privé depuis cette adresse :

{4}/usercp.php?action=options

Cordialement,
L'équipe de {3}
{4}";

$l['email_emailuser'] = "{1},

{2} de {3} vous a envoyé le message suivant :
------------------------------------------
{5}
------------------------------------------

Cordialement,
L'équipe de {3}
{4}

------------------------------------------
Vous ne voulez plus recevoir de messages email des autres membres ?

Si vous ne voulez pas que les autres membres aient la possibilité de vous envoyer des emails veuillez vous rendre sur votre Panneau de configuration et activez l'option 'Cacher votre email aux autres membres' :
{4}/usercp.php?action=options

------------------------------------------";
?>