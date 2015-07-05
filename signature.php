<?PHP

require_once("./confg.php");
//global scriptsettings
$fontfolder = $cfg['fonts_abs'];
$fontfile = $fontfolder."/verdana.ttf";
$layerfolder = $cfg['signature_abs'];
//----
$sep="-";
//----
$_SERVER['PHP_SELF'] = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8', FALSE);

$_SERVER['HTTP_USER_AGENT'] = htmlentities(strip_tags($_SERVER['HTTP_USER_AGENT']), ENT_QUOTES, 'UTF-8', FALSE);
$_SERVER['SERVER_NAME'] = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8', FALSE);


# logfile
$cachefolder = $cfg['signature_abs']."/cache/".date("Y.m");
if (!is_dir($cachefolder)) mkdir($cachefolder);
$cachefile = $cachefolder."/".md5($_GET['sig']);

if (!preg_match('/\/sigcreate/', $_SERVER['HTTP_REFERER']) && !preg_match('/\/sigusercreate/', $_SERVER['HTTP_REFERER'])) {
	$logfiledir = $cfg['signature_abs']."/logfiles/".date("Y.m");
	if (!is_dir($logfiledir)) mkdir($logfiledir);
	$siglogfile = fopen($logfiledir."/signature_" . date("Y-m-d"),"a");
		$text = date("U")."/".date("Y-m-d H:i:s")." -- ".$_GET['sig']." -- ".$_SERVER['HTTP_REFERER']."\n";
		fputs($siglogfile, $text);
	fclose($siglogfile);

	// loookup if there is already a cached image available
	if (!isset($_GET['nocache'])) {
		clearstatcache();
		if (is_file($cachefile) && filectime($cachefile)>=bcsub(date("U"),(60*60*2))) {
			$cached = imagecreatefrompng($cachefile);
			header('Content-Type: image/png');
			imagesavealpha($cached, TRUE);
			imagepng($cached);
			imagedestroy($cached);
			die;
		}
	}
}

/* * * */

$db = MYSQL_CONNECT($cfg['dbhost'], $cfg['dbuser'], $cfg['dbpasswd']) or die("No Database connection");
MYSQL_SELECT_DB($cfg['dbname'], $db) or die("No Database connection");
MYSQL_QUERY("SET NAMES 'utf8'");


/*	workaround so no need to include whole forum-class	*/
class parser {
	function parse_message($text, $settings=array()) {
		return $text;
	}
}
$parser = new parser;
/*	END	*/


require_once("{$cfg['root_abs']}/class_lang.php");
	$flolang = new class_lang;

require_once($cfg['root_abs']."/class_character.php");

preg_match('/^([a-z]{2})/', $_GET['sig'], $language);
if (is_dir($cfg['language_abs'].'/'.$language[1]) && $language[1]!="") {
	$flolang->language = $language[1];
}
$flolang->load("signature");

/*
 * Loading character...
 */

preg_match("/^([a-z]{2}|0)$sep([0-9t]+)$sep(.+)\.(png|gif)$/", $_GET['sig'], $character);
$character = explode($sep, $character[3]);
foreach ($character as $charname) {
	if (trim($charname)=="0" OR $charname=="") {$charinfo['placeholder_'.bcadd(count($charinfo),1)]=0; continue; }
	$api = new class_character($charname);
	if (!$api->is_valid()) {
		//timeout or deleted
		continue;
	}
	$charinfo[$charname] = $api->data;
}

/*
 * Loading template/signature...
 */

preg_match("/^([a-z]{2}|0)$sep([0-9t]+)/", $_GET['sig'], $signature);
$signature = $signature[2];
if (preg_match('/^t([0-9]+)$/', $signature, $signaturetemp)) {
	$querysignature = MYSQL_QUERY("SELECT template FROM flobase_signaturetemp WHERE userid='" . $signaturetemp[1] . "'");
	if ($signature = MYSQL_FETCH_ARRAY($querysignature)) {
		$signature['layout'] = "create/".$signaturetemp[1];
	}
	else {
					//ungueltige id oder keine angegeben
 					$querysignature = MYSQL_QUERY("SELECT * FROM flobase_signature");
 					$signature = rand(1,MYSQL_NUM_ROWS($querysignature));
 					$querysignature = MYSQL_QUERY("SELECT * FROM flobase_signature LIMIT " . bcsub($signature,1) . ",$signature");
 					$signature = MYSQL_FETCH_ARRAY($querysignature);
	}
}
else {
				$querysignature = MYSQL_QUERY("SELECT * FROM flobase_signature WHERE id='" . $signature . "'");
				if (!($signature = MYSQL_FETCH_ARRAY($querysignature))) {
					//ungueltige id oder keine angegeben
					$querysignature = MYSQL_QUERY("SELECT * FROM flobase_signature");
					$signature = rand(1,MYSQL_NUM_ROWS($querysignature));
					$querysignature = MYSQL_QUERY("SELECT * FROM flobase_signature LIMIT " . bcsub($signature,1) . ",$signature");
					$signature = MYSQL_FETCH_ARRAY($querysignature);
				}
}

if (!($xml = simplexml_load_string($signature['template']))) die("Failed to load template");

				$imagepic = $layerfolder."/".$signature['layout'];
				$info = getimagesize ( $imagepic );
				if ($info[2] == 2 ) { //JPEG / JPG
					$Teilgrafik = imagecreatefromjpeg($imagepic);
				}
				elseif ($info[2] == 3) { //PNG
					$Teilgrafik = imagecreatefrompng($imagepic);
				}
				elseif ($info[2] == 1)  { // GIF
					$Teilgrafik = imagecreatefromgif($imagepic);
				}
				else { //BMP
					require_once($cfg['root_abs']."/convertbmp.php");
					$Teilgrafik = imagecreatefrombmp($imagepic);
				}

function imagettfborder($im, $size, $angle, $x, $y, $color, $font, $text, $width) {
	if ($width<=0) return;
	for ($i = 0; $i < $width; $i++) {
		// top line
		imagettftext($im, $size, $angle, $x-$i, $y-$width, $color, $font, $text);
		imagettftext($im, $size, $angle, $x+$i, $y-$width, $color, $font, $text);
		// bottom line
		imagettftext($im, $size, $angle, $x-$i, $y+$width, $color, $font, $text);
		imagettftext($im, $size, $angle, $x+$i, $y+$width, $color, $font, $text);
		// left line
		imagettftext($im, $size, $angle, $x-$width, $y-$i, $color, $font, $text);
		imagettftext($im, $size, $angle, $x-$width, $y+$i, $color, $font, $text);
		// right line
		imagettftext($im, $size, $angle, $x+$width, $y-$i, $color, $font, $text);
		imagettftext($im, $size, $angle, $x+$width, $y+$i, $color, $font, $text);
	}
}

//init 0 (default values)
$settings[0]['fontfile'] = $fontfile;
$settings[0]['fontbold'] = 0;
$settings[0]['fontangle'] = 0;
$settings[0]['fontcolor'] = explode(',', '0,0,0');
$settings[0]['fontsize'] = 10;
$settings[0]['fontalign'] = "left";
$settings[0]['bordercolor'] = "0,0,0";
$settings[0]['bordersize'] = 0;


function loadsettings($subtag, $taglevel) {
	global $settings, $fontfolder;

	$settings[$taglevel] = $settings[$taglevel-1]; //copy from above tag and/or overwrite existing settings of tag before

		if (!isset($subtag)) return; //in case manually loaded global tag is not specified

		//font
		if (isset($subtag->font->file) && is_file($fontfolder."/".$subtag->font->file)) {
				$settings[$taglevel]['fontfile'] = $fontfolder."/".$subtag->font->file;
		}
		if (preg_match('/^[0-9]+,[0-9]+,[0-9]+$/', str_replace(" ", "", $subtag->font->bordercolor))) {
			$settings[$taglevel]['bordercolor'] = explode(',', $subtag->font->bordercolor);
		}
		if (isset($subtag->font->bordersize)) {
				$settings[$taglevel]['bordersize'] = $subtag->font->bordersize+0;
		}
		if (isset($subtag->font->bold)) {
				$settings[$taglevel]['fontbold'] = $subtag->font->bold+0;
		}
		if (isset($subtag->font->angle)) {
				$settings[$taglevel]['fontangle'] = $subtag->font->angle+0;
		}

		//color
		if (preg_match('/^[0-9]+,[0-9]+,[0-9]+$/', str_replace(" ", "", $subtag->font->color))) {
			$settings[$taglevel]['fontcolor'] = explode(',', $subtag->font->color);
		}

		//size
		if (isset($subtag->font->size) && 0<intval($subtag->font->size)) {
			$settings[$taglevel]['fontsize'] = $subtag->font->size+0;
		}
					
		//align
		if (isset($subtag->font->align)) {
			if ($subtag->font->align=="left" OR $subtag->font->align=="center" OR $subtag->font->align=="right") {
				$settings[$taglevel]['fontalign'] = "".$subtag->font->align;
			}
		}
}

function loadsubtag ($subtag, $tagkey, $taglevel) {
	global $settings;
	loadsettings($subtag, $taglevel);
	switch ($tagkey) {
		case "global": { return; } //do nothing ("ignore" global tag, because it's loaded manually)
		case "char": { loadcharacter($subtag, $taglevel+1); $stoploading=true; break; } //do_charbuild
		case "text": { loadtext($subtag, $taglevel+1); $stoploading=true; break; } // do_textbuild
		case "object": { insertobject($subtag, $taglevel+1); $stoploading=true; break; } //do_object
	}
	
	if (!$stoploading) {
		foreach ($subtag as $newsubkey => $newsubvalue) {
			loadsubtag($newsubvalue, "$newsubkey", $taglevel+1);
		}
	}
}

function loadcharacter($charactertag, $taglevel) {
	global $settings, $vcardslot, $charinfo, $flolang;

	if (!$charinfo[$vcardslot]) { $vcardslot++; return; }
	foreach ($charactertag as $charactertagkey => $charactertagvalue) {
		loadsettings($charactertagvalue, $taglevel);

		if (!preg_match('/^([0-9]+),([0-9]+)$/', $charactertagvalue->coords, $coords)) continue; //not be able to set text properly without coords
		unset($attributes, $text);
		foreach ($charactertagvalue->attributes() as $attributeskey => $attributesvalue) {
			$attributes[$attributeskey]="".$attributesvalue;
		}

		$textoffset = 0;
		$objectoffset = 0;

		//lvlland/levelsea
		if ($charactertagkey=="levelland" OR $charactertagkey=="levelsea") {
				if ($charactertagkey=="levelland") {
					$leveltext = $flolang->signature_pic_lvlland;
					$levelnumber = $charinfo[$vcardslot]['levelland'];
					$objectclass="symbol_land";
				}
				else {
					$leveltext = $flolang->signature_pic_lvlsea;
					$levelnumber = $charinfo[$vcardslot]['levelsea'];
					$objectclass="symbol_sea";
				}

					if ($attributes['class']) {
						switch ($attributes['class']) {
							case "symbol": {
								$text=$levelnumber;
								$textwerte = imagettfbbox ( $settings[$taglevel]['fontsize'], 0, $settings[$taglevel]['fontfile'], $text );
								$objectheight = $objectwidth= abs($textwerte['5'])+3;
								switch ($settings[$taglevel]['fontalign']) {
									case "left": {
										$textoffset=bcadd($objectwidth,3).",0";
										break;
									}
									case "right": {
										$textoffset="-".bcadd($objectwidth,3).",0";
										break;
									}
									case "center": {
										$objectoffset="-".bcadd(bcdiv($objectwidth,2),3).",0";
										$textoffset=bcadd(bcdiv($objectwidth,2),3).",0";
										break;
									}
								}
								$symbolclass = simplexml_load_string('<object class="'.$objectclass.'"><coords>'.$coords[1].','.$coords[2].'</coords><width>'.$objectwidth.'</width><height>'.$objectheight.'</height><align>'.$settings[$taglevel]['fontalign'].'</align></object>');
								insertobject($symbolclass, $taglevel+1, "", $objectoffset);
								break;
							}
							case "invisible": { $text=$levelnumber; break; }
							default: $text=$leveltext." ".$levelnumber;
						}
					}
					elseif ($attributes['text']=="invisible") $text=$levelnumber;
					else $text=$leveltext." ".$levelnumber;
		}
		else {
			switch ($charactertagkey) {
				case "name": {
					$text=$charinfo[$vcardslot]['charname'];
					break;
				}
				case "class": {
					$text=$charinfo[$vcardslot]['jobclass'];
					break;
				}
				case "server": {
					$text=$charinfo[$vcardslot]['server'];
					break;
				}
				case "guild": {
					$text=$charinfo[$vcardslot]['guild'];
					break;
				}
				case "object": {
					insertobject($charactertagvalue, $taglevel+1, $charinfo[$vcardslot]);
					continue;
				}
			}
		}
		writetext($text, $taglevel, $coords, $textoffset);
	}
	$vcardslot++;
}

function loadtext($texttag, $taglevel) {
	global $settings;
	if (!preg_match('/^([0-9]+),([0-9]+)$/', $texttag->coords, $coords)) return; //not be able to set text properly without coords
	if (!isset($texttag->value)) return; //if no text is given

	loadsettings($texttag, $taglevel);
	writetext($texttag->value, $taglevel, $coords);
}

function writetext($text="", $taglevel, $coords, $offset=0) {
	global $Teilgrafik, $settings;
	if (!strlen($text)) return;
	$textcolor = imagecolorallocate ($Teilgrafik, $settings[$taglevel]['fontcolor'][0], $settings[$taglevel]['fontcolor'][1], $settings[$taglevel]['fontcolor'][2]);

	//get fontsize hight //workaround with a default string for crazy fonts
//		$textwerte = imagettfbbox ( $settings[$taglevel]['fontsize'], 0, $settings[$taglevel]['fontfile'], " &nbsp; " );
//		$text_y = abs($textwerte['5']);
	// get fontsize width - now it's the normal text which will be written
		$textwerte = imagettfbbox ( $settings[$taglevel]['fontsize'], 0, $settings[$taglevel]['fontfile'], $text);
		$text_x = abs($textwerte['2']);
		$text_y = abs($textwerte['5']);

$coords[1]=intval($coords[1]);
$coords[2]=intval($coords[2]);
// echo "{$coords[1]}/{$coords[2]} ++ $text_x/$text_y ==> ";	
	//align font
		switch ($settings[$taglevel]['fontalign']) {
			case "right": { $coords[1] = imagesx($Teilgrafik)-$coords[1]-$text_x; break; }
			//case "left": { break; } //no need for
			case "center": { $coords[1] = $coords[1]-($text_x/2); break; }
		}
		$coords[2] = $coords[2]+$text_y/2;
	//offset
		if ($offset) {
			$offset = explode(",", $offset);
			$coords[1] += intval($offset[0]);
			$coords[2] += intval($offset[1]);
		}

	//final text
	if ($settings[$taglevel]['bordersize']>0) {
		$bordercolor = imagecolorallocate($Teilgrafik, $settings[$taglevel]['bordercolor'][0], $settings[$taglevel]['bordercolor'][1], $settings[$taglevel]['bordercolor'][2]);
		imagettfborder($Teilgrafik, $settings[$taglevel]['fontsize'], $settings[$taglevel]['fontangle'], $coords[1], $coords[2], $bordercolor, $settings[$taglevel]['fontfile'], $text, $settings[$taglevel]['bordersize']);
	}
	if ($settings[$taglevel]['fontbold']>0) {
		imagettfborder($Teilgrafik, $settings[$taglevel]['fontsize'], $settings[$taglevel]['fontangle'], $coords[1], $coords[2], $textcolor, $settings[$taglevel]['fontfile'], $text, $settings[$taglevel]['fontbold']);
	}
//  	echo "{$coords[1]},{$coords[2]} :: $text<br /><br />";
	imagettftext($Teilgrafik, $settings[$taglevel]['fontsize'], $settings[$taglevel]['fontangle'], $coords[1], $coords[2], $textcolor, $settings[$taglevel]['fontfile'], html_entity_decode($text, ENT_QUOTES, "UTF-8"));
}

function insertobject ($object, $taglevel, $charinfo=array(),  $offset=0) {
	global $settings, $cfg;
	if (!preg_match('/^([0-9]+),([0-9]+)$/', $object->coords, $coords)) return; //not be able to set object properly without coords

	loadsettings($object, $taglevel);
	foreach ($object->attributes() as $attributeskey => $attributesvalue) {
		$attributes[$attributeskey]="".$attributesvalue;
	}
	switch ($attributes['class']) {
		case "symbol_land": { $picture = "symbol_land"; break; }
		case "symbol_sea": { $picture = "symbol_sea"; break; }
		case "symbol_class": {
			if (!$charinfo['jobclass']) return;
			switch ($charinfo['jobclass']) {
				case "Saint": { $picture = "class_s"; break; }
				case "Shaman": { $picture = "class_s"; break; }
				case "Priest": { $picture = "class_s"; break; }

				case "Noble": { $picture = "class_n"; break; }
				case "Court Magician": { $picture = "class_n"; break; }
				case "Magic Knight": { $picture = "class_n"; break; }

				case "Mercenary": { $picture = "class_w"; break; }
				case "Guardian Swordsman": { $picture = "class_w"; break; }
				case "Gladiator": { $picture = "class_w"; break; }

				case "Explorer": { $picture = "class_e"; break; }
				case "Sniper": { $picture = "class_e"; break; }
				case "Excavator": { $picture = "class_e"; break; }
				default: return;
			}
			break;
		}
		default: return;
	}

	$picture = $cfg['signature_abs']."/objects/".$picture;
	if (!is_file($picture)) return;
	// load objectpicture
	global $Teilgrafik;
	$info = getimagesize ( $picture );
	if ($info[2] == 2 ) { //JPEG / JPG
		$objectpicture = imagecreatefromjpeg($picture);
	}
	elseif ($info[2] == 3) { //PNG
		$objectpicture = imagecreatefrompng($picture);
	}
	elseif ($info[2] == 1)  { // GIF
		$objectpicture = imagecreatefromgif($picture);
	}
	else { //BMP
		require_once($cfg['root_abs']."/convertbmp.php");
		$objectpicture = imagecreatefrombmp($picture);
	}

	//some global options (not in loadsettings()?)
// 	if (isset($object->transparency) && 0<intval($object->transparency)) {
// 		$transparency = $object->transparency+0;
// 	}
// 	else { $transparency = 100; }

	$width = imagesx($objectpicture);
	$height = imagesy($objectpicture);

	if (isset($object->height) && 0<intval($object->height)) {
		$height = $object->height+0;
		if (!$object->width) {
			//if only height set, resize in width
			$width = ceil(imagesx($objectpicture)*($height/imagesy($objectpicture)));
		}
	}

	if (isset($object->width) && 0<intval($object->width)) {
		$width = $object->width+0;
		if (!$object->height) {
			//if only width set, resize in height
			$height = ceil(imagesy($objectpicture)*($width/imagesx($objectpicture)));
		}
	}

	switch ($object->align) {
		case "right": { $coords[1] = imagesx($Teilgrafik)-$coords[1]-$width; break; }
		case "center": { $coords[1] = $coords[1]-($width/2); break; }
	}

	//offset
		if ($offset) {
			$offset = explode(",", $offset);
			$coords[1] += $offset[0];
			$coords[2] += $offset[1];
		}

	imagecopyresized($Teilgrafik, $objectpicture,$coords[1], $coords[2],0,0, $width,$height, imagesx($objectpicture), imagesy($objectpicture));

}

//init first tag
$vcardslot = 0; //begin with first slot
if (!is_array($charinfo)) $charinfo = array();
$charinfo = array_values($charinfo); //index it numerically
loadsettings($xml->global, 1); //global tag is first
loadsubtag($xml, "-", 2); //all others goes second+

header('Content-Type: image/png');
imagesavealpha($Teilgrafik, TRUE);
@unlink($cachefile);
imagepng($Teilgrafik, $cachefile);
imagepng($Teilgrafik);
imagedestroy($Teilgrafik);


?>
