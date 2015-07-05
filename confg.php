<?PHP

$cfg['dbhost'] = 'localhost';
$cfg['dbname'] = 'florensia';
$cfg['dbuser'] = 'xxx';
$cfg['dbpasswd'] = 'xxx';

$cfg['root'] = "http://localhost/florensia";
$cfg['root_abs'] = "/var/www/florensia";
$cfg['downloads_abs'] = "/var/www/florensia";

$cfg['layer_abs'] = $cfg['root_abs']."/layer";
$cfg['layer_rel'] = $cfg['root']."/layer";

$cfg['pictures_abs'] = $cfg['root_abs']."/pictures";
$cfg['pictures_rel'] = $cfg['root']."/pictures";

$cfg['images_abs'] = $cfg['root_abs']."/images";
$cfg['images_rel'] = $cfg['root']."/images";

$cfg['language_abs'] = $cfg['root_abs']."/language";
$cfg['language_rel'] = $cfg['root']."/language";

$cfg['fonts_abs'] = $cfg['root_abs']."/fonts";

$cfg['signatureurl'] = "http://localhost/florensia/signature.php?sig=";
$cfg['signature_abs'] = $cfg['root_abs']."/signature";
$cfg['signature_rel'] = $cfg['root']."/signature";


$cfg['googleanalytics'] = false;
$cfg['googleadsense'] = false;

//last color is gray for all others
$cfg['maptoolcolors'] = array(
"255,255,0",
"2128,128,0",
"192,192,0",
"123,0,0",
"255,0,0",
"41,41,0",
"0,64,0",
"0,123,0",
"0,255,0",
"0,123,123",
"0,255,223",
"0,12,149",
"0,0,255",
"72,90,255",
"103,33,173",
"138,0,131",
"255,0,242",
"89,68,79",
"192,88,0",
"255,168,88",
"255,255,255",
"176,176,176");

$cfg['forumurl'] = "http://forum.florensia-base.com";
$cfg['forum'] = $cfg['root_abs']."/forum";
$cfg['cache'] = false;

?>
