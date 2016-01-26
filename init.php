<?PHP

//--Verschiedene Sicherheitsluecken sichern
        //HTTP header injection
        $_SERVER['PHP_SELF'] = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8', FALSE);

	$_SERVER['HTTP_USER_AGENT'] = htmlentities(strip_tags($_SERVER['HTTP_USER_AGENT']), ENT_QUOTES, 'UTF-8', FALSE);
        $_SERVER['SERVER_NAME'] = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8', FALSE);

//redirect, language
$validsubdomains = array("de", "fr", "en", "pt", "es", "tr", "pl", "ru", "nl", "it");
preg_match('/^([a-z]+)\./', $_SERVER['SERVER_NAME'], $subdomain);
if ($_SERVER['SERVER_NAME']!="localhost" && !in_array($subdomain[1], $validsubdomains)) {
	$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	if (preg_match('/^de/i', $language)) header("Location: http://de.florensia-base.com".$_SERVER['REQUEST_URI']);
	elseif (preg_match('/^fr/i', $language)) header("Location: http://fr.florensia-base.com".$_SERVER['REQUEST_URI']);
	elseif (preg_match('/^es/i', $language)) header("Location: http://es.florensia-base.com".$_SERVER['REQUEST_URI']);
	elseif (preg_match('/^en/i', $language)) header("Location: http://en.florensia-base.com".$_SERVER['REQUEST_URI']);
	elseif (preg_match('/^tr/i', $language)) header("Location: http://tr.florensia-base.com".$_SERVER['REQUEST_URI']);
	elseif (preg_match('/^pt/i', $language)) header("Location: http://pt.florensia-base.com".$_SERVER['REQUEST_URI']);
	elseif (preg_match('/^it/i', $language)) header("Location: http://it.florensia-base.com".$_SERVER['REQUEST_URI']);
	//elseif (preg_match('/^ru/i', $language)) header("Location: http://ru.florensia-base.com".$_SERVER['REQUEST_URI']);
	//elseif (preg_match('/^pl/i', $language)) header("Location: http://pl.florensia-base.com".$_SERVER['REQUEST_URI']);
	else header("Location: http://en.florensia-base.com".$_SERVER['REQUEST_URI']);
} elseif ($_SERVER['SERVER_NAME']=="localhost") {
	$subdomain[1]="en";
}

require_once("./confg.php");

if (is_file($cfg['root_abs']."/lock")) {
	echo "
		<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"
		\"http://www.w3.org/TR/html4/loose.dtd\">
		<html>
			<title>Florensia Base - Under Contruction</title>
			<meta name='author' content='Noxx'>
			<meta name='copyight' content='(c) 2008-".date("Y")." by www.florensia-base.com'>
			<meta name='language' content='en'>
			<meta name='revisit-after' content='daily'>
			<meta name='keywords' content='Florensia,Florensia Online,Flo,Quests,Ranking,Guild,Character,Market,Guides,Items,Monsters,NPCs,Signature,Skilltree,Database,Forum,official,Fansite'>
			<meta name='description' content='Fansite about Florensia - Join our community'>
			<meta http-equiv='Content-Type' content='text/html;charset=utf-8'>
			<link rel='stylesheet' type='text/css' href='css.css'>
			<!--[if lt IE 8]>
			<script src='http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js' type='text/javascript'></script>
			<![endif]-->
			<body style='margin-width:0px; margin-height:0px; margin-top:0px; margin-left:0px;' bgcolor='#37658a'>
				<div style='height:90px'></div>
				<div style='text-align:center;' class='subtitle'>
					<table width='100%' style='text-align:center;'>
						<tr><td height='200' colspan='3' style='text-align:center;'><img src='{$cfg['layer_rel']}/shiplogo.png' border='0'></td></tr>
						<tr><td width='33%'></td><td class='warning' style='text-align:center;'>Under construction</td><td width='33%'></td></tr>
						<tr><td height='20' colspan='3'></td></tr>
						<tr><td width='33%'></td><td class='warning' style='text-align:center;'>This website is currently down for maintenance.<br />Please check back in some minutes.</td><td width='33%'></td></tr>
						<tr><td height='20' colspan='3'></td></tr>
						<tr><td width='33%'></td><td class='bordered' style='text-align:center;'><a href='http://forum.florensia-base.com'>Forum</a></td><td width='33%'></td></tr>
						<tr><td height='50' colspan='3'></td></tr>
					</table>
				</div>
			</body>
		</html>
	";
	die;
}


/* some workarounds */
bcscale(0); // set all bc-functions like bcsub to a scale of 0

/* end workarounds */

$db = MYSQL_CONNECT($cfg['dbhost'], $cfg['dbuser'], $cfg['dbpasswd']) or die("No Database connection");
MYSQL_SELECT_DB($cfg['dbname'], $db) or die("No Database connection");
MYSQL_QUERY("SET NAMES 'utf8'");
$db_workaround = $db;
require_once($cfg['root_abs']."/functions.php"); // + cachesystem

define('is_florensia', '1');

require_once($cfg['root_abs']."/class_florensia.php");
	$florensia = new class_florensia;

	foreach ($cfg as $varname => $varvalue) {
		$florensia->$varname=$varvalue;
	}

define("KILL_GLOBALS", 1);
define("IN_MYBB", 1);
require($florensia->forum."/global.php");
require_once MYBB_ROOT."inc/functions_user.php";
require_once MYBB_ROOT."inc/class_parser.php";
$parser = new postParser;
$lang->load("member");
#date_default_timezone_set("System/Localtime"); # use system time for all date() calculations
	
require_once($cfg['root_abs']."/class_lang.php");
	$flolang = new class_lang;

if (is_dir($cfg['language_abs'].'/'.$subdomain[1]) && $subdomain[1]!="") {
	$flolang->language = $subdomain[1];
}
$flolang->load("global");
$flolang->load("market");
$flolang->load("tabbar");


$florensia->root = str_replace('http://www.', 'http://'.$flolang->language.'.', $florensia->root);
$flolang->get_languages();

require_once("{$florensia->root_abs}/class_stringtable.php");
	$stringtable = new class_stringtable;
	$stringtable->get_valid_languages();

	$stringtable->language = $flolang->lang[$flolang->language]->prefered_stringtablelang;
	if (isset($_POST['quicksearch'])) $_GET['names'] = $_POST['names'];
	if (isset($_GET['names']) && $_GET['names']!=$stringtable->language && array_key_exists($_GET['names'], $stringtable->languagearray)) {
		$stringtable->language = $_GET['names'];
	}

require_once("{$florensia->root_abs}/class_quest.php");
	$classquest = new class_quest;

require_once("{$florensia->root_abs}/class_questtext.php");
	$classquesttext = new class_questtext;
	$classquesttext->get_valid_languages();
		$classquesttext->language = $flolang->lang[$flolang->language]->prefered_questtextlang;
		if (isset($_GET['text']) && $_GET['text']!=$classquesttext->language && array_key_exists($_GET['text'], $classquesttext->languagearray)) {
			$classquesttext->language = $_GET['text'];
		}

//needs string/quest for output_page() if there is an error
if ($_SERVER['SERVER_NAME']=="localhost") { $mybb->user['uid']=1; }
require_once($cfg['root_abs']."/forum.php");
$db = $db_workaround;

require_once("{$florensia->root_abs}/floclass.php");
	$floclass = new floclass;

require_once("{$florensia->root_abs}/class_droplist.php");
	$classdroplist = new class_droplist;

require_once("{$florensia->root_abs}/class_usernote.php");
	$classusernote = new class_usernote;

require_once("{$florensia->root_abs}/class_usermarket.php");
	$classusermarket = new class_usermarket;

require_once("{$florensia->root_abs}/class_misc.php");
	$misc = new class_misc;

require_once("{$florensia->root_abs}/class_user.php");
	$flouserdata = new class_userdata();
	$flouser = new class_user($mybb->user['uid']);
	
require_once("{$florensia->root_abs}/class_log.php");
	$flolog = new class_log();

require_once("{$florensia->root_abs}/class_addition.php");
	$floaddition = new class_addition();

require_once("{$florensia->root_abs}/class_guide.php");
	$classguide = new class_guide();

require_once("{$florensia->root_abs}/class_gallery.php");
	$classgallery = new class_gallery();
	
require_once("{$florensia->root_abs}/class_vote.php");
	$classvote = new class_vote();
	

require_once("{$florensia->root_abs}/class_character.php");

if (is_file("{$florensia->root_abs}/experimental") && !($mybb->user['usergroup']==4 OR $mybb->user['usergroup']==3)){
	if ($mybb->session->is_spider) { header("Location: http://www.florensia-base.com".$_SERVER['REQUEST_URI']); }
	$florensia->output_page($florensia->login());
}

if (!$mybb->session->is_spider) {

//antibot.php
#$siteimpressionslimit = 8;
#$siteimpressionstimelimit = 30; 

#MYSQL_QUERY("DELETE FROM flobase_sessions WHERE lastcaptcha<'".bcsub(date("U"), $siteimpressionstimelimit)."'");

$queryantibot = MYSQL_QUERY("SELECT antibot FROM flobase_sessions WHERE sessionid='{$mybb->cookies['sid']}' AND antibot=1 LIMIT 1");
if (MYSQL_NUM_ROWS($queryantibot)){
	$antibotcontent = "
		<div class='warning' style='text-align:center;'>{$flolang->content_locked}</div>
		<div class='warning' style='margin-top:30px; text-align:center;'>{$flolang->content_locked_text}</div>
	";
	$florensia->sitetitle("Antibot");
	$florensia->output_page($antibotcontent);
}
/****

//Sessions
	if (isset($_POST['captchasubmit'])) {
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
		$sicherheits_eingabe = encrypt($_POST["Securitycode"], "8h384ls94");
		$sicherheits_eingabe = str_replace("=", "", $sicherheits_eingabe);
		
		
		if ($sicherheits_eingabe == $_SESSION['captcha_spam']) {
			unset($_SESSION['captcha_spam']);
			MYSQL_QUERY("DELETE FROM flobase_sessions WHERE sessionid='{$mybb->cookies['sid']}'");
		}
	}

	$sessionquery = MYSQL_QUERY("SELECT siteimpressions FROM flobase_sessions WHERE sessionid='{$mybb->cookies['sid']}'");
	if ($session = MYSQL_FETCH_ARRAY($sessionquery)) {
		if ($session['siteimpressions']>$siteimpressionslimit) {
			$sessioncontent = "
			<div class='warning' style='text-align:center'>{$flolang->crawlingcaptcha}</div>
			<div style='margin-top:15px;'>
				<form action='" . $_SERVER['PHP_SELF'] . "?".$_SERVER['QUERY_STRING']."' method='post'>
				<table width='100%'>
					<tr>
						<td style='width:150px'><img src='{$florensia->root_rel}/captcha.php' border='0' title='Securitycode' alt='Securitycode'></td>
						<td><input type='Text' name='Securitycode' size='7'></td>
					</tr>
					<tr><td style='height:7px'>";
								foreach ($_POST as $key => $value) {
									$sessioncontent .= "<input type='hidden' name='$key' value='".$florensia->escape($_POST[$key])."'>
									";
								}
					$sessioncontent .= "</td></tr>
					<tr><td colspan='2'><input type='Submit' name='captchasubmit' value='{$flolang->crawlingcaptcha_next}'></td></tr>
				</table>
				</form>
			</div>
			";

			MYSQL_QUERY("UPDATE flobase_sessions SET lastcaptcha='".date("U")."' WHERE sessionid='{$mybb->cookies['sid']}'");
			$florensia->sitetitle("Antibot");
			$florensia->sitetitle("Captcha");
			$florensia->output_page($sessioncontent);
		}
		MYSQL_QUERY("UPDATE flobase_sessions SET siteimpressions='".bcadd($session['siteimpressions'],1)."' WHERE sessionid='{$mybb->cookies['sid']}'");
	}
	else { MYSQL_QUERY("INSERT INTO flobase_sessions (sessionid, lastcaptcha) VALUES('{$mybb->cookies['sid']}', '".date("U")."')"); }
******/
}// !spider
?>
