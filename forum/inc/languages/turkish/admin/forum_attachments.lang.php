<?php
/**
 * MyBB 1.6 Turkish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 *
 * $Id: forum_attachments.lang.php 4941 2010-05-15 18:17:38Z MD $
 */

// Tabs
$l['attachments'] = "Eklentiler";
$l['stats'] = "İstatistikler";
$l['find_attachments'] = "Eklentileri Bul";
$l['find_attachments_desc'] = "Eklenti arama sistemini kullanarak kullanıcılarınızın foruma eklediği dosyaları arayabilirsiniz. önce arama terimlerini girmelisiniz. Tüm alanlar opsiyoneldir ve eğer bir değer içermezse kriterlerde dahil edilmeyecek.";
$l['find_orphans'] = "Sahipsiz Eklentileri Ara";
$l['find_orphans_desc'] = "Sahipsiz eklentiler database veya dosya sisteminde karışmış eklentilerdir.. Bu, eklentileri temizleyebilir veya yönlendirebilirsiniz.";
$l['attachment_stats'] = "Eklenti İstatistikleri";
$l['attachment_stats_desc'] = "Aşağıdakiler şu anda forumunuzda bulunan eklentiler için bazı genel istatistiklerdir.";

// Errors
$l['error_nothing_selected'] = "Lütfen silmek için bir yada daha fazla eklenti seçiniz.";
$l['error_no_attachments'] = "Forumunda henüz hiç eklenti yok. Bir eklenti eklediğinizde bu kısma erişebileceksiniz.";
$l['error_not_all_removed'] = "Sahipsiz eklentiler başarıyla silindi, diğerleriyse yükleme dizininden silinemedi.";
$l['error_invalid_username'] = "Girdiğiniz kullanıcı adı geçersiz.";
$l['error_invalid_forums'] = "Seçtiğiniz forumlardan bir yada birkaçı geçersiz.";
$l['error_no_results'] = "Belirtilen arama kriterlerinde eklenti bulunamadı.";
$l['error_not_found'] = "Eklenti dosyaları uploads klasöründe bulunamadı.";
$l['error_not_attached'] = "Eklenti yükleneli 24 saati geçmiş ancak bir mesaj ile ilişkilendirilmemiş.";
$l['error_does_not_exist'] = "Bu eklenti için konu veya mesaj mevcut değil.";

// Success
$l['success_deleted'] = "Seçilen eklentiler başarılı bir şekilde silindi.";
$l['success_orphan_deleted'] = "Seçilen sahipsiz eklentiler başarılı bir şekilde silindi.";
$l['success_no_orphans'] = "Forumunuzda sahipsiz eklenti bulunmamaktadır.";

// Confirm
$l['confirm_delete'] = "Seçilen eklentileri silmek istediğinizden eminmisiniz?";

// == Pages
// = Stats
$l['general_stats'] = "Genel İstatistikler";
$l['stats_attachment_stats'] = "Eklentiler - Eklenti İstatistikleri";
$l['num_uploaded'] = "<strong>Yüklenen Eklenti Sayısı</strong>";
$l['space_used'] = "<strong>Eklentilerin Kullandığı Alan</strong>";
$l['bandwidth_used'] = "<strong>Tahmin Bant Genişliği Kullanımı</strong>";
$l['average_size'] = "<strong>Ortalama Eklenti Boyutu</strong>";
$l['size_attachments'] = "<span class=\"float_right\">Boyutu</span>Eklenti";
$l['posted_by'] = "Gönderen";
$l['thread'] = "Konu";
$l['downloads'] = "İndirme";
$l['date_uploaded'] = "Yüklenme Tarihi";
$l['popular_attachments'] = "En Popüler 5 Eklenti";
$l['largest_attachments'] = "En Büyük 5 Eklenti";
$l['users_diskspace'] = "En çok Yükleme Yapan 5 Kullanıcı";
$l['username'] = "Kullanıcı Adı";
$l['total_size'] = "Toplam Boyut";

// = Orphans
$l['orphan_results'] = "Sahipsiz Eklenti Araması - Sonuçlar";
$l['orphan_attachments_search'] = "Sahipsiz Eklenti Ara";
$l['reason_orphaned'] = "Sahipsizlik Nedeni";
$l['reason_not_in_table'] = "Eklenti tablosunda değil";
$l['reason_file_missing'] = "Eklenen dosya yok";
$l['reason_thread_deleted'] = "Konu silinmiş";
$l['reason_post_never_made'] = "Mesaj hiç oluşmamış";
$l['unknown'] = "Bilinmiyor";
$l['results'] = "Sonuçlar";
$l['step1'] = "1. Adım";
$l['step2'] = "2. Adım";
$l['step1of2'] = "Adım 2 de 1 - Dosya Sistemi Taraması";
$l['step2of2'] = "Adım 2 de 2 - Veritabanı Taraması";
$l['step1of2_line1'] = "Lütfen bekleyin, şu anda dosya sisteminde sahipsiz eklentiler aranıyor.";
$l['step2of2_line1'] = "Lütfen bekleyin, şu anda veritabanında sahipsiz eklentiler aranıyor.";
$l['step_line2'] = "Bu işlem tamamlandığında otomatik olarak bir sonraki adıma yönlendirileceksiniz.";

// = Attachments / Index
$l['index_find_attachments'] = "Eklentiler - Eklentileri Bul";
$l['find_where'] = "Eklentileri bul...";
$l['name_contains'] = "Dosya adını içerir";
$l['name_contains_desc'] = " Joker kart ile aramak için *.[dosya uzantısı]. girin. örnek: *.zip.";
$l['type_contains'] = "Dosya tipini içerir";
$l['forum_is'] = "Forum";
$l['username_is'] = "Mesajı atanın kullanıcı adı";
$l['more_than'] = "Daha fazla";
$l['greater_than'] = "Daha büyük";
$l['is_exactly'] = "Tam olarak";
$l['less_than'] = "Daha küçük";
$l['date_posted_is'] = "Gönderim tarihi";
$l['days_ago'] = "gün önce";
$l['file_size_is'] = "Dosya boyutu";
$l['kb'] = "KB";
$l['download_count_is'] = "İndirilme Sayısı";
$l['display_options'] = "Gösterim Seçenekleri";
$l['filename'] = "Dosya Adı";
$l['filesize'] = "Dosya Boyutu";
$l['download_count'] = "İndirilme Sayısı";
$l['post_username'] = "Kullanıcı Adı";
$l['asc'] = "Artan";
$l['desc'] = "Azalan";
$l['sort_results_by'] = "Sonuçları sırala";
$l['results_per_page'] = "Sayfa başına sonuçlar";
$l['in'] = "içinde";

// Buttons
$l['button_delete_orphans'] = "Kontrol Edilen Sahipsizleri Sil";
$l['button_delete_attachments'] = "Kontrol Edilen Eklentileri Sil";
$l['button_find_attachments'] = "Eklentileri Bul";

?>