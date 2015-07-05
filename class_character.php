<?php

class class_character {
    
    private $archiv = false;
    private $error = true;
    private $errormsg = "-";
    public $data = null;
    
    function __construct($character, $settings=array()) {
        $defaultsettings = array('archivid'=>false);
        $settings = array_merge($defaultsettings, $settings);
        if (is_int($character)) {
            //we get already an ID, check if active character
            if (!$settings['archivid']) $this->data = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM flobase_character as c, flobase_character_data as d WHERE c.characterid=d.characterid AND c.characterid='{$character}'"));
            if (!$this->data) { //if not, maybe we get an archiv-character
                $this->data = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM flobase_character_archiv as c, flobase_character_data as d WHERE c.characterid=d.characterid AND c.characterid='{$character}'"));
                if (!$this->data) {//ooww... even that's not a hit
                    $this->error = "notfound";
                } else {
                    $this->archiv = true;
                    $this->error = false;
                    $this->data['updatepriority']='-';
                }
            }
        } elseif (is_array($character)) { //character already loaded
            $this->error = false;
            $this->data = $character;
            
        } else {
            //same way twice...
            if (preg_match("/^[a-z0-9]{1,14}$/i", $character)) $this->data = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM flobase_character as c, flobase_character_data as d WHERE c.characterid=d.characterid AND c.charname='".mysql_real_escape_string($character)."'"));
            if (!$this->data) {//no hit? don't look into our archiv, because if we are searching via charname we don't need one. - take a look into our API
                    $data = $this->look_up($character);
                    global $flolang;
                    switch ($data) {
                        case "invalidname":
                        case "notfound": {
                            $this->error = "notfound";
                            $this->errormsg = $flolang->character_api_error_notfound;
                            break;
                        }
                        case "timeout": {
                            $this->error = "timeout";
                            $this->errormsg = $flolang->character_api_error_timeout;
                            break;
                        }
                        default: {
                            if (is_array($data)) {
                                $this->data = $data;
                                $this->error = false;
                            }
                        }
                    }
            } else {
                $this->error = false;
            }
        }
    }
    
    static function guildgrade($grade) {
        global $florensia;
        switch (intval($grade)) {
            case 5:
                $gradename = "Guild Master"; break;
            case 4:
                $gradename = "Second Guild Master"; break;
            case 3:
                $gradename = "Special Guild Member"; break;
            case 2:
                $gradename = "Elite Guild Member"; break;
            case 1:
                $gradename = "Basic Guild Member"; break;
            default: return false;
        }
        return "<img src='{$florensia->layer_rel}/guildgrade_{$grade}.png' alt='{$gradename}' title='{$gradename}' style='border:0px; vertical-align:bottom;'>";
    }
    
    static function random() {
      list($max_id) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT MAX(characterid) FROM flobase_character WHERE updatepriority>='15'"));
      $random_number = mt_rand(1, $max_id);
      $random_row = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM flobase_character as c, flobase_character_data as d WHERE c.characterid=d.characterid AND c.characterid>='{$random_number}' AND c.updatepriority>='15' ORDER BY c.characterid ASC LIMIT 1"));
      return new class_character($random_row);
    }
    
    public function is_valid() {
        return $this->error ? false : true;
    }
    
    public function get_error() {
        return $this->error;
    }
    public function get_errormsg() {
        return $this->errormsg;
    }
    
    public function is_archiv() {
        return $this->archiv;
    }
    
    public function is_owner() {
        global $flouser;
        if ($flouser->get_permission("character", "owneroverride")) return true;
        if (!$flouser->userid || !$this->data['ownerid']) return false;
        return ($flouser->userid==$this->data['ownerid']);
    }
    
    public function check_privacy($act) {
        global $flouser;
        
        if ($this->is_owner()) return true;
        //check always both ways, so it's faster in some cases
        switch ($act) {
            case "log_level":
            case "log_guild":
            case "friendlist":
                if ($act=="log_level") $priv = str_split($this->data['priv_log_level']);
                elseif ($act=="log_guild") $priv = str_split($this->data['priv_log_guild']);
                elseif ($act=="friendlist") $priv = str_split($this->data['priv_friends']);
                
                if (in_array("", $priv) OR in_array("a", $priv)) return true;
                
                //all following checks assums that it's a registered user.. so stop if it's not
                if (!$flouser->userid) return false;
                //easy checks with minimal load
                if (!$this->is_owner() && in_array("n", $priv)) return false;
                if ($flouser->userid && in_array("r", $priv)) return true;
                
                //now it's getting more tricky..
                $flouser->get_privacy();
                if (in_array("g", $priv) && in_array($this->data['guildid'], $flouser->privacy['guildlist'])) return true;
                
                $owner = new class_user($this->data['ownerid']);
                if (in_array("b", $priv) && in_array($flouser->userid, explode(",", $owner->user['buddylist']))) return true;
                
                
                // F-flag!

                break;
            //no other acts yet
        }
        return false;
    }
    
    public function get_link($settings=array()) {
        $defaults = array("guild"=>false, "server"=>false);
        $settings = array_merge($defaults, $settings);
        global $florensia;
        
        
        if ($this->is_archiv()) $link = "<a href='".$florensia->outlink(array("characterdetails", $this->data['charname']), array('archivid'=>$this->data['characterid']))."' class='archiv'>".$florensia->escape($this->data['charname'])."</a>";
        else $link = "<a href='".$florensia->outlink(array("characterdetails", $this->data['charname']))."'>".$florensia->escape($this->data['charname'])."</a>";
        
        if ($settings['guild'] && strlen($this->data['guild'])) {
            if ($this->is_archiv()) { //if not an archiv character, char is always in an active guild
                $guild = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT memberamount FROM flobase_guild WHERE guildid='{$this->data['guildid']}'"));
                if (!$guild['memberamount']) $class="class='archiv'";
            }
            
            if ($this->data['guildid']) $link .= " (<a href='".$florensia->outlink(array("guilddetails", $this->data['guildid'], $this->data['server'], $this->data['guild']))."' {$class}>".$florensia->escape($this->data['guild'])."</a>)";
            else $link .= "(".$this->data['guild'].")";
        }
        
        if ($settings['server']) $link .= ", <a href='".$florensia->outlink(array("statistics", $this->data['server']))."'>".$florensia->escape($this->data['server'])."</a>";
        return $link;
    }
    
    public function merge_opt_link($optarray=array()) {
        if ($this->is_archiv()) {
            $optarray['archivid']=$this->data['characterid'];
        }
        return $optarray;
    }
    
    private function look_up($charname) {
        global $flolog;
        if (!preg_match("/^[a-z0-9]{1,14}$/i", $charname)) return "invalidname";
	$vcardurl= "http://florensia.en.alaplaya.net/api/vcards/__charname__.xml";

	$api = simplexml_load_file(rawurlencode(str_replace('__charname__', $charname, $vcardurl)), 'SimpleXMLElement', LIBXML_NOWARNING);
	if (!$api) return "timeout";
	//we got something..
	if ($api->error) return "notfound";
	
        $updatetime = date("U");
	$guildid = 0;
	#look up if guild already there
	if (mb_strlen("".$api->guild, 'UTF-8')<14 && mb_strlen("".$api->guild, 'UTF-8')>0) {
		$queryguild = MYSQL_QUERY("SELECT guildid FROM flobase_guild WHERE guildname='".mysql_real_escape_string($api->guild)."' AND server='".mysql_real_escape_string($api->server)."' AND memberamount!='0' LIMIT 1");
		if ($guild = MYSQL_FETCH_ARRAY($queryguild)) $guildid = $guild['guildid'];
		else {
			#guild doesn't exist yet.
			if (!MYSQL_QUERY("INSERT INTO flobase_guild (guildname, server, firstappearance, maxmember, maxmembertimestamp) VALUES('".mysql_real_escape_string($api->guild)."', '".mysql_real_escape_string($api->server)."', '{$updatetime}', '1', '{$updatetime}')")) {
                            $flolog->add("api:error", "MySQL-Insert-Error while adding new guild.");
                        }
			$guildid = mysql_insert_id();
                        if (!MYSQL_QUERY("INSERT INTO flobase_guild_log_general (guildid, timestamp, attribute, oldvalue, newvalue) VALUES
                                    ('{$guildid}', '{$updatetime}', 'memberamount', '0', '1'),
                                    ('{$guildid}', '{$updatetime}', 'maxmember', '0', '1'),
                                    ('{$guildid}', '{$updatetime}', 'averagelevel', '0', '".bcdiv(intval($api->level_land)+intval($api->level_sea), 2, 4)."'),
                                    ('{$guildid}', '{$updatetime}', 'averagelandlevel', '0', '".intval($api->level_land)."'),
                                    ('{$guildid}', '{$updatetime}', 'averagesealevel', '0', '".intval($api->level_sea)."')")) {
                            $flolog->add("api:error", "MySQL-Insert-Error while adding general-log of new guild.");
                        }
		}
	}
	
	//insert character and get ID
	if (!MYSQL_QUERY("INSERT INTO flobase_character (charname, lastupdate) VALUES('".mysql_real_escape_string($api->nickname)."', '{$updatetime}')")) {
            $flolog->add("api:error", "MySQL-Insert-Error while adding new character \"{$api->nickname}\": ".mysql_error());
        } else {
            $characterid = mysql_insert_id();
            //insert char-data
            if (!MYSQL_QUERY("INSERT INTO flobase_character_data (characterid, levelland, levelsea, levelsum, guild, guildid, guildgrade, jobclass, gender, server, firstappearance) VALUES('{$characterid}', '".intval($api->level_land)."', '".intval($api->level_sea)."', ".bcadd(intval($api->level_sea), intval($api->level_land)).", '".mysql_real_escape_string($api->guild)."', '$guildid', '".intval($api->guild_grade->attributes()->id)."', '".mysql_real_escape_string($api->jobclass)."', '".mysql_real_escape_string($api->gender)."', '".mysql_real_escape_string($api->server)."', '".date("U")."')")) {
                $flolog->add("api:error", "MySQL-Insert-Error while adding data of new character \"{$api->nickname}\": ".mysql_error());
            }
            //insert level-logs
            if (!MYSQL_QUERY("INSERT INTO flobase_character_log_level_land (characterid, level) VALUES('{$characterid}', '".intval($api->level_land)."')")) {
                $flolog->add("api:error", "MySQL-Insert-Error while adding log-level-land of new character.");
            }
            if (!MYSQL_QUERY("INSERT INTO flobase_character_log_level_sea (characterid, level) VALUES('{$characterid}', '".intval($api->level_sea)."')")) {
                $flolog->add("api:error", "MySQL-Insert-Error while adding log-level-sea of new character.");
            }
            //insert into guildlog if available
            if ($guildid) {
                if (!MYSQL_QUERY("INSERT INTO flobase_character_log_guild (characterid, action, guildid, newguildgrade, timestamp) VALUES('{$characterid}', 'a', '{$guildid}', '".intval($api->guild_grade->attributes()->id)."', '{$updatetime}')")) {
                    $flolog->add("api:error", "MySQL-Insert-Error while adding guild-log of new character.");
                }
                #guildupdate at last, otherwise we got maybe a race condition if script is updating first and then character is added
                if (!MYSQL_QUERY("UPDATE flobase_guild SET forceupdate='1' WHERE guildid='{$guildid}'")) {
                    $flolog->add("api:error", "MySQL-Update-Error while set forceupdate='1' to character's guild.");
                }
            }
        }
	
	//build final $return
	$cachechar['characterid'] = $characterid;
	$cachechar['charname'] = "".$api->nickname;
	$cachechar['levelland'] = intval($api->level_land);
	$cachechar['levelsea'] = intval($api->level_sea);
	$cachechar['levelsum'] = bcadd($cachechar['levelsea'], $cachechar['levelland']);
	$cachechar['guild'] = "".$api->guild;
	$cachechar['guildid'] = $guildid;
        $cachechar['guildgrade'] = intval($api->guild_grade->attributes()->id);
	$cachechar['jobclass'] = "".$api->jobclass;
	$cachechar['gender'] = "".$api->gender;
	$cachechar['server'] = "".$api->server;
	$cachechar['lastupdate'] = $updatetime;
	$cachechar['updatepriority'] = 20;
	
	return $cachechar;
    }
}

?>
