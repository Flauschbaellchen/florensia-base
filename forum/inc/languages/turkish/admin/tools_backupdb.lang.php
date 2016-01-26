<?php
/**
 * MyBB 1.6 Turkish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: tools_backupdb.lang.php 4941 2010-05-15 18:17:38Z MD $
 */


$l['database_backups'] = "Veritabanı Yedekleri";
$l['database_backups_desc'] = "Buradan web sunucunuzdaki MyBB yedekleri klasöründeki mevcut depolanmış yedeklerin bir listesini bulabilirsiniz.";
$l['new_database_backup'] = "Yeni Veritabanı Yedeği";
$l['new_backup'] = "Yeni Yedek";
$l['new_backup_desc'] = "Buradan veritabanınızın yeni bir yedeğini oluşturabilirsiniz";
$l['backups'] = "Yedekler";
$l['existing_database_backups'] = "Mevcut Veritabanı Yedekleri";

$l['backup_saved_to'] = "Yedek kaydedildi:";
$l['download'] = "İndir";
$l['table_selection'] = "Tablo Seçimi";
$l['backup_options'] = "Yedek Seçenekleri";
$l['table_select_desc'] = "Hangi tabloların yedeğini alacağınızı buradan belirleyebilirsiniz. CTRL ye basılı tutarak tabloları çoklu olarak seçebilirsiniz.";
$l['select_all'] = "Hepsini Seç";
$l['deselect_all'] = "Hepsinin Seçimini Kaldır";
$l['select_forum_tables'] = "Forum Tablolarını Seç";
$l['file_type'] = "Dosya Tipi";
$l['file_type_desc'] = "Veritabanı yedeği olarak kaydedilmesini istediğiniz dosya tipini seçiniz.";
$l['gzip_compressed'] = "GZIP Sıkıştırması";
$l['plain_text'] = "Düz Yazı";
$l['save_method'] = "Kayıt Metodu";
$l['save_method_desc'] = "Yedek olarak kaydedilmesini istediğiniz metodu seçiniz.";
$l['backup_directory'] = "Yedek Klasörü";
$l['backup_contents'] = "Yedek İçeriği";
$l['backup_contents_desc'] = "Yedeğin içermesini istediğiniz bilgiyi seçiniz.";
$l['structure_and_data'] = "Yapı ve Veri";
$l['structure_only'] = "Sadece Yapı";
$l['data_only'] = "Sadece Veri";
$l['analyze_and_optimize'] = "Seçilen Tabloları Analiz ve Optimize Et";
$l['analyze_and_optimize_desc'] = "Seçilen tabloların yedek alınırken analiz ve optimize edilmesini ister misiniz?";
$l['perform_backup'] = "Yedeği Oluştur";
$l['backup_filename'] = "Yedek Dosya Adı";
$l['file_size'] = "Dosya Boyutu";
$l['creation_date'] = "Oluşturulma Tarihi";
$l['no_backups'] = "Şu anda henüz oluşturulmuş bir yedek bulunmamaktadır.";

$l['error_file_not_specified'] = "İndirmek için herhangibir veritabanı yedeği belirtmediniz";
$l['error_invalid_backup'] = "Seçtiğiniz yedek dosyası ya geçersiz ya da mevcut değil.";
$l['error_backup_doesnt_exist'] = "Belirtilen yedek mevcut değil";
$l['error_backup_not_deleted'] = "Yedek silinmedi.";
$l['error_tables_not_selected'] = "Yedeği alınması için herhangibir tablo seçmediniz.";
$l['error_no_zlib'] = "PHP için zlib kütüphanesi uygun değil - GZIP sıkıştırılmış yedekler oluşturamazsınız.";

$l['alert_not_writable'] = "Yedek klasörünüz (Admin KP klasörü içindeki) yazılabilir değil. Sunucuda yedek kaydedemezsiniz.";

$l['confirm_backup_deletion'] = "Bu yedeği silmek istediğinizden emin misiniz?";

$l['success_backup_deleted'] = "Yedek başarılı bir şekilde silindi.";
$l['success_backup_created'] = "Yedek başarılı bir şekilde oluşturuldu.";

?>