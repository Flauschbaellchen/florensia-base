<?php

class class_user {
	
	var $userid = 0;
	var $userpermissions = array();
	var $permissions = array();
	var $getdbentry = FALSE;
	var $user = array();
        var $privacy = array('done'=>false, 'guildlist'=>array(), 'characterlist'=>array());

	function class_user($userid) {
                global $flouserdata;
                $userid = intval($userid);
		if ($userid) {
			$this->user = MYSQL_FETCH_ASSOC(MYSQL_QUERY("SELECT * FROM forum_users WHERE uid='{$userid}'"));
                         //this is the second check because mybb did this already.. however its more secure to do it on my own
                         //also if we manually load users then this would be the first check ;)
                         
			if ($this->user) {
                            //user was found
                            $this->userid = $this->user['uid'];
                
                            $queryuser = MYSQL_QUERY("SELECT permissions, rank, title, inactive FROM flobase_user WHERE userid='{$this->userid}'");
                            if ($user = MYSQL_FETCH_ASSOC($queryuser)) {
                                    $this->getdbentry = TRUE;
                                    
                                    $this->title = $user['title'];
                                    $this->rank = $user['rank'];
                                    $this->inactive = $user['inactive'];
                                    
                                    $temp = $flouserdata->parse_permissions($user['permissions']);
                                    $this->userpermissions = $temp['permissions'];
                                    if ($temp['need_update']) $this->update_permissions($this);
                            }
                        }
                    $this->permissions = array_merge($flouserdata->defaultpermissions, $this->userpermissions);
		} else {
                    $this->permissions = $flouserdata->defaultpermissions;
                }		
	}

	function get_permission($perm, $section=false) {
		if (!$this->userid) return false;
		if ($section) {
			if ($this->permissions[$perm]['type']=="grand" && (in_array("*", $this->permissions[$perm]['sections']) OR in_array($section, $this->permissions[$perm]['sections']))) return $this->permissions[$perm]['sections'];
		}
		elseif ($this->permissions[$perm]['type']=="grand") {
			if ($this->permissions[$perm]['sections']) return $this->permissions[$perm]['sections'];
			return true;
		}
		return false;
	}
        
        function get_privacy() {
            if ($this->privacy['done'] OR !$this->userid OR !strlen($this->user['flobase_characterkey'])) return $this->privacy;
            $cq = MYSQL_QUERY("SELECT characterid, guildid FROM flobase_character_data WHERE ownerid='{$this->userid}'");
            while ($c = MYSQL_FETCH_ARRAY($cq)) {
                $this->privacy['characterlist'][] = $c['characterid'];
                $c['guildid'] = intval($c['guildid']);
                if ($c['guildid']) $this->privacy['guildlist'][] = $c['guildid'];
            }
            $this->privacy['done'] = true;
            return $this->privacy;
        }
/*
        function get_username($userid=false, $settings=array()) {
                global $flouserdata;
                return $flouserdata->get_username($userid, $settings);
        }
*/
	function noaccess($error="") {
                global $flouserdata;
                return $flouserdata->noaccess($error);
	}
}

class class_userdata {
    
        function class_userdata() {
		$querydefaults = MYSQL_QUERY("SELECT settings FROM flobase_defaults WHERE title='userpermissions_set'");
		$defaults = MYSQL_FETCH_ARRAY($querydefaults);
		$defaulttemp = $this->parse_permissions($defaults['settings']);
		$this->defaultpermissions = $defaulttemp['permissions'];
		if ($defaulttemp['need_update']) $this->update_permissions(0);            
        }
    
	function noaccess($error="") {
		global $florensia, $flouser, $flolang;
		if ($error=="") $error = $flolang->user_noaccess_defaulterror;
		if (!$flouser->userid) $loginform = "<div style='text-align:center; margin-top:10px;'>".$florensia->login()."</div>";
		return "<div class='warning' style='text-align:center'>$error</div>$loginform";
	}
        
	function update_permissions($userclass) {
		if (!$userclass) { $perm = $this->defaultpermissions; }
		else { $perm = $userclass->userpermissions; }
		foreach($perm as $permkey => $permvalue) {
			$temp[] = "{$permkey}:{$permvalue['type']}:{$permvalue['lifetime']}:".join(",",$permvalue['sections']);
		}
		$perm = join(";", $temp);
		if (!$userclass) {
			MYSQL_QUERY("UPDATE flobase_defaults SET settings='".mysql_real_escape_string($perm)."' WHERE title='userpermissions_set");
		}
		elseif ($userclass->getdbentry) {
			MYSQL_QUERY("UPDATE flobase_user SET permissions='".mysql_real_escape_string($perm)."' WHERE userid='{$userclass->userid}'");
		}
		else {
			MYSQL_QUERY("INSERT INTO flobase_user (userid, permissions) VALUES($userclass->userid, '".mysql_real_escape_string($perm)."')");
		}
	}
	
	function parse_permissions($string) {
		$return['need_update'] = FALSE;
		foreach(explode(";", $string) as $perm) {
			$perm = explode(":", $perm); //0=id, 1=[grand|revoke] 2=until timestamp
			$perm[2] = intval($perm[2]);
			if ($perm[2]==0 OR $perm[2]>date("U")) $return['permissions'][$perm[0]] = array("type"=>$perm[1], "lifetime"=>$perm[2], "sections"=>explode(",",$perm[3]));
			else $return['need_update'] = TRUE;
		}
		return $return;
	}
        
        function get_username($userid=false, $settings=array()) {
            global $florensia, $flouser;

            $defaultsettings = array("rawoutput"=>0);
            $settings = array_merge($defaultsettings, $settings);
            
            if ($userid===FALSE OR $userid==$flouser->userid) {
                $nickname = $flouser->user['username'];
                $userid = $flouser->userid;
            }
            elseif ($userid==0) return "Guest";
            else {
			if (!($user = MYSQL_FETCH_ASSOC(MYSQL_QUERY("SELECT username FROM forum_users WHERE uid='{$userid}'")))) { return "Guest"; }
                        $nickname = $user['username'];
            }
            
            if ($settings['rawoutput']) return $nickname;
            $return = "<a href='{$florensia->forumurl}/user-{$userid}.html' target='_blank'>".$florensia->escape($nickname)."</a>";

            if ($flouser->get_permission("mod_usernotes") OR $flouser->get_permission("mod_coordinates") OR $flouser->get_permission("mod_droplist", "contributed")) {
                $adminlinks[] = "<a href='".$florensia->outlink(array('contributed', $userid, $nickname))."' target='_blank'>C</a>";
            }
            if ($flouser->get_permission("character", "moderate")) {
                $adminlinks[] = "<a href='".$florensia->outlink(array('usercharacter', $userid, $nickname))."' target='_blank'>I</a>";
            }
            if ($flouser->get_permission("mod_permission")) {
                $adminlinks[] = "<a href='{$florensia->root}/adminpermission.php?userid={$userid}' target='_blank'>P</a>";
            }
            if ($flouser->get_permission("watch_log")) {
                $adminlinks[] = "<a href='{$florensia->root}/adminlog?currentuser={$userid}' target='_blank'>L</a>";
            }
            if (count($adminlinks)) { $return .= " <span class='small' style='font:weight:normal'>[".join("|", $adminlinks)."]</span>"; }
            return $return;
        }
        
}

?>