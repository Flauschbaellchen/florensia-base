<?php
/**
 * Idioma Português do Brasil para MyBB 1.6 - Versão 1.0
 * Tradutor: Speeder
 * Copyright © 2008 - 2010 MyBB Brasil - Todos os Direitos Reservados
 * http://www.mybb.com.br
 *
 * $Id: config_attachment_types.lang.php 1000 03/08/2010 16:25:00 Speeder $
 */

$l['attachment_types'] = "Tipos de Anexos";
$l['attachment_types_desc'] = "Aqui você pode criar e gerenciar os tipos de anexos que definem os tipos de arquivos que os usuários podem adicionar nas respostas.";
$l['add_new_attachment_type'] = "Adicionar Novo Tipo de Anexo";
$l['add_attachment_type'] = "Adicionar Tipo de Anexo";
$l['add_attachment_type_desc'] = "Adicionar um novo tipo de anexo permitirá que os usuários adicionem arquivos deste tipo nas respostas. Você pode controlar a extensão, tipo MIME, tamanho máximo e mostrar um pequeno ícone para cada tipo de anexo.";
$l['edit_attachment_type'] = "Editar Tipo de Anexo";
$l['edit_attachment_type_desc'] = "Você pode controlar a extensão, tipo MIME, tamanho máximo e mostrar um pequeno tipo MIME para este tipo de anexo.";

$l['extension'] = "Extensão";
$l['maximum_size'] = "Tamanho Máximo";
$l['no_attachment_types'] = "Não existem tipos de anexos definidos no momento.";

$l['file_extension'] = "Extensão do Arquivo";
$l['file_extension_desc'] = "Informe a extensão do arquivo que deseja liberar uploads (Não use vírgulas antes da extensão) (Examplo: txt)";
$l['mime_type'] = "Tipo MIME";
$l['mime_type_desc'] = "Informe o tipo MIME enviado pelo servidor ao baixar arquivos deste tipo (<a href=\"http://www.webmaster-toolkit.com/mime-types.shtml\">Veja a lista aqui</a>)";
$l['maximum_file_size'] = "Tamanho Máximo do Arquivo (Kilobytes)";
$l['maximum_file_size_desc'] = "O tamanho máximo do arquivo para uploads deste tipo de anexo em Kilobytes (1 MB = 1024 KB)";
$l['limit_intro'] = "Tenha certeza de que o tamanho máximo é menor do que os seguintes limites do PHP do seu servidor:";
$l['limit_post_max_size'] = "Tamanho Máximo de Postagem (Max Post Size): {1}";
$l['limit_upload_max_filesize'] = "Tamanho Máximo de Upload (Upload Max File Size): {1}";
$l['attachment_icon'] = "Ícone de Anexo";
$l['attachment_icon_desc'] = "Se você deseja mostrar um pequeno ícone para o tipo de anexo, informe o caminho para a imagem aqui. {theme} será substituído pela pasta de imagens usada pelo tema em uso, então é possível definir ícones de anexo diferentes, dependendo do tema usado.";
$l['save_attachment_type'] = "Salvar Tipo de Anexo";

$l['error_invalid_attachment_type'] = "Você selecionou um tipo de anexo inválido.";
$l['error_missing_mime_type'] = "Você não informou um tipo MIME para este tipo de anexo";
$l['error_missing_extension'] = "Você não informou a extensão para este tipo de anexo";

$l['success_attachment_type_created'] = "O tipo de anexo foi criado com sucesso.";
$l['success_attachment_type_updated'] = "O tipo de anexo foi atualizado com sucesso.";
$l['success_attachment_type_deleted'] = "O tipo de anexo foi excluído com sucesso.";

$l['confirm_attachment_type_deletion'] = "Tem certeza que deseja excluir este tipo de anexo?";

?>