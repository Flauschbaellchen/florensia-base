<?PHP

if (!preg_match('/(noxx|florensia-base\.com)/i', $_SERVER['HTTP_REFERER'])) die;
require_once("./confg.php");

if (isset($_GET['picture'])) {
	switch ($_GET['picture']) {
		case "monster": {
			$width = 50;
			$height = 50;
				$pic = imagecreatetruecolor($width, $height);
				$colourbackground = imagecolorallocate($pic, 55,101,138);
				imagefilledrectangle($pic, 0, 0, $width,  $height, $colourbackground);
				imagecolortransparent($pic, $colourbackground);
			if (isset($_GET['monsterid'])) {
				$mainpicture=true;
				if (is_file($cfg['images_abs'].'/'.$_GET['monsterid'].'.png')) {
					$img = imagecreatefrompng($cfg['images_abs'].'/'.$_GET['monsterid'].'.png');
					imagecopy($pic,$img,0,0,0,0,$width,$height);
					$watermark = @imagecreatefromgif($cfg['layer_abs']."/watermark_small.gif");
					@imagecopymerge($pic,$watermark,bcsub(imagesx($pic),imagesx($watermark)),bcsub(imagesy($pic),bcadd(imagesy($watermark),1)),0,0,imagesx($watermark),imagesy($watermark),25);
					imagedestroy($img);
				}
			}
		break;
		}
		case "item": {
			$width = 32;
			$height = 32;
			if (isset($_GET['itemid'])) {
				$mainpicture=true;
				$code = $_GET['itemid'];
				$img = imagecreatefrompng($cfg['images_abs'].'/'.substr($code,0,3).'.png');
				$id = intval(substr($code,3));
				$row = floor($id / 16)+1;
				$col = $id - (($row - 1) * 16) + 1;
				$x = ($col - 1) * $height;
				$y = ($row - 1) * $width;
				$pic = imagecreatetruecolor($width,$height);
				imagecopy($pic,$img,0,0,$x,$y,$width,$height);
				$watermark = @imagecreatefromgif($cfg['layer_abs']."/watermark_small.gif");
				@imagecopymerge($pic,$watermark,bcsub(imagesx($pic),imagesx($watermark)),bcsub(imagesy($pic),bcadd(imagesy($watermark),1)),0,0,imagesx($watermark),imagesy($watermark),25);
			}
			else {
				$pic = imagecreatetruecolor($width, $height);
				$colourwhite = imagecolorallocate($pic, 55,101,138);
				imagefilledrectangle($pic, 0, 0, $width+1,  $height, $colourwhite);
				imagecolortransparent($pic, $colourwhite);
			}
		break;
		}
		case "skill": {
			$width = 24;
			$height = 24;
			if (isset($_GET['skillid'])) {
				$mainpicture=true;
				$code = $_GET['skillid'];
				$img = imagecreatefrompng($cfg['images_abs'].'/'.substr($code,0,3).'24.png');
				$id = intval(substr($code,3));
				$row = floor($id / 16)+1;
				$col = $id - (($row - 1) * 16) + 1;
				$x = ($col - 1) * $height;
				$y = ($row - 1) * $width;
				$pic = imagecreatetruecolor($width,$height);
				imagecopy($pic,$img,0,0,$x,$y,$width,$height);
				$watermark = @imagecreatefromgif($cfg['layer_abs']."/watermark_small.gif");
				@imagecopymerge($pic,$watermark,bcsub(imagesx($pic),imagesx($watermark)),bcsub(imagesy($pic),bcadd(imagesy($watermark),1)),0,0,imagesx($watermark),imagesy($watermark),25);
			}
			else {
				$pic = imagecreatetruecolor($width, $height);
				$colourwhite = imagecolorallocate($pic, 55,101,138);
				imagefilledrectangle($pic, 0, 0, $width+1,  $height, $colourwhite);
				imagecolortransparent($pic, $colourwhite);
			}
		break;
		}
		case "skillbig": {
			$width = 32;
			$height = 32;
			if (isset($_GET['skillbigid'])) {
				$mainpicture=true;
				$code = $_GET['skillbigid'];
				$img = imagecreatefrompng($cfg['images_abs'].'/'.substr($code,0,3).'.png');
				$id = intval(substr($code,3));
				$row = floor($id / 16)+1;
				$col = $id - (($row - 1) * 16) + 1;
				$x = ($col - 1) * $height;
				$y = ($row - 1) * $width;
				$pic = imagecreatetruecolor($width,$height);
				imagecopy($pic,$img,0,0,$x,$y,$width,$height);
				$watermark = @imagecreatefromgif($cfg['layer_abs']."/watermark_small.gif");
				@imagecopymerge($pic,$watermark,bcsub(imagesx($pic),imagesx($watermark)),bcsub(imagesy($pic),bcadd(imagesy($watermark),1)),0,0,imagesx($watermark),imagesy($watermark),25);
			}
			else {
				$pic = imagecreatetruecolor($width, $height);
				$colourwhite = imagecolorallocate($pic, 55,101,138);
				imagefilledrectangle($pic, 0, 0, $width+1,  $height, $colourwhite);
				imagecolortransparent($pic, $colourwhite);
			}
		break;
		}
		case "skilltree": {
			//fensterrand sieht scheisse aus...
			$skipborder_width=16;
			$skipborder_height=16;
			//--
			$width=531-$skipborder_width*2;
			$height=667-$skipborder_height*2;

			$pic = imagecreatetruecolor($width, $height);
			$colourbackground = imagecolorallocate($pic, 55,101,138);
			imagefilledrectangle($pic, 0, 0, $width,  $height, $colourbackground);
			imagecolortransparent($pic, $colourbackground);

			if (isset($_GET['skilltreeid'])) {

				$db = MYSQL_CONNECT($cfg['dbhost'], $cfg['dbuser'], $cfg['dbpasswd']) or die("No Database connection");
				MYSQL_SELECT_DB($cfg['dbname'], $db) or die("No Database connection");
				MYSQL_QUERY("SET NAMES 'utf8'");
/*
				if ($_GET['skilltreeid']=="UID_SEA_COMBATSKILL_TREE") $content = "sea";
				else $content = "land";
				$queryrectinfolist = MYSQL_QUERY("SELECT xmltree FROM server_skilltrees WHERE skilltreeid='rectinfolist_$content' LIMIT 1");
				$rectinfolist = MYSQL_FETCH_ARRAY($queryrectinfolist);
				$rectinfolist = explode(",",$rectinfolist['xmltree']);
*/
				$queryskilltree = MYSQL_QUERY("SELECT xmltree, rectinfostart FROM server_skilltrees WHERE skilltreeid='".mysql_real_escape_string($_GET['skilltreeid'])."' LIMIT 1");
				if ($skilltree = MYSQL_FETCH_ARRAY($queryskilltree)) {
					$xmlskill = simplexml_load_string($skilltree['xmltree']);

/*
				$queryskilltree = MYSQL_QUERY("SELECT xmltree FROM server_skilltrees WHERE skilltreeid='".mysql_real_escape_string($_GET['skilltreeid'])."' LIMIT 1");
				if ($skilltree = MYSQL_FETCH_ARRAY($queryskilltree)) {
					$xmlskill = simplexml_load_string($skilltree['xmltree']);

					//loading rectinfo-line
					$queryrectinfolist = MYSQL_QUERY("SELECT xmltree FROM server_skilltrees WHERE skilltreeid='rectinfolist' LIMIT 1");
					$rectinfolist = MYSQL_FETCH_ARRAY($queryrectinfolist);
					$rectinfolist = simplexml_load_string($rectinfolist['xmltree']);

					//loading needed subfile for rectinfolist
					$queryrectinfofile = MYSQL_QUERY("SELECT rectinfolist FROM flobase_landclass WHERE skilltree='".mysql_real_escape_string($_GET['skilltreeid'])."' LIMIT 1");
					$rectinfofile = MYSQL_FETCH_ARRAY($queryrectinfofile);

					$rectinfolist = explode(" ",$rectinfolist->$rectinfofile['rectinfolist']);

*/
					// $maincoords = explode(',', $xmlskill->treeid->coords);
					// $width=$maincoords[2];
					// $height=$maincoords[3];
						$mainpicture=true;
	
						$img = imagecreatefrompng($cfg['images_abs']."/skill.png");
						imagecopy($pic,$img,0,0,$skipborder_width,$skipborder_height,$width,$height);
						//tabs
						imagecopy($pic,$img,49-16,50-16,620,15,70,30);
						imagecopy($pic,$img,120-16,50-16,620,15,70,30);
						imagecopy($pic,$img,191-16,50-16,620,15,70,30);

						//fensterrahmen vom tab
						$skipborder_width=33-$skipborder_width;
						$skipborder_height=77-$skipborder_height;

						foreach ($xmlskill->insertpic as $insertpic) {
							$img = imagecreatefrompng($cfg['images_abs']."/".trim($insertpic->name));
							$imgcoords = explode(',', trim($insertpic->coords));
							$imgbordercoords = explode(',', trim($insertpic->bordercoords));
							imagecopy($pic,$img,$imgbordercoords[0]+$skipborder_width,$imgbordercoords[1]+$skipborder_height,$imgcoords[0],$imgcoords[1],$imgcoords[2],$imgcoords[3]);
						}


						//skillpics
						//warrior 7 / 0 //ck003900
						//exp 44 / 37 (-7=30) //ck000700
						//noble 73 / 66 (-7=59) //cp002300
						//saint 103 / 96 (-7=89) //cp003100
/*
 						$i=bcadd($skilltree['rectinfostart'],7);
 						if ($_GET['skilltreeid']=="UID_SEA_COMBATSKILL_TREE" OR $_GET['skilltreeid']=="UID_WARRIOR_COMBATSKILL_TREE") $i=0;

						$watermark = @imagecreatefromgif($cfg['layer_abs']."/watermark_small.gif");
						foreach ($xmlskill->rectinfo as $skillpic) {

							if (preg_match('/^ck/',$rectinfolist[$i]) OR preg_match('/^sk/',$rectinfolist[$i])) $dbselect="skill";
							else $dbselect="spell";

							$queryskillpiccode = MYSQL_QUERY("SELECT * FROM server_$dbselect WHERE skill_코드='".$rectinfolist[$i]."'");
							$skillpiccode = MYSQL_FETCH_ARRAY($queryskillpiccode);
							$code=$skillpiccode['skill_picture'];
							$width = 32;
							$height = 32;

							$skillpic = explode(',', $skillpic);
							//workaround fuer see-content -.-
							if ($_GET['skilltreeid']=="UID_SEA_COMBATSKILL_TREE") {
								$skillpic[0] = $skillpic[0]+$imgbordercoords[0];
								$skillpic[1] = $skillpic[1]+$imgbordercoords[1];
							}
								$img = @imagecreatefrompng($cfg['images_abs'].'/'.substr($code,0,3).'.png');
								$id = intval(substr($code,3));
								$row = floor($id / 16)+1;
								$col = $id - (($row - 1) * 16) + 1;
								$x = ($col - 1) * $height;
								$y = ($row - 1) * $width;

								@imagecopy($pic,$img,$skillpic[0]+$skipborder_width,$skillpic[1]+$skipborder_height,$x,$y,$width,$height);
								@imagecopymerge($pic,$watermark,$skillpic[0]+$skipborder_width+17,$skillpic[1]+$skipborder_height+18,0,0,imagesx($watermark),imagesy($watermark),25);
							$i++;
						}
*/
						@imagedestroy($img);
					}
			}
		break;
		}
		case "itemcharacter":
		case "character": {
			if ($_GET['picture']=="itemcharacter") { $subfolder="itemcharacter"; $pictureid = $_GET['itemcharacterid']; }
			else { $subfolder = "character"; $pictureid = $_GET['characterid']; }

			$width = 250;
			$height = 350;
			$fontsize=14;
			$color[0]=140;
			$color[1]=224;
			$color[2]=255;
			$x=60;
			$y=280;
			$angle=55;
				$pic = imagecreatetruecolor($width, $height);
				$colourbackground = imagecolorallocate($pic, 55,101,138);
				imagefilledrectangle($pic, 0, 0, $width,  $height, $colourbackground);
				imagecolortransparent($pic, $colourbackground);
			if (isset($pictureid) && is_file($cfg['pictures_abs']."/{$subfolder}/{$pictureid}.gif")) {
				$mainpicture=true;
				$img = imagecreatefromgif($cfg['pictures_abs']."/{$subfolder}/{$pictureid}.gif");

				$top = bcdiv(bcsub(imagesy($img),$height),2);
				imagecopy($pic,$img,0,-$top,0,0,imagesx($img),imagesy($img));

				$watermark = @imagecreatefromgif($cfg['layer_abs']."/watermark_character.gif");
				@imagecopymerge($pic,$watermark,0,0,0,0,250,350,20);

        			imagedestroy($img);
			}
		break;
		}
		case "map": {
			$width = 512;
			$height = 512;

			$fontsize=14;
			$color[0]=140;
			$color[1]=224;
			$color[2]=255;
			$x=60;
			$y=280;
			$angle=55;

				$pic = imagecreatetruecolor($width, $height);
				$colourbackground = imagecolorallocate($pic, 55,101,138);
				imagefilledrectangle($pic, 0, 0, $width,  $height, $colourbackground);
				imagecolortransparent($pic, $colourbackground);
			if (isset($_GET['mapid'])) {
				$mainpicture=true;
				$db = MYSQL_CONNECT($cfg['dbhost'], $cfg['dbuser'], $cfg['dbpasswd']) or die("No Database connection");
				MYSQL_SELECT_DB($cfg['dbname'], $db) or die("No Database connection");
				MYSQL_QUERY("SET NAMES 'utf8'");

				$querymap = MYSQL_QUERY("SELECT mapid, mappicture, LTWH FROM server_map WHERE mapid='".mysql_real_escape_string($_GET['mapid'])."'");
				if ($map = MYSQL_FETCH_ARRAY($querymap)) {
					$mappicture = $cfg['images_abs']."/".strtolower($map['mappicture']."_Minimap_En.png");
					if (is_file($mappicture) && is_file("{$cfg['root_abs']}/convertbmp.php")) {
						//require_once("{$cfg['root_abs']}/convertbmp.php");
						$img = imagecreatefrompng($mappicture);
		
						$top = bcdiv(bcsub(imagesy($img),$height),2);
						imagecopy($pic,$img,0,-$top,0,0,imagesx($img),imagesy($img));

						//monstercoord
						if (preg_match('/^[a-z0-9,]+$/i', $_GET['npcs'])) {
							$y_count = 0;
							$y_all=0;
							$npc_count = 0;

							$LTWH = explode(",", $map['LTWH']);
							$MapInfoLeft = $LTWH[0];
							$MapInfoTop = $LTWH[1];
							$MapInfoWidth = $LTWH[2];
							$MapInfoHeight = $LTWH[3];

							$npclocator = imagecreatefrompng($cfg['layer_abs']."/MiniMapNpc.png");
							$fillcolorblack = imagecolorallocate($npclocator, 0,0,0);
							imagefilledarc($npclocator, 4, 4, 7, 7, 0, 360, $fillcolorblack, IMG_ARC_PIE);

							foreach (explode(",", $_GET['npcs']) as $npcid) {
								$querynpccoords = MYSQL_QUERY("SELECT coordinates FROM flobase_npc_coordinates WHERE mapid='".$map['mapid']."' AND npcid='".mysql_real_escape_string($npcid)."'");
								if ($npccoords = MYSQL_FETCH_ARRAY($querynpccoords)) {
									if ($cfg['maptoolcolors'][$npc_count]) {
										$colors = explode(",",$cfg['maptoolcolors'][$npc_count]);
										imagefilledarc($npclocator, 4, 4, 5, 5, 0, 360, imagecolorallocate($npclocator, $colors[0],$colors[1],$colors[2]), IMG_ARC_PIE);
									}

									$npccoords['coordinates'] = explode(";", $npccoords['coordinates']);
									foreach ($npccoords['coordinates'] as $coordsvalue) {
										$coordsvalue = explode(",", $coordsvalue);
										$x = $coordsvalue[0];
										$y = $coordsvalue[1];
	
										$x = ($x-$MapInfoLeft)/$MapInfoWidth*512;
										$y = ($MapInfoTop-$y)/$MapInfoHeight*512;
										imagecopy($pic,$npclocator,$x-4,$y-4,0,0,8,8);
										$y_all +=$y;
										$y_count++;
									}

									$npc_count++;
								}
							}
							if ($y_count) $y_average = $y_all/$y_count;
						//monstercoord_end
						}

						$watermark = @imagecreatefromgif($cfg['layer_abs']."/watermark_character.gif");
						@imagecopymerge($pic,$watermark,0,0,0,0,250,350,25);
						@imagecopymerge($pic,$watermark,131,81,0,0,250,350,25);
						@imagecopymerge($pic,$watermark,262,162,0,0,250,350,25);
						imagedestroy($img);
					}
				}
			}
		break;
		}
	}
	if (isset($pic)) {

		if (isset($_GET['width']) && is_numeric($_GET['width']) && isset($_GET['height']) && is_numeric($_GET['height'])) {
			$final = imagecreatetruecolor($_GET['width'], $_GET['height']);
			if ($mainpicture!==true) {
				$colourbackground = imagecolorallocate($final, 55,101,138);
				imagefilledrectangle($final, 0, 0, $_GET['width'], $_GET['height'], $colourbackground);
				imagecolortransparent($final, $colourbackground);
			}
			else imagecopyresampled($final,$pic,0,0,0,0,$_GET['width'], $_GET['height'],imagesx($pic),imagesy($pic));
			$pic = $final;
		}
		elseif (isset($_GET['locatorheight']) && is_numeric($_GET['locatorheight'])) {
			$y_min = $y_average-$_GET['locatorheight']/2;
			$y_max = $y_average+$_GET['locatorheight']/2;
			if ($y_max>512) {
				$diff = $y_max-512;
				$y_min -= $diff;
				$y_max = 512;
			}
			elseif ($y_min<0) {
				$y_max += abs($y_min);
				$y_min = 0;
			}
			$final = imagecreatetruecolor(imagesx($pic), $_GET['locatorheight']);
			if ($mainpicture!==true) {
				$colourbackground = imagecolorallocate($final, 55,101,138);
				imagefilledrectangle($final, 0, 0, imagesx($final), imagesy($final), $colourbackground);
				imagecolortransparent($final, $colourbackground);
			}
			else imagecopy($final,$pic,0,0,0,$y_min,imagesx($pic),$_GET['locatorheight']);			
			$pic = $final;
		}
		else {
			if (isset($_GET['maxwidth']) && is_numeric($_GET['maxwidth']) && imagesx($pic)>$_GET['maxwidth']) {
				$multi = bcdiv($_GET['maxwidth'],imagesx($pic),5);
				$newheight = bcmul(imagesy($pic),$multi);
				$final = imagecreatetruecolor($_GET['maxwidth'], $newheight);
				if ($mainpicture!==true) {
					$colourbackground = imagecolorallocate($final, 55,101,138);
					imagefilledrectangle($final, 0, 0, $_GET['maxwidth'], $newheight, $colourbackground);
					imagecolortransparent($final, $colourbackground);
				}
				else	imagecopyresampled($final,$pic,0,0,0,0,$_GET['maxwidth'], $newheight,imagesx($pic),imagesy($pic));
				$pic = $final;
			}
			if (isset($_GET['maxheight']) && is_numeric($_GET['maxheight']) && imagesy($pic)>$_GET['maxheight']) {
				$multi = bcdiv($_GET['maxheight'],imagesy($pic),5);
				$newwidth= bcmul(imagesx($pic),$multi);
				$final = imagecreatetruecolor($newwidth, $_GET['maxheight']);
				if ($mainpicture!==true) {
					$colourbackground = imagecolorallocate($final, 55,101,138);
					imagefilledrectangle($final, 0, 0, $newwidth, $_GET['maxheight'], $colourbackground);
					imagecolortransparent($final, $colourbackground);
				}
				else imagecopyresampled($final,$pic,0,0,0,0,$newwidth, $_GET['maxheight'],imagesx($pic),imagesy($pic));
				$pic = $final;
			}
		}

		if ($mainpicture!==true) { 
			header("Content-type: image/gif");
 			imagegif($pic);
		}
		else {
			header("Content-type: image/png");
			//imagepng($pic, "/home/noxx/Desktop/".$_GET['skillbigid'].".png");
			imagepng($pic);
		}
		imagedestroy($pic);
	}
}
?>
