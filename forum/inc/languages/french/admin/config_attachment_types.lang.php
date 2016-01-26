<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright Œ 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: config_attachment_types.lang.php 4941 2010-05-15 18:17:38Z RyanGordon $
 */

$l['attachment_types'] = "Types de pièces jointes";
$l['attachment_types_desc'] = "Ici, vous pouvez créer et gérer les types de pièces jointes qui définissent quels types de fichiers les utilisateurs peuvent joindre à leurs messages.";
$l['add_new_attachment_type'] = "Ajouter un nouveau type de pièce jointe";
$l['add_attachment_type'] = "Ajouter un type de pièce jointe";
$l['add_attachment_type_desc'] = "L'ajout d'un nouveau type de pièce jointe permettra aux membres de joindre des fichiers de ce type à leurs messages. Vous avez la possibilité de contrôler l'extension, le type MIME, la taille maximale et l'affichage d'une petite icône pour chaque type de pièce jointe.";
$l['edit_attachment_type'] = "Éditer type de pièce jointe";
$l['edit_attachment_type_desc'] = "Vous avez la possibilité de contrôler l'extension, le type MIME, la taille maximale et l'affichage d'une petite icône pour ce type de pièce jointe.";

$l['extension'] = "Extension";
$l['maximum_size'] = "Taille maximum";
$l['no_attachment_types'] = "Il n'y a actuellement aucun type de pièce jointe sur votre forum.";

$l['file_extension'] = "Extension de fichier";
$l['file_extension_desc'] = "Entrez ici l'extension de fichier que vous voulez autoriser en upload (N'incluez pas le point avant l'extension) (Exemple : txt).";
$l['mime_type'] = "Type MIME";
$l['mime_type_desc'] = "Entrez le type MIME envoyé par le serveur quand on télécharge des fichiers de ce type (<a href=\"http://www.webmaster-toolkit.com/mime-types.shtml\">Voir une liste ici</a>).";
$l['maximum_file_size'] = "Taille maximum du fichier (Kilo-octets)";
$l['maximum_file_size_desc'] = "La taille maximum du fichier à uploader pour ce type de pièce jointe en Kilo-octets (1 Mo = 1024 Ko).";
$l['limit_intro'] = "Assurez-vous que la taille maximum du fichier est inférieure aux limites PHP suivantes :";
$l['limit_post_max_size'] = "Taille max. du message : {1}";
$l['limit_upload_max_filesize'] = "Taille max. du fichier uploadé : {1}";
$l['attachment_icon'] = "Icône de pièce jointe";
$l['attachment_icon_desc'] = "Si vous voulez afficher une petite icône pour les pièces jointes de ce type, entrez son chemin ici. {theme} sera remplacé par le dossier image pour l'affichage des thèmes vous permettant de spécifier des icônes de pièces jointes regroupées par thèmes.";
$l['save_attachment_type'] = "Enregistrer type de pièce jointe";
$l['error_invalid_attachment_type'] = "Vous avez choisi un type de pièce jointe invalide.";
$l['error_missing_mime_type'] = "Vous n'avez pas entré de type MIME pour ce type de pièce jointe.";
$l['error_missing_extension'] = "Vous n'avez pas entré d'extension de fichier pour ce type de pièce jointe.";

$l['success_attachment_type_created'] = "Le type de pièce jointe a été effacé.";
$l['success_attachment_type_updated'] = "Le type de pièce jointe a été mis à jour.";
$l['success_attachment_type_deleted'] = "Le type de pièce jointe a été supprimé.";

$l['confirm_attachment_type_deletion'] = "Êtes-vous sûr de vouloir supprimer ce type de pièce jointe ?";

?>