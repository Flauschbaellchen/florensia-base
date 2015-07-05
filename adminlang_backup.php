<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("access_admincp") OR !$flouser->get_permission("mod_language")) { $florensia->output_page($flouser->noaccess()); }

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Languages");

$dbtables = array('flobase_class_columns'=>'id',
'flobase_item_categories'=>'id',
'flobase_item_columns'=>'id',
'flobase_item_effect'=>'id',
'flobase_item_types'=>'itemtypeid',
'flobase_npc_columns'=>'id',
'flobase_skill_columns'=>'id',
'flobase_landclass'=>'classid',
'flobase_seaclass'=>'classid',
'flobase_seal_optionlang'=>'sealid',
'flobase_menubar'=>'id');

/*
$lang="tr";
$copylang = "en";

$error=false;
$content = "<div class='subtitle'>Adding new language \"$lang\"...</div>";

$content .= "<div class='bordered'>Working on DB...</div>";
foreach ($dbtables as $dbtable => $copykey) {
	$content .= "<div>$dbtable with $copykey</div>";
	MYSQL_QUERY("ALTER TABLE $dbtable ADD name_{$lang} TEXT NOT NULL;");
		$querycopy = MYSQL_QUERY("SELECT $copykey, name_{$copylang} FROM $dbtable");
		while ($copy = MYSQL_FETCH_ARRAY($querycopy)) {
			if (!($update = MYSQL_QUERY("UPDATE $dbtable SET name_{$lang}='".mysql_real_escape_string($copy['name_'.$copylang])."' WHERE $copykey='".$copy[$copykey]."'"))) {
				$content .= "<divclass='warning'>$dbtable: ".MYSQL_ERROR()."</div>";
				$error=true;
			}
		}
}

if (!$error) $content .= "<div style='margin-top:10px;' class='successful'>Great! No errors occoured.</div>";
$florensia->output_page($content);

$content .= "<div class='bordered'>Adding language settings to DB...</div>";

		$header = array();
		$querylang = MYSQL_QUERY("SHOW COLUMNS FROM flobase_language");
		while ($lang = MYSQL_FETCH_ARRAY($querylang)) {
			array_push($header, $lang[0]);
		}

		$querycopylang = MYSQL_QUERY("SELECT * FROM flobase_language WHERE languageid='$copylang'");
		if ($copylang = MYSQL_FETCH_ARRAY($querycopylang)) {
			foreach ($header as $headertitle) {
					if ($headertitle=="languageid") $copylang[$headertitle] = $lang;
					$dbcolumns .= $comma.$headertitle;
					$dbcolumnsvalue .= $comma."'".$copylang[$headertitle]."'";
					$comma = ",";
			}
		}
		if (!MYSQL_QUERY("INSERT INTO flobase_language ($dbcolumns) VALUES($dbcolumnsvalue)")) {
				$content .= "<div class='warning'>ERROR: ".MYSQL_ERROR()."</div>";
				$error=true;
		}


if (!$error) $content .= "<div style='margin-top:10px;' class='successful'>Great! No errors occoured.</div>";
$florensia->output_page($content);
/* */


	function signencode($sign) {
		MYSQL_QUERY("CREATE TABLE IF NOT EXISTS `cache_languagesigns` (
		`id` int(11) NOT NULL auto_increment,
		`signs` text NOT NULL,
		PRIMARY KEY  (`id`)
		)");	
		MYSQL_QUERY("INSERT INTO cache_languagesigns (signs) VALUES('".mysql_real_escape_string($sign)."')");
		$signs = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT signs FROM cache_languagesigns ORDER BY id DESC LIMIT 1"));
		MYSQL_QUERY("TRUNCATE TABLE cache_languagesigns");
		return $signs['signs'];
	}

if (!isset($_GET['lang']) OR (!isset($_GET['db']) && !isset($_GET['file']))) {
	$content = "<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; Languages</div>";

	if ($mybb->user['uid']==1) {
		//saving ---
		if ($_POST['new_language_submit']) {
			if (!preg_match('/^[a-zA-Z]+[a-zA-Z0-9_]*$/i', $_POST['new_language_langvar'])) {
				$florensia->notice("New variable could not be saved - Wrong variable name", "warning");
			}
			else {
				foreach ($flolang->lang as $langkey => $langvalue) {
					$sourcefile = $florensia->language_abs."/{$langkey}/{$_POST['new_language_selectfile']}.lang.php";
					if ($languagefile = simplexml_load_file($sourcefile)) {
						if (isset($languagefile->$_POST['new_language_langvar'])) {
							$florensia->notice("Could not save new entry for {$langkey}/{$_POST['new_language_selectfile']} - Variablename already exists", "warning");
						}
						else {
							if (!rename($sourcefile, $sourcefile.'_backup_'.date("Y.m.d-H.i.s"))) {
								$florensia->notice("Cannot rename old file for backup. Please verify your chmod settings!", "warning");
							}
							else {
								$languagefile->$_POST['new_language_langvar'] = signencode($_POST['new_language_content_'.$langkey]);
								if (!$languagefile->asXML($sourcefile)) {
									$florensia->notice("Could not save new entry for {$langkey}/{$_POST['new_language_selectfile']} - Cannot save file", "warning");
								}
								else {
									$florensia->notice("Successfully saved new entry for {$langkey}/{$_POST['new_language_selectfile']}", "successful");
								}
							}
						}
					}
					else {
						$florensia->notice("Could not save new entry for {$langkey}/{$_POST['new_language_selectfile']} - File not found or xml not parseable", "warning");
					}
				}
			}
		}
		//----
		$langfiles = scandir($florensia->language_abs."/en");
		unset($files, $selected_newlangfile);
		$selected_newlangfile[$_POST['new_language_selectfile']] = "selected='selected'";
		foreach ($langfiles as $langfile) {
			if ($langfile=="." OR $langfile==".." OR preg_match('/~$/', $langfile) OR !preg_match('/\.lang\.php$/', $langfile)) continue;
			$files .= "<option value='".str_replace('.lang.php', '', $langfile)."' ".$selected_newlangfile[str_replace('.lang.php', '', $langfile)].">".str_replace('.lang.php', '', $langfile)."</option>";
		}

		$newlangvar = "
			<div class='subtitle' style='margin-top:10px; padding-left:10%;'>
				<input type='text' name='new_language_langvar' style='width:200px'>
				<select name='new_language_selectfile'>$files</select>
			</div>
		";
		foreach ($flolang->lang as $langkey => $langvalue) {
			$newlangvar .= "
			<div>
				<table style='width:100%'><tr>
					<td style='width:10%'>".$flolang->lang[$langkey]->languagename."</td>
					<td><textarea name='new_language_content_{$langkey}' style='width:100%; height:50px;'></textarea></td>
				</tr></table>
			</div>
			";
		}
		$content .= "
			<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
				{$newlangvar}
				<div style='margin-left:10%;'><input type='submit' name='new_language_submit' value='Save new variable'></div>
			</form>";
	}

	foreach ($flolang->lang as $langkey => $langvalue) {
		if (!$flouser->get_permission("mod_language", $langkey)) continue;
		$content .= "<div class='subtitle' style='text-align:center; margin-top:10px;'>".$flolang->lang[$langkey]->languagename." (".$flolang->lang[$langkey]->author.")</div>";
		unset($dbentrys);
		foreach ($dbtables as $dbtable => $dbtablekey) {
 			$dbentrys .= "<a href='{$florensia->root}/adminlang.php?lang=$langkey&amp;db=".str_replace('flobase_', '', $dbtable)."'>".str_replace('flobase_', '', $dbtable)."</a><br />";
		}
		if ($langfiles = scandir($florensia->language_abs."/".$langkey)) {
			unset($files);
			foreach ($langfiles as $langfile) {
				if ($langfile=="." OR $langfile==".." OR preg_match('/~$/', $langfile) OR !preg_match('/\.lang\.php$/', $langfile)) continue;
				$files .= "<a href='{$florensia->root}/adminlang.php?lang=$langkey&amp;file=".str_replace('.lang.php', '', $langfile)."'>".str_replace('.lang.php', '', $langfile)."</a><br />";
			}
		}
		else $files = "No language files were found in ".$florensia->language_abs."/$langkey";

		$content .= "<div><table style='width:100%'><tr><td style='width:50%'>$dbentrys</td><td>$files</td></tr></table></div>";
	}
	$florensia->output_page($content);
}
elseif (isset($_GET['db']) && $flolang->lang[$_GET['lang']]) {
	if (!$flouser->get_permission("mod_language", $_GET['lang'])) { $florensia->output_page($flouser->noaccess()); }

	$query = "SELECT ".mysql_real_escape_string($dbtables['flobase_'.$_GET['db']]).", name_".mysql_real_escape_string($_GET['lang']).", name_en FROM ".mysql_real_escape_string("flobase_".$_GET['db']);
	if ($querylangfile = MYSQL_QUERY($query)) {
		if (isset($_POST['save'])) {
			//normal "update"
			while ($langfile = MYSQL_FETCH_ARRAY($querylangfile)) {
				if (!MYSQL_QUERY("UPDATE ".mysql_real_escape_string('flobase_'.$_GET['db'])." SET name_".mysql_real_escape_string($_GET['lang'])."='".mysql_real_escape_string(strip_tags($_POST[$langfile[$dbtables['flobase_'.$_GET['db']]]]))."' WHERE ".$dbtables['flobase_'.$_GET['db']]."='".$langfile[$dbtables['flobase_'.$_GET['db']]]."'")) {
					$content .= "<div class='warning'>ERROR while saving: <br /><b>".$langfile[$dbtables['flobase_'.$_GET['db']]]."</b><br />".$florensia->escape($_POST[$langfile[$dbtables['flobase_'.$_GET['db']]]])."<br /><span class='small'>".MYSQL_ERROR()."</span></div>";
					$error=true;
				}
			}
			if (!$error) $content .= "<div class='successful' style='text-align:center;'>Successfully updated</div>";
			else $content .= "<div class='warning' style='text-align:center;'>An error occurred while updating.</div>";
			$querylangfile = MYSQL_QUERY($query);
		}
		$savelangfile = "<div style='text-align:right; margin-bottom:2px;'><input type='submit' name='save' value='Save this language file'></div>";

		$content .= "
			<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; <a href='{$florensia->root}/adminlang.php'>Languages</a> &gt; ".$florensia->escape($_GET['db'])." (".$flolang->lang[$_GET['lang']]->languagename.")</div>
			<div><form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
			$savelangfile
		";
		while ($langfile = MYSQL_FETCH_ARRAY($querylangfile)) {
			$content .= "
				<div class='subtitle' style='margin-bottom:2px;'>".$langfile[$dbtables['flobase_'.$_GET['db']]]."</div>
				<div class='bordered' style='margin-bottom:2px;'><span class='small'>".$langfile['name_en']."</span></div>
				<div class='bordered' style='margin-bottom:10px; text-align:center;'><textarea name='".$langfile[$dbtables['flobase_'.$_GET['db']]]."' style='width:98%; height:60px;'>".$langfile['name_'.$_GET['lang']]."</textarea></div>
			";
		}
		$content .= "
			$savelangfile
			</form></div>";
		$florensia->output_page($content);
	}
	else { header("Location: {$florensia->root}/adminlang.php"); }
}
elseif (isset($_GET['file']) && $flolang->lang[$_GET['lang']]) {

	if (!$flouser->get_permission("mod_language", $_GET['lang'])) { $florensia->output_page($flouser->noaccess()); }

	$langfile = $florensia->language_abs."/".$_GET['lang']."/".$_GET['file'].".lang.php";
	if (!is_file($langfile)) header("Location: {$florensia->root}/adminlang.php");
	if (isset($_POST['save'])) {
		if (!rename($langfile, $langfile.'_backup_'.date("Y.m.d-H.i.s"))) {
			$notice = "<div class='warning' style='text-align:center; margin-bottom:10px;'>Cannot rename old file for backup. Please verify your chmod settings!</div>";
		}
		elseif (!($newfile = fopen($langfile, 'a'))) {
			$notice = "<div class='warning' style='text-align:center; margin-bottom:10px;'>Cannot create new file. Please verify your chmod settings!</div>";
		}
		else {
			$newlangfile = new SimpleXMLElement("<{$_GET['file']} createdate=\"".date("Y-m-d H:i:s / U")."\"></{$_GET['file']}>");

			foreach ($_POST as $postkey => $postvalue) {
				if (preg_match('/^language_filecontent_'.$_GET['lang'].'_'.$_GET['file'].'_([a-zA-Z0-9_]+)$/', $postkey, $postkey)) {
					if ($_POST['delete_language_filecontent_'.$_GET['lang'].'_'.$_GET['file'].'_'.$postkey[1]]==1) continue;
					$postvalue = signencode($postvalue);
					$newlangfile->$postkey[1] = $postvalue;
				}
			}
			$newlangfile->asXML($langfile);
			$notice .= "<div class='successful' style='text-align:center; margin-bottom:10px;'>Language file and backup successfully created.</div>";
		}
	}

	if ($filecontent = simplexml_load_file($langfile)) {
		if ($_GET['lang']!="en") $flolang->load($_GET['file'], "en");

		foreach ($filecontent as $variables[1] => $variables[2]) {
			if ($_GET['lang']!="en") $englishversion = "<div class='bordered small' style='margin-top:2px;'>{$flolang->$variables[1]}</div>";
				$filecontentvar .= "
					<div style='float:right; margin-top:10px;' class='small'>
						Delete:<input type='checkbox' name='delete_language_filecontent_".$florensia->escape($_GET['lang'])."_".$florensia->escape($_GET['file'])."_".$variables[1]."' value='1'>
					</div>
					<div class='subtitle' style='margin-top:10px;'>".$variables[1]."</div>
					{$englishversion}
					<div><textarea name='language_filecontent_".$florensia->escape($_GET['lang'])."_".$florensia->escape($_GET['file'])."_".$variables[1]."' style='width:100%; height:50px;'>".$florensia->escape(str_replace(array("&quot;", "<br />", "&#039;"), array("\"", "\n", "'"), $variables[2]))."</textarea></div>
				";
		}
		$content = "
			$notice
			<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; <a href='{$florensia->root}/adminlang.php'>Languages</a> &gt; ".$florensia->escape($_GET['file'])." (".$flolang->lang[$_GET['lang']]->languagename.")</div>
			<div>
				<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
					<div style='text-align:right; margin-bottom:2px;'><input type='submit' name='save' value='Save this language file'></div>
					$filecontentvar
					<div style='text-align:right; margin-bottom:2px;'><input type='submit' name='save' value='Save this language file'></div>
				</form>
			</div>";
		$florensia->output_page($content);
	}
	else { header("Location: {$florensia->root}/adminlang.php"); }
}
else { header("Location: {$florensia->root}/adminlang.php"); }


?>