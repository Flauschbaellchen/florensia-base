<?php
/**
 * MyBB 1.6 Turkish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: user_banning.lang.php 4941 2010-05-15 18:17:38Z MD $
 */

// Tabs
$l['banning'] = "Yasaklama";
$l['banned_accounts'] = "Yasaklı Hesaplar";
$l['banned_accounts_desc'] = "Buradan forum girişinden yasaklanmış olan kullanıcı hesaplarını yönetebilirsiniz.";
$l['ban_a_user'] = "Kullanıcı Yasakla";
$l['ban_a_user_desc'] = "Buradan bir kullanıcı yasaklayabilirsiniz.";
$l['edit_ban'] = "Yasaklamayı Düzenle";
$l['edit_ban_desc'] = "Buradan şu anda yasaklı kullanıcıların yasaklama sebep ve uzunluklarını düzenleyebilirsiniz.";
$l['banned_ips'] = "Yasaklı IP Adresleri";
$l['disallowed_usernames'] = "Yasaklı Kullanıcılar";
$l['disallowed_email_addresses'] = "Yasaklı Email Adresleri";

// Errors
$l['error_invalid_ban'] = "Düzenlemek için geçersiz bir yasaklama seçtiniz.";
$l['error_invalid_username'] = "Girmiş olduğunuz kullanıcı adı ya geçersiz ya da mevcut değil.";
$l['error_no_perm_to_ban'] = "Bu kullanıcıyı yasaklama izniniz yok.";
$l['error_already_banned'] = "Bu kullanıcı zaten yasaklı bir grupta ve yeni bir tanesine eklenemez.";
$l['error_ban_self'] = "Kendinizi yasaklayamazsınız";
$l['error_no_reason'] = "Bu kullanıcıyı yasaklamak için bir sebep girmediniz.";

// Success
$l['success_ban_lifted'] = "Yasaklama başarılı bir şekilde iptal edildi.";
$l['success_banned'] = "Kullanıcı başarılı bir şekilde yasaklandı";
$l['success_ban_updated'] = "Yasaklama başarılı bir şekilde güncellendi.";
$l['success_pruned'] = "Seçilen kullanıcının konu ve mesajları başarılı bir şekilde kaldırılmıştır";

// Confirm
$l['confirm_lift_ban'] = "Bu yasaklamayı iptal etmek istediğinizden eminmisiniz?";
$l['confirm_prune'] = "Bu kullanıcı tarafından oluşturulan bütün konu ve mesajlarını kaldırmak istediğinizden eminmisiniz?";

//== Pages
//= Add / Edit
$l['ban_username'] = "Kullanıcı Adı <em>*</em>";
$l['autocomplete_enabled'] = "Bu alan için otomatik tamamlama açık.";
$l['ban_reason'] = "Yasak Sebebi";
$l['ban_group'] = "Yasaklanan Grup <em>*</em>";
$l['ban_group_desc'] = "Bu kullanıcının yasaklanmaya uygun olması için yasaklı bir gruba taşınmaları gereklidir.";
$l['ban_time'] = "Yasak Uzunluğu <em>*</em>";

//= Index
$l['user'] = "Kullanıcı Adı";
$l['moderation'] = "Moderasyon";
$l['ban_lifts_on'] = "Yasaklama Tarihi";
$l['time_left'] = "Kalan Süre";
$l['permenantly'] = "kalıcı olarak";
$l['na'] = "Yok";
$l['for'] = "";
$l['bannedby_x_on_x'] = "<strong>{1}</strong><br /><small>Yasaklayan: {2} Tarihi: {3} Süresi: {4}</small>";
$l['lift'] = "İptal Et";
$l['no_banned_users'] = "Şu anda yasaklanan bir kullanıcı yok.";
$l['prune_threads_and_posts'] = "Konuları &amp; Mesajları Sil";

// Buttons
$l['ban_user'] = "Kullanıcı Yasakla";
$l['update_ban'] = "Yasaklama Güncelle";

?>