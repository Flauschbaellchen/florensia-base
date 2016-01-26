<?php
/**
 * MyBB 1.6 Turkish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: tools_tasks.lang.php 4941 2010-05-15 18:17:38Z MD $
 */

$l['task_manager'] = "Görev Yöneticisi";
$l['add_new_task'] = "Yeni Görev Ekle";
$l['add_new_task_desc'] = "Buradan otomatik olarak forumunuzda çalıştırılacak planlanmış görevler oluşturabilirsiniz.";
$l['edit_task'] = "Görev Düzenle";
$l['edit_task_desc'] = "Aşağıdan planlanmış görevlerin çeşitli ayarlarını düzenleyebilirsiniz.";
$l['task_logs'] = "Görev Kayıtları";
$l['view_task_logs'] = "Görev Kayıtlarını İncele";
$l['view_task_logs_desc'] = "Bir görev çalıştırıldığında ve kayıt etme etkin ise, tüm sonuçlar ve hatalar aşağıda listelenecektir. 30 günden eski olan girişler otomatik olarak silinir.";
$l['scheduled_tasks'] = "Planlanmış Görevler";
$l['scheduled_tasks_desc'] = "Buradan forumunuzda otomatik olarak çalıştırılacak olan görevleri yönetebilirsiniz. şimdi bir görevi çalıştırmak için o görevin sağındaki ikona tıklayın.";

$l['title'] = "Başlık";
$l['short_description'] = "Kısa Açıklama";
$l['task_file'] = "Görev Dosyası";
$l['task_file_desc'] = "Görevi aktif etmek için bir görev dosyası seçin.";
$l['time_minutes'] = "Zaman: Dakika";
$l['time_minutes_desc'] = "Görevi çalıştırmak için dakikaları (0-59) virgül ile ayırın. '*' simgesini kullanırsanız her dakika aktif olacaktır.";
$l['time_hours'] = "Zaman: Saat";
$l['time_hours_desc'] = "Görevi çalıştırmak için saatleri (0-23) virgül ile ayırın. '*' simgesini kullanırsanız her saat aktif olacaktır.";
$l['time_days_of_month'] = "Zaman: Gün";
$l['time_days_of_month_desc'] = "Görevi çalıştırmak için günleri (1-31) virgül ile ayırın. '*' simgesini kullanırsanız her gün aktif olacaktır.";
$l['every_weekday'] = "Hafta içi Hergün";
$l['sunday'] = "Pazar";
$l['monday'] = "Pazartesi";
$l['tuesday'] = "Salı";
$l['wednesday'] = "Çarşamba";
$l['thursday'] = "Perşembe";
$l['friday'] = "Cuma";
$l['saturday'] = "Cumartesi";
$l['time_weekdays'] = "Zaman: Hafta İçi";
$l['time_weekdays_desc'] = "Bu görevin çalıştırılacağı haftanın günlerini seçin. çoklu seçim için CTRL tusuna basılı tutun. Eğer bu görevi tüm hafta günleri için çalıştırmak istiyorsanız ya da aşağıda önceden tanımlı gün girdiyseniz, 'Tüm hafta günleri' ni seçiniz.";
$l['every_month'] = "Her Ay";
$l['time_months'] = "Zaman: Ay";
$l['time_months_desc'] = "Bu görevin çalıştırılacağı ayları girin. çoklu seçim için CTRL tuşuna basılı tutun. Eğer her ay için bu görevi çalıştırmak istiyorsanız, 'Her ay' ı seçin.";
$l['enabled'] = "Görevler Seçilsin mi?";
$l['enable_logging'] = "Kayıt Tutma Etkin mi?";
$l['save_task'] = "Kaydet";
$l['task'] = "Görev";
$l['date'] = "Tarih";
$l['data'] = "Veri";
$l['no_task_logs'] = "Şu anda hiçbir planlanmış görev için kayıt girdisi bulunmamaktadır.";
$l['next_run'] = "Bir Sonraki çalıştırma";
$l['run_task_now'] = "Görevi şimdi çalıştır";
$l['run_task'] = "Görevi çalıştır";
$l['disable_task'] = "Görevi Etkisizleştir";
$l['enable_task'] = "Görevi Etkinleştir";
$l['delete_task'] = "Görevi Sil";
$l['alt_enabled'] = "Etkin";
$l['alt_disabled'] = "Etkisiz";

$l['error_invalid_task'] = "Belirtilen görev mevcut değil.";
$l['error_missing_title'] = "Planlanmış görev için bir başlık girmediniz.";
$l['error_missing_description'] = "Planlanmış görev için bir açıklama girmediniz.";
$l['error_invalid_task_file'] = "Seçtiğiniz görev dosyası mevcut değil.";
$l['error_invalid_minute'] = "Seçtiğiniz dakika geçersiz.";
$l['error_invalid_hour'] = "Seçtiğiniz saat geçersiz.";
$l['error_invalid_day'] = "Seçtiğiniz gün geçersiz.";
$l['error_invalid_weekday'] = "Seçtiğiniz hafta geçersiz.";
$l['error_invalid_month'] = "Seçtiğiniz ay geçersiz.";

$l['success_task_created'] = "Görev başarılı bir şekilde oluşturuldu.";
$l['success_task_updated'] = "Seçilen görev başarılı bir şekilde güncellendi.";
$l['success_task_deleted'] = "Seçilen görev başarılı bir şekilde silindi.";
$l['success_task_enabled'] = "Seçilen görev başarılı bir şekilde etkinleştirildi.";
$l['success_task_disabled'] = "Seçilen görev başarılı bir şekilde etkisizleştirildi.";
$l['success_task_run'] = "Seçilen görev başarılı bir şekilde çalıştırıldı.";

$l['confirm_task_deletion'] = "Bu planlanmış görevi silmek istediğinizden emin misiniz?";
$l['confirm_task_enable'] = "<strong>UYARI:</strong> Bir UNIX komutu yoluyla çalıştırılacak görevi etkinleştirmektesiniz. (Daha fazla bilgi için lütfen <a href=\"http://wiki.mybboard.net/\" target=\"_blank\">MyBB Wiki</a> adresini ziyaret edin). Devam et?";

?>