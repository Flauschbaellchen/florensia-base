<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("access_admincp") OR !$flouser->get_permission("mod_menubar")) { $florensia->output_page($flouser->noaccess()); }

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Menubar and Userbar");


if ($_GET['classname']!="menubar" && $_GET['classname']!="userbar") { $florensia->output_page($flouser->noaccess()); }
$classname = $_GET['classname'];

//available permission-flags
$querypermission = MYSQL_QUERY("SELECT settings FROM flobase_defaults WHERE title='userpermissions_available'");
$permissiontmp = MYSQL_FETCH_ARRAY($querypermission);
$permissiontmp = explode(";", $permissiontmp['settings']);
foreach ($permissiontmp as $permissiontmpflag) {
	$permissiontmpflag = explode(":", $permissiontmpflag);
	$permission[] = $permissiontmpflag[0];
}


//database work
if ($_POST['do_update']) {

	$querysections = MYSQL_QUERY("SELECT id FROM flobase_menubar WHERE classname='{$classname}'");
	while ($sections = MYSQL_FETCH_ARRAY($querysections)) {
		if ($_POST['section_'.$sections['id'].'_delete']=="1") {
			if (!MYSQL_QUERY("DELETE FROM flobase_menubar WHERE id='{$sections['id']}' OR (sectionid='{$sections['id']}' AND section=0)")) {
				$florensia->notice("Error: ".mysql_error(), "warning");
			}
			continue;
		}

		if ($_POST['section_'.$sections['id'].'_standalone']) $standalone = 1;
		else $standalone=0;

		unset($comma, $sectionnamedb);
		foreach ($flolang->lang as $langkey => $langname) {
			$sectionnamedb .= "$comma name_$langkey='".mysql_real_escape_string($_POST['section_'.$sections['id'].'_name_'.$langkey])."'";
			$comma=",";
		}

		if ($_POST['section_'.$sections['id'].'_sectionid']=="0") { $sectioniddb = "sectionid=0, section=1"; }
		else $sectioniddb = "section=0, sectionid=".intval($_POST['section_'.$sections['id'].'_sectionid']);

		if (!intval($_POST['section_'.$sections['id'].'_rankid'])) $rankiddb="rankid=1";
		else $rankiddb = "rankid=".intval($_POST['section_'.$sections['id'].'_rankid']);

		switch ($_POST['section_'.$sections['id'].'_source']) {
			case "intern": { $pagelinkdb = "intern|".$_POST['section_'.$sections['id'].'_pagelink']; break; }
			case "forum": { $pagelinkdb = "forum|".$_POST['section_'.$sections['id'].'_pagelink']; break; }
			case "extern": {
				if (preg_match('^http', $_POST['section_'.$sections['id'].'_pagelink'])) $pagelinkdb = "extern|".$_POST['section_'.$sections['id'].'_pagelink']; 
				else $pagelinkdb = "extern|".$_POST['section_'.$sections['id'].'_pagelink'];
				break;
			}
			default: continue;
		}
		$pagelinkdb = "pagelink = '".mysql_real_escape_string($pagelinkdb)."'";
		
		if ($flouser->get_permission("mod_permission")) {
			if (!$_POST['section_'.$sections['id'].'_permission']) $permissionsdb = ", permission = ''";
			else $permissionsdb = ", permission = '".join(";", $_POST['section_'.$sections['id'].'_permission'])."'";
		} else unset($permissionsdb);

		if (!MYSQL_QUERY("UPDATE flobase_menubar SET standalone=$standalone, $sectionnamedb, $sectioniddb, $rankiddb, $pagelinkdb{$permissionsdb} WHERE id='{$sections['id']}'")) {
				$florensia->notice("Error: ".mysql_error(), "warning");
		}
	}
}
//------------ end updating -----------
//------------ start new entry --------
elseif ($_POST['do_new']) {

		if ($_POST['section_0_standalone']) $do_new['standalone'] = 1;
		else $do_new['standalone']=0;

		foreach ($flolang->lang as $langkey => $langname) {
			$do_new['name_'.$langkey]="'".mysql_real_escape_string($_POST['section_0_name_'.$langkey])."'";
		}

		if ($_POST['section_0_sectionid']=="0") { $do_new['sectionid']=0; $do_new['section']=1; }
		else { $do_new['section']=0; $do_new['sectionid']=intval($_POST['section_0_sectionid']); }

		if (!intval($_POST['section_0_rankid'])) $do_new['rankid']=1;
		else $do_new['rankid']=intval($_POST['section_0_rankid']);

		switch ($_POST['section_0_source']) {
			case "intern": { $pagelinkdb = "intern|".$_POST['section_0_pagelink']; break; }
			case "forum": { $pagelinkdb = "forum|".$_POST['section_0_pagelink']; break; }
			case "extern": {
				if (preg_match('^http', $_POST['section_0_pagelink'])) $pagelinkdb = "extern|".$_POST['section_0_pagelink']; 
				else $pagelinkdb = "extern|".$_POST['section_0_pagelink'];
				break;
			}
		}
		$do_new['pagelink'] = "'".mysql_real_escape_string($pagelinkdb)."'";

		if ($flouser->get_permission("mod_permission")) {
			$do_new['permission'] = "'".join(";", $_POST['section_0_permission'])."'";
		} 
		$dbnewkeys = join(", ", array_keys($do_new));
		$dbnewvalues = join(", ", array_values($do_new));

		if (!MYSQL_QUERY("INSERT INTO flobase_menubar (classname, $dbnewkeys) VALUES('{$classname}', $dbnewvalues)")) {
				$florensia->notice("Error: ".mysql_error(), "warning");
		}


}
//------------ end new entry --------
//preselectform sections
$sectionselect = "<option value='0'>--</option>";
$querysections = MYSQL_QUERY("SELECT id, name_".$flolang->language." FROM flobase_menubar WHERE classname='{$classname}' AND section=1 AND standalone=0");
while ($sections = MYSQL_FETCH_ARRAY($querysections)) {
	$sectionselect .= "<option value='{$sections['id']}' {\$selectedsection['{$sections['id']}']}>".$florensia->escape($sections['name_'.$flolang->language])."</option>";
}


	$sectionform = "
		<div class='shortinfo_\$colorchange small' \$subsectionmargin>
			<table style='width:100%;'>
				<tr>
					<td style='vertical-align:top;'><table>\$sectionname</table></td>
					<td style='border-right:1px solid; width:10px;'></td>
					<td style='padding-left:10px; vertical-align:top; width:310px;'>
						<table>
							<tr><td>Mainsection:</td><td><select name='section_{\$sections['id']}_sectionid'>\$sectionselectform</select></td></tr>
							<tr><td>Standalone:</td><td><input type='checkbox' name='section_{\$sections['id']}_standalone' value='1' \$standalonechecked> (only if section)</td></tr>
							<tr><td style='width:100px;'>Order:</td><td><input type='text' name='section_{\$sections['id']}_rankid' maxsize='2' value='{\$sections['rankid']}'></td></tr>
							<tr><td>File:</td><td><input type='text' name='section_{\$sections['id']}_pagelink' maxsize='2' value='{\$sections['pagelink'][1]}'></td></tr>
							<tr><td>Source:</td><td>
								<select name='section_{\$sections['id']}_source'>
									<option value='intern' {\$selected['intern']}>Intern (file only, without domain)</option>
									<option value='forum' {\$selected['forum']}>Forum (file only, without domain)</option>
									<option value='extern' {\$selected['extern']}>Extern (with http://, full url)</option>
								</select>
							</td></tr>
							<tr><td>Permission:<br />(Non for all)</td><td>
								<select name='section_{\$sections['id']}_permission[]' multiple='multiple' style='height:100px;'>
									\$permissionselectform
								</select>
							</td></tr>
						</table>
					</td>
					<td style='border-right:1px solid; width:10px;'></td>
					<td style='padding-left:10px; vertical-align:top; width:110px;'>
						<table style='width:100%;'>
							<tr><td style='text-align:right;'>
								\$submitbuttomform
							</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	";

//---
//------- New entry form -------------
	$sections['id']=0;
	foreach ($flolang->lang as $langkey => $langname) {
		$sectionname .= "<tr><td style='width:150px;'>Sectionname [$langkey]:</td><td><input type='text' name='section_{$sections['id']}_name_{$langkey}' maxlength='255'></td></tr>";
	}
	
	unset($permissionselectform);
	foreach ($permission as $permissionflag) {
		$permissionselectform .= "<option value='{$permissionflag}'>$permissionflag</option>";
	}
	
	$colorchange = $florensia->change();
	$submitbuttomform = "<input type='submit' name='do_new' value='Save new entry'>";
	eval("\$sectionselectform = \"$sectionselect\";");
	eval("\$permissionselectform = \"$permissionselectform\";");
	eval("\$content = \"$sectionform\";");
	$content .= "<div style='margin-top:10px; height:10px; border-top:1px solid;'></div>";
	$content .= "<div style='margin-top:10px; height:10px; border-top:1px solid;'></div>";

// -------------- 

$submitbuttom = "<input type='submit' name='do_update' value='Update all entries'><br /><br />
				Delete: <input type='checkbox' name='section_{\$sections['id']}_delete' value='1'><br />
				\$delete_subsection_notice
			";

$querysections = MYSQL_QUERY("SELECT * FROM flobase_menubar WHERE classname='{$classname}' AND section=1 ORDER BY rankid");
while ($mainsections = MYSQL_FETCH_ARRAY($querysections)) {
	$sections = $mainsections;
	$colorchange = $florensia->change();
	$delete_subsection_notice = "<span style='color:#9E9E9E'>Subsections will be deleted, too!</span>";
	unset($sectionname,$selected, $subsectionmargin, $selectedsection);
	foreach ($flolang->lang as $langkey => $langname) {
		$sectionname .= "<tr><td style='width:150px;'>Sectionname [$langkey]:</td><td><input type='text' name='section_{$sections['id']}_name_{$langkey}' maxlength='255' value='".$florensia->escape($sections['name_'.$langkey])."'></td></tr>";
	}

	if ($sections['standalone']) $standalonechecked = "checked='checked'";
	else unset($standalonechecked);

	$sections['pagelink'] = explode("|", $sections['pagelink']);
	$selected[$sections['pagelink'][0]]="selected='selected'";
	
	unset($permissionselectform, $permissionchecked);
	foreach (explode(";", $sections['permission']) as $permissionsetflag) {
		$permissionchecked[$permissionsetflag] = "selected='selected'";
	}
	foreach ($permission as $permissionflag) {
		$permissionselectform .= "<option value='{$permissionflag}' ".$permissionchecked[$permissionflag].">$permissionflag</option>\n";
	}

	eval("\$sectionselectform = \"$sectionselect\";");
	eval("\$permissionselectform = \"$permissionselectform\";");
	eval("\$submitbuttomform = \"$submitbuttom\";");
	eval("\$content .= \"$sectionform\";");
	

	if (!$sections['standalone']) {
		$querysubsections = MYSQL_QUERY("SELECT * FROM flobase_menubar WHERE classname='{$classname}' AND section=0 AND sectionid={$sections['id']} ORDER BY rankid");
		while ($subsections = MYSQL_FETCH_ARRAY($querysubsections)) {
			$sections = $subsections;
			$subsectionmargin = "style='margin-left:80px; margin-top:5px;'";

			unset($sectionname, $selected,$delete_subsection_notice);
			foreach ($flolang->lang as $langkey => $langname) {
				$sectionname .= "<tr><td style='width:150px;'>Sectionname [$langkey]:</td><td><input type='text' name='section_{$sections['id']}_name_{$langkey}' maxlength='255' value='".$florensia->escape($sections['name_'.$langkey])."'></td></tr>";
			}

			$sections['pagelink'] = explode("|", $sections['pagelink']);
			$selected[$sections['pagelink'][0]]="selected='selected'";
			
			unset($permissionselectform, $permissionchecked);
			foreach (explode(";", $sections['permission']) as $permissionsetflag) {
				$permissionchecked[$permissionsetflag] = "selected='selected'";
			}
			foreach ($permission as $permissionflag) {
				$permissionselectform .= "<option value='{$permissionflag}' ".$permissionchecked[$permissionflag].">$permissionflag</option>\n";
			}

			$selectedsection[$sections['sectionid']] = "selected='selected'";
			eval("\$sectionselectform = \"$sectionselect\";");
			eval("\$permissionselectform = \"$permissionselectform\";");
			eval("\$submitbuttomform = \"$submitbuttom\";");
			eval("\$content .= \"$sectionform\";");
		}
	}

	$content .= "<div style='margin-top:10px; height:10px; border-top:1px solid;'></div>";
}

$content = "
	<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
	<div class='small bordered' style='margin-bottom:15px; text-align:center;'>Leave namespace blank to ignore the specified language and hide section/link.</div>
	$content
	</form>
";

$florensia->output_page($content);

?>