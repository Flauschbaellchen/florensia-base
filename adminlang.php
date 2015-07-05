<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("access_admincp") OR !$flouser->get_permission("mod_language")) { $florensia->output_page($flouser->noaccess()); }

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Languages");

$langid = $_GET['lang'];

$dbtables = array(
'flobase_guides_categories'=>'id',
'flobase_item_categories'=>'id',
'flobase_item_columns'=>'id',
'flobase_item_effect'=>'effectid',
'flobase_item_types'=>'itemtypeid',
'flobase_landclass'=>'classid',
'flobase_npc_columns'=>'id',
'flobase_seaclass'=>'classid',
'flobase_seal_optionlang'=>'sealid',
'flobase_skill_columns'=>'id');

	
	
if (($_GET['new'] OR $_GET['file']) && $flolang->lang[$langid]) {
	if (!$flouser->get_permission("mod_language", $_GET['lang'])) { $florensia->output_page($flouser->noaccess()); }
	$langpushnew = array();
	
	if ($_GET['new']) {
		$myvarsquery = "SELECT varname, filename, lang_{$langid}, lang_{$langid}_flag, lang_en FROM flobase_languagefiles WHERE lang_{$langid}_flag=1";
	}
	else {
		$myvarsquery = "SELECT varname, filename, lang_{$langid}, lang_{$langid}_flag, lang_en FROM flobase_languagefiles WHERE filename='".mysql_real_escape_string($_GET['file'])."'";
	}

	
	$querynewvar = MYSQL_QUERY($myvarsquery);
	while ($newvar = MYSQL_FETCH_ARRAY($querynewvar)) {
		//update-routine
		if ($_POST['language_save']) {
			$langpushnew[] = $newvar['filename'];
			
			if ($_POST['language_'.$langid.'_'.$newvar['filename'].'_'.$newvar['varname'].'_flag']) $flag = 1;
			else $flag = 0;
			
			if (MYSQL_QUERY("UPDATE flobase_languagefiles SET lang_{$langid}='".mysql_real_escape_string($_POST['language_'.$langid.'_'.$newvar['filename'].'_'.$newvar['varname'].'_content'])."', lang_{$langid}_flag={$flag} WHERE varname='".mysql_real_escape_string($newvar['varname'])."' AND filename='".mysql_real_escape_string($newvar['filename'])."'")) {
				//$florensia->notice("Updated {$newvar['varname']}/{$newvar['filename']} successful", "successful");
			} else {
				//$florensia->notice("An error occurred while trying to update {$newvar['varname']}/{$newvar['filename']}", "warning");
			}
			
			//continue/update after query
			if (!$flag && $_GET['new']) continue;
			$newvar['lang_'.$langid] = $_POST['language_'.$langid.'_'.$newvar['filename'].'_'.$newvar['varname'].'_content'];
			$newvar['lang_'.$langid.'_flag'] = $flag;
			
		}
		
		if ($langid!="en") {
			$englishversion = "<div class='bordered small' style='margin-top:2px;'>{$newvar['lang_en']}</div>";
		}
		
		if ($newvar['lang_'.$langid.'_flag']) $checkedflag = "checked='checked'";
		else unset($checkedflag);
		
		$newvarcontent .= "
			<div style='margin-top:10px;'>
				<div class='small' style='float:right;'>
					Set as new: <input type='checkbox' value='1' name='language_{$langid}_{$newvar['filename']}_{$newvar['varname']}_flag' style='margin:1px 1px 0px 0px;' {$checkedflag}>
				</div>
				<div class='subtitle'>{$newvar['varname']}</div>
				{$englishversion}
				<div><textarea name='language_{$langid}_{$newvar['filename']}_{$newvar['varname']}_content' style='width:100%; height:50px;'>".$florensia->escape(str_replace(array("&quot;", "<br />", "&#039;"), array("\"", "\n", "'"), $newvar['lang_'.$langid]))."</textarea></div>
			</div>
		";
	}
	if (!$newvarcontent) $newvarcontent = "<div class='bordered' style='text-align:center'>No variables found</div>";
	else {
		$newvarcontent = "
			<form action='".$florensia->escape($florensia->request_uri())."' method='post'>
			$newvarcontent
			<div style='text-align:right; margin-bottom:2px;'><input type='submit' name='language_save' value='Save language variables'></div>
			</form>
		";
	}
	//need to push new language files?
	foreach (array_unique($langpushnew) as $languagefile) $flolang->exportfile($languagefile, $langid);
	
	//final content
	$content = "
		<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; <a href='{$florensia->root}/adminlang.php'>Languages</a> &gt; ".$flolang->lang[$langid]->languagename." &gt; File Variables</div>
		$newvarcontent
	";

	$florensia->output_page($content);	
}

elseif (isset($_GET['db']) && $flolang->lang[$langid]) {
	if (!$flouser->get_permission("mod_language", $langid)) { $florensia->output_page($flouser->noaccess()); }

	$query = "SELECT ".mysql_real_escape_string($dbtables['flobase_'.$_GET['db']]).", name_{$langid}, name_en FROM ".mysql_real_escape_string("flobase_".$_GET['db']);
	if ($querylangfile = MYSQL_QUERY($query)) {
		if (isset($_POST['save'])) {
			//normal "update"
			while ($langfile = MYSQL_FETCH_ARRAY($querylangfile)) {
				if (!MYSQL_QUERY("UPDATE ".mysql_real_escape_string('flobase_'.$_GET['db'])." SET name_{$langid}='".mysql_real_escape_string(strip_tags($_POST[$langfile[$dbtables['flobase_'.$_GET['db']]]]))."' WHERE ".$dbtables['flobase_'.$_GET['db']]."='".$langfile[$dbtables['flobase_'.$_GET['db']]]."'")) {
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
			<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; <a href='{$florensia->root}/adminlang.php'>Languages</a> &gt; ".$flolang->lang[$langid]->languagename." &gt; ".$florensia->escape($_GET['db'])."</div>
			<div><form action='".$florensia->escape($florensia->request_uri())."' method='post'>
			$savelangfile
		";
		while ($langfile = MYSQL_FETCH_ARRAY($querylangfile)) {
			$content .= "
				<div class='subtitle' style='margin-bottom:2px;'>".$langfile[$dbtables['flobase_'.$_GET['db']]]."</div>
				<div class='bordered' style='margin-bottom:2px;'><span class='small'>".$langfile['name_en']."</span></div>
				<div class='bordered' style='margin-bottom:10px; text-align:center;'><textarea name='".$langfile[$dbtables['flobase_'.$_GET['db']]]."' style='width:98%; height:60px;'>".$langfile['name_'.$langid]."</textarea></div>
			";
		}
		$content .= "
			$savelangfile
			</form></div>";
		$florensia->output_page($content);
	}
	else { header("Location: {$florensia->root}/adminlang.php"); }
}
else {
	$content = "<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; Languages</div>";

	if ($mybb->user['uid']==1) {
		//saving ---
		if ($_POST['new_language_submit']) {
			if (!preg_match('/^[a-zA-Z]+[a-zA-Z0-9_]*$/i', $_POST['new_language_langvar'])) {
				$florensia->notice("New variable could not be saved - Wrong variable name", "warning");
			}
			else {
				unset($query);
				
				$overridequery = MYSQL_QUERY("SELECT varname FROM flobase_languagefiles WHERE varname='{$_POST['new_language_langvar']}' AND filename='{$_POST['new_language_selectfile']}'");
				if (MYSQL_NUM_ROWS($overridequery)) {
					//already existing, override?
					if ($_POST['save_override']) {
						foreach (array_keys($flolang->lang) as $langid) {
							$query['update'][] = "lang_{$langid}='".mysql_real_escape_string($_POST['new_language_content_'.$langid])."', lang_{$langid}_flag=1";
						}

						if (!MYSQL_QUERY("UPDATE flobase_languagefiles SET ".join(", ", $query['update'])." WHERE varname='{$_POST['new_language_langvar']}' AND filename='{$_POST['new_language_selectfile']}'")) {
							$florensia->notice("An error occurred while trying to update {$_POST['new_language_langvar']} in {$_POST['new_language_selectfile']}. No file export done.", "warning");
						} else {
							$florensia->notice("Updating variable {$_POST['new_language_langvar']} in {$_POST['new_language_selectfile']} successful", "successful");
							foreach (array_keys($flolang->lang) as $langid) {
								$flolang->exportfile($_POST['new_language_selectfile'], $langid);
							}
						}
					} else {
						$florensia->notice("{$_POST['new_language_langvar']} in {$_POST['new_language_selectfile']} already exist. Use override to update it.", "warning");
						foreach (array_keys($flolang->lang) as $langid) {
							$overridevalue[$langid] = $_POST['new_language_content_'.$langid];
						}
						$overridevalue['varname'] = $_POST['new_language_langvar'];
					}
				} else {		
					$query['columns'][] = "varname";
					$query['values'][] = "'{$_POST['new_language_langvar']}'";
					$query['columns'][] = "filename";
					$query['values'][] = "'{$_POST['new_language_selectfile']}'";
					foreach (array_keys($flolang->lang) as $langid) {
						$query['columns'][] = "lang_".$langid;
						$query['columns'][] = "lang_{$langid}_flag";
						$query['values'][] = "'".mysql_real_escape_string($_POST['new_language_content_'.$langid])."'";
						$query['values'][] = 1;
					}
					
					if (!MYSQL_QUERY("INSERT INTO flobase_languagefiles (".join(", ", $query['columns']).") VALUES(".join(", ", $query['values']).")")) {
						$florensia->notice("An error occurred while trying to add {$_POST['new_language_langvar']} to {$_POST['new_language_selectfile']}. No file export done.", "warning");
					} else {
						$florensia->notice("Adding new variable {$_POST['new_language_langvar']} to {$_POST['new_language_selectfile']} successful", "successful");
						foreach (array_keys($flolang->lang) as $langid) {
							$flolang->exportfile($_POST['new_language_selectfile'], $langid);
						}
					}
				}
			}
		}
		//----
		$langfiles = scandir($florensia->language_abs."/en");
		unset($files, $selected_newlangfile);
		$selected_newlangfile[$_POST['new_language_selectfile']] = "selected='selected'";
		foreach ($langfiles as $langfile) {
			if (!preg_match('/\.lang\.php$/', $langfile)) continue;
			$files .= "<option value='".str_replace('.lang.php', '', $langfile)."' ".$selected_newlangfile[str_replace('.lang.php', '', $langfile)].">".str_replace('.lang.php', '', $langfile)."</option>";
		}

		$newlangvar = "
			<div class='subtitle' style='margin-top:10px; padding-left:10%;'>
				<input type='text' name='new_language_langvar' style='width:200px' value='".$florensia->escape($overridevalue['varname'])."'>
				<select name='new_language_selectfile'>$files</select>
			</div>
		";
		foreach (array_keys($flolang->lang) as $langkey) {
			$newlangvar .= "
			<div>
				<table style='width:100%'><tr>
					<td style='width:10%'>".$flolang->lang[$langkey]->languagename."</td>
					<td><textarea name='new_language_content_{$langkey}' style='width:100%; height:50px;'>".$florensia->escape($overridevalue[$langkey])."</textarea></td>
				</tr></table>
			</div>
			";
		}
		$content .= "
			<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
				{$newlangvar}
				<div style='margin-left:10%;'><input type='submit' name='new_language_submit' value='Save new variable'> <input type='checkbox' name='save_override' value='1'> Override variable if already existing</div>
			</form>";
	}

	foreach (array_keys($flolang->lang) as $langkey) {
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


?>