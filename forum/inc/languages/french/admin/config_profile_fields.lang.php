<?php
/**
 * MyBB 1.6 French Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: config_profile_fields.lang.php 5016 2010-06-12 00:24:02Z RyanGordon $
 */
 
$l['custom_profile_fields'] = "Champs de profil personnalisés";
$l['custom_profile_fields_desc'] = "Cette section vous permet d'éditer, supprimer, et gérer vos champs de profil personnalisés.";
$l['add_profile_field'] = "Ajouter un champ de profil";
$l['add_new_profile_field'] = "Ajouter un champ de profil";
$l['add_new_profile_field_desc'] = "Ici vous pouvez ajouter un nouveau champ de profil personnalisé.";
$l['edit_profile_field'] = "Éditer un champ de profil";
$l['edit_profile_field_desc'] = "Ici vous pouvez éditer un champ de profil personnalisé.";

$l['title'] = "Titre";
$l['short_description'] = "Courte description";
$l['maximum_length'] = "Longueur maximum";
$l['maximum_length_desc'] = "Le nombre maximum de caractères qui peuvent être entrés. Ceci s'applique seulement aux boîtes de texte et zones de texte.";
$l['field_length'] = "Longueur du champ";
$l['field_length_desc'] = "La longueur du champ. Ceci s'applique seulement aux boîtes de sélection simples et multiples.";
$l['display_order'] = "Ordre d'affichage";
$l['display_order_desc'] = "Ceci est l'ordre des champs de profil personnalisés en relation avec les autres champs de profil personnalisés. Ce nombre ne doit pas être le même que celui d'un autre champ.";
$l['text'] = "Boîte texte";
$l['textarea'] = "Zone texte";
$l['select'] = "Boîte sélection";
$l['multiselect'] = "Boîte de sélection à options multiple";
$l['radio'] = "Boutons radio";
$l['checkbox'] = "Cases à cocher";
$l['field_type'] = "Type de champ";
$l['field_type_desc'] = "Ceci est le type de champ qui doit être affiché.";
$l['selectable_options'] = "Option sélectionnable ?";
$l['selectable_options_desc'] = "Entrez chaque option sur une ligne séparée. Ceci s'applique seulement aux types boîte de sélection, cases à cocher, et boutons radio.";
$l['required'] = "Requis ?";
$l['required_desc'] = "Est-il exigé de remplir ce champ pendant l'enregistrement ou l'édition de profil ? Notez que ceci ne s'applique pas si le champ est caché.";
$l['editable_by_user'] = "Éditable par l'utilisateur ?";
$l['editable_by_user_desc'] = "Ce champ doit-il être éditable par l'utilisateur ? Si non, les administrateurs/modérateurs peuvent toujours éditer ce champ.";
$l['hide_on_profile'] = "Cacher dans le profil ?";
$l['hide_on_profile_desc'] = "Ce champ doit-il être caché dans le profil de l'utilisateur ? S'il est caché, il sera uniquement visible par les administrateurs/modérateurs.";
$l['save_profile_field'] = "Enregistrer le champ de profil";
$l['name'] = "Nom";
$l['id'] = "ID";
$l['editable'] = "Éditable ?";
$l['hidden'] = "Caché ?";
$l['edit_field'] = "Éditer le champ";
$l['delete_field'] = "Supprimer le champ";
$l['no_profile_fields'] = "Il n'y a actuellement aucun profil d'utilisateur personnalisé sur votre forum.";

$l['error_missing_name'] = "Vous n'avez pas entré de titre pour ce champ de profil personnalisé.";
$l['error_missing_description'] = "Vous n'avez pas entré de description pour ce champ de profil personnalisé.";
$l['error_missing_filetype'] = "Vous n'avez pas entré de type de champ pour ce champ de profil personnalisé.";
$l['error_missing_required'] = "Vous n'avez pas sélectionné Oui ou Non pour l'option \"Requis ?\".";
$l['error_missing_editable'] = "Vous n'avez pas sélectionné Oui ou Non pour l'option \"Éditable par l'utilisateur ?\".";
$l['error_missing_hidden'] = "Vous n'avez pas sélectionné Oui ou Non pour l'option \"Cacher dans le profil ?\".";
$l['error_invalid_fid'] = "Le champ de profil sélectionné n'existe pas.";

$l['success_profile_field_added'] = "Le champ de profil personnalisé a été créé.";
$l['success_profile_field_saved'] = "Le champ de profil personnalisé a été enregistré.";
$l['success_profile_field_deleted'] = "Le champ de profil personnalisé sélectionné a été supprimé.";
$l['confirm_profile_field_deletion'] = "Êtes-vous sûr de vouloir supprimer ce champ de profil personnalisé ?";
?>