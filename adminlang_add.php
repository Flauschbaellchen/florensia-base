<?PHP
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("access_admincp") OR !$flouser->get_permission("mod_language")) { $florensia->output_page($flouser->noaccess()); }

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Languages");

$dbtables = array(
'flobase_guides_categories'=>'id',
'flobase_item_categories'=>'id',
'flobase_item_columns'=>'id',
'flobase_item_effect'=>'effectid',
'flobase_item_types'=>'itemtypeid',
'flobase_landclass'=>'classid',
'flobase_menubar'=>'id',
'flobase_npc_columns'=>'id',
'flobase_seaclass'=>'classid',
'flobase_seal_optionlang'=>'sealid',
'flobase_skill_columns'=>'id');


$lang="nl";
$copylang = "de";

$error=false;
$content = "<div class='subtitle'>Adding new language \"$lang\"...</div>";

$content .= "<div class='bordered'>Working on DB...</div>";
foreach ($dbtables as $dbtable => $copykey) {
	$content .= "<div>$dbtable with $copykey</div>";
	MYSQL_QUERY("ALTER TABLE $dbtable ADD name_{$lang} TEXT NOT NULL;");
		$querycopy = MYSQL_QUERY("SELECT $copykey, name_{$copylang} FROM $dbtable");
		while ($copy = MYSQL_FETCH_ARRAY($querycopy)) {
			if (!MYSQL_QUERY("UPDATE $dbtable SET name_{$lang}='".mysql_real_escape_string($copy['name_'.$copylang])."' WHERE $copykey='".$copy[$copykey]."'")) {
				$content .= "<div class='warning'>$dbtable: ".MYSQL_ERROR()."</div>";
				$error=true;
			}
		}
}


	$content .= "<div>Copying language files (flobase_languagefiles)</div>";
	MYSQL_QUERY("ALTER TABLE flobase_languagefiles ADD lang_{$lang} TEXT NOT NULL, ADD lang_{$lang}_flag TINYINT( 1 ) NOT NULL DEFAULT '1';");
		$querycopy = MYSQL_QUERY("SELECT varname, lang_{$copylang} FROM flobase_languagefiles");
		while ($copy = MYSQL_FETCH_ARRAY($querycopy)) {
			if (!MYSQL_QUERY("UPDATE flobase_languagefiles SET lang_{$lang}='".mysql_real_escape_string($copy['lang_'.$copylang])."' WHERE varname='".$copy['varname']."'")) {
				$content .= "<div class='warning'>flobase_languagefiles: ".MYSQL_ERROR()."</div>";
				$error=true;
			}
		}
		
		
if (!$error) $content .= "<div style='margin-top:10px;' class='successful'>Great! No errors occoured.</div>";
$florensia->output_page($content);

?>
