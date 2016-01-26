<?php
/**
 * MyBB 1.6 Turkish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 *
 * $Id: datahandler_event.lang.php 4941 2010-05-15 18:17:38Z MD $
 */

$l['eventdata_missing_name'] = "Bu olay için isim eksik. Lütfen bir olay ismi girin.";
$l['eventdata_missing_description'] = "Bu olay için tanım eksik. Lütfen olay için bir tanım girin.";

$l['eventdata_invalid_start_date'] = "Girmiş olduğunuz olay başlangıç tarihi geçersiz. Belirttiğiniz gün, ay ve yılın geçerli olduğundan, ayrıca girdiğiniz günün geçerli bir aya ait olduğundan emin olmanız gerekmektedir.";
$l['eventdata_invalid_start_year'] = "Olaylar ancak gelecek 5 yıl içerisinde oluşturulabilir. Lütfen listeden uygun bir başlangıç yılı seçin.";
$l['eventdata_invalid_start_month'] = "Seçtiğiniz başlangıç ayı geçerli bir ay değildir. Lütfen geçerli bir başlangıç ayı giriniz.";

$l['eventdata_invalid_end_date'] = "Girmiş olduğunuz olay bitiş tarihi geçersiz. Belirttiğiniz gün, ay ve yılın geçerli olduğundan, ayrıca girdiğiniz günün geçerli bir aya ait olduğundan emin olmanız gerekmektedir.";
$l['eventdata_invalid_end_year'] = "Olaylar ancak gelecek 5 yıl içerisinde oluşturulabilir. Lütfen listeden uygun bir başlangıç yılı seçin.";
$l['eventdata_invalid_end_month'] = "Seçtiğiniz bitiş ayı geçerli bir ay değildir. Lütfen geçerli bir bitiş ayı giriniz.";
$l['eventdata_invalid_end_day'] = "Seçtiğiniz bitiş ayı geçerli bir ay değildir. Seçtiğiniz gün muhtemelen bu ayın içerdiği günlerden daha büyük.";

$l['eventdata_cant_specify_one_time'] = "Eğer bir olay başlangıç zamanı belirtiyorsanız, bir olay bitiş zamanı da girmeniz gerekebilir.";
$l['eventdata_start_time_invalid'] = "Girdiğiniz baslangıç zamanı geçersiz. Geçerli örnekler: 12am, 12:01am, 00:01.";
$l['eventdata_end_time_invalid'] = "Girdiğiniz bitiş zamanı geçersiz. Geçerli örnekler: 12am, 12:01am, 00:01.";
$l['eventdata_invalid_timezone'] = "Bu olay için girdiğiniz zaman dilimi geçersiz.";
$l['eventdata_end_in_past'] = "Olayınızın bitiş zamanı ya da tarihi, olayın başlangıç zamanı ya da tarihinden daha önce.";

$l['eventdata_only_ranged_events_repeat'] = "Sadece sınıflandırılmış olaylar (başlangıç ve bitiş tarihi olanlar) tekrarlanabilir.";
$l['eventdata_invalid_repeat_day_interval'] = "Girdiğiniz tekrar edilecek gün aralığı geçersiz.";
$l['eventdata_invalid_repeat_week_interval'] = "Girdiğiniz tekrar edilecek hafta aralığı geçersiz.";
$l['eventdata_invalid_repeat_weekly_days'] = "Bu olayın gerçekleşebilmesi için hiçbir iş günü seçmediniz.";
$l['eventdata_invalid_repeat_month_interval'] = "Girdiğiniz tekrar edilecek ay aralığı geçersiz.";
$l['eventdata_invalid_repeat_year_interval'] = "Girdiğiniz tekrar edilecek yıl aralığı geçersiz.";
$l['eventdata_event_wont_occur'] = "Olay tekrarlama ayarları ile başlangıç ve bitiş zamanlarını kullanarak, bu olay gerçekleşmeyecek.";

$l['eventdata_no_permission_private_event'] = "Özel olayları mesaj olarak göndermeye izniniz yok.";
?>