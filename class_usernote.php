<?PHP

class class_usernote {

	var $parser_options = array(
				"allow_html" => 0,
				"allow_mycode" => 1,
				"allow_smilies" => 0,
				"allow_imgcode" => 0,
				"filter_badwords" => 1
			);

	function display($sectionid, $section) {
		global $parser, $florensia, $flouser, $flolang;

		if ($_POST['savenote']) { $usernotenotice = $writeanoteform = $this->save_note($sectionid, $section); }
		foreach ($_POST as $postkey => $postvalue) {
			if (preg_match('/^usernote_(delete|update)_([0-9]+)$/', $postkey, $dbkey)) {
				if ($dbkey[1]=="delete" && $postvalue=="1") $usernotenotice =  $this->updateentry($dbkey[2], $dbkey[1]);
				elseif ( $dbkey[1]=="update" && $_POST['usernote_delete_'.$dbkey[2]]!="1") $usernotenotice =  $this->updateentry($dbkey[2], $dbkey[1]);
			}
		}

		if ($usernotenotice) $florensia->notice($usernotenotice);

		if (isset($_GET['usernotes']) && $flolang->lang[$_GET['usernotes']]) { $dblang = "AND language='{$_GET['usernotes']}'"; }

		$querynotes = MYSQL_QUERY("SELECT id FROM flobase_usernotes WHERE section='".mysql_real_escape_string($section)."' AND sectionid='".mysql_real_escape_string($sectionid)."' {$dblang} ORDER BY dateline");
		while ($notes = MYSQL_FETCH_ARRAY($querynotes)) {
			$usernotes .= $this->get_entry($notes['id']);
		}
		if (!$usernotes) { 
			$usernotes = "<div class='warning'>".$flolang->sprintf($flolang->usernotes_noentry, strtolower($florensia->escape($_GET['usernotes'])))."</div>";
		}

		//<tr><td colspan='2' class='small' style='padding-left:8px;'>".str_replace('http://florensia-forumurl', $florensia->forumurl, $flolang->usernotes_nodiscussion)."</td></tr>
		if ($flouser->get_permission("add_usernotes")) {
				$writeanoteform = "
				<div class='bordered'>
					<form action='".$florensia->escape($florensia->request_uri(array(), 'usernotes'))."' method='post'>
					<table width='100%' style='border-collapse:0px; border-spacing:0px; padding:0px;'>
						
						<tr><td colspan='2' style='text-align:center;'><textarea name='commenttext' style='width:98%; height:170px;'></textarea></td></tr>
						<tr>
							<td style='width:100px;'>&nbsp;&nbsp;".str_replace(' ', '&nbsp;', $flolang->usernotes_selectlang)."</td>
							<td>".$this->language_select()."
								<input type='submit' name='savenote' value='{$flolang->usernotes_savenote}'>
							</td>
						</tr>
					</table>
					</form>
				</div>";
			}
		else { $writeanoteform = "<div class='warning' style='margin-top:5px; text-align:center'>".str_replace('http://florensia-forumurl', $florensia->forumurl, $flolang->usernotes_nomember)."</div>"; }

		$writeanoteform = "
		<div class='subtitle' style='margin-top:20px;'>{$flolang->usernotes_writeanote}</div>
		<div>$writeanoteform</div>
		";

		/* usernotesselect */
			if (isset($_GET['usernotes']) && $flolang->lang[$_GET['usernotes']]) $selected[$_GET['usernotes']]="selected='selected'";
			$allentrys = 0;
			foreach ($flolang->lang as $langkey => $langvalue) {
				if (!$flolang->lang[$langkey]->visible_usernotes) continue;
				$entrys = MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_usernotes WHERE language='$langkey' AND sectionid='$sectionid' AND section='$section'"));
				$allentrys += $entrys;
				$usernotesselect .= "<option value='".$flolang->lang[$langkey]->languageid."' ".$selected[$langkey].">".$flolang->lang[$langkey]->languagename." ($entrys/__allentries__)</option>";
			}
			$usernotesselect = str_replace('__allentries__',$allentrys, $usernotesselect);
			$usernotesselect = "
			<select name='usernotes' class='small'>
				<option value='all'>{$flolang->global_select_usernotes_all} ($allentrys)</option>
				$usernotesselect
			</select>";
		/*        *        */

		return "
			<div style='margin-bottom:5px; margin-top:10px;'>
							<table width='100%' style='border-collapse:0px; border-spacing:0px; padding:0px;' class='subtitle'>
								<tr>
									<td>{$flolang->usernotes_subtitle}</td>
									<td class='small' style='text-align:right'>".$florensia->quick_select('usernotes', array("section"=>$section, "sectionid"=>$sectionid, 'text'=>$_GET['text'], 'names'=>$_GET['names']), array($flolang->usernotes_selecttitle=>$usernotesselect), array("anchor"=>"usernotes"))."</td>
								</tr>
							</table>
			</div>
			{$usernotes}
			{$writeanoteform}
		";
	}

	function language_select($preselect=false) {
		global $florensia, $flolang;
			if ($preselect) $usernotesselect[$preselect]="selected='selected'";
			elseif ($_GET['usernotes']) $usernotesselect[$_GET['usernotes']]="selected='selected'";
			else $usernotesselect[$flolang->language]="selected='selected'";

			foreach ($flolang->lang as $langkey => $langvalue) {
				if (!$flolang->lang[$langkey]->visible_usernotes) continue;
				$usernotes .= "<option value='".$flolang->lang[$langkey]->languageid."' ".$usernotesselect[$langkey].">".$flolang->lang[$langkey]->languagename."</option>";
			}
			return "
			<select name='noteslanguage' class='small'>
				$usernotes
			</select>
			";
	}

	function save_note($sectionid, $section) {
		global $flouser, $florensia, $flolang, $flolog;
		if (!$flouser->userid) return $flolang->usernotes_savenote_nomember;
		if (!strlen(trim($_POST['commenttext'])) OR !$flolang->lang[$_POST['noteslanguage']]) return "<div class='warning' style='margin-top:5px; text-align:center'>{$flolang->usernotes_savenote_errorsends}</div>";

		if (MYSQL_QUERY("INSERT INTO flobase_usernotes (userid, section, sectionid, commenttext, dateline, language, writeip) VALUES('".$flouser->userid."', '$section', '$sectionid', '".mysql_real_escape_string($_POST['commenttext'])."', '".date("U")."', '".$_POST['noteslanguage']."', '".getenv("REMOTE_ADDR")."')")) {
			$flolog->add("usernote:new", "{user:{$flouser->userid}} wrote {usernote:".mysql_insert_id()."} on {{$section}:{$sectionid}}");
			return "<div class='successful' style='margin-top:5px; text-align:center'>{$flolang->usernotes_savenote_successful}</div>";
		}
		else {
			$flolog->add("error:usernote", "MySQL-INSERT-Error while add note on {{$section}:{$sectionid}}");
			return "<div class='warning' style='margin-top:5px; text-align:center'>{$flolang->usernotes_savenote_error}</div>";
		}
	}

	
	function get_tabdesc($sectionid, $section) {
		global $flolang;
		return $flolang->sprintf($flolang->tabbar_desc_usernotes, MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_usernotes WHERE sectionid='".mysql_real_escape_string($sectionid)."' AND section = '".mysql_real_escape_string($section)."'")));
	}
	
	
	function get_entry($noteid) {
		global $flouser, $flouserdata, $stringtable, $classquest, $florensia, $flolang, $parser, $flolog;
		$noteid = intval($noteid);
		$querynotes = MYSQL_QUERY("SELECT id, section, sectionid, userid, language, dateline, writeip, moderated, commenttext, lastupdate FROM flobase_usernotes WHERE id={$noteid}");
		if ($notes = MYSQL_FETCH_ARRAY($querynotes)) {
			
			
			if ($flouser->get_permission("mod_usernotes", $notes['language']) && !($_POST['do_update_'.$noteid] && $_POST['delete_usernote_'.$noteid] && $_POST['delete_usernote_'.$noteid.'_verify'])) {
				//check first if something has changes... moderated
				if ($_POST['do_update_'.$noteid] && $_POST['moderated_usernote_'.$noteid]) {
					if (!MYSQL_QUERY("UPDATE flobase_usernotes SET moderated='{$flouser->userid},".date("U")."' WHERE id='{$notes['id']}'")) {
						$flolog->add("error:usernote", "MySQL-Update-Error while set {usernote:{$notes['id']}} from {user:{$notes['userid']}} written on {timestamp:{$notes['dateline']}} as moderated on {{$notes['section']}:{$notes['sectionid']}}");
						$florensia->notice("An error occurred while set note ({$notes['id']}) to moderated", "warning");
					} else {
						$flolog->add("usernote:moderate", "{user:{$flouser->userid}} set {usernote:{$notes['id']}} from {user:{$notes['userid']}} written on {timestamp:{$notes['dateline']}} as moderated on {{$notes['section']}:{$notes['sectionid']}}");
						$florensia->notice("Successful set note ({$notes['id']}) to moderated", "successful");
						//set changed variables
						$notes['moderated'] = "{$flouser->userid},".date("U");
					}
				}
				
				if ($flouser->get_permission("watch_log")) $adminipadress = " (<a href='{$florensia->root}/adminlog?currentip={$notes['writeip']}' target='_blank'>{$notes['writeip']}</a>)";
				else $adminipadress = " ({$notes['writeip']})";
				if (!$notes['moderated']) {
					$adminquickmod = "
						<span style='margin-left:10px;'>{$flolang->usernotes_moderate_title}: <input type='checkbox' name='moderated_usernote_".$notes['id']."' value='1' style='vertical-align:middle; padding:0px; margin:0px;'></span>
					";
				} else {
					list($moduser, $modtime) = explode(",", $notes['moderated']);
					$adminquickmod = "<span style='margin-left:10px;'>{$flolang->usernotes_moderate_title} ".date("m.d.y", $modtime)." (".$flouserdata->get_username($moduser).")</span>";
				}
			}
			
			if ($notes['userid'] == $flouser->userid OR $flouser->get_permission("mod_usernotes", $notes['language'])) {
				//check first if something has changes... delete, update
				if ($_POST['do_update_'.$noteid] && $_POST['delete_usernote_'.$noteid] && $_POST['delete_usernote_'.$noteid.'_verify']) {
					if (!MYSQL_QUERY("DELETE FROM flobase_usernotes WHERE id='{$notes['id']}'")) {
						$flolog->add("error:usernote", "MySQL-Delete-Error while delete {usernote:{$notes['id']}} from {user:{$notes['userid']}} written on {timestamp:{$notes['dateline']}} on {{$notes['section']}:{$notes['sectionid']}}");
						$florensia->notice($flolang->usernotes_delete_error_notice, "warning");
					} else {
						$flolog->add("usernote:delete", "{user:{$flouser->userid}} deleted {usernote:{$notes['id']}} from {user:{$notes['userid']}} written on {timestamp:{$notes['dateline']}} on {{$notes['section']}:{$notes['sectionid']}}");
						$florensia->notice($flolang->usernotes_delete_successful_notice, "successful");
						//break and return nothing
						return;
					}	
				}
				if ($_POST['do_update_'.$noteid] && strlen(trim($_POST['commenttext_update_'.$noteid])) && $flolang->lang[$_POST['noteslanguage']] && ($_POST['commenttext_update_'.$noteid]!=$notes['commenttext'] OR $_POST['noteslanguage']!=$notes['language'])) {
					if (!MYSQL_QUERY("UPDATE flobase_usernotes SET language='{$_POST['noteslanguage']}', lastupdate='{$flouser->userid},".date("U")."', commenttext='".mysql_real_escape_string($_POST['commenttext_update_'.$noteid])."' WHERE id='{$notes['id']}'")) {
						$flolog->add("error:usernote", "MySQL-Update-Error while update {usernote:{$notes['id']}} from {user:{$notes['userid']}} written on {timestamp:{$notes['dateline']}} on {{$notes['section']}:{$notes['sectionid']}}");
						$florensia->notice($flolang->usernotes_edit_error_notice, "warning");
					} else {
						$flolog->add("usernote:update", "{user:{$flouser->userid}} updated {usernote:{$notes['id']}} from {user:{$notes['userid']}} written on {timestamp:{$notes['dateline']}} on {{$notes['section']}:{$notes['sectionid']}}");
						$florensia->notice($flolang->usernotes_edit_successful_notice, "successful");
						//set changed variables
						$notes['language'] = $_POST['noteslanguage'];
						$notes['lastupdate'] = "{$flouser->userid},".date("U");
						$notes['commenttext'] = $_POST['commenttext_update_'.$noteid];
					}				
				}
				
				
				//----			
				$editlink = "<a href='javascript:switchlayer(\"usernotetext_{$noteid},usernotetext_{$noteid}_edit\", \"usernotetext_{$noteid}_edit\")'>[{$flolang->usernotes_editlink}]</a>";
				$editform = "
					<div class='small' id='usernotetext_{$noteid}_edit' style='display:none;'>
						<div>".$this->language_select($notes['language'])." <span style='margin-left:10px;'>{$flolang->usernotes_delete_title}: <input type='checkbox' name='delete_usernote_".$notes['id']."' value='1' style='vertical-align:middle; padding:0px; margin:0px 3px 0px 0px;'><input type='checkbox' name='delete_usernote_".$notes['id']."_verify' value='1' style='vertical-align:middle; padding:0px; margin:0px;'></span>{$adminquickmod}</div>
						<div style='text-align:center;'>
							<textarea name='commenttext_update_{$noteid}' style='width:99%; height:100px;'>".$florensia->escape($notes['commenttext'])."</textarea>
						</div>
						<div><table style='width:100%;'><tr>
							<td><input type='submit' name='do_update_{$noteid}' value='{$flolang->usernotes_savechanges_submit}'></td>
							<td style='text-align:right; font-weight:bold;'><a href='javascript:switchlayer(\"usernotetext_{$noteid},usernotetext_{$noteid}_edit\", \"usernotetext_{$noteid}\")'>[{$flolang->usernotes_cancellink}]</a></td>
						</tr></table></div>
					</div>
				";
			}	
			
			if ($flouser->get_permission("mod_usernotes", $notes['language'])) {
				switch ($notes['section']) {
					case "quest": {
						$usernotelink = "Quest: <a href='".$florensia->outlink(array("questdetails", $notes['sectionid'], $classquest->get_title($notes['sectionid'])), array("usernotes"=>$notes['language']), array('anchor'=>'usernotes'))."' target='_blank'>".$classquest->get_title($notes['sectionid'])."</a>";
						break;
					}
					case "npc": {
						$usernotelink = "NPC: <a href='".$florensia->outlink(array("npcdetails", $notes['sectionid'], $stringtable->get_string($notes['sectionid'])), array("usernotes"=>$notes['language']), array('anchor'=>'usernotes'))."' target='_blank'>".$stringtable->get_string($notes['sectionid'], array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
						break;
					}
					case "item": {
						$usernotelink = "Item: <a href='".$florensia->outlink(array("itemdetails", $notes['sectionid'], $stringtable->get_string($notes['sectionid'])), array("usernotes"=>$notes['language']), array('anchor'=>'usernotes'))."' target='_blank'>".$stringtable->get_string($notes['sectionid'], array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
						break;
					}
					case "map": {
						$usernotelink = "Map: <a href='".$florensia->outlink(array("mapdetails", $notes['sectionid'], $stringtable->get_string($notes['sectionid'])), array("usernotes"=>$notes['language']), array('anchor'=>'usernotes'))."' target='_blank'>".$stringtable->get_string($notes['sectionid'], array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
						break;
					}
					case "gallery": {
						$image = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT name FROM flobase_gallery WHERE galleryid='".intval($notes['sectionid'])."'"));
						if (!strlen($image['name'])) $image['name'] = "-";
						$usernotelink = "Image: <a href='".$florensia->outlink(array("gallery", "i", $notes['sectionid'], $image['name']), array("usernotes"=>$notes['language']))."' target='_blank'>".$florensia->escape($image['name'])."</a>";
						break;
					}
					default: $usernotelink = "--";
				}
				$usernotelink .="<br />";
			} else unset($usernotelink);
			
			if ($notes['lastupdate']) {
				list($luser, $ltime) = explode(",", $notes['lastupdate']);
				$lastupdate = "<div class='small' style='text-align:right; margin-top:7px; font-style:italic;'>".$flolang->sprintf($flolang->usernotes_lastupdate_notice, $flouserdata->get_username($luser), date("m.d.y", $ltime))."</div>";
			}
		
			return "
			<div>
				<form action='".$florensia->escape($florensia->request_uri(array(), 'usernotes'))."' method='post'>
				<div class='small subtitle' style='margin-top:10px;'>
					<table style='width:100%'>
						<tr><td style='vertical-align:top;'>
							<img src='{$florensia->layer_rel}/flags/png/".$flolang->lang[$notes['language']]->flagid.".png' alt='".$flolang->lang[$notes['language']]->languagename."' border='0'>
							".$flouserdata->get_username($notes['userid'])."{$adminipadress}<br />
							".date("m.d.y - H:i", $notes['dateline'])."
						</td><td style='text-align:right; vertical-align:top;'>
							$usernotelink
							{$editlink}
						</td></tr>
					</table>
				</div>
				<div class='shortinfo_1'>
					<div id='usernotetext_{$noteid}'>".$parser->parse_message($notes['commenttext'], $this->parser_options)."{$lastupdate}</div>
					{$editform}
				</div>
				</form>
			</div>
			";	
		}
		return false;
	}
	
}


?>