<?PHP

class class_guide {

	function updateentry($guideid, $action="thumpsup", $log=true) {
		$guideid = intval($guideid);
		global $flolang, $flouser, $flouserdata, $florensia, $flolog, $classvote;
		//not logged in
		if (!$flouser->userid) return "<div class='warning' style='text-align:center'>{$flolang->guides_updateentry_error_notloggedin}</div>";
		//banned from droplist
		if (!$flouser->get_permission("guides", "vote")) return "<div class='warning' style='text-align:center'>{$flolang->guides_updateentry_error_banned}</div>";

		$verifylinequerystring = "SELECT id, thumpsup, thumpsdown, userlist, title FROM flobase_guides WHERE id={$guideid} LIMIT 1";
		$verifylinequery = MYSQL_QUERY($verifylinequerystring);
		if ($verifyline = MYSQL_FETCH_ARRAY($verifylinequery)) {
			$entryname = $verifyline['title'];
			if (strlen($entryname)>60) $entryname = substr($entryname, 0, 60)."...";
				
			$votestatus = $classvote->votestatus($verifyline['userlist']);
			
				if ($action=="thumpsup" OR $action=="thumpsdown") {
						if (isset($votestatus['vote']) && ($action=="thumpsup" && $votestatus['vote'] OR $action=="thumpsdown" && !$votestatus['vote'])) {
							return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->guides_updateentry_verify_alreadyverified, $entryname, date("m.d.Y", $votestatus['dateline']))."</div>";
						}
						else {
							$newuserline = $classvote->vote($action, $verifyline['userlist']);
							$newuserline = $newuserline['userline'];
							$votestats = $classvote->votestats($newuserline);
							
							if (!MYSQL_QUERY("UPDATE flobase_guides SET thumpsup={$votestats['thumpsup']}, thumpsdown={$votestats['thumpsdown']}, userlist='{$newuserline}' WHERE id='{$verifyline['id']}'")) {
								$flolog->add("error:guide", "MySQL-Update-Error while $action on {guide:{$guideid}}");
								return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->guides_updateentry_verify_error, $entryname)."</div>";
							}
							else {
								if ($log) {
									if ($action=="thumpsup") $flolog->add("guide:thumpsup", "{user:{$flouser->userid}} voted for {guide:{$guideid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
									else $flolog->add("guide:thumpsdown", "{user:{$flouser->userid}} voted against {guide:{$guideid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
								}
								return "<div class='successful' style='text-align:center'>".$flolang->sprintf($flolang->guides_updateentry_verify_successful, $entryname)."</div>";
							}
						}
				}
				elseif ($action=="withdraw") {
					if (!$votestatus) { return false; }
					else {
							$newuserline = $classvote->vote("withdraw", $verifyline['userlist']);
							$newuserline = $newuserline['userline'];
							$votestats = $classvote->votestats($newuserline);
							
							if (!MYSQL_QUERY("UPDATE flobase_guides SET thumpsup={$votestats['thumpsup']}, thumpsdown={$votestats['thumpsdown']}, userlist='{$newuserline}' WHERE id='{$verifyline['id']}'")) {
								$flolog->add("error:guide", "MySQL-Update-Error while withdraw from {guide:{$guideid}}");
								return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->guides_updateentry_withdraw_error, $entryname)."</div>";
							}
							else {
								if ($log) $flolog->add("guide:withdraw", "{user:{$flouser->userid}} withdraw from {guide:{$guideid}} ({$votestats['thumpsup']}/{$votestats['thumpsdown']})");
								return "<div class='successful' style='text-align:center'>".$flolang->sprintf($flolang->guides_updateentry_withdraw_successful, $entryname)."</div>";
							}
					}
				}
				elseif ($action=="delete") {
						if (!$flouser->get_permission("guides", "delete")) return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->guides_updateentry_error_delete_nopermission, $entryname)."</div>";
						if (!MYSQL_QUERY("DELETE FROM flobase_guides WHERE id='{$verifyline['id']}'")) {
							$flolog->add("error:guide", "MySQL-Delete-Error while delete {guide:{$guideid}}");
							return "<div class='warning' style='text-align:center'>".$flolang->sprintf($flolang->guides_updateentry_delete_error, $entryname)."</div>";
						}
						else {
							if ($log) $flolog->add("guide:delete", "{user:{$flouser->userid}} deleted {guide:{$guideid}} ({$verifyline['thumpsup']}/{$verifyline['thumpsdown']})");
							return "<div class='successful' style='text-align:center'>".$flolang->sprintf($flolang->guides_updateentry_delete_successful, $entryname)."</div>";
						}
				}
		}
		else {
			return "<div class='warning' style='text-align:center'>{$flolang->guides_updateentry_error_falseid}</div>";
		}

	}
}

?>