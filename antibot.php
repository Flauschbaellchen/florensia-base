<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

	MYSQL_QUERY("UPDATE flobase_sessions SET lastcaptcha='".bcadd(date("U"),86400)."', antibot='1' WHERE sessionid = '{$mybb->cookies['sid']}'");
	header("Location: ".$florensia->root);

?>