<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

if (!$mybb->user['uid']) { $florensia->output_page($flouser->noaccess()); }

$flolang->load("signature");
$sep = "-";
$createfile=$cfg['signature_abs']."/create/".$mybb->user['uid'];

if (isset($_POST['layerupload']) && is_file($_FILES['sigupload']['tmp_name'])) {
	$upload=false;
				$sigpic = $_FILES['sigupload']['tmp_name'];
				if (imagetype($sigpic, 700, 700)) {
					if (copy($_FILES['sigupload']['tmp_name'], $createfile)) {
						chmod($createfile, 0755);
						$upload=true;
					}
				}
	if (!$upload) $florensia->notice($flolang->signature_create_backgroundupload_error_notice, "warning");
}


if ($template = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT template,characters FROM flobase_signaturetemp WHERE userid='".$mybb->user['uid']."'"))) {
	$template_exists = true;
}
if (isset($_POST['layerupload']) OR isset($_POST['refresh'])) {
	if (simplexml_load_string($_POST['signaturetemplate'], 'SimpleXMLElement', LIBXML_NOWARNING)) {
		if ($template_exists) {
			if (!MYSQL_QUERY("UPDATE flobase_signaturetemp SET template='".mysql_real_escape_string($_POST['signaturetemplate'])."' WHERE userid='".$mybb->user['uid']."'")) {
				$florensia->notice($flolang->signature_create_savetemplate_error_notice, "warning");
				$template_exists = false;
			}
		}
		else {
			if (!MYSQL_QUERY("INSERT INTO flobase_signaturetemp (template, userid) VALUES('".mysql_real_escape_string($_POST['signaturetemplate'])."','".$mybb->user['uid']."')")) {
				$florensia->notice($flolang->signature_create_savetemplate_error_notice, "warning");
				$template_exists = false;
			}
		}
		//rehash
		if ($template = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT template,characters FROM flobase_signaturetemp WHERE userid='".$mybb->user['uid']."'"))) {
			$template_exists = true;
		}
	}
	else {
		$florensia->notice($flolang->signature_create_template_error_notice, "warning");
		$template_exists = true;
		$template['template'] = $_POST['signaturetemplate'];
	}
}

if ($template_exists==false) {
	$template['template'] = "<xml>\n&nbsp;<global>\n&nbsp;</global>\n</xml>";
}

/*
 * languageselection
 */

if ($flolang->lang[$_POST['siglang']]) $selected[$_POST['siglang']] = "selected='selected'";
foreach ($flolang->lang as $langkey => $langvalue) {
	if (!$flolang->lang[$langkey]->visible_flag) continue;
	$siglangselect .= "<option value='$langkey' ".$selected[$langkey].">".$flolang->lang[$langkey]->languagename."</option>";
}
$siglangselect = "
	<table><tr>
		<td style='width:100px;'>{$flolang->signature_selectlang_title}</td>
		<td>
			<select name='siglang'>
				<option value='0'>{$flolang->signature_selectlang_default}</option>
				$siglangselect
			</select>
		</td>
	</tr></table>
";

if (is_file($createfile)) {

	//get characters and slots
	try {
		$xml = simplexml_load_string($template['template'], 'SimpleXMLElement', LIBXML_NOWARNING);
	} catch (Exception $e) {
		$xml = false;
	}
	if ($xml) {
		if (isset($_POST['layerupload']) OR isset($_POST['refresh'])) {
			for ($i=count($xml->char); $i>=0; $i--) {
				if ($create_slots OR $_POST['slot_'.$i]!="") {
					//-1 because array begins with 0
					if ($_POST['slot_'.$i]=="")  $_POST['slot_'.$i]="0";
					$create_slots[bcsub($i,1)]=$_POST['slot_'.$i];
				}
			}
			if (is_array($create_slots)) {
				$create_slots = array_reverse($create_slots);
				$sigchars = join($sep, $create_slots);
				
				//save chars
				MYSQL_QUERY("UPDATE flobase_signaturetemp SET characters='".mysql_real_escape_string($sigchars)."' WHERE userid='".$mybb->user['uid']."'");
			}
		}
		else {
			$saved_chars = explode($sep, $template['characters']);
			$sigchars = join($sep, $saved_chars);
			foreach ($saved_chars as $i => $charname) {
				$_POST['slot_'.$i]=$charname;
			}
		}

		unset($charselect);
		for ($i=0; $i<count($xml->char); $i++) {
			if (strlen($_POST['slot_'.$i])) {
				$characterslot = new class_character($_POST['slot_'.$i]);
				if (!$characterslot->is_valid()) $errorcharnotice = $characterslot->get_errormsg();
				else unset($errorcharnotice);
				if ($errorcharnotice) $errorcharnotice = " <span class='small' style='font-weight:normal;'><span style='color:#FF0000;'>(</span>{$errorcharnotice}<span style='color:#FF0000;'>)</span></span>";
			} else unset($errorcharnotice);
			$charselect .= "<tr><td style='width:100px;'>".$flolang->sprintf($flolang->signature_slots, $i+1).":</td><td><input type='text' name='slot_{$i}' value='".$florensia->escape($_POST['slot_'.$i])."' maxlength='255' style='width:200px;'>{$errorcharnotice}</td></tr>";
		}
		if (count($xml->char)==0) $charselect .= "<tr><td colspan='2' class='small'>{$flolang->signature_create_characterslots_notice}</tr>";
		$charselect = "<div class='bordered' style='margin-top:10px;'><table>$charselect<tr><td></td><td><input type='Submit' name='refresh' value='{$flolang->signature_create_refresh}'></td></tr></table></div>";
	}
	else {
		$charselect = "<div class='warning' style='margin-top:10px;'><span class='small'>{$flolang->signature_create_characterslots_templateerror_notice}</span></div>";
	}


	/*	language	*/
	if (!$flolang->lang[$_POST['siglang']]) $siglang = "0";
	else $siglang = $_POST['siglang'];

	$signatureupload = "
			<div class='bordered' style='padding:5px; text-align:center;'>
				<table style='position:relative; margin:auto;'><tr><td>
				<img name='imgMap' style='position:relative; z-index:1;' src='".$florensia->signatureurl.$siglang.$sep."t".$mybb->user['uid'].$sep.urlencode($sigchars).".png?".date("U")."' onClick='getCords()' alt='Signature' border='0'>";
				//<img name='crosshair' style='position:absolute; left:-5px; top:-5px; z-index:2; display:none' src='signature/sigcreatecrosshair.png' onClick='getCords()' alt='+'><br />
			$signatureupload.="</td></tr></table>
				<span class='small'>{$flolang->signature_create_getcoordinates} x,y:<input name='rxy' value='0,0' size='6'></span>
			</div>
			<div style='margin-bottom:5px;' class='bordered'>
				<table class='small' style='width:100%'>
					<tr><td style='width:120px;'>{$flolang->signature_create_bcccode}</td><td><input type='text' readonly='readonly' style='width:98%' value='[url=http://www.florensia-base.com/characterdetails/".urlencode($sigchars)."][img]http://signature.florensia-base.com/".$siglang.$sep."t".$mybb->user['uid'].$sep.urlencode($sigchars).".png[/img][/url]'></td></tr>
					<tr><td style='width:120px;'>{$flolang->signature_create_htmlcode}</td><td><input type='text' readonly='readonly' style='width:98%' value='".$florensia->escape('<a href="http://www.florensia-base.com/characterdetails/'.urlencode($sigchars).'"><img src="http://signature.florensia-base.com/'.$siglang.$sep."t".$mybb->user['uid'].$sep.urlencode($sigchars).'.png" alt="Signature" border="0"></a>')."'></td></tr>
					<tr><td></td><td class='small'>{$flolang->signature_create_signaturecode}</td></tr>
				</table>
			</div>
	";
			$javascript['signaturecreate']='
			<script type="text/javascript">
			
						function setCross(cx,cy) {';						
// 							document.images.crosshair.style.left = cx-10 + "px";
// 							document.images.crosshair.style.top = cy-10 + "px";
// 							document.images.crosshair.style.display = "";
			$javascript['signaturecreate'] .='
							rxy = ""+ x +"," + y;
							lxy = document.images.imgMap.width - x;
							lxy = lxy + "," + y;
							document.getElementsByName("rxy")[0].value = rxy;
							document.getElementsByName("lxy")[0].value = lxy;
							document.images.crosshair.style.display = "";
						}
										
						function setCords(e) {
							x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX;
							y = (document.all) ? window.event.y + document.body.scrollTop : e.pageY;
										
							x -= document.images.imgMap.offsetParent.offsetLeft;
							y -= document.images.imgMap.offsetParent.offsetTop;
						}
									
						function getCords() {
							if (x>0 && y>0) setCross(x,y);
						}
			
						document.onmousemove = setCords;
			</script>
			';
}

/*	font-helper	*/
			//fontselect
			$files = scandir($florensia->fonts_abs, 0);
			foreach ($files as $file) {
				if (preg_match("/^([^\.]+)\.ttf$/i",$file, $tffpicture)) {
					if (!is_file($florensia->signature_abs."/fonts/".$tffpicture[1].".png")) {
						$grafik = imagecreatetruecolor(250, 30);
						$colourwhite = imagecolorallocate($grafik, 73,111,150);
						imagefilledrectangle($grafik, 0, 0, 249, 29, $colourwhite);
						imagecolortransparent($grafik, $colourwhite);

						$color = imagecolorallocate ($grafik, 255, 255, 255);
						imagettftext($grafik, 14, 0, 0, 28, $color, $florensia->fonts_abs."/$file", "ABCDEF abcdef 123456");
						imagepng($grafik, $florensia->signature_abs."/fonts/".$tffpicture[1].".png");
					}
					$fonts .= "
						<tr>
							<td width='250'><img src='{$florensia->signature_rel}/fonts/".$tffpicture[1].".png' border='0' alt='".$tffpicture[1]."'></td>
							<td class='small' style='vertical-align:middle; text-align:right;'><input type='text' readonly='readonly' style='width:95%%' value='$file'></td>
						</tr>";
				}
			}


/*	objects	*/
	$signatureobjectsquery = MYSQL_QUERY("SELECT objectname, wheretouse, mandatorytags, optionaltags FROM flobase_signatureobjects");
	while ($signatureobjects = MYSQL_FETCH_ARRAY($signatureobjectsquery)) {
		unset($mandatorytags, $optionaltags);

		foreach (explode("\n", $signatureobjects['mandatorytags']) as $tags) {
			$mandatorytags .= $florensia->escape($florensia->escape($signatureobjects['mandatorytags']), TRUE)."<br />";
		}
		foreach (explode("\n", $signatureobjects['optionaltags']) as $tags) {
			$optionaltags .= $florensia->escape($florensia->escape($signatureobjects['optionaltags']), TRUE)."<br />";
		}

		$object[$signatureobjects['objectname']] = "
			<div style='width:300px;'>
				<div class='small shortinfo_".$florensia->change()."'>{$signatureobjects['objectname']}</div>
				<div class='small'>
					<table style='width:100%'>
						<tr>
							<td style='width:100px;'>{$flolang->signature_create_popup_wheretouse}</td>
							<td>".$florensia->escape($florensia->escape($signatureobjects['wheretouse']), TRUE)."</td>
						</tr>
						<tr>
							<td style='width:100px;'>{$flolang->signature_create_popup_madatorytags}</td>
							<td>$mandatorytags</td>
						</tr>
						<tr>
							<td style='width:100px;'>{$flolang->signature_create_popup_optionaltags}</td>
							<td>$optionaltags</td>
						</tr>
					</table>
				</div>
			</div>
		";
	}
$florensia->notice("<div class='warning' style='margin-bottom:10px;'>{$flolang->signature_api_failnotice}</div>");
$content = $javascript['signaturecreate']."
<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post' enctype='multipart/form-data'>
	<div class='bordered' style='margin-top:10px; margin-bottom:8px;'><span class='small'>
		".str_replace('http://florensia-forumurl', $florensia->forumurl, $flolang->signature_create_pagedescription)."
	</span></div>
	$signatureupload
	<div class='bordered' style='text-align:center; margin-top:10px;'>{$flolang->signature_create_backgroundupload} <input type='file' name='sigupload'>&nbsp;<input type='Submit' name='layerupload' value='{$flolang->signature_create_refresh}'><br />
		<span class='small'>{$flolang->signature_create_backgroundupload_data}</span>
	</div>
	<div class='bordered' style='margin-top:10px;'>$siglangselect</div>
	$charselect
	<div style='margin-top:10px;'>
		<table style='width:100%;'>
			<tr><td class='bordered' style='vertical-align:top;'>
				<table style='width:100%'>
					<tr>
						<td class='subtitle'>{$flolang->signature_create_template_title}</td>
					</tr>
					<tr><td style='height:3px;' colspan='2'></td></tr>
					<tr>
						<td style='padding:5px; vertical-align:top;' rowspan='3'><input type='Submit' name='refresh' value='{$flolang->signature_create_refresh}'><br /><textarea name='signaturetemplate' rows='".count(explode("\n", $template['template']))."' style='width:100%;'>".$florensia->escape($template['template'])."</textarea><br /><input type='Submit' name='refresh' value='{$flolang->signature_create_refresh}'></td>
					</tr>
				</table>
			</td>
			<td style='width:3px;'></td>
			<td style='width:350px; vertical-align:top;' class='bordered'>
				<table style='width:100%;'>
					<tr>
						<td class='subtitle'>{$flolang->signature_create_colorpicker_title}</td>
					</tr>
					<tr>
						<td style='vertical-align:top; text-align:center;'><iframe src='signature/colorpicker/index.html' frameborder='0' style='height:350px; width:350px; overflow:hidden'></iframe></td>
					</tr>
					<tr><td style='height:3px;' colspan='2'></td></tr>

					<tr><td class='subtitle'>{$flolang->signature_create_additionalobjects_title}<br /><span style='font-weight:normal' class='small'>".$florensia->escape('<object class="OBJECTNAME"></object>'). "<br />{$flolang->signature_create_additionalobjects_notice}</span></td></tr>
					<tr><td style='vertical-align:top; text-align:center;'>
						<table style='width:90%'>
							<tr><td style='width:50%; text-align:center; padding-bottom:10px;'>
								<img src='{$florensia->signature_rel}/objects/symbol_land' border='0' ".popup($object['symbol_land']).">
								</td><td style='text-align:center;'>
								<img src='{$florensia->signature_rel}/objects/symbol_sea' border='0' ".popup($object['symbol_sea']).">
							</td></tr>
							<tr><td style='width:50%; text-align:center;'>
								<img src='{$florensia->signature_rel}/objects/class_n' style='width:25px;' border='0' ".popup($object['symbol_class']).">
								</td><td style='text-align:center;'>&nbsp;&nbsp;
							</td></tr>
						</table>
					</td></tr>
					<tr><td style='height:10px;' colspan='2'></td></tr>

					<tr><td class='subtitle'>{$flolang->signature_create_fonthelper_title}</td></tr>
					<tr><td style='vertical-align:top; text-align:center;'><table>$fonts</table></td></tr>
				</table>
			</td></tr>
		</table>
	</div>
</form>

";

$florensia->sitetitle("Personal Signature Generator");
$florensia->output_page($content);

?>
