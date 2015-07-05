<?php
   session_start();
   unset($_SESSION['captcha_spam']);
   $text = rand(1000,10000); //Zufallszahl

   function encrypt($string, $key) {
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
   }

   $_SESSION['captcha_spam'] = encrypt($text, "8h384ls94"); //Key
   $_SESSION['captcha_spam'] = str_replace("=", "", $_SESSION['captcha_spam']);

   $img = ImageCreateFromPNG('./captcha.PNG'); //Backgroundimage
   $color = ImageColorAllocate($img, 0, 0, 0); //Farbe
   $ttf = "./arial.ttf"; //Schriftart
   $ttfsize = 25; //Schriftgrösse
   $angle = rand(0,5);
   $t_x = rand(5,50);
   $t_y = 35;
   imagettftext($img, $ttfsize, $angle, $t_x, $t_y, $color, $ttf, $text);

   header('Content-type: image/png');
   imagepng($img);
   imagedestroy($img);
?>
