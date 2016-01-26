<?php
/**
 * MyBB 1.6 Turkish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: messages.lang.php 4941 2010-05-15 18:17:38Z MD $
 */
 
$l['click_no_wait'] = "Daha fazla beklemek istemiyorsanız buraya tıklayın.";
$l['redirect_return_forum'] = "<br /><br />Alternatif olarak, <a href=\"{1}\">foruma dön</a>.";
$l['redirect_emailsent'] = "E-mail mesajınız başarılı bir şekilde gönderildi";
$l['redirect_loggedin'] = "Başarılı bir şekilde giriş yaptınız.<br />Şimdi geldiğiniz yere döneceksiniz.";

$l['error_invalidpworusername'] = "Geçersiz kullanıcı adı veya şifre kombinasyonu girdiniz.<br /><br />Eğer şifrenizi unuttu iseniz lütfen<a href=\"member.php?action=lostpw\"> buraya tıklayınız.</a>";
$l['error_incompletefields'] = "Bir veya daha fazla gerekli alanı boş bırakmışsınız. Lütfen geri dönüp gerekli alanları doldurun.";
$l['error_alreadyuploaded'] = "Bu mesaj içindeki dosyayı zaten yüklemiş gözüküyorsunuz (eşit boyut ve dosya adı). Lütfen eklemek için farklı bir dosya seçiniz.";
$l['error_nomessage'] = "Üzgünüz, işleminizi gerçekleştiremiyoruz çünkü uygun bir mesaj girmediniz. Lütfen geri dönünüz ve düzeltiniz.";
$l['error_invalidemail'] = "Uygun bir email adresi girmediniz.";
$l['error_nomember'] = "Belirttiğiniz üye uygun veya mevcut değil.";
$l['error_maxposts'] = "Üzgünüz, Günlük mesaj kotanızı aştınız. Lütfen yarına kadar bekleyiniz yada site yöneticisiyle temasa geçiniz.<br /><br />Bir günde maksimum {1} mesaj gönderebilirsiniz.";
$l['error_nohostname'] = "Girdiğiniz IP adresi için host adı bulunamadı.";
$l['error_invalidthread'] = "Belirtilen konu mevcut değil.";
$l['error_invalidpost'] = "Belirtilen mesaj mevcut değil.";
$l['error_invalidattachment'] = "Belirtilen eklenti mevcut degil.";
$l['error_invalidforum'] = "Uygun olmayan forum";
$l['error_closedinvalidforum'] = "Buraya mesaj gönderemzsiniz ya forum kapalı yada burası kategoridir.";
$l['error_attachtype'] = "Eklenti olarak girmek istediğiniz dosya tipine izin verilmiyor. Lütfen eklentiyi silin veya farklı bir dosya tipi seçin.";
$l['error_attachsize'] = "Eklediğiniz dosyanın boyutu çok büyük. Bu tip dosyanın maksimum boyutu {1} kilobyte.";
$l['error_uploadsize'] = "Yüklemek istediğiniz dosyanın boyutu çok büyük.";
$l['error_uploadfailed'] = "Dosya yükleme işlemi başarısız. Lütfen geçerli bir dosya seçerek tekrar deneyiniz.";
$l['error_uploadfailed_detail'] = "Hata ayrıntıları:";
$l['error_uploadfailed_php1'] = "PHP Geri Döndü: php.ini deki  upload_max_filesize değeri upload etmeye çalıştığınızdan daha düşük. Lütfen bu hatayı forum yöneticilerine bildiriniz.";
$l['error_uploadfailed_php2'] = "Eklemeye çalıştığınız dosya izin verilen boyutu aşmıştır.";
$l['error_uploadfailed_php3'] = "Yüklenmeye çalışılan dosyanın sadece bir kısmı yüklenmistir.";
$l['error_uploadfailed_php4'] = "Hiçbir dosya eklenemedi.";
$l['error_uploadfailed_php6'] = "PHP Geri Döndü: Geçici temporary klasörü eksik. Lütfen bu hatayı site yöneticisine bildiriniz.";
$l['error_uploadfailed_php7'] = "PHP Geri Döndü: Dosyayı diske kaydedemedi. Lütfen bu hatayı site yöneticisine bildiriniz.";
$l['error_uploadfailed_phpx'] = "PHP Hata Kodu: {1}. Lütfen bu hatayı site yöneticisine bildiriniz.";
$l['error_uploadfailed_nothingtomove'] = "Geçersiz bir dosya tanımlandı, eklenmiş dosya hedefe taşınamadı.";
$l['error_uploadfailed_movefailed'] = "Ekli dosyayı hedefe taşırken bir hata oluştu.";
$l['error_uploadfailed_lost'] = "Eklenti serverda bulunamıyor.";
$l['error_emailmismatch'] = "Girmiş olduğunuz email adresleri aynı gözükmüyor. Lütfen geri dönüp tekrar deneyiniz";
$l['error_nopassword'] = "Geçerli bir şifre girmediniz.";
$l['error_usernametaken'] = "Seçtiğiniz kullanıcı adı zaten kayıtlı.";
$l['error_nousername'] = "Bir kullanıcı adı girmediniz.";
$l['error_invalidusername'] = "Girdiğiniz kullanıcı adı geçersiz.";
$l['error_invalidpassword'] = "Girdiğiniz şifre hatalı. Eğer şifrenizi unuttu iseniz, <a href=\"member.php?action=lostpw\">buraya tıklayınız</a>. Tekrar denemek için tarayıcınızda geri gidin.";
$l['error_postflooding'] = "Üzgünüz fakat mesajınızı işleme alamayacağız. Yöneticiler her {1} saniyede sadece 1 mesaj gönderebilirsiniz.";
$l['error_nopermission_guest_1'] = "Giriş yapmadınız veya bu sayfayı görme yetkiniz yok. Bunun sebebi aşağıdakilerden biri olabilir:";
$l['error_nopermission_guest_2'] = "Giriş yapmadınız veya kayıtlı değilsiniz. Lütfen girmek için bu sayfanın altındaki formu kullanın.";
$l['error_nopermission_guest_3'] = "Bu sayfayı görme yetkiniz yok. Yönetici sayfalarına yada bir yönetici kaynağına mı girmeyi deniyorsunuz? Forum kurallarından bu işlemi yapma yetkiniz olup olmadığını kontrol ediniz.";
$l['error_nopermission_guest_4'] = "Hesabınız bir yönetici tarafından iptal edilmiş veya hala aktivasyon bekliyor olabilir.";
$l['error_nopermission_guest_5'] = "Erişmeye çalıştığınız bu sayfayı görme yetkiniz yok. Uygun bağlantıları veya linkleri kullanın.";
$l['login'] = "Oturum Aç";
$l['need_reg'] = "Kayıt Gerekli?";
$l['forgot_password'] = "Şifremi Unuttum?";
$l['error_nopermission_user_1'] = "Bu sayfayı görme yetkiniz yok. Bunun sebebi aşağıdakilerden biri olabilir:";
$l['error_nopermission_user_ajax'] = "Bu sayfaya giriş izniniz yok.";
$l['error_nopermission_user_2'] = "Hesabınız askıya alındı veya bu kaynağa giriş izniniz yasaklandı.";
$l['error_nopermission_user_3'] = "Bu sayfayı görme yetkiniz yok. Yönetici sayfalarına yada bir yönetici kaynağına mı girmeyi deniyorsunuz? Forum kurallarından bu işlemi yapma yetkiniz olup olmadığını kontrol ediniz.";
$l['error_nopermission_user_4'] = "Hesabınız hala aktivasyon bekliyor olabilir.";
$l['error_nopermission_user_5'] = "Erişmeye çalıştığınız bu sayfayı görme yetkiniz yok. Uygun bağlantıları veya linkleri kullanın.";
$l['error_nopermission_user_resendactivation'] = "Aktivasyon Kodunu Tekrar Gönder";
$l['error_nopermission_user_username'] = "Giriş Yapmış Olduğunuz Kullanıcı Adı: '{1}'";
$l['logged_in_user'] = "Giriş Yapmış Kullanıcı";
$l['error_too_many_images'] = "Çok Fazla Resim.";
$l['error_too_many_images2'] = "Üzgünüz, mesajınızı işleme alamıyoruz çünkü çok fazla resim içeriyor. Lütfen devam etmek için mesajınızdan bazı resimleri siliniz.";
$l['error_too_many_images3'] = "<b>Not:</b> Mesaj başına izin verilen maksimum resim sayısı";
$l['error_attach_file'] = "Dosya Ekleme Hatası";
$l['please_correct_errors'] = "Lütfen devam etmeden önce aşağıdaki hataları düzeltiniz:";
$l['error_reachedattachquota'] = "Üzgünüz fakat bu dosyayı ekleyemezsiniz çünkü {1} in eklenti kotanızı aştınız";
$l['error_invaliduser'] = "Belirtilen kullanıcı geçersiz veya mevcut değil.";
$l['error_invalidaction'] = "Geçersiz İşlem";
$l['error_messagelength'] = "Üzgünüz, mesajınızın çok uzun olmasından dolayı gönderilemiyor. Lütfen mesajınızı kısaltıp tekrar deneyiniz.";
$l['error_message_too_short'] = "Üzgünüz, mesajınızın çok kısa olmasından dolayı gönderilemiyor.";
$l['failed_login_wait'] = "Size tanınan giriş deneme hakkınızı aştınız.Yeniden denemek için {1} Saat {2} Dakika {3} Saniye beklemek zorundasınız.";
$l['failed_login_again'] = "<br/><strong>{1}</strong> giriş yapma hakkınız kalmış.";
$l['error_max_emails_day'] = "Son 24 saat içinde size ayrılmış {1} mesaj gönderme kotanızı çoktan doldurduğunuz için, 'Konuyu Arkadaşa' ya da 'Email Kullanıcısı' özelliklerini kullanamazsınız.";

$l['emailsubject_lostpw'] = "Şifreyi Sıfırla {1}";
$l['emailsubject_passwordreset'] = "Yeni Şifre {1}";
$l['emailsubject_subscription'] = "Konuya Yeni Mesaj: {1}";
$l['emailsubject_randompassword'] = "{1} için şifreniz";
$l['emailsubject_activateaccount'] = "{1} Sitesi Hesap Aktivasyonu";
$l['emailsubject_forumsubscription'] = "{1} içinde Yeni Konu";
$l['emailsubject_reportpost'] = "{1} de Rapor Edilmiş Mesaj";
$l['emailsubject_reachedpmquota'] = "Özel Mesaj Kotanız Şuan {1} olmuştur";
$l['emailsubject_changeemail'] = "{1} Email Değişikliği";
$l['emailsubject_newpm'] = "{1} Size Yeni Özel Mesaj Var";
$l['emailsubject_sendtofriend'] = "{1} Sayfasıyla ilgileniliyor";
$l['emailbit_viewthread'] = "... (daha fazlası için konuyu ziyaret edin..)";

$l['email_lostpw'] = "{1},

{2} deki Hesap şifresi sıfırlama işlemini tamamlamak için, aşağıdaki linki tıklamanız gerekmektedir.

{3}/member.php?action=resetpassword&uid={4}&code={5}

Eğer yukarıdaki link çalışmıyorsa, alttaki linki deneyiniz

{3}/member.php?action=resetpassword

Aşağıdakileri girmeniz gerekecektir:
Kullanıcı Adı: {1}
Aktivasyon Kodu: {5}

Teşekkürler,
{2} Yonetimi";


$l['email_reportpost'] = "{1}den {2} bu mesajı rapor etti:

{3}
{4}/{5}

Bu konuyu rapor eden kullanıcının sebebi:
{7}

Bu mesaj forumdaki tum moderatörlere gonderildi, eğer moderatör mevcut değilse, tum yoneticiler ve süper moderatörlere gonderildi.

Lütfen bu mesajı mumkün olduğu kadar kısa bir sürede kontrol ediniz.";

$l['email_passwordreset'] = "{1},

{2} deki şifreniz sıfırlanmıştır.

Yeni şifreniz: {3}

Foruma giriş yapmak için bu şifreye ihtiyacınız var, birkez girdikten sonra Kullanıcı KP ile şifrenizi değiştirebilirsiniz.

Teşekkürler,
{2} Yonetimi";

$l['email_randompassword'] = "{1},

{2} Sitemize üye oldugunuz için Teşekkür ederiz. Aşağıdakiler sizin kullanıcı adınız ve rastgele seçilmiş şifrenizdir. {2} sitesine giriş yapabilmek için, bu bilgilere ihtiyacınız olacaktır.

Kullanıcı Adı: {3}
Şifre: {4}

Giriş yaptıktan sonra hemen şifrenizi değiştirmeniz gereklidir. Bunu Kullanıcı KP de sol menüden Şifre Değiştir'e tıklayarak yapabilirsiniz.

Teşekkürler,
{2} Yonetimi";

$l['email_sendtofriend'] = "Merhaba,

{2} sitesindeki {1} konusunun ilgini çekeceğini duşünüyorum,

{3}

{1} aşağıdaki mesajı içeriyor:
------------------------------------------
{4}
------------------------------------------

Teşekkürler,
{2} Yönetimi
";

$l['email_forumsubscription'] = "{1},

{2}, {3} içinde yeni bir konu başlattı. Bu sizin {4} te abone olduğunuz bir forum.

Konu Başlığı {5}

Mesajın Bir Bölümü Aşağıdadır:
--
{6}
--

Hepsini okumak için lütfen aşagıdaki linki ziyaret ediniz:
{7}/{8}

Ayrıca diğer yeni konular ve cevaplar mevcut olabilir, fakat forumu tekrar ziyaret edinceye kadar herhangi bir uyarı mesaji almayacaksınız.

Teşekkürler,
{4} Yonetimi

------------------------------------------
Abonelikten çıkma Bilgisi:

Bu forumdan artık daha fazla yeni konu bildirisi almak istemiyorsanız, Aşağıdaki linki ziyaret ediniz:
{7}/usercp2.php?action=removesubscription&type=forum&fid={9}&my_post_key={10}

------------------------------------------";

$l['email_activateaccount'] = "{1},

{2} sitesindeki kayıt işlemini tamamlamak icin, aşağıdaki linki kullanmalısınız.

{3}/member.php?action=activate&uid={4}&code={5}

Eğer yukarıdaki link çalışmazsa lütfen aşağıdakini kullanınız

{3}/member.php?action=activate

Aşağıdakileri girmeniz gereklidir:
Kullanıcı Adı: {1}
Aktivasyon Kodu: {5}

Teşekkürler,
{2} Yonetimi";

$l['email_subscription'] = "{1},

{2} kullanıcısı sizin kayıtlı olduğunuz {3} deki bir konuya cevap verdi. Bu konunun başlığı {4}.

Mesajın Bir Bölümü Aşağıdadır:
--------------------------------
{5}
--------------------------------

Konuyu görmek için, aşağıdaki linki ziyaret ediniz:
{6}/{7}

Ayrıca yeni cevaplar mevcut olabilir, fakat forumu tekrar ziyaret edinceye kadar herhangi bir uyarı mesajı almayacaksınız.

Teşekkürler,
{3} Yonetimi

------------------------------------------
Abonelikten çıkma Bilgisi:

Bu forum içindeki yeni konular hakkında bilgi almak istemiyorsanız, tarayıcınızdan aşağıdaki linki giriniz:
{6}/usercp2.php?action=removesubscription&tid={8}&key={9}&my_post_key={10}

------------------------------------------";
$l['email_reachedpmquota'] = "{1},

Bu {2} den gelen özel Mesaj kotanızın dolduğunu bildirmeye yarayan, otomatik bir maildir.

Bir veya daha fazla kullanıcı size ÖM gondermeyi denedi bu yuzden başarılı olamadı.

Lütfen sakladığınız bazı özel mesajlarınızı siliniz, unutmayın bu mesajları ayrıca 'Çöp Kutusu' klasöründende silmelisiniz.

Teşekkürler,
{2} Yonetimi
{3}";
$l['email_changeemail'] = "{1},

{2} den email adresinizi değiştirmeniz için bir taleb aldınız (lütfen aşagıdaki detaylara bakınız).

Eski email adresi: {3}
Yeni email adresi: {4}

Eğer bu değişiklikler doğru ise, {2} daki onaylamayı tamamlayınız bunun içinde aşağıdaki linki kullanınız.

{5}/member.php?action=activate&uid={8}&code={6}

Eğer yukarıdaki link çalışmazsa lütfen aşağıdakini kullanınız

{5}/member.php?action=activate

Aşağıdakileri girmeniz gerekecektir:
Kullanıcı Adı: {7}
Aktivasyon Kodu: {6}

Eğer email adresinizi onaylamazsanız profil alanında kayıtlı olan email adresiniz değişmeden eskisi gibi kalacaktır.

Teşekkürler,
{2} Yonetimi
{5}";

$l['email_newpm'] = "{1},

{3} sitesinde yeni özel Mesajınız var Gönderen {2}. Bu mesaja bakmak için linki tıklayınız:

{4}/private.php

{3} burayı ziyaret edene kadar başka yeni mesaj bildirimi alamayacaksınız.

Hesap seçenekleri sayfasından yeni mesaj bildirimini kapatabilirsiniz:

{4}/usercp.php?action=options

Teşekkürler,
{3} Yonetimi
{4}";

$l['email_emailuser'] = "{1},

{3} sitesinden aşağıdaki mesajı aldınız. Gönderen: {2}.
------------------------------------------
{5}
------------------------------------------

Teşekkürler,
{3} Yonetimi
{4}

------------------------------------------
Diğer kullanıcılardan email almak istemiyor musunuz?

{4}/usercp.php?action=options 
Adresinden 'Diğer Kullanıcılardan Email Adresimi Gizle' seçeneğine tıklayabilirsiniz.

------------------------------------------";
?>