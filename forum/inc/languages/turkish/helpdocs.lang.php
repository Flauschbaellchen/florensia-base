<?php
/**
 * MyBB 1.6 Turkish Language Pack
 * Copyright 2010 MyBB Group, All Rights Reserved
 * 
 * $Id: helpdocs.lang.php 4941 2010-05-15 18:17:38Z MD $
 */

// Help Document 1
$l['d1_name'] = "Kullanıcı Kaydı";
$l['d1_desc'] = "Kullanıcı Kaydının Ayrıcalık ve Avantajları.";
$l['d1_document'] = "Bu forumun bazı bölümlerine girebilmeniz için üye olup giriş yapmanız gerekmektedir. Kayıt olmak ücretsizdir ve bu işlem 10-15 saniye kadar sürer. 
<br /><br />Üye olduktan sonra çesitli seçenekleri kullanabilirsiniz. ( konulara abone olmak vb. )";

// Help Document 2
$l['d2_name'] = "Profil Güncelleme";
$l['d2_desc'] = "Şu anda kayıtta olan verileri değiştirme";
$l['d2_document'] = "Bazen hakkınızdaki bazı bilgileri güncellemeye karar verebilirsiniz. Bunlar şifreniz, msn adresiniz veya email adresiniz olabilir. Bu değişiklikleri yapmak için kullanıcı kontrol panelini kullanmalısınız. Kullanıcı panelinde sol tarafta bulunan menüden değiştirmek istediğiniz bölümü seçip sağ taraftan gerekli değişiklikleri yapabilirsiniz.";

// Help Document 3
$l['d3_name'] = "MyBBde çerez Kullanımı";
$l['d3_desc'] = "MyBB kaydınız hakkındaki bazı bilgileri depolamak için çerezleri kullanır.";
$l['d3_document'] = "MyBulletinBoard sistemi, eğer kayıtlı iseniz giriş bilgilerinizi son giriş tarihinizi depolamak için çerezleri kullanır. 
<br /><br />çerezler bilgisayarınızda depolanan küçük yazı dökümanlarıdır. Bu forum hiç bir güvenlik sakıncası oluşturmadan bu çerezleri kullanır. 
<br /><br />Bu forumdaki çerezler hangi mesajları okuduğunuzu ve bu mesajları ne zaman okuduğunuzu takip eder. 
<br /><br />Bu forumdan gelen çerezleri temizlemek için <a href=\"misc.php?action=clearcookies\">buraya</a> tıklayabilirsiniz.";

// Help Document 4
$l['d4_name'] = "Oturum Açmak / Kapamak ";
$l['d4_desc'] = "Oturum nasıl açılır ve kapatılır?";
$l['d4_document'] = "Oturum açtığınız zaman bilgisayarınızda çerez ayarlanır ve her seferinde kullanıcı adı ve şifre yazmadan forumda oturumunuz açılmış olur. Oturum kapattığınız takdirde tüm çerezler silinir.";

// Help Document 5
$l['d5_name'] = "Yeni Bir Konu Gönderimi";
$l['d5_desc'] = "Bir forumda yeni bir konu başlatmak.";
$l['d5_document'] = "İlgilendiğiniz foruma girdikten sonra eğer orada bir konu olusturmak isterseniz, forumda altta ve üstte bulunan \"Yeni Konu\" tuşuna basınız. Bu forumda konu açmaya izniniz olmayabilir. çünkü forum yöneticiler tarafından kapatılmış veya sizin bu bölüme konu açma izniniz verilmemiş olabilir.";

// Help Document 6
$l['d6_name'] = "Cevap Gönderimi";
$l['d6_desc'] = "Bir başlığa cevap vermek.";
$l['d6_document'] = "Ziyaretiniz sırasında bir konuya cevap vermek isteyebilirsiniz. Bunun için konunun üstünde ve altında bulunan \"Cevapla\" tuşuna basınız. Bazı konular veya forumlarda cevap yazma izniniz yönetim tarafından kapatılmış olabilir. 
<br /><br />Ayrıca, forum bir moderatör tarafından kapatılmışsa o konuya kullanıcılar cevap yazamaz. Bu bölümün kullanıcılar tarafından açılması mümkün olmayıp ancak bir yönetici tarafından açilabilir.";

// Help Document 7
$l['d7_name'] = "MyKod";
$l['d7_desc'] = "Mesajlarınızda nasıl MyKod kullanacağınızı öğrenin.";
$l['d7_document'] = "HTMLnin basitleştirilmiş bir versiyonu olan myKodları mesajlarınıza değişik efektler vermek için kullanabilirsiniz.. 
<p><br />[b]Bu kalın yazı[/b]<br />&nbsp;&nbsp;&nbsp;<b>Bu kalın yazı</b> 
<p>[i]Bu italik yazı[/i]<br />&nbsp;&nbsp;&nbsp;<i>Bu italik yazı</i> 
<p>[u]Bu altı çizili yazı[/u]<br />&nbsp;&nbsp;&nbsp;<u>Bu altı çizili yazı</u> 
<p>[s]Bu çizilmiş yazı[/s]<br />&nbsp;&nbsp;&nbsp;<strike>Bu çizilmiş yazı</strike> 
<p><br />[url]http://www.mybbdestek.com/[/url]<br />&nbsp;&nbsp;&nbsp;<a href=\"http://www.mybbdestek.com/\">http://www.mybbdestek.com/</a> 
<p>[url=http://www.mybbdestek.com/]MyBBDestek.com[/url]<br />&nbsp;&nbsp;&nbsp;<a href=\"http://www.mybbdestek.com/\">MyBBDestek.com</a> 
<p>[email]example@example.com[/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:example@example.com\">example@example.com</a> 
<p>[email=example@example.com]Bana E-mail Gönder![/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:example@example.com\">Bana E-mail Gönder!</a> 
<p>[email=example@example.com?subject=spam]Konusuyla E-mail[/email]<br />&nbsp;&nbsp;&nbsp;<a href=\"mailto:example@example.com?subject=spam\">Konusuyla E-mail</a> 
<p><br />[quote]Buradaki alıntı yazıdır[/quote]<br />&nbsp;&nbsp;&nbsp;<quote>Buradaki alıntı yazıdır</quote> 
<p>[code]Kodlar burada[/code]<br />&nbsp;&nbsp;&nbsp;<code>Kodlar burada</code> 
<p><br />[img]http://www.php.net/images/php.gif[/img]<br />&nbsp;&nbsp;&nbsp;<img src=\"http://www.php.net/images/php.gif\"> 
<p>[img=50x50]http://www.php.net/images/php.gif[/img]<br />&nbsp;&nbsp;&nbsp;<img src=\"http://www.php.net/images/php.gif\" width=\"50\" height=\"50\"> 
<p><br />[color=red]Yazı kırmızı[/color]<br />&nbsp;&nbsp;&nbsp;<font color=\"red\">Yazı kırmızı</font> 
<p>[size=3]Yazı boyutu 3[/size]<br />&nbsp;&nbsp;&nbsp;<font size=\"3\">Yazı boyutu 3</font> 
<p>[font=Tahoma]Yazı tipi Tahoma[/font]<br />&nbsp;&nbsp;&nbsp;<font face=\"Tahoma\">Yazı tipi Tahoma</font> 
<p><br />[align=center]Ortalanmış[/align]<div align=\"center\">Ortalanmış</div> 
<p>[align=right]Sağa dayalı yazı[/align]<div align=\"right\">Sağa dayalı yazı</div> 
<p><br />[list]<br />[*]List Item #1<br />[*]List Item #2<br />[*]List Item #3<br />[/list]<br /><ul><li>List item #1</li><li>List item #2</li><li>List Item #3</li> 
</ul> 
<p><font size=1>Numaralı liste için [list=1] ,  alfabetik liste için [list=a] kodlarını kullanabilirsiniz .</p>";
?>