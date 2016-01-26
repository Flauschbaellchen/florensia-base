<?php
/**
 * MyBB 1.6 Turkish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: config_warning.lang.php 4941 2010-05-15 18:17:38Z MD $
 */
 
$l['warning_system'] = "Uyarı Sistemi";
$l['warning_types'] = "Uyarı Tipleri";
$l['warning_types_desc'] = "Buradan listelenen uyarı tiplerini yönetebilirsiniz.";
$l['add_warning_type'] = "Yeni Uyarı Tipi Ekle";
$l['add_warning_type_desc'] = "Buradan yeni önceden tanımlanan uyarı tipi oluşturabilirsiniz. Kullanıcı uyarırken uyarı tipleri seçilebilirdir ve bu tip için puan sayısı tanımlayabilir hem de uyarı tipi bitişi için zaman aralığı tanımlayabilirsiniz.";
$l['edit_warning_type'] = "Uyarı Tipini Düzenle";
$l['edit_warning_type_desc'] = "Buradan bu uyarı tipini düzenleyebilirsiniz. Kullanıcı uyarırken uyarı tipleri seçilebilirdir ve bu tip için puan sayısı tanımlayabilir hem de uyarı tipi bitişi için zaman aralığı tanımlayabilirsiniz.";
$l['warning_levels'] = "Uyarı Seviyeleri";
$l['warning_levels_desc'] = "Uyarı seviyeleri maksimum uyarı puanı yüzdesine göre kullanıcıya olacaklar olarak tanımlanabilir. Kullanıcıları banlayabilir ya da ayrıcalıklarını askıya alabilirsiniz.";
$l['add_warning_level'] = "Yeni Uyarı Seviyesi Ekle";
$l['add_warning_level_desc'] = "Buradan yeni bir uyarı seviyesi oluşturabilirsiniz. Uyarı seviyeleri bir kullanıcı belirli bir uyarı seviyesi yüzdesini geçen kullanıcılara yapılacaklardır.";
$l['edit_warning_level'] = "Uyarı Seviyesi Düzenle";
$l['edit_warning_level_desc'] = "Uyarı seviyeleri bir kullanıcı belirli bir uyarı seviyesi yüzdesini geçen kullanıcılara yapılacaklardır.";

$l['percentage'] = "Yüzde";
$l['action_to_take'] = "Yapılacak Hareket";
$l['move_banned_group'] = "{2} {3} yasaklı gruba ({1}) taşı";
$l['move_banned_group_permanent'] = "Yasaklı gruba kalıcı olarak ({1}) taşı.";
$l['suspend_posting'] = "{1} {2} mesajlaşma ayrıcalıklarını askıya al";
$l['suspend_posting_permanent'] = "Mesajlaşma ayrıcalıklarını kalıcı askıya al";
$l['moderate_new_posts'] = "{1} {2} yeni mesajları yönet";
$l['moderate_new_posts_permanent'] = "Yeni mesajları kalıcı yönet";
$l['no_warning_levels'] = "Şu anda forumunuzda bir uyarı seviyesi bulunmamaktadır.";

$l['warning_type'] = "Uyarı Tipi";
$l['points'] = "Puan";
$l['expires_after'] = "Sonra Bitir";
$l['no_warning_types'] = "Şu anda forumunuzda bir uyarı tipi bulunmamaktadır.";

$l['warning_points_percentage'] = "Maksimum Uyarı Puanı Yüzdesi";
$l['warning_points_percentage_desc'] = "Lütfen 1 ve 100 arasında bir sayısal değer girin";
$l['action_to_be_taken'] = "Yapılacak Hareket";
$l['action_to_be_taken_desc'] = "Kullanıcılar aşağıdaki seviyeyi aşarsa yapılmasını istediğiniz hareketi seçin.";
$l['ban_user'] = "Kullanıcı Yasakla";
$l['banned_group'] = "Yasaklanan Grup:";
$l['ban_length'] = "Yasaklama Uzunluğu:";
$l['suspend_posting_privileges'] = "Mesajlaşmada Askıya Alma Ayrıcalıkları";
$l['suspension_length'] = "Askıya alma uzunluğu:";
$l['moderate_posts'] = "Mesajları Yönet";
$l['moderation_length'] = "Yönetme Uzunluğu:";
$l['save_warning_level'] = "Uyarı Seviyesini Kaydet";

$l['title'] = "Başlık";
$l['points_to_add'] = "Eklenecek Puan";
$l['points_to_add_desc'] = "Bir kullanıcıya eklenecek uyarı seviyesi için puan sayıdır.";
$l['warning_expiry'] = "Uyarı Bitişi";
$l['warning_expiry_desc'] = "Bu uyarıdan ne kadar zaman sonra bitmesini istersiniz?";
$l['save_warning_type'] = "Uyarı Tipini Kaydet";

$l['expiration_hours'] = "Saat";
$l['expiration_days'] = "Gün";
$l['expiration_weeks'] = "Hafta";
$l['expiration_months'] = "Ay";
$l['expiration_never'] = "Asla";
$l['expiration_permanent'] = "Kalıcı";

$l['error_invalid_warning_level'] = "Belirtilen uyarı seviyesi mevcut değil.";
$l['error_invalid_warning_percentage'] = "Bu uyarı seviyesi için uygun bir yüzdelik değer girmediniz. Yüzdelik değer 1 ile 100 arasında olmalıdır.";
$l['error_invalid_warning_type'] = "Belirtilen uyarı tipi mevcut değil";
$l['error_missing_type_title'] = "Bu uyarı tipi için bir başlık girmediniz";
$l['error_missing_type_points'] = "Girdiğiniz puan sayısı bu tip için uyarı verilirken kullabilecek geçerli bir numara değil. 0 dan büyük {1} den küçük bir sayı girmelisiniz.";

$l['success_warning_level_created'] = "Uyarı seviyesi başarılı bir şekilde oluşturuldu.";
$l['success_warning_level_updated'] = "Uyarı seviyesi başarılı bir şekilde güncellendi.";
$l['success_warning_level_deleted'] = "Seçilen uyarı seviyesi başarılı bir şekilde silindi.";
$l['success_warning_type_created'] = "Uyarı tipi başarılı bir şekilde oluşturuldu.";
$l['success_warning_type_updated'] = "Uyarı tipi başarılı bir şekilde güncellendi";
$l['success_warning_type_deleted'] = "Belirtilen uyarı tipi başarılı bir şekilde silindi.";

$l['confirm_warning_level_deletion'] = "Bu uyarı seviyesini silmek istediğinizden emin misiniz?";
$l['confirm_warning_type_deletion'] = "Bu uyarı tipini silmek istediğinizden emin misiniz?";

?>
