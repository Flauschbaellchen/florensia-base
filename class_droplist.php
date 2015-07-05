<?PHP

class class_droplist {

	function updateentry($itemid, $npcid, $action="thumpsup", $log=true) {
		global $flolang, $flouser, $flouserdata, $florensia, $stringtable, $flolog, $classvote;
		//not logged in
		if (!$flouser->userid) return "<div class='warning' style='text-align:center'>{$flolang->droplist_updateentry_error_notloggedin}</div>";
		//banned from droplist
		if (!$flouser->get_permission("add_droplist")) return "<div class='warning' style='text-align:center'>{$flolang->droplist_updateentry_error_banned}</div>";

		if (($action=="verified" OR $action=="unverified") && !$flouser->get_permission("mod_droplist", "verify")) return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_error_verify_nopermission, $entryname)."</div>";
		if ($action=="delete" && !$flouser->get_permission("mod_droplist", "delete")) return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_error_delete_nopermission, $entryname)."</div>";


		if (!MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT itemid FROM server_item_idtable WHERE itemid='".mysql_real_escape_string($itemid)."' LIMIT 1")) OR !MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT ".$florensia->get_columnname("npcid", "npc")." FROM server_npc WHERE ".$florensia->get_columnname("npcid", "npc")."='".mysql_real_escape_string($npcid)."' LIMIT 1"))) {
			return "<div class='warning' style='text-align:center'>{$flolang->droplist_updateentry_error_falseid}</div>";
		}

		$entryname = $stringtable->get_string($itemid, array('protectionsmall'=>1))."/".$stringtable->get_string($npcid, array('protectionsmall'=>1));
		#get dropid
		list($dropid, $thumpsup, $thumpsdown) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT dropid, thumpsup, thumpsdown FROM flobase_droplist WHERE itemid='".mysql_real_escape_string($itemid)."' AND npcid='".mysql_real_escape_string($npcid)."'"));
		if (!$dropid) {
			#not yet listed
			MYSQL_QUERY("INSERT INTO flobase_droplist (itemid, npcid) VALUES('".mysql_real_escape_string($itemid)."', '".mysql_real_escape_string($npcid)."')");
			$dropid = mysql_insert_id();
			$votestats = array("thumpsup"=>0, "thumpsdown"=>0);
		} else $votestats = array("thumpsup"=>$thumpsup, "thumpsdown"=>$thumpsdown);

				unset($prerating, $preratingtime);
				$error = false;
			
				if ($action=="thumpsup" OR $action=="thumpsdown") {
					$rating = ($action=="thumpsup") ? 1 : -1;
					#any pre-ratings?
					if ($pre = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT rating, timestamp FROM flobase_droplist_ratings WHERE dropid='{$dropid}' AND userid='{$flouser->userid}'"))) {
						list($prerating, $preratingtime) = $pre;
					}
					if ($pre && intval($prerating)==$rating) {
						#already voted and nothing changed
						return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_verify_alreadyverified, $entryname, date("m.d.Y", $preratingtime))."</div>";
					} elseif ($pre) {
						#already voted but changed, update rating and timestamp
						if (!MYSQL_QUERY("UPDATE flobase_droplist_ratings SET rating='{$rating}', timestamp='".date("U")."' WHERE dropid='{$dropid}' AND userid='{$flouser->userid}'")) $error=true;
					} else {
						#net yet voted, insert new row
						if (!MYSQL_QUERY("INSERT INTO flobase_droplist_ratings (dropid, userid, rating, timestamp) VALUES('{$dropid}', '{$flouser->userid}', '{$rating}', '".date("U")."')")) $error=true;
					}
					
					if ($error) {
						$this->refresh($dropid); #refresh so the ghost (if any) is killed
						$flolog->add("error:droplist", "MySQL-Update-Error while $action on {npc:{$npcid}}/{item:{$itemid}}");
						return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_verify_error, $entryname)."</div>";
					}
					
					#no errors
					$votestats = $this->refresh($dropid); #refresh thumps-count
					if ($log) {
						if ($action=="thumpsup") $flolog->add("droplist:thumpsup", "{user:{$flouser->userid}} voted for {npc:{$npcid}}/{item:{$itemid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
						else $flolog->add("droplist:thumpsdown", "{user:{$flouser->userid}} voted against {npc:{$npcid}}/{item:{$itemid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
					}
					return "<div class='successful' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_verify_successful, $entryname)."</div>";
				}
				elseif ($action=="withdraw") {
					if (!MYSQL_QUERY("DELETE r, v FROM
								flobase_droplist_ratings as r
								LEFT JOIN flobase_droplist_verified as v ON(r.dropid=v.dropid)
								WHERE r.dropid='{$dropid}' AND r.userid='{$flouser->userid}'")) {
						$flolog->add("error:droplist", "MySQL-Delete-Error while withdraw from {npc:{$npcid}}/{item:{$itemid}}");
						return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_withdraw_error, $entryname)."</div>";
					}
					$votestats = $this->refresh($dropid); #refresh thumps-count
					if ($log) $flolog->add("droplist:withdraw", "{user:{$flouser->userid}} withdraw from {npc:{$npcid}}/{item:{$itemid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
					return "<div class='successful' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_withdraw_successful, $entryname)."</div>";
				}
				elseif ($action=="delete") {
						if (!MYSQL_QUERY("DELETE d,r,v,q
							FROM flobase_droplist as d 
							LEFT JOIN flobase_droplist_ratings as r ON (r.dropid=d.dropid)
							LEFT JOIN flobase_droplist_verified as v ON (v.dropid=d.dropid)
							LEFT JOIN flobase_droplist_quest as q ON (q.dropid=d.dropid)
							WHERE d.dropid='{$dropid}'")) {
							$flolog->add("error:droplist", "MySQL-Delete-Error while delete {npc:{$npcid}}/{item:{$itemid}}");
							return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_delete_error, $entryname)."</div>";
						}
						else {
							if ($log) $flolog->add("droplist:delete", "{user:{$flouser->userid}} deleted {npc:{$npcid}}/{item:{$itemid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
							return "<div class='successful' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_delete_successful, $entryname)."</div>";
						}
				}
				elseif ($action=="verified") {					
					list($vtime) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT timestamp FROM flobase_droplist_verified WHERE dropid='{$dropid}' AND userid='{$userid}'"));
					if ($vtime) return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_verify_error_alreadyverified, $entryname, date("m.d.y H:i", $vtime))."</div>";
					
					#net yet verified
					if (!MYSQL_QUERY("INSERT INTO flobase_droplist_verified (dropid, userid, timestamp) VALUES('{$dropid}', '{$flouser->userid}', '".date("U")."') ON DUPLICATE KEY UPDATE timestamp='".date("U")."'")) {
						$flolog->add("error:droplist", "MySQL-Insert-Error while verify {npc:{$npcid}}/{item:{$itemid}}");
						return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_adminverify_error, $entryname)."</div>";	
					}
					#successfully
					$this->updateentry($itemid, $npcid, "thumpsup", false); #add also a thumpsup
					
					if ($log) $flolog->add("droplist:verified", "{user:{$flouser->userid}} verified {npc:{$npcid}}/{item:{$itemid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
					return "<div class='successful' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_adminverify_successful, $entryname)."</div>";					
				}
				elseif ($action=="unverified") {
					if (!MYSQL_QUERY("DELETE r,v
						    FROM flobase_droplist_verified as v
						    LEFT JOIN flobase_droplist_ratings as r ON (v.dropid=r.dropid AND r.userid=v.userid)
						    WHERE v.dropid='{$dropid}'")) {
						$flolog->add("error:droplist", "MySQL-Update-Error while remove verified status on {npc:{$npcid}}/{item:{$itemid}}");
						return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_unverify_error, $entryname)."</div>";
					}
					
					$votestats = $this->refresh($dropid);
					
					if (!mysql_affected_rows()) {
						return "<div class='bordered' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_error_unverified_notverified, $entryname)."</div>";
					}
					
					if ($log) $flolog->add("droplist:unverified", "{user:{$flouser->userid}} removed verified status on {npc:{$npcid}}/{item:{$itemid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
					return "<div class='successful' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_unverify_successful, $entryname)."</div>";
				}
/*
				if ($action=="thumpsup") {
					$sqlquery = "INSERT INTO flobase_item_droplist (itemid, npcid, thumpsup, userlist) VALUES('".mysql_real_escape_string($itemid)."', '".mysql_real_escape_string($npcid)."', 1, '".$flouser->userid."-1-".date("U")."')";
				}
				elseif ($action=="thumpsdown") {
					$sqlquery = "INSERT INTO flobase_item_droplist (itemid, npcid, thumpsdown, userlist) VALUES('".mysql_real_escape_string($itemid)."', '".mysql_real_escape_string($npcid)."', 1, '".$flouser->userid."-0-".date("U")."')";
				} else return;
				
				if (!MYSQL_QUERY($sqlquery)) {
					$flolog->add("error:droplist", "MySQL-Insert-Error while $action on {npc:{$npcid}}/{item:{$itemid}}");
					return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_verify_error, $entryname)."</div>";
				}
				else {
					if ($log) $flolog->add("droplist:new", "{user:{$flouser->userid}} created new entry {npc:{$npcid}}/{item:{$itemid}}");
					return "<div class='successful' style='text-align:center'>".$flolang->sprintf($flolang->droplist_updateentry_verify_successful, $entryname)."</div>";
				}
*/
	}

	function refresh($dropid) {
		$q = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(r.userid) as users, SUM(r.rating) as rating, COUNT(q.questid) as quests
			    FROM flobase_droplist as d
			    LEFT JOIN flobase_droplist_ratings as r ON (r.dropid=d.dropid)
			    LEFT JOIN flobase_droplist_quest as q ON (q.dropid=d.dropid)
			    WHERE d.dropid='".intval($dropid)."'"));
		if (!$q['users'] && !$q['quests']) {
			#no ratings or quests were found -> ghost
			MYSQL_QUERY("DELETE FROM flobase_droplist WHERE dropid='".intval($dropid)."'");
			return array("thumpsup"=>"-", "thumpsdown"=>"-");
		}
		
		$thumpsdown = ($q['users']-$q['rating'])/2;
		$thumpsup = $q['users']-$thumpsdown;
		MYSQL_QUERY("UPDATE flobase_droplist SET thumpsup='{$thumpsup}', thumpsdown='{$thumpsdown}' WHERE dropid='{$dropid}'");
		return array("thumpsup"=>$thumpsup, "thumpsdown"=>$thumpsdown);
	}
	
	
	function get_droplist($dbid, $act="item") {
		global $flolang, $florensia, $stringtable, $flouser, $flouserdata, $classquest, $classvote;

			//db-work
			foreach ($_POST as $postkey => $postvalue) {
				if (preg_match('/^droplist_(thumpsup|thumpsdown|withdraw|selected)_([a-z0-9]+)_([a-z0-9]+)_x$/', $postkey, $dbkey)) {
					if ($dbkey[1]=="selected") {
						if (!$_POST['do_selected'] OR !($_POST['action_selected']=="thumpsdown" OR $_POST['action_selected']=="thumpsup" OR $_POST['action_selected']=="withdraw" OR ($flouser->get_permission("mod_droplist", "delete") && $_POST['action_selected']=="delete") OR ($flouser->get_permission("mod_droplist", "verify") && ($_POST['action_selected']=="verified" OR $_POST['action_selected']=="unverified")))) next;
						$dbkey[1] = $_POST['action_selected'];
					}
					$florensia->notice($this->updateentry($dbkey[2], $dbkey[3], $dbkey[1]));
				}
			}

			if ($act=="item") {
				$querystring = "SELECT d.dropid, itemid, npcid, thumpsup, thumpsdown, COUNT(v.userid) as vusers, COUNT(q.questid) as quests FROM flobase_droplist as d LEFT JOIN flobase_droplist_verified as v ON (v.dropid=d.dropid) LEFT JOIN flobase_droplist_quest as q ON (q.dropid=d.dropid) WHERE itemid='".mysql_real_escape_string($dbid)."' GROUP BY d.dropid ORDER BY thumpsup DESC, thumpsdown";
				$droplisttitle = $flolang->sprintf($flolang->droplist_title_npc, $stringtable->get_string($dbid, array('protectionsmall'=>1)));
				$dropentryaddtolist = "
					<input type='hidden' value='".$florensia->escape($dbid)."' name='itemid'>
					<input type='submit' value='{$flolang->droplist_search_npc}'>
				";
			}
			else {
				$querystring = "SELECT d.dropid, itemid, npcid, thumpsup, thumpsdown, COUNT(v.userid) as vusers, COUNT(q.questid) as quests FROM flobase_droplist as d LEFT JOIN flobase_droplist_verified as v ON (v.dropid=d.dropid) LEFT JOIN flobase_droplist_quest as q ON (q.dropid=d.dropid) WHERE npcid='".mysql_real_escape_string($dbid)."' GROUP BY d.dropid ORDER BY thumpsup DESC, thumpsdown";
				$droplisttitle = $flolang->sprintf($flolang->droplist_title_item, $stringtable->get_string($dbid, array('protectionsmall'=>1)));
				$dropentryaddtolist = "
					<input type='hidden' value='".$florensia->escape($dbid)."' name='npcid'>
					<input type='submit' value='{$flolang->droplist_search_item}'>
				";
			}
			
			$querydroplist = MYSQL_QUERY($querystring);
			while ($droplist = MYSQL_FETCH_ARRAY($querydroplist)) {

				if ($act=="item") {
					$npc = new floclass_npc($droplist['npcid']);
					$dropentrydetails = $npc->shortinfo();
					$adminlogfileid = "{npc:{$droplist['npcid']}}/{item:{$dbid}}";
				}
				else {
					$item = new floclass_item($droplist['itemid']);
					$dropentrydetails = $item->shortinfo(array('marketlist'=>true));
					$adminlogfileid = "{npc:{$dbid}}/{item:{$droplist['itemid']}}";
				}

					$colorchange = 1;
					unset($smallentrynotice, $adminverifiedlist);
					$droplistlevel = "normal";
					
					if ($droplist['quests']) {
						$questquery = MYSQL_QUERY("SELECT questid, droprate FROM flobase_droplist_quest WHERE dropid='{$droplist['dropid']}'");
						while ($questdrop = MYSQL_FETCH_ARRAY($questquery)) {
							$smallentrynotice = "<br />".$flolang->sprintf($flolang->droplist_list_notice_questentry, bcdiv(intval($questdrop['droprate']), 10)."%")." [<a href='".$florensia->outlink(array("questdetails", $questdrop['questid'], $classquest->get_title($questdrop['questid'])))."'>Q</a>]";	
						}						
						$colorchange = "droplist_questentry";
						$droplistlevel = "questentry";
					}
					if ($droplist['vusers']) {
						$smallentrynotice .= "<br />{$flolang->droplist_list_notice_verifiedentry}";
						$colorchange = "droplist_verified";
						$droplistlevel = "verified";
					}
					if ($smallentrynotice) $smallentrynotice = "<span style='font-size:90%;'>$smallentrynotice</span>";
					$droplist['thumpsdown'] = 0-$droplist['thumpsdown'];

					if ($flouser->userid) {
						$vote = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT rating, timestamp FROM flobase_droplist_ratings WHERE dropid='{$droplist['dropid']}' AND userid='{$flouser->userid}'"));
						if ($vote) {
							if ($vote['rating']>0) $droplist['thumpsup'] = "<span style='text-decoration:underline;''>{$droplist['thumpsup']}</span>";
							else $droplist['thumpsdown'] = "<span style='text-decoration:underline;'>{$droplist['thumpsdown']}</span>";
							
							$verifydetails = "
								".$flolang->sprintf($flolang->droplist_quick_verify_alreadyverified_notice, date("m.d.Y", $vote['timestamp']))."<br />
								<span style='font-weight:bold; font-size:150%;'>{$droplist['thumpsup']}/{$droplist['thumpsdown']}</span>
								<input type='image' name='droplist_withdraw_{$droplist['itemid']}_{$droplist['npcid']}' src='{$florensia->layer_rel}/withdraw.gif' style='background-color:transparent; border:0px; height:18px;'>
							";
						}
						else {
							$verifydetails = "
									<input type='image' name='droplist_thumpsup_{$droplist['itemid']}_{$droplist['npcid']}' src='{$florensia->layer_rel}/thumpsup.gif' style='background-color:transparent; border:0px; width:15px; height:18px;'>
									<span style='font-weight:bold; font-size:150%; margin-left:4px; margin-right:4px;'>{$droplist['thumpsup']}/{$droplist['thumpsdown']}</span>
									<input type='image' name='droplist_thumpsdown_{$droplist['itemid']}_{$droplist['npcid']}' src='{$florensia->layer_rel}/thumpsdown.gif' style='background-color:transparent; border:0px; width:15px; height:18px;'>
							";
						}
					}
					else { $verifydetails = "<span style='font-weight:bold; font-size:150%; margin-left:4px; margin-right:4px;'>{$droplist['thumpsup']}/{$droplist['thumpsdown']}</span><br />{$flolang->droplist_quick_notloggedin}"; }

					if ($flouser->get_permission("add_droplist")) {
						$quickselect = "<input type='checkbox' name='droplist_selected_{$droplist['itemid']}_{$droplist['npcid']}_x' value='1'>";
					}
					else unset($quickselect);

					if ($flouser->get_permission("mod_droplist", "contributed")) {
						$rating = array("up"=>array(), "down"=>array());
						$userquery = MYSQL_QUERY("SELECT r.timestamp, r.userid, r.rating, v.userid as verified FROM flobase_droplist_ratings as r LEFT JOIN flobase_droplist_verified as v ON (v.dropid=r.dropid AND v.userid=r.userid) WHERE r.dropid='{$droplist['dropid']}' ORDER BY r.timestamp");
						while ($user = MYSQL_FETCH_ARRAY($userquery)) {
							$cat = ($user['rating']>0) ? "up" : "down";
							if ($user['verified']) $rating[$cat][] = date("m.d.y", $user['timestamp']).": <span style='text-decoration:underline;'>".$flouserdata->get_username($user['userid'])."</span>";
							else $rating[$cat][] = date("m.d.y", $user['timestamp']).": ".$flouserdata->get_username($user['userid']);
						}
						$contributed = "
							<table class='small' style='width:100%'>
								<tr><td style='text-align:center; width:50%;'><img src='{$florensia->layer_rel}/thumpsup.gif' alt='ThumpsUp' style='width:10px; height:13px;'></td><td style='text-align:center;'><img src='{$florensia->layer_rel}/thumpsdown.gif' alt='ThumpsDown' style='width:10px; height:13px;'></td></tr>
								<tr><td>".join("<br>", $rating['up'])."</td><td>".join("<br>", $rating['down'])."</td></tr>
							</table>";
			
						$adminuserlist = "
									<td style='width:5px;'></td>
									<td class='shortinfo_{$colorchange}' style='width:10px; vertical-align:top;'>
										<span ".popup("<div class='shortinfo_{$colorchange}' style='width:450px'>{$contributed}</div>", "LEFT, MOUSEOFF, STICKY").">C</span>
										<a href='{$florensia->root}/adminlog?section=droplist&amp;logvalue=".urlencode($adminlogfileid)."' target='_blank'>L</a>
									</td>
								";
					}
					else unset($adminuserlist);
					
					$droplistcontent[$droplistlevel] .= $florensia->adsense(8)."
						<div style='margin-bottom:5px;'>
							<table style='width:100%' class='small'>
								<tr>
									<td class='shortinfo_{$colorchange}' style='vertical-align:top; width:650px;'>
										{$dropentrydetails}
									</td>
									<td style='width:15px;'>{$quickselect}</td>
									<td class='shortinfo_{$colorchange}' style='text-align:center; vertical-align:middle;'>
										$verifydetails
										$smallentrynotice
									</td>
									{$adminuserlist}
								</tr>
							</table>
						</div>
					";

			}

			unset($quickselect);
			if (!$droplistcontent) {
				$droplistcontent = "<div class='small bordered' style='text-align:center;'>{$flolang->droplist_list_noentry}</div>";
			}
			else {
				$droplistcontent = $droplistcontent['verified'].$droplistcontent['questentry'].$droplistcontent['normal'];
				
				if ($flouser->get_permission("add_droplist") OR $flouser->get_permission("mod_droplist", "verify") OR $flouser->get_permission("mod_droplist", "delete")) {
					$quickselect = "
						<div class='small subtitle' style='padding:2px; text-align:right;'>
							<select name='action_selected'>
								<option value='thumpsup'>{$flolang->droplist_quick_thumpsup}</option>
								<option value='thumpsdown'>{$flolang->droplist_quick_thumpsdown}</option>
								<option value='withdraw'>{$flolang->droplist_quick_withdraw}</option>";
								if ($flouser->get_permission("mod_droplist", "delete")) $quickselect .= "<option value='delete'>{$flolang->droplist_quick_delete}</option>";
								if ($flouser->get_permission("mod_droplist", "verify")) {
									$quickselect .= "
										<option value='verified'>{$flolang->droplist_quick_verify}</option>
										<option value='unverified'>{$flolang->droplist_quick_unverify}</option>
									";
								}
							$quickselect .= "</select>
							<input type='submit' name='do_selected' value='{$flolang->droplist_quick_submit}'>
						</div>
					";
				}
			}

			$whatsthat = "<div style='width:300px;'>
				<div class='small shortinfo_".$florensia->change()."'>".$flolang->droplist_whatsthis_title."</div>
				<div class='small'>".$flolang->droplist_whatsthis_text."</div>
			</div>
			";
			return "
				<div class='subtitle small' style='text-align:center'>{$droplisttitle} (<span class='small' style='font-weight:normal;' ".popup($whatsthat).">{$flolang->droplist_whatsthis_title}</span>)</div>
				<div>
					<form action='".$florensia->escape($florensia->request_uri(array(), 'droplist'))."' method='post'>
					{$droplistcontent}
					{$quickselect}
					</form>
				</div>
				<div class='subtitle small' style='padding:2px; text-align:right'>
					<form action='{$florensia->root}/droplist.php' method='get'>
						{$flolang->droplist_list_addentry}
						<input type='text' name='search'>
						<input type='hidden' value='{$stringtable->language}' name='names'>
						{$dropentryaddtolist}
					</form>
				</div>
			";
	}
	
	function get_tabdesc($dbid, $act) {
		global $flolang;
			if ($act=="item") {
				list($amount) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(dropid) from flobase_droplist WHERE itemid='".mysql_real_escape_string($dbid)."'"));
			}
			else {
				list($amount) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(dropid) from flobase_droplist WHERE npcid='".mysql_real_escape_string($dbid)."'"));
			}
		return $flolang->sprintf($flolang->tabbar_desc_droplist, $amount);
	}
}

?>
