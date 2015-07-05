<?PHP

class class_gallery {

	function updateentry($imageid, $action="thumpsup", $log=true) {
		$imageid = intval($imageid);
		global $flolang, $flouser, $flouserdata, $florensia, $flolog, $classvote;
		//not logged in
		if (!$flouser->userid) return "<div class='warning' style='text-align:center'>{$flolang->gallery_vote_error_notloggedin}</div>";
		//banned from droplist
		if (!$flouser->get_permission("gallery", "vote")) return "<div class='warning' style='text-align:center'>{$flolang->gallery_vote_error_banned}</div>";

		$verifylinequerystring = "SELECT galleryid, thumpsup, thumpsdown, userlist, name FROM flobase_gallery WHERE galleryid='{$imageid}' LIMIT 1";
		$verifylinequery = MYSQL_QUERY($verifylinequerystring);
		if ($verifyline = MYSQL_FETCH_ARRAY($verifylinequery)) {
			$entryname = $verifyline['name'];
			if (strlen($entryname)>60) $entryname = substr($entryname, 0, 60)."...";
				
			$votestatus = $classvote->votestatus($verifyline['userlist']);
			
				if ($action=="thumpsup" OR $action=="thumpsdown") {
						if (isset($votestatus['vote']) && ($action=="thumpsup" && $votestatus['vote'] OR $action=="thumpsdown" && !$votestatus['vote'])) {
							return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->gallery_vote_already_voted, $entryname, date("m.d.Y", $votestatus['dateline']))."</div>";
						}
						else {
							$newuserline = $classvote->vote($action, $verifyline['userlist']);
							$newuserline = $newuserline['userline'];
							$votestats = $classvote->votestats($newuserline);
							
							if (!MYSQL_QUERY("UPDATE flobase_gallery SET thumpsup='{$votestats['thumpsup']}', thumpsdown='{$votestats['thumpsdown']}', userlist='{$newuserline}' WHERE galleryid='{$verifyline['galleryid']}'")) {
								$flolog->add("error:gallery", "MySQL-Update-Error while $action on {gallery:{$imageid}}");
								return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->gallery_vote_error, $entryname)."</div>";
							}
							else {
								if ($log) {
									if ($action=="thumpsup") $flolog->add("gallery:thumpsup", "{user:{$flouser->userid}} voted for {gallery:{$imageid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
									else $flolog->add("gallery:thumpsdown", "{user:{$flouser->userid}} voted against {gallery:{$imageid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
								}
								return "<div class='successful' style='text-align:center'>".$flolang->sprintf($flolang->gallery_vote_successful, $entryname)."</div>";
							}
						}
				}
				elseif ($action=="withdraw") {
					if (!$votestatus) { return false; }
					else {
							$newuserline = $classvote->vote("withdraw", $verifyline['userlist']);
							$newuserline = $newuserline['userline'];
							$votestats = $classvote->votestats($newuserline);
							
							if (!MYSQL_QUERY("UPDATE flobase_gallery SET thumpsup='{$votestats['thumpsup']}', thumpsdown='{$votestats['thumpsdown']}', userlist='{$newuserline}' WHERE galleryid='{$verifyline['galleryid']}'")) {
								$flolog->add("error:gallery", "MySQL-Update-Error while withdraw from {gallery:{$imageid}}");
								return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->gallery_vote_withdraw_error, $entryname)."</div>";
							}
							else {
								if ($log) $flolog->add("gallery:withdraw", "{user:{$flouser->userid}} withdraw from {gallery:{$imageid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
								return "<div class='successful' style='text-align:center'>".$flolang->sprintf($flolang->gallery_withdraw_successful, $entryname)."</div>";
							}
					}
				}
		}
		else {
			return "<div class='warning' style='text-align:center'>{$flolang->gallery_error_nosuchid}</div>";
		}

	}
	
	function check_privacy($image) {
	    global $flouser, $florensia, $flolang;
	    $privacy = str_split($image['pflags']);
	    $privacyaccess = false;
	    if (in_array("a", $privacy) OR $flouser->userid==$image['userid'] OR $flouser->get_permission("gallery", "moderate")) $privacyaccess = true;
	    else {
		$privacyreasons = array();
		$userprivacy = $flouser->get_privacy();
		
		foreach($privacy as $flag) {
		    switch ($flag) {
			case "r": {
			    if ($flouser->userid) $privacyaccess = true;
			    else $privacyreasons[] = $flolang->gallery_error_nopermission_registererduser;
			    break;
			}
			case "c": {
			    $charlist = array();
			    foreach(explode(",", $image['characterlinkedlist']) as $c) {
				if (!$c) continue;
				if (in_array($c, $userprivacy['characterlist'])) $privacyaccess=true;
				$charlist[] = "<a href='".$florensia->outlink(array("characterdetails", $c))."'>".$florensia->escape($c)."</a>";
			    }

			    if (!$privacyaccess && count($charlist)) {
				$privacyreasons[] = $flolang->sprintf($flolang->gallery_error_nopermission_linkedcharacter, join(", ", $charlist));
			    }
			    break;
			}
			case "g": {
			    $guildlist = array();
			    $queryguild=array();
			    foreach(explode(",", $image['guildlinkedlist']) as $g) {
				$g = intval($g);
				if (!$g) continue;
				if (in_array($g, $userprivacy['guildlist'])) $privacyaccess=true;
				$queryguild[] = "guildid='{$g}'";
			    }

			    if (!$privacyaccess) {
				$privacyreasons[] = $flolang->gallery_error_nopermission_linkedguild;
			    }
			    break;
			}
			case "b": {
			    $creator = new class_user($image['userid']);
			    if (in_array("{$flouser->userid}", explode(",", $creator->user['buddylist']))) $privacyaccess = true;
			    else $privacyreasons[] = $flolang->gallery_error_nopermission_buddylist;
			    break;
			}
			case "u": {
			    if (in_array($flouser->userid, explode(",", $image['pusers']))) $privacyaccess = true;
			    else $privacyreasons[] = $flolang->gallery_error_nopermission_linkeduser;
			    break;
			}
		    }
		    if ($privacyaccess) break;
		}
	    }
	    return array("access"=>$privacyaccess, "reason"=>$privacyreasons);
	}
}

?>