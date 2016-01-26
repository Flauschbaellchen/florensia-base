<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: config_mod_tools.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */
 
$l['mod_tools'] = "Outil de modérateur";

$l['thread_tools'] = "Outils de discussion";
$l['thread_tools_desc'] = "Les outils pesonnalisés de modérateur vous permettent de créer des combinaisons d'actions de modération qui peuvent être utilisées pour les discussions et les messages. Ils peuvent être utilisés comme les outils par défaut pour la gestion de votre forum. Vous pouvez gérer ici vos outils personnalisés de discussion.";

$l['add_thread_tool'] = "Ajouter un outil de discussion";
$l['add_new_thread_tool'] = "Ajouter un outil de discussion";
$l['add_thread_tool_desc'] = "Vous pouvez ajouter ici un nouvel outil de modération de discussion. Cet outil sera accessible à la fois depuis la modération de discussion interne et depuis les discussions elles-mêmes, listé avec les outils de modération par défaut.";

$l['post_tools'] = "Outils de message";
$l['post_tools_desc'] = "Les outils personnalisés de modérateur vous permettent de créer des combinaisons d'actions de modération qui peuvent être utilisées pour les discussions et les messages. Ils peuvent être utilisés comme les outils par défaut pour la gestion de votre forum. Vous pouvez gérer ici vos outils personnalisés de message.";

$l['add_post_tool'] = "Ajouter un outil de message";
$l['add_new_post_tool'] = "Ajouter un nouvel outil de message";
$l['add_post_tool_desc'] = "Vous pouvez ajouter ici un nouvel outil de modération de message. Cet outil sera accessible depuis les discussions elles-mêmes, listé avec les outils de modération par défaut."; 

$l['edit_post_tool'] = "Éditer outil de message";
$l['edit_post_tool_desc'] = "Ici, vous pouvez modifier les paramètres et les actions de l'outil de message.";
$l['edit_thread_tool'] = "Éditer outil de discussion";
$l['edit_thread_tool_desc'] = "Ici, vous pouvez modifier les paramètres et les actions de l'outil de discussion.";

$l['no_thread_tools'] = "Il n'y a aucun outil de discussion installé sur votre forum.";
$l['no_post_tools'] = "Il n'y a aucun outil de message installé sur votre forum.";

$l['confirm_thread_tool_deletion'] = "Êtes-vous sûr de vouloir supprimer cet outil de discussion ?";
$l['confirm_post_tool_deletion'] = "Êtes-vous sûr de vouloir supprimer cet outil de message ?";

$l['success_post_tool_deleted'] = "L'outil personnalisé de modération de message sélectionné a été supprimé.";
$l['success_thread_tool_deleted'] = "L'outil personnalisé de modération de discussion sélectionné a été supprimé.";

$l['error_invalid_post_tool'] = "L'outil de message spécifié n'existe pas.";
$l['error_invalid_thread_tool'] = "L'outil de discussion spécifié n'existe pas.";

$l['general_options'] = "Options générales";
$l['short_description'] = "Courte description";
$l['available_in_forums'] = "Disponible dans les forums";
$l['all_forums'] = "Tous les forums";
$l['select_forums'] = "Sélectionner les forums";
$l['save_thread_tool'] = "Enregistrer l'outil de discussion";

$l['title'] = "Titre";

$l['thread_moderation'] = "Modération de discussion";
$l['approve_unapprove'] = "Approuver/Désapprouver la discussion ?";

$l['no_change'] = "Inchangé";
$l['approve'] = "Approuver";
$l['unapprove'] = "Désapprouver";
$l['stick'] = "Épingler";
$l['unstick'] = "Retirer l'épingle";
$l['open'] = "Ouvrir";
$l['close'] = "Fermer";
$l['toggle'] = "Basculer";
$l['days'] = "Jours";
$l['no_prefix'] = "Pas de préfixe";

$l['forum_to_move_to'] = "Déplacer vers le forum :";
$l['leave_redirect'] = "Laisser une redirection ?";
$l['delete_redirect_after'] = "Supprimer la redirection après";
$l['do_not_move_thread'] = "Ne pas déplacer la discussion";
$l['do_not_copy_thread'] = "Ne pas copier la discussion";
$l['move_thread'] = "Déplacer la discussion ?";
$l['move_thread_desc'] = "Si vous déplacez des discussions, la mention \"supprimer la redirection après... jours\" est à remplir seulement si une redirection doit être laissée.";
$l['forum_to_copy_to'] = "Forum de destination :";
$l['copy_thread'] = "Copier la discussion ?";
$l['open_close_thread'] = "Ouvrir/Fermer la discussion ?";
$l['delete_thread'] = "Supprimer la discussion ?";
$l['merge_thread'] = "Fusionner la discussion ?";
$l['merge_thread_desc'] = "Seulement si utilisé en modération interne.";
$l['delete_poll'] = "Supprimer le sondage ?";
$l['delete_redirects'] = "Supprimer les redirections ?";
$l['apply_thread_prefix'] = "Appliquer le préfixe de discussion ?";
$l['new_subject'] = "Nouveau titre ?";
$l['new_subject_desc'] = "{subject} représente le titre original. {username} représente le nom d'utilisateur du modérateur.";

$l['add_new_reply'] = "Ajouter une nouvelle réponse";
$l['add_new_reply_desc'] = "Laisser vide pour pas de réponse.";
$l['reply_subject'] = "Titre de la réponse.";
$l['reply_subject_desc'] = "Utilisé seulement si une réponse a été créée.<br />{subject} représente le titre original. {username} représente le nom d'utilisateur du modérateur.";

$l['success_mod_tool_created'] = "L'outil de modération a été créé.";
$l['success_mod_tool_updated'] = "L'outil de modération a été mis à jour.";

$l['inline_post_moderation'] = "Modération interne de message";
$l['delete_posts'] = "Supprimer les messages ?";
$l['merge_posts'] = "Fusionner les messages ?";
$l['merge_posts_desc'] = "Seulement si utilisé depuis la modération interne.";
$l['approve_unapprove_posts'] = "Approuver/Désapprouver les messages ?";

$l['split_posts'] = "Diviser les messages";
$l['split_posts2'] = "Diviser les messages ?";
$l['do_not_split'] = "Ne pas diviser les messages";
$l['split_to_same_forum'] = "Diviser dans le même forum";
$l['close_split_thread'] = "Fermer la discussion divisée ?";
$l['stick_split_thread'] = "Épingler la discussion divisée ?";
$l['unapprove_split_thread'] = "Désapprouver la discussion divisée ?";
$l['split_thread_subject'] = "Diviser le titre de la discussion";
$l['split_thread_subject_desc'] = "{subject} représente le titre original. Requis seulement si vous divisez des messages.";
$l['add_new_split_reply'] = "Ajouter une réponse à la discussion divisée";
$l['add_new_split_reply_desc'] = "Laisser vide pour pas de réponse.";
$l['split_reply_subject'] = "Titre de la réponse";
$l['split_reply_subject_desc'] = "Utilisé seulement si une réponse est créée";
$l['save_post_tool'] = "Enregistrer l'outil de message";

$l['error_missing_title'] = "Entrez un nom pour cet outil.";
$l['error_missing_description'] = "Entrez une courte description pour cet outil.";
$l['error_no_forums_selected'] = "Sélectionnez les forums dans lesquels cet outil sera disponible.";
$l['error_forum_is_category'] = "Vous ne pouvez pas choisir un forum type catégorie comme forum de destination.";
?>