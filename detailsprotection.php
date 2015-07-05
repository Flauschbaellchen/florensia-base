<?PHP
if (!preg_match('/(noxx|florensia-base\.com)/i', $_SERVER['HTTP_REFERER'])) die;

require_once("./confg.php");

require_once($cfg['root_abs']."/class_lang.php");
	$flolang = new class_lang;

/*	workaround so no need to include whole forum-class	*/
class parser {
	function parse_message($text, $settings=array()) {
		return $text;
	}
}
$parser = new parser;
/*	END	*/

preg_match('/^([a-z]+)\./', $_SERVER['SERVER_NAME'], $subdomain);
if (is_dir($cfg['language_abs'].'/'.$subdomain[1]) && $subdomain[1]!="") {
	$flolang->language = $subdomain[1];
}
$flolang->load("global");

	$db = MYSQL_CONNECT($cfg['dbhost'], $cfg['dbuser'], $cfg['dbpasswd']) or die("No Database connection");
	MYSQL_SELECT_DB($cfg['dbname'], $db) or die("No Database connection");
	MYSQL_QUERY("SET NAMES 'utf8'");

require_once($cfg['root_abs']."/functions.php");
define('is_florensia', 1);
require_once($cfg['root_abs']."/class_florensia.php");
	$florensia = new class_florensia;

	$color1=255;
	$color2=255;
	$color3=255;

	$columnname_x = 0;
	$columnall_start = 10;
	$columnvalue_x = 300;

	$picturewidth = 310;
	$fontsize = 9;
	$steps = 12;

if ($_REQUEST['small']) { $small=true; }
else { $small=false; }

$detailsrow=0;
$placeholder=0;
function write($title="", $value="", $columnname="-", $gotitle="") {
	global $detailsrow, $columnall_y, $steps, $write, $placeholder;

	if ($gotitle=="") { $row = $detailsrow; }
	else { $row = $write[$gotitle]; }

	$write[$row]['title'] = $title;
	$write[$row]['value'] .= $value;
	$write[$columnname] = $row;

	if ($gotitle=="") {
		$columnall_y += $steps;
		$detailsrow++;
	}
	$placeholder=0;
	return $write;
}

if (isset($_REQUEST['npcid'])) { $columnstable="npc"; $searchquery = "SELECT * FROM server_npc WHERE ".$florensia->get_columnname("npcid", "npc")."='".mysql_real_escape_string($_REQUEST['npcid'])."'"; }
elseif (isset($_REQUEST['skillid'])) { $columnstable="skill"; $searchquery = "SELECT * FROM server_skill WHERE ".$florensia->get_columnname("skillid", "skill")."='".mysql_real_escape_string($_REQUEST['skillid'])."'"; }
//elseif (isset($_REQUEST['spellid'])) { $columnstable="skill"; $searchquery = "SELECT * FROM server_spell WHERE ".$florensia->get_columnname("skillid", "skill")."='".mysql_real_escape_string($_REQUEST['spellid'])."'"; }
elseif (isset($_REQUEST['spellid'])) { $columnstable="skill"; $searchquery = "SELECT * FROM server_skill WHERE ".$florensia->get_columnname("skillid", "skill")."='".mysql_real_escape_string($_REQUEST['spellid'])."'"; }
else {
	$columnstable="item";
	$queryitemtable = MYSQL_QUERY("SELECT tableid FROM server_item_idtable WHERE itemid='".mysql_real_escape_string($_REQUEST['itemid'])."'");
	if ($itemtable = MYSQL_FETCH_ARRAY($queryitemtable)) {
		$searchquery = "SELECT * FROM server_item_".$itemtable['tableid']." WHERE ".$florensia->get_columnname("itemid", "item")."='".mysql_real_escape_string($_REQUEST['itemid'])."'";
	}
}

	$querydetails = MYSQL_QUERY($searchquery);
	if ($details = MYSQL_FETCH_ARRAY($querydetails)) {
		//set all keys to lower-case
		foreach ($details as $key => $value) {
			$details[strtolower($key)] = $value;
		}

		unset($column);
		$columnall_y = 0;
		$querycolumns = MYSQL_QUERY("SELECT * FROM flobase_{$columnstable}_columns WHERE secret='0' ORDER BY vieworder");
		while ($columns = MYSQL_FETCH_ARRAY($querycolumns)) {

			if ($columns['code_korean']===NULL && isset($write) && !$placeholder) {
				$write = write();
				$placeholder=1;
				continue;
			}

			//set korean-code to tolower
			$columns['code_korean'] = strtolower($columns['code_korean']);

			//ausslassen wenn defaultwert oder nicht gesetzt
			if ($columns['defaultvalue']==$details[$columns['code_korean']] OR !isset($details[$columns['code_korean']])) continue;
			if ($columns['floatvalue']) $details[$columns['code_korean']] = bin2float($details[$columns['code_korean']]);

				if ($columnstable == "npc") {
					switch ($columns['code_english']) {
						case "attackmax": {
							if (intval($details[$columns['code_korean']])==0) { $details[$columns['code_korean']]=$details[$florensia->get_columnname("attackmin", "npc")]; }
							$write = write($columns['name_'.$flolang->language], " ~ ".$details[$columns['code_korean']], $columns['code_english'], "attackmin");
							break;
						}
						case "attackrange": {
							if (!intval($details[$columns['code_korean']])) { $details[$columns['code_korean']]="Melee"; }
							else { $details[$columns['code_korean']]="Range"; }
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']], $columns['code_english']);
							break;
						}
						case "airmonster": {
							if (intval($details[$columns['code_korean']])!=0) { $value = $flolang->yes; }
							else { $value = $flolang->no; }
							$write = write($columns['name_'.$flolang->language], $value, $columns['code_english']);
							break; 
						}

						case "navalguns": {
							if (!intval($details[$florensia->get_columnname("attackrange", "npc")])) continue; //melee
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "navalgunspeed": {
							if (!intval($details[$florensia->get_columnname("navalguns", "npc")])) continue; //no guns
							$write = write($columns['name_'.$flolang->language], bcdiv($details[$columns['code_korean']],1000,1).$columns['endvalue'], $columns['code_english']);
							break; 
						}
						case "navalgunscope": {
							if (!intval($details[$florensia->get_columnname("navalguns", "npc")])) continue; //no guns
							$write = write($columns['name_'.$flolang->language], bcdiv($details[$columns['code_korean']],100).$columns['endvalue'], $columns['code_english']);
							break; 
						}

 						case "exp": {

							//class land/sea
							if (intval($details[$florensia->get_columnname("fielddividing", "npc")])) $expclass = "sea";
							else $expclass = "land";

 							$expprocent = $florensia->get_exp($details[$columns['code_korean']], $details[$florensia->get_columnname("level", "npc")], $expclass);
 							$write = write($columns['name_'.$flolang->language], "".$details[$columns['code_korean']]." ($expprocent/".$details[$florensia->get_columnname("level", "npc")].")", $columns['code_english']);
  							break; 
 						}
/*
						case "attackcooldown1": { }
						case "attackcooldown2": { }
						case "attackcooldown3": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/100;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']]."s", $columns['code_english']);
							break;
						}
*/
						case "attackpowerelement":
						case "resistenceelement":
						case "attackpowerillusion":
						case "resistenceillusion":
						case "attackpowerholy":
						case "resistenceholy":
						case "attackpowerdark":
						case "resistencedark":
						case "attackpowerphysical":
						case "resistencephysical":
						case "attackpowerpoison":
						case "resistencepoison":
						case "attackpowerfire":
						case "resistencefire":
						case "attackpowerice":
						case "resistenceice":
						case "attackpowerlighning":
						case "resistencelighning":
						case "attackpowerholyph":
						case "resistenceholyph":
						case "attackpowerdark":
						case "resistancedark":
						case "attackpowerabsolute":
						case "resistanceabsolute":
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						default : { $write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']); break; }
					}
				}
				elseif ($columnstable == "item") {
					if (preg_match('/^itembonus([0-9]{1})$/', $columns['code_english'], $itembonus)) {
						if ($details[$florensia->get_columnname("itembonus".$itembonus['1']."code", "item")] == "4294967295") continue;
						$querybonusname = MYSQL_QUERY("SELECT name_".$flolang->language.", end_operator, effectid FROM flobase_item_effect WHERE effectid='".$details[$florensia->get_columnname("itembonus".$itembonus['1']."code", "item")]."'");
						$bonusname = MYSQL_FETCH_ARRAY($querybonusname);

						//117 n.d.?
						if ($bonusname['effectid']==117) continue;

						unset($startoperator, $endoperator);
						switch ($details[$florensia->get_columnname("itembonus".$itembonus['1']."operator", "item")]) {
							case "*": { $endoperator="%"; $details[$columns['code_korean']] = round($details[$columns['code_korean']]*100); break;}
							case "+": { $startoperator="+";  break;}
						}
						if ($bonusname['end_operator']) { $endoperator=$bonusname['end_operator']; }

						$write = write($bonusname['name_'.$flolang->language], $startoperator.$details[$columns['code_korean']].$endoperator, $columns['code_english']);
						continue;
					}

					switch ($columns['code_english']) {
						case "physicalphmax": {
							if (intval($details[$columns['code_korean']])==0) { $details[$columns['code_korean']]=$details[$florensia->get_columnname("physicalphmin", "item")]; }
							$write = write($columns['name_'.$flolang->language], " ~ ".$details[$columns['code_korean']], $columns['code_english'], "physicalphmin");
							break;
						}
						case "spellphmax": {
							if (intval($details[$columns['code_korean']])==0) { $details[$columns['code_korean']]=$details[$florensia->get_columnname("spellphmin", "item")]; }
							$write = write($columns['name_'.$flolang->language], " ~ ".$details[$columns['code_korean']], $columns['code_english'], "spellphmin");
							break;
						}
						case "exchange": {
							if (intval($details[$columns['code_korean']])==0) { $value = $flolang->no; }
							else { $value = $flolang->yes; }
							$write = write($columns['name_'.$flolang->language], $value, $columns['code_english']);
							break; 
						}
						case "trading": {
							if (intval($details[$columns['code_korean']])==0) { $value = $flolang->no; }
							else { $value = $flolang->yes; }
							$write = write($columns['name_'.$flolang->language], $value, $columns['code_english']);
							break; 
						}
						case "tradingshop": {
							if (intval($details[$columns['code_korean']])==0) { $value = $flolang->no; }
							else { $value = $flolang->yes; }
							$write = write($columns['name_'.$flolang->language], $value, $columns['code_english']);
							break; 
						}
						case "questitem": {
							if (intval($details[$columns['code_korean']])==0) { $value = $flolang->no; }
							else { $value = $flolang->yes; }
							$write = write($columns['name_'.$flolang->language], $value, $columns['code_english']);
							break; 
						}
						case "landclass": {
							foreach ($florensia->get_classname($details[$columns['code_korean']], "land") as $key => $classid) {
								$write = write(bcadd($key,1).". ".$columns['name_'.$flolang->language], $classid, $columns['code_english']);
							}
							break;
						}
						case "seaclass": {
							foreach ($florensia->get_classname($details[$columns['code_korean']], "sea") as $key => $classid) {
								$write = write(bcadd($key,1).". ".$columns['name_'.$flolang->language], $classid, $columns['code_english']);
							}
							break;
						}
						case "spellph": {
							if (intval($details[$florensia->get_columnname("spellphmax", "item")])!=0) { continue; }
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "physicalph": {
							if ($column[$columns['physicalphmin']]!=0 OR $column[$columns['physicalphmax']]!=0) { continue; }
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "acceleration": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "deceleration": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "shiprange": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "shiphitrange": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "shipgunspeed": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "reloadspeed": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/1000;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "explosiveradius": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/100;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "balance": {
							if ($details[$columns['code_korean']]=="4294967295" && $itemtable['tableid']=="shipfrontitem") $details[$columns['code_korean']]="-1";
							elseif ($details[$columns['code_korean']]=="4294967295") continue;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "duration": {
							$details[$columns['code_korean']]=round($details[$columns['code_korean']]/60);
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "gender": {
							if ($details[$columns['code_korean']]==1) $details[$columns['code_korean']] = $flolang->female;
							else $details[$columns['code_korean']] = $flolang->male;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "range": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/100;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "attackspeed": {
							if ($details[$columns['code_korean']]=="0") continue;
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/1000; //why there was a +1 at the end?
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "itemtype": {
							$querytypes = MYSQL_QUERY("SELECT * FROM flobase_item_types WHERE itemtypeid='".$details[$columns['code_korean']]."'");
							if ($types = MYSQL_FETCH_ARRAY($querytypes)) { $details[$columns['code_korean']] = $types['name_'.$flolang->language]; }
							else $details[$columns['code_korean']] = $typeid;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						default : { $write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']); }
					}
				}
				elseif ($columnstable == "skill") {
					unset($boni);
					if (preg_match('/^passivebonus([0-9]{1})$/', $columns['code_english'], $skillbonus)) {
						$boni="passive";
					}
					elseif (preg_match('/^skillbonus([0-9]{1})$/', $columns['code_english'], $skillbonus)) {
						$boni="skill";
					}
					elseif (preg_match('/^activebonus([0-9]{1})$/', $columns['code_english'], $skillbonus)) {
						$boni="active";
					}

					if ($columns['code_english']=="requiredlandlevel" &&  $details[$columns['code_korean']] == "4294967295") continue;
					if ($columns['code_english']=="requiredsealevel" &&  $details[$columns['code_korean']] == "4294967295") continue;

					if (isset($boni)) {
						if ($details[$florensia->get_columnname("{$boni}bonus".$skillbonus['1']."code", "skill")] == "4294967295") continue;

						$querybonusname = MYSQL_QUERY("SELECT effectid, name_".$flolang->language.", end_operator FROM flobase_item_effect WHERE effectid='".$details[$florensia->get_columnname("{$boni}bonus".$skillbonus['1']."code", "skill")]."'");
						$bonusname = MYSQL_FETCH_ARRAY($querybonusname);

						unset($startoperator, $endoperator);
						switch ($details[$florensia->get_columnname("{$boni}bonus".$skillbonus['1']."operator", "skill")]) {
							case "*": { $endoperator="%"; $details[$columns['code_korean']] = round($details[$columns['code_korean']]*100); break;}
							case "+": {
								if ($details[$columns['code_korean']]>=0) {
									if ($boni=="active") { $startoperator = "-"; }
									else { $startoperator="+"; }
								}  break;
							}
						}
						if ($bonusname['end_operator']) { $endoperator=$bonusname['end_operator']; }

						/* workaround for timespecific effects e.g. stun?	*/

						//if ($endoperator=="s" && $details[$florensia->get_columnname("begintimemeter", "skill")]) {
						//	$details[$columns['code_korean']] = bcdiv($details[$florensia->get_columnname("begintimemeter", "skill")],10);
						//}
						if ($endoperator=="s" && $details[$florensia->get_columnname("duration", "skill")]) {
							$details[$columns['code_korean']] = $details[$florensia->get_columnname("duration", "skill")];
						}
						/*	*	*/

						switch ($bonusname['effectid']) {
							case "5": { //Moving Speed
									$details[$columns['code_korean']] = bcdiv(bcmul($details[$columns['code_korean']],2),10);
									break;
							}
							case "21": { // range distance
									$details[$columns['code_korean']] = bcdiv($details[$columns['code_korean']],100);
									break;
							}
							case "68": { // gun specialization
									$details[$columns['code_korean']] = bcdiv($details[$columns['code_korean']],1000, 2);
									break;
							}
						}

						$write = write($bonusname['name_'.$flolang->language], $startoperator.$details[$columns['code_korean']].$endoperator, $columns['code_english']);
						continue;
					}
					switch ($columns['code_english']) {
						case "duration": {
 							$details[$columns['code_korean']]=$details[$columns['code_korean']]/1000;
 							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
 							break;
						}
						case "cooldown": {
							if ($details[$columns['code_korean']]=="4294967295") continue;
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/1000;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "castingtime": {
							if ($details[$columns['code_korean']]=="4294967295") continue;
							if ($details[$columns['code_korean']]=="1") $details[$columns['code_korean']]=0;
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/1000;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "mpconsumptionpercent": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]*100;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "effectscope": {
							//defaulting to "0" via db but also needs to ignore "4294967295"
							if ($details[$columns['code_korean']] == "4294967295") continue;
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/100;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "attackrange": {
							if ($details[$columns['code_korean']]=="4294967295") continue;
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/100;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}

						case "damagereflectionpercent": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]*100;
							$write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						default : { $write = write($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']); break; }
					}
				}

		}

	//Additional infos for skillbooks

		if (isset($itemtable['tableid']) && $itemtable['tableid']=="skillbookitem") {
			//for ($i=1; $i<3; $i++) {
			//	if ($i==1) $querydescription = MYSQL_QUERY("SELECT * FROM server_skill WHERE ".$florensia->get_columnname("bookid", "skill")."='".$details[$florensia->get_columnname("description", "item")]."' ORDER BY ".$florensia->get_columnname("skillid", "skill")."");
			//	else $querydescription = MYSQL_QUERY("SELECT * FROM server_spell WHERE ".$florensia->get_columnname("bookid", "skill")."='".$details[$florensia->get_columnname("description", "item")]."' ORDER BY ".$florensia->get_columnname("skillid", "skill")."");
				$querydescription = MYSQL_QUERY("SELECT ".$florensia->get_columnname("masterlevel", "skill").",".$florensia->get_columnname("classid", "skill")." FROM server_skill WHERE ".$florensia->get_columnname("bookid", "skill")."='".$details[$florensia->get_columnname("description", "item")]."' ORDER BY ".$florensia->get_columnname("skillid", "skill")."");
				if ($description = MYSQL_FETCH_ARRAY($querydescription)) {
					foreach ($florensia->get_classname($description[$florensia->get_columnname("classid", "skill")], "land") as $key => $classid) {
						$write = write("Class", $classid, "skillclass");
					}
					foreach ($florensia->get_classname($description[$florensia->get_columnname("classid", "skill")], "sea") as $key => $classid) {
						$write = write("Class", $classid, "skillclass");
					}
					$write = write("Max. Level", $description[$florensia->get_columnname("masterlevel", "skill")], "skillmasterlevel");
					//break;
				}
			//}
		}
	}
	else {
		$columnall_y += $steps;
		$column['Error']="Error: No details were found.";
	}

	if ($columnall_y<=0) $columnall_y=1;

	$Teilgrafik = imagecreatetruecolor($picturewidth, $columnall_y);
#	imageantialias($Teilgrafik, true);

	$colourwhite = imagecolorallocate($Teilgrafik, 73,111,150);
	imagefilledrectangle($Teilgrafik, 0, 0, $picturewidth+1,  $columnall_y, $colourwhite);

	$color = imagecolorallocate ($Teilgrafik, $color1, $color2, $color3);
	imagecolortransparent($Teilgrafik, $colourwhite);

	if ($_REQUEST['view']==1 && is_array($write)) {
		foreach ($write as $key => $value) {
			if (!is_numeric($key)) continue;
			imagettftext($Teilgrafik, $fontsize, 0, 0, $columnall_start, $color, $cfg['fonts_abs'].'/verdana.ttf', $write[$key]['title']);
	
			$textwerte = imagettfbbox ($fontsize, 0, $cfg['fonts_abs'].'/verdana.ttf', $write[$key]['value']);
			imagettftext($Teilgrafik, $fontsize, 0, bcsub($columnvalue_x,abs($textwerte['2'])), $columnall_start, $color, $cfg['fonts_abs'].'/verdana.ttf', $write[$key]['value']);
			$columnall_start += $steps;
		}
	}


		header('Content-Type: image/gif');
		imagegif($Teilgrafik);
		imagedestroy($Teilgrafik);


?>
