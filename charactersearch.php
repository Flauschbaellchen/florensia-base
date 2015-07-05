<?PHP


require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

header("Location: ".$florensia->outlink(array('characterdetails'), array(), array("language"=>TRUE, "escape"=>FALSE)));
exit;

?>