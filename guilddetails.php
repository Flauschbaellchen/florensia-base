<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$florensia->sitetitle("Guilddetails");

$flolang->load("statistic,character,guild");
$guildlatestupdates = 72; #hours
$searchdelete_afterdays = 60; #days

foreach($florensia->validserver as $server) {
    if ($_GET['server']==$server) $selected="selected='selected'";
    else unset($selected);
    $jumptoguild .= "<option value='{$server}' {$selected}>{$server}</option>";
}
$jumptoguild = "<input type='text' maxlength='13' name='search' value='".$florensia->escape($_GET['search'])."'> <select name='server'>{$jumptoguild}</select>";
$jumptoguild = "
    <div class='subtitle' style='text-align:center; margin-bottom:15px;'>{$flolang->guild_jumpto}<br />
        ".$florensia->quick_select("guilddetails", array(), array(0=>$jumptoguild))."
    </div>
";

$_GET['guildid'] = intval($_GET['guildid']);
if ($_GET['guildid']) {
    $queryguild = MYSQL_QUERY("SELECT * FROM flobase_guild WHERE guildid='{$_GET['guildid']}' LIMIT 1");
    if ($guild = MYSQL_FETCH_ARRAY($queryguild)) {

        //any uploaded pictures?
        list($guildimages) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(*) FROM flobase_guild_gallery WHERE guildid='{$guild['guildid']}'"));

        $tabbar['details'] = array("anchor"=>"details", "name"=>$flolang->tabbar_title_guild, "desc"=>$flolang->tabbar_desc_guild);
        $tabbar['memberlist'] = array("anchor"=>"memberlist", "name"=>$flolang->tabbar_title_guild_member, "inactive"=>true);
        $tabbar['activity'] = array("anchor"=>"activity", "name"=>$flolang->tabbar_title_guild_activity, "inactive"=>true);
	$tabbar['gallery'] = array("link"=>$florensia->outlink(array("gallery", "g", $guild['guildid'], $guild['server'], $guild['guildname'])), "name"=>$flolang->tabbar_title_gallery, "desc"=>$flolang->sprintf($flolang->tabbar_desc_gallery, $guildimages));


        if (!$guild['memberamount']) {
            $disbanded = $guild['lastappearance'] ? date("m.d.y", $guild['lastappearance']) : "<i>{$flolang->guild_disbanded_notice_timestamp_unknown}</i>";
            $lastmemberlist = array();
            $querylastmember = MYSQL_QUERY("SELECT l.characterid, charname FROM flobase_character_log_guild as l LEFT JOIN flobase_character as c ON (l.characterid=c.characterid) WHERE action='l' AND guildid='{$guild['guildid']}' ORDER BY timestamp DESC LIMIT 5");
            while ($lastmember = MYSQL_FETCH_ARRAY($querylastmember)) {
                if (!$lastmember['charname']) {
                    list($lastmember['charname']) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT charname FROM flobase_character_archiv WHERE characterid='{$lastmember['characterid']}'"));
                    $lastmemberlist[] = "<a href='".$florensia->outlink(array("characterdetails", $lastmember['charname']), array('archivid'=>$lastmember['characterid']))."' class='archiv'>".$florensia->escape($lastmember['charname'])."</a>";
                } else {
                    $lastmemberlist[] = "<a href='".$florensia->outlink(array("characterdetails", $lastmember['charname']))."'>".$florensia->escape($lastmember['charname'])."</a>";
                }
            }
            
            $activeguildlist = array();
            $queryactiveguild = MYSQL_QUERY("SELECT guildid, guildname, server FROM flobase_guild WHERE memberamount!='0' AND guildname LIKE '".mysql_real_escape_string($guild['guildname'])."'");
            while ($activeguild = MYSQL_FETCH_ARRAY($queryactiveguild)) {
                $activeguildlist[] = "<a href='".$florensia->outlink(array("guilddetails", $activeguild['guildid'], $activeguild['guildname'], $activeguild['server']))."'>".$florensia->escape($activeguild['guildname'])." (".$florensia->escape($activeguild['server']).")</a>";
            }
            if (!count($activeguildlist)) $activeguildlist[] = "<i>{$flolang->guild_disbanded_notice_activeguild_none}</i>"; 
            
            $deletedguildnotice = "
                <tr>
                    <td colspan='2' style='padding-top:15px;'>
                        ".$flolang->sprintf($flolang->guild_disbanded_notice_timestamp, $disbanded)."
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='padding-top:3px; font-weight:normal;'>
                        ".$flolang->sprintf($flolang->guild_disbanded_notice_lastmember, join(", ", $lastmemberlist))."
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='padding-top:3px; font-weight:normal;'>
                        ".$flolang->sprintf($flolang->guild_disbanded_notice_activeguild, join(", ", $activeguildlist))."
                    </td>
                </tr>";
            
            $archivimage = "
                <div style='float:right; text-align:center; vertical-align:middle; width:130px; height:123px; background-image:url({$florensia->layer_rel}/archiv_icon.png);'>
                    <div style='margin-top:53px;'>Archive<br />{$guild['guildid']}</div>
                </div>
            ";
                
        }
        else {

            $userprivacy = $flouser->get_privacy();
            MYSQL_QUERY("DELETE FROM flobase_guild_wanted WHERE guildid='{$guild['guildid']}' AND timestamp<='".bcsub(date("U"),60*60*24*$searchdelete_afterdays)."'");
            
            $found_access = false;
            //only if some characters are to searching through
            if ($flouser->get_permission("guild", "owneroverride")) $found_access = true;
            elseif (count($userprivacy['characterlist'])) list($found_access) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(d.characterid) FROM flobase_character_data as d, flobase_character as c WHERE c.characterid=d.characterid AND d.guildgrade>='{$guild['settings_accesslevel']}' AND d.guildid='{$guild['guildid']}' AND d.ownerid='{$flouser->userid}'"));
            
            if ($found_access) {
                //access granded
                $tabbar['settings'] = array("anchor"=>"settings", "name"=>$flolang->tabbar_title_guild_settings);
                
                //save changes
                if ($_POST['save_settings']) {
                    //handling of avatar
                    $avatarpath = "{$florensia->root_abs}/pictures/guild_avatars/{$guild['guildid']}";
                    if ($_POST['rm_avatar'] && is_file($avatarpath)) {
                        if (unlink($avatarpath)) {
                            if (!MYSQL_QUERY("UPDATE flobase_guild SET avatar_size='' WHERE guildid='{$guild['guildid']}'")) $error=true;
                            $guild['avatar_size'] = "";
                        }
                    } elseif ($_FILES['file_avatar']['tmp_name']) {
                        $image = $_FILES['file_avatar']['tmp_name'];            
                        list($width, $height, $type, $attr) = getimagesize($image);
                        if ($height<=0 OR $width<=0 OR !in_array($type, array(
                                       1, //'IMAGETYPE_GIF'
                                       2, //'IMAGETYPE_JPEG'
                                       3 //'IMAGETYPE_PNG'
                                ))) {
                            $florensia->notice($flolang->guild_edit_error_avatar, "warning");
                        } else {
                            //if it's already in the right size, just copy it
                            if ($height<=250 && $width<=800) @rename($image, $avatarpath);
                            else {
                                
                                $theight = $height;
                                $twidth = $width;
                                if ($theight>250) { 
                                    $twidth = ceil($twidth*(250/$theight));
                                    $theight = 250;
                                }
                                if ($twidth>800) { 
                                    $theight = ceil($theight*(800/$twidth));
                                    $twidth = 800;
                                }
                
                                switch($type) {
                                    case 1: {
                                        $source = @imagecreatefromgif($image);
                                        break;
                                    }
                                    case 2: {
                                        $source = @imagecreatefromjpeg($image);
                                        break;
                                    }
                                    case 3: {
                                        $source = @imagecreatefrompng($image);
                                        break;
                                    }
                                }
                                $thumbnail = @imagecreatetruecolor($twidth,$theight);
                                @imagecopyresized($thumbnail,$source,0,0,0,0,$twidth, $theight,$width,$height);
                                @imagesavealpha($thumbnail, TRUE);
                                @imagepng($thumbnail, $avatarpath);
                                @imagedestroy($thumbnail);
                                @imagedestroy($source);
                                $height = $theight;
                                $width = $twidth;
                            }
			    chmod($avatarpath, 0755);
                            $guild['avatar_size'] = "{$width}x{$height}";
                            if (!MYSQL_QUERY("UPDATE flobase_guild SET avatar_size = '{$width}x{$height}' WHERE guildid='{$guild['guildid']}'")) $error=true;
                        }
                    }
                    
                    #contacts
                    foreach($_POST as $postkey => $postvalue) {
                        if (preg_match("/^contact_type_([1-9]+)$/", $postkey, $contactid)) {
                            $contactid = intval($contactid[1]);
                            //delete?
                            if (!strlen($_POST['contact_value_'.$contactid])) {
                                MYSQL_QUERY("DELETE FROM flobase_guild_contacts WHERE guildid='{$guild['guildid']}' AND contactid='{$contactid}'");
                            } elseif (in_array($_POST['contact_type_'.$contactid], array('website', 'forum', 'teamspeak2', 'teamspeak3', 'icq', 'skype', 'msn'))) {
                                //priv
                                if (preg_match("/^[rg]$/", $_POST['contact_priv_'.$contactid])) $contactprivacy = $_POST['contact_priv_'.$contactid];
                                else $contactprivacy = "a";
                                MYSQL_QUERY("REPLACE INTO flobase_guild_contacts (guildid, contactid, contacttype, contactvalue, contactpassword, contactprivacy) VALUES('{$guild['guildid']}', '{$contactid}', '{$_POST['contact_type_'.$contactid]}', '".mysql_real_escape_string($_POST['contact_value_'.$contactid])."', '".mysql_real_escape_string($_POST['contact_pass_'.$contactid])."', '{$contactprivacy}')");
                            }
                        }
                    }
                    
                    #search
                    foreach($_POST as $postkey => $postvalue) {
                        if (preg_match("/^search_class_([1-9]+)$/", $postkey, $searchid)) {
                            $searchid = intval($searchid[1]);
                            //delete?
                            if (!$_POST['search_class_'.$searchid]) {
                                MYSQL_QUERY("DELETE FROM flobase_guild_wanted WHERE guildid='{$guild['guildid']}' AND searchid='{$searchid}'");
                            } else {
                                $searchclass = $_POST['search_class_'.$searchid];
                                $level_land = intval($_POST['search_level_land_'.$searchid]);
                                $level_sea = intval($_POST['search_level_sea_'.$searchid]);
                                
                                if ($level_land<0) $level_land=0;
                                elseif ($level_land>99) $level_land=99;
                                
                                if ($level_sea<0) $level_sea=0;
                                elseif ($level_sea>99) $level_sea=99;
                                MYSQL_QUERY("REPLACE INTO flobase_guild_wanted (guildid, searchid, searchclass, level_land, level_sea, timestamp) VALUES('{$guild['guildid']}', '{$searchid}', '".mysql_real_escape_string($searchclass)."', '{$level_land}', '{$level_sea}', '".date("U")."')");
                            }
                        }
                    }
                    
                    
                    #privacy
                    #memberlist
                    if (preg_match("/^[rgl]$/", $_POST['privacy_memberlist'])) $guild['priv_memberlist'] = $_POST['privacy_memberlist'];
                    else $guild['priv_memberlist'] = "a";
                    
                    #guildactivity (join,parts...)
                    if (preg_match("/^[rgl]$/", $_POST['privacy_guild'])) $guild['priv_activity_guild'] = $_POST['privacy_guild'];
                    else $guild['priv_activity_guild'] = "a";
                    
                    #playeractivity (levels...)
                    if (preg_match("/^[rgl]$/", $_POST['privacy_player'])) $guild['priv_activity_player'] = $_POST['privacy_player'];
                    else $guild['priv_activity_player'] = "a";
                    
                    #settings access
                    if (preg_match("/^[1-5]$/", $_POST['settings_access'])) $guild['settings_accesslevel'] = $_POST['settings_access'];
                    else $guild['settings_accesslevel'] = "5";
                    
                    #misc
                    $guild['misc_description'] = $_POST['misc_description'];
                    $guild['misc_wanted_age'] = intval($_POST['misc_wanted_age']);
                    if ($guild['misc_wanted_age']<0) $guild['misc_wanted_age'] = 0;
                    elseif ($guild['misc_wanted_age']>99) $guild['misc_wanted_age'] = 99;
                    
                    if ($_POST['misc_wanted_secondcharacter']) $guild['misc_wanted_secondcharacter'] = 1;
                    else $guild['misc_wanted_secondcharacter'] = 0;
                    
                    if ($_POST['misc_language']=="--" OR (preg_match("/^[a-z]{2}$/i", $_POST['misc_language']) && is_file("{$florensia->layer_abs}/flags/png/".strtolower($_POST['misc_language']).".png"))) {
                        $guild['misc_language'] = strtolower($_POST['misc_language']);
                    } else $guild['misc_language'] = "";
                    
                    
                    if (MYSQL_QUERY("UPDATE flobase_guild SET
                                priv_memberlist='{$guild['priv_memberlist']}',
                                priv_activity_guild='{$guild['priv_activity_guild']}',
                                priv_activity_player='{$guild['priv_activity_player']}',
                                settings_accesslevel='{$guild['settings_accesslevel']}',
                                misc_description='".mysql_real_escape_string($_POST['misc_description'])."',
                                misc_wanted_age='{$guild['misc_wanted_age']}',
                                misc_wanted_secondcharacter='{$guild['misc_wanted_secondcharacter']}',
                                misc_language='{$guild['misc_language']}'
                                WHERE guildid='{$guild['guildid']}'")) {
                        $florensia->notice($flolang->guild_edit_successful, "successful");
                    } else {
                        $error=true;
                    }
                    if ($error) $florensia->notice($flolang->guild_edit_error, "error");
                    
                }
                //-- end save changes
                
                //some defaults
                if (!$guild['priv_memberlist']) $guild['priv_memberlist'] = "a";
                if (!$guild['priv_activity_guild']) $guild['priv_activity_guild'] = "a";
                if (!$guild['priv_activity_player']) $guild['priv_activity_player'] = "a";
                if (!$guild['settings_accesslevel']) $guild['settings_accesslevel'] = "5";
                
                //preselection
                $privacy_memberlist['checked'][$guild['priv_memberlist']] = "checked='checked'";
                $privacy_guildactivity['checked'][$guild['priv_activity_guild']] = "checked='checked'";
                $privacy_playeractivity['checked'][$guild['priv_activity_player']] = "checked='checked'";
                $privacy_access_settings['checked'][$guild['settings_accesslevel']] = "checked='checked'";
                $preselected['misc_wanted_secondcharacter'][$guild['misc_wanted_secondcharacter']] = "checked='checked'";
                    
                ##-- contactlist
                $guildcontacts_lastid = 0;
                $queryguildcontacts = MYSQL_QUERY("SELECT * FROM flobase_guild_contacts WHERE guildid='{$guild['guildid']}' ORDER BY contactid");
                while ($guildcontacts = MYSQL_FETCH_ARRAY($queryguildcontacts)) {
                    unset($preselect_contacts);
                    $preselect_contacts['type'][$guildcontacts['contacttype']] = "selected='selected'";
                    $preselect_contacts['privacy'][$guildcontacts['contactprivacy']] = "selected='selected'";
                    
                    $guildcontactslist .= "
                        <table style='width:100%; border-bottom:1px solid; padding-bottom:2px; padding-top:2px;'>
                            <tr><td style='width:100px;'>{$flolang->guild_edit_general_contact_type}</td><td>
                                <select name='contact_type_{$guildcontacts['contactid']}'>
                                    <option value='forum' {$preselect_contacts['type']['forum']}>{$flolang->guild_contacts_forum}</option>
                                    <option value='website' {$preselect_contacts['type']['website']}>{$flolang->guild_contacts_website}</option>
                                    <option value='teamspeak2' {$preselect_contacts['type']['teamspeak2']}>{$flolang->guild_contacts_teamspeak2}</option>
                                    <option value='teamspeak3' {$preselect_contacts['type']['teamspeak3']}>{$flolang->guild_contacts_teamspeak3}</option>
                                    <option value='msn' {$preselect_contacts['type']['msn']}>{$flolang->guild_contacts_msn}</option>
                                    <option value='icq' {$preselect_contacts['type']['icq']}>{$flolang->guild_contacts_icq}</option>
                                    <option value='skype' {$preselect_contacts['type']['skype']}>{$flolang->guild_contacts_skype}</option>
                                </select>
                            </td></tr>
                            <tr><td>{$flolang->guild_edit_general_contact_value}</td><td>
                                <input type='text' name='contact_value_{$guildcontacts['contactid']}' value='".$florensia->escape($guildcontacts['contactvalue'])."'>
                            </td></tr>
                            <tr><td>{$flolang->guild_edit_general_contact_password}</td><td>
                                <input type='text' name='contact_pass_{$guildcontacts['contactid']}' value='".$florensia->escape($guildcontacts['contactpassword'])."'>
                            </td></tr>
                            <tr><td>{$flolang->guild_edit_general_contact_privacy}</td><td>
                                <select name='contact_priv_{$guildcontacts['contactid']}'>
                                    <option value='a' {$preselect_contacts['privacy']['a']}>{$flolang->guild_privacy_all}</option>
                                    <option value='r' {$preselect_contacts['privacy']['r']}>{$flolang->guild_privacy_registered}</option>
                                    <option value='g' {$preselect_contacts['privacy']['g']}>{$flolang->guild_privacy_guildmates}</option>
                                </select>
                            </td></tr>
                        </table>
                    ";
                    $guildcontacts_lastid = $guildcontacts['contactid'];
                }
                
                $guildcontactslist .= "
                    <table id='contact_template' style='width:100%; border-bottom:1px solid; padding-bottom:2px; padding-top:2px; display:none;'>
                        <tr><td style='width:100px;'>{$flolang->guild_edit_general_contact_type}</td><td>
                            <select name='contact_type'>
                                <option value='forum'>{$flolang->guild_contacts_forum}</option>
                                <option value='website'>{$flolang->guild_contacts_website}</option>
                                <option value='teamspeak2'>{$flolang->guild_contacts_teamspeak2}</option>
                                <option value='teamspeak3'>{$flolang->guild_contacts_teamspeak3}</option>
                                <option value='msn'>{$flolang->guild_contacts_msn}</option>
                                <option value='icq'>{$flolang->guild_contacts_icq}</option>
                                <option value='icq'>{$flolang->guild_contacts_skype}</option>
                            </select>
                        </td></tr>
                        <tr><td>{$flolang->guild_edit_general_contact_value}</td><td>
                            <input type='text' name='contact_value'>
                        </td></tr>
                        <tr><td>{$flolang->guild_edit_general_contact_password}</td><td>
                            <input type='text' name='contact_pass'>
                        </td></tr>
                        <tr><td>{$flolang->guild_edit_general_contact_privacy}</td><td>
                            <select name='contact_priv'>
                                <option value='a'>{$flolang->guild_privacy_all}</option>
                                <option value='r'>{$flolang->guild_privacy_registered}</option>
                                <option value='g'>{$flolang->guild_privacy_guildmates}</option>
                            </select>
                        </td></tr>
                    </table>
                ";
                ##--end of contactlist
                
                
                ##-- searchlist
                $guildsearch_lastid = 0;
                $guildsearch_timestamp = 0;
                $queryguildsearch = MYSQL_QUERY("SELECT * FROM flobase_guild_wanted WHERE guildid='{$guild['guildid']}' ORDER BY searchid");
                while ($guildsearch = MYSQL_FETCH_ARRAY($queryguildsearch)) {
                    
                    $searchclassfilter = array();
                    unset($preselectclass);
                    $preselectclass[$guildsearch['searchclass']] = "selected='selected'";
                    $searchclassfilter[] = "
                        <option value='0'>{$flolang->guild_edit_wanted_select_ignore}</option>
                        <option disabled='disabled'></option>
                        <option value='all' {$preselectclass['all']}>{$flolang->guild_edit_wanted_select_allclasses}</option>
                    ";
                    foreach ($florensia->get_classlist("landclass", 0) as $class) {
                            $searchclassfilter[] = "<option value='{$class['classname']}' ".$preselectclass[$class['classname']].">{$class['classname']}</option>";
                    }
                    $searchclassfilter = "<select name='search_class_{$guildsearch['searchid']}'>".join("", $searchclassfilter)."</select>";
                    
                    $guildsearchlistedit .= "
                    <div style='padding-bottom:2px; padding-top:2px;'>
                        ".$flolang->sprintf($flolang->guild_edit_wanted_entryrow, $searchclassfilter, "<input name='search_level_land_{$guildsearch['searchid']}' type='text' style='width:30px' maxlength='2' value='{$guildsearch['level_land']}'> <img src='{$florensia->layer_rel}/land.gif' style='height:11px;'>", "<input name='search_level_sea_{$guildsearch['searchid']}' type='text' style='width:30px' maxlength='2' value='{$guildsearch['level_sea']}'> <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;'>")."
                    </div>
                    ";
                    $guildsearch_lastid = $guildsearch['searchid'];
                    $guildsearch_timestamp = $guildsearch['timestamp'];
                }
                
                $searchclassfilter = array();
                $searchclassfilter[] = "
                    <option value='0'>{$flolang->guild_edit_wanted_select_ignore}</option>
                    <option disabled='disabled'></option>
                    <option value='all'>{$flolang->guild_edit_wanted_select_allclasses}</option>
                ";
                foreach ($florensia->get_classlist("landclass", 0) as $class) {
                        $searchclassfilter[] = "<option value='{$class['classname']}'>{$class['classname']}</option>";
                }
                $searchclassfilter = "<select name='search_class'>".join("", $searchclassfilter)."</select>";
                $guildsearchlistedit .= "
                    <div id='search_template' style='padding-bottom:2px; padding-top:2px; display:none;'>
                        ".$flolang->sprintf($flolang->guild_edit_wanted_entryrow, $searchclassfilter, "<input name='search_level_land' type='text' style='width:30px' maxlength='2' value='0'> <img src='{$florensia->layer_rel}/land.gif' style='height:11px;'>", "<input name='search_level_sea' type='text' style='width:30px' maxlength='2' value='0'> <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;'>")."
                    </div>
                ";
                
                if ($guildsearch_lastid>0) {
                    $guildsearchlistedit .= "<div style='float:right'>".$flolang->sprintf($flolang->guild_edit_wanted_notice_deletion, timetamp2string(bcsub(60*60*24*$searchdelete_afterdays, date("U")-$guildsearch_timestamp), "h"))."</div>";
                }
                ##--end of searchlist

                $guildsettings = "
                    <a name='settings'></a>
                    <div name='settings'>
                    
                    <script type=\"text/javascript\">
                        var contactcount = ".bcadd($guildcontacts_lastid,1).";
                        function moar_contacts() {
                                var newFields = document.getElementById(\"contact_template\").cloneNode(true);
                                newFields.id = \"tcontent_\"+contactcount;
                                newFields.style.display = \"block\";
                                var insertHere = document.getElementById(\"contact_template\");
                                insertHere.parentNode.insertBefore(newFields, insertHere);
                                
                                document.getElementsByName(\"contact_type\")[0].name = document.getElementsByName(\"contact_type\")[0].name+\"_\"+contactcount;
                                document.getElementsByName(\"contact_value\")[0].name = document.getElementsByName(\"contact_value\")[0].name+\"_\"+contactcount;
                                document.getElementsByName(\"contact_pass\")[0].name = document.getElementsByName(\"contact_pass\")[0].name+\"_\"+contactcount;
                                document.getElementsByName(\"contact_priv\")[0].name = document.getElementsByName(\"contact_priv\")[0].name+\"_\"+contactcount;
                                contactcount++;
                        }
                        
                        var searchcount = ".bcadd($guildsearch_lastid,1).";
                        function moar_search() {
                                var newFields = document.getElementById(\"search_template\").cloneNode(true);
                                newFields.id = \"\";
                                newFields.style.display = \"block\";
                                var newField = newFields.childNodes;
                                for (var i=0;i<newField.length;i++) {
                                        var fieldname = newField[i].name;
                                        if (fieldname) newField[i].name = fieldname + \"_\"+searchcount;
                                }
                                var insertHere = document.getElementById(\"search_template\");
                                insertHere.parentNode.insertBefore(newFields, insertHere);
                                searchcount++;
                        }
                    </script>

                    <form action='".$florensia->escape($florensia->request_uri(array(), 'settings'))."' method='post' enctype='multipart/form-data'>
                        <table class='subtitle small' style='font-weight:normal; width:100%;'>
                            <tr><td colspan='2' style='border-bottom:1px solid;'>
                                <span style='font-weight:bold;'>{$flolang->guild_edit_avatar_title}</span><br />
                                {$flolang->guild_edit_avatar_desc}
                            </td></tr>
                            <tr>
                                <td style='width:50%;'>
                                    ".$flolang->sprintf($flolang->guild_edit_avatar_uploadnotice, 800, 250)."
                                </td>
                                <td>
                                    <input type='file' name='file_avatar'><br />
                                    <input type='checkbox' name='rm_avatar' value='1'> {$flolang->guild_edit_avatar_deletecurrent}
                                </td>
                            </tr>
                        </table>
                        <table class='subtitle small' style='font-weight:normal; width:100%;'>
                            <tr><td colspan='2' style='border-bottom:1px solid;'>
                                <span style='font-weight:bold;'>{$flolang->guild_edit_general_title}</span><br />
                                {$flolang->guild_edit_general_desc}
                            </td></tr>
                            <tr>
                                <td style='width:50%;'>
                                    {$flolang->guild_edit_general_description}
                                </td>
                                <td>
                                    <textarea name='misc_description' style='width:98%; height:100px;'>".$florensia->escape($guild['misc_description'])."</textarea>
                                </td>
                            </tr>
                            <tr><td colspan='2' class='shortinfo_1' style='height:4px;'></d></tr>
                            <tr>
                                <td>
                                    {$flolang->guild_edit_general_language}
                                </td>
                                <td>
                                    <input type='text' name='misc_language' maxlength='2' style='width:25px;' value='{$guild['misc_language']}'> ".floclass_guild::get_language_pic($guild['misc_language'])."
                                </td>
                            </tr>
                            <tr><td colspan='2' class='shortinfo_1' style='height:4px;'></d></tr>
                            <tr>
                                <td>
                                    {$flolang->guild_edit_general_contact}
                                </td>
                                <td>
                                    {$guildcontactslist}
                                    <a href='javascript:moar_contacts();'>{$flolang->guild_edit_general_contact_more}</a>
                                </td>
                            </tr>
                        </table>
                        
                        <table class='subtitle small' style='font-weight:normal; width:100%;'>
                            <tr><td style='border-bottom:1px solid;'>
                                <span style='font-weight:bold;'>{$flolang->guild_edit_wanted_title}</span><br />
                                ".$flolang->sprintf($flolang->guild_edit_wanted_desc, $searchdelete_afterdays)."
                            </td></tr>
                            <tr>
                                <td>
                                    ".$flolang->sprintf($flolang->guild_edit_wanted_age, "<input type='text' name='misc_wanted_age' value='{$guild['misc_wanted_age']}' style='width:25px;'>")."<br />
                                    ".$flolang->sprintf($flolang->guild_edit_wanted_secondcharacter, "<input type='radio' name='misc_wanted_secondcharacter' value='1' {$preselected['misc_wanted_secondcharacter'][1]}>", "<input type='radio' name='misc_wanted_secondcharacter' value='0' {$preselected['misc_wanted_secondcharacter'][0]}>")."
                                    <div class='shortinfo_1' style='height:2px;'></div>
                                    {$guildsearchlistedit}
                                    <a href='javascript:moar_search();'>{$flolang->guild_edit_wanted_more}</a>
                                </td>
                            </tr>
                        </table>
                        
                        <table class='subtitle small' style='font-weight:normal; width:100%;'>
                            <tr><td colspan='2' style='border-bottom:1px solid;'>
                                <span style='font-weight:bold;'>{$flolang->guild_edit_privacy_title}</span><br />
                                {$flolang->guild_edit_privacy_desc}
                            </td></tr>
                            <tr>
                                <td style='width:50%;'>
                                    {$flolang->guild_edit_privacy_memberlist}
                                </td>
                                <td>
                                    <ul style='list-style-type: none; padding:0px; margin:0px;'>
                                    <li style='padding-bottom:6px;'><input type='radio' name='privacy_memberlist' style='float:left;' value='a' {$privacy_memberlist['checked']['a']}> {$flolang->guild_privacy_all}</li>
                                    <li style='padding-bottom:6px; margin-left:15px;'><input type='radio' name='privacy_memberlist' style='float:left;' value='r' {$privacy_memberlist['checked']['r']}> {$flolang->guild_privacy_registered}</li>
                                    <li style='padding-bottom:6px; margin-left:30px;'><input type='radio' name='privacy_memberlist' style='float:left;' value='g' {$privacy_memberlist['checked']['g']}> {$flolang->guild_privacy_guildmates}</li>
                                    <li style='margin-left:45px;'><input type='radio' name='privacy_memberlist' style='float:left;' value='l' {$privacy_memberlist['checked']['l']}> {$flolang->guild_privacy_leader}</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {$flolang->guild_edit_privacy_activity_guild}
                                </td>
                                <td>
                                    <ul style='list-style-type: none; padding:0px; margin:0px;'>
                                    <li style='padding-bottom:6px;'><input type='radio' name='privacy_guild' style='float:left;' value='a' {$privacy_guildactivity['checked']['a']}> {$flolang->guild_privacy_all}</li>
                                    <li style='padding-bottom:6px; margin-left:15px;'><input type='radio' name='privacy_guild' style='float:left;' value='r' {$privacy_guildactivity['checked']['r']}> {$flolang->guild_privacy_registered}</li>
                                    <li style='padding-bottom:6px; margin-left:30px;'><input type='radio' name='privacy_guild' style='float:left;' value='g' {$privacy_guildactivity['checked']['g']}> {$flolang->guild_privacy_guildmates}</li>
                                    <li style='margin-left:45px;'><input type='radio' name='privacy_guild' style='float:left;' value='l' {$privacy_guildactivity['checked']['l']}> {$flolang->guild_privacy_leader}</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {$flolang->guild_edit_privacy_activity_player} 
                                </td>
                                <td>
                                    <ul style='list-style-type: none; padding:0px; margin:0px;'>
                                    <li style='padding-bottom:6px;'><input type='radio' name='privacy_player' style='float:left;' value='a' {$privacy_playeractivity['checked']['a']}> {$flolang->guild_privacy_all}</li>
                                    <li style='padding-bottom:6px; margin-left:15px;'><input type='radio' name='privacy_player' style='float:left;' value='r' {$privacy_playeractivity['checked']['r']}> {$flolang->guild_privacy_registered}</li>
                                    <li style='padding-bottom:6px; margin-left:30px;'><input type='radio' name='privacy_player' style='float:left;' value='g' {$privacy_playeractivity['checked']['g']}> {$flolang->guild_privacy_guildmates}</li>
                                    <li style='margin-left:45px;'><input type='radio' name='privacy_player' style='float:left;' value='l' {$privacy_playeractivity['checked']['l']}> {$flolang->guild_privacy_leader}</li>
                                    </ul>
                                </td>
                            </tr>
                        </table>                        
                        <table class='subtitle small' style='font-weight:normal; width:100%;'>
                            <tr><td colspan='2' style='border-bottom:1px solid;'>
                                <span style='font-weight:bold;'>{$flolang->guild_edit_access_title}</span><br />
                                {$flolang->guild_edit_access_desc}
                            </td></tr>
                            <tr>
                                <td style='width:50%;'>
                                    {$flolang->guild_edit_access_desc2}
                                </td>
                                <td>
                                    <ul style='list-style-type: none; padding:0px; margin:0px;'>
                                    <li style='padding-bottom:6px;'><input type='radio' name='settings_access' style='float:left;' value='5' {$privacy_access_settings['checked']['5']}> ".class_character::guildgrade(5)."</li>
                                    <li style='padding-bottom:6px;'><input type='radio' name='settings_access' style='float:left;' value='4' {$privacy_access_settings['checked']['4']}> ".class_character::guildgrade(4)."</li>
                                    <li style='padding-bottom:6px;'><input type='radio' name='settings_access' style='float:left;' value='3' {$privacy_access_settings['checked']['3']}> ".class_character::guildgrade(3)."</li>
                                    <li style='padding-bottom:6px;'><input type='radio' name='settings_access' style='float:left;' value='2' {$privacy_access_settings['checked']['2']}> ".class_character::guildgrade(2)."</li>
                                    <li><input type='radio' name='settings_access' style='float:left;' value='1' {$privacy_access_settings['checked']['1']}> ".class_character::guildgrade(1)."</li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                        <div class='subtitle small' style='text-align:right; padding:2px;'>
                            <input type='submit' name='save_settings' value='{$flolang->guild_edit_save}'>
                        </div>
                    </form>                    
                    </div>
                    <script type=\"text/javascript\">moar_contacts();</script>
                    <script type=\"text/javascript\">moar_search();</script>
                ";
            }            
            //--end guildsettings
            
            
            
            
            //ranking
            list($rankingsum) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(guildid) FROM flobase_guild WHERE averagelevel>'{$guild['averagelevel']}' AND memberamount>='3'"));
            list($rankingsea) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(guildid) FROM flobase_guild WHERE averagesealevel>'{$guild['averagesealevel']}' AND memberamount>='3'"));
            list($rankingland) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(guildid) FROM flobase_guild WHERE averagelandlevel>'{$guild['averagelandlevel']}' AND memberamount>='3'"));
            list($rankingmember) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(guildid) FROM flobase_guild WHERE memberamount>'{$guild['memberamount']}'"));
            $rankingsum++;
            $rankingsea++;
            $rankingland++;
            $rankingmember++;
            list($rankingserversum) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(guildid) FROM flobase_guild WHERE server='{$guild['server']}' AND averagelevel>'{$guild['averagelevel']}' AND memberamount>='3'"));
            list($rankingserversea) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(guildid) FROM flobase_guild WHERE server='{$guild['server']}' AND averagesealevel>'{$guild['averagesealevel']}' AND memberamount>='3'"));
            list($rankingserverland) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(guildid) FROM flobase_guild WHERE server='{$guild['server']}' AND averagelandlevel>'{$guild['averagelandlevel']}' AND memberamount>='3'"));
            list($rankingservermember) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(guildid) FROM flobase_guild WHERE server='{$guild['server']}' AND memberamount>'{$guild['memberamount']}'"));
            $rankingserversum++;
            $rankingserversea++;
            $rankingserverland++;
            $rankingservermember++;

            if ($guild['firstappearance']) {
                $firstappearance = "
                        <b>{$flolang->guild_title_firstappearance}</b><br />
                        ".date("m.d.y", $guild['firstappearance'])." (".$flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$guild['firstappearance'], "d")).")<br />
                        <br />
                ";
            }
            else unset($firstappearance);


            $guildranking = "
                <div style='float:right; text-align:right;'>
                        {$firstappearance}
                        <b>{$flolang->character_ranking_global_title}</b><br/>
                        <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'>+<img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'> # {$rankingsum}<br />
                        <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'> # {$rankingland}<br />
                        <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'> # {$rankingsea}<br />
                        <img src='{$florensia->layer_rel}/icon_guild.gif' style='height:11px;' alt='Member'> # {$rankingmember}<br />
                        <b>{$flolang->character_ranking_server_title}</b><br/>
                        <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'>+<img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'> # {$rankingserversum}<br />
                        <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'> # {$rankingserverland}<br />
                        <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'> # {$rankingserversea}<br />
                        <img src='{$florensia->layer_rel}/icon_guild.gif' style='height:11px;' alt='Member'> # {$rankingservermember}<br />
                </div>
            ";


            if (!floclass_guild::check_privacy($guild['priv_memberlist'], $guild['guildid']) OR !floclass_guild::check_privacy($guild['priv_activity_guild'], $guild['guildid']) OR !floclass_guild::check_privacy($guild['priv_activity_player'], $guild['guildid'])) {
                $privlist = array();
                if (!floclass_guild::check_privacy($guild['priv_memberlist'], $guild['guildid'])) {
                        $privlist[] = $flolang->sprintf($flolang->guild_privacy_notice_memberlist, floclass_guild::get_privacy_desc($guild['priv_memberlist']));
                } else {
                    if (!floclass_guild::check_privacy($guild['priv_activity_guild'], $guild['guildid'])) {
                        $privlist[] = $flolang->sprintf($flolang->guild_privacy_notice_activity_guild, floclass_guild::get_privacy_desc($guild['priv_activity_guild']));
                    }
                    if (!floclass_guild::check_privacy($guild['priv_activity_player'], $guild['guildid'])) {
                        $privlist[] = $flolang->sprintf($flolang->guild_privacy_notice_activity_player, floclass_guild::get_privacy_desc($guild['priv_activity_player']));
                    }
                }
                $privacynotice = "
                    <div style='font-weight:normal; font-style:italic; margin-top:10px;'>
                        ".$flolang->sprintf($flolang->guild_privacy_notice, join("<br />", $privlist))."
                    </div>
                ";
            }

       
            $latestactivity = array();
                    
            if (floclass_guild::check_privacy($guild['priv_memberlist'], $guild['guildid'])) {
                
                $setguildinvisiblenotice = "<div style='margin-top:7px; font-weight:normal;'>".$flolang->sprintf($flolang->guild_hidedetails_notice, $flouserdata->get_username(1))."</div>";
                //level-sort
                if (!$_GET['order'] OR !in_array($_GET['order'], array("sum", "land", "sea", "name", "class", "grade"))) $_GET['order']="grade";
                $preselectorder[$_GET['order']] = "selected='selected'";
                $orderbyselect = "
                        <select name='order'>
                                <option value='grade' {$preselectorder['grade']}>{$flolang->statistic_ranking_quickselect_orderby_grade}</option>
                                <option value='sum' {$preselectorder['sum']}>{$flolang->statistic_ranking_quickselect_orderby_sum}</option>
                                <option value='land' {$preselectorder['land']}>{$flolang->statistic_ranking_quickselect_orderby_landsea}</option>
                                <option value='sea' {$preselectorder['sea']}>{$flolang->statistic_ranking_quickselect_orderby_sealand}</option>
                                <option value='name' {$preselectorder['name']}>{$flolang->statistic_ranking_quickselect_orderby_name}</option>
                                <option value='class' {$preselectorder['class']}>{$flolang->statistic_ranking_quickselect_orderby_class}</option>
                        </select>
                ";
                switch($_GET['order']) {
                        case "sum": { $dborderby = "ORDER BY levelsum DESC, charname"; break; }
                        case "land": { $dborderby = "ORDER BY levelland DESC, levelsea DESC, charname"; break; }
                        case "sea": { $dborderby = "ORDER BY levelsea DESC, levelland DESC, charname"; break; }
                        case "name": { $dborderby = "ORDER BY charname, levelsea DESC, levelland DESC"; break; }
                        case "class": { $dborderby = "ORDER BY jobclass, charname"; break; }
                        case "grade": { $dborderby = "ORDER BY guildgrade DESC, charname"; break; }
                }
            
                $today = mktime(0,0,0, date("m"), date("d"), date("Y"));
                $overall = array('levelsea'=>0, 'levelland'=>0, 'member'=>0, 'f'=>0, 'm'=>0, 'jobclass'=>array());
                
                $querycharacter = MYSQL_QUERY("SELECT * FROM flobase_character_data, flobase_character WHERE guildid='{$_GET['guildid']}' AND flobase_character_data.characterid=flobase_character.characterid {$dborderby}");
                while ($character = MYSQL_FETCH_ASSOC($querycharacter)) {
                    $character = new class_character($character);
                    
                    $overall['member']++;
                    $overall[$character->data['gender']]++;
                    $overall['levelland'] += $character->data['levelland'];
                    $overall['levelsea'] += $character->data['levelsea'];
                    $overall['jobclass'][$character->data['jobclass']]++;
                
                    if ($character->data['gender']=="m") $gender = "<img src='{$florensia->layer_rel}/gender_male.gif' border='0' alt='male' style='height:12px;'>";
                    else  $gender = "<img src='{$florensia->layer_rel}/gender_female.gif' border='0' alt='female' style='height:12px;'>";
                    
                    if ($character->data['guildgrade']) $guildgrade = class_character::guildgrade($character->data['guildgrade']);
                    else unset($guildgrade);
                    
                    $memberlist .= "
                    <div class='small shortinfo_".$florensia->change()."'>
                        <table style='width:100%'><tr>
                            <td style='width:50px; text-align:right;'>".intval($character->data['levelland'])." <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'></td>
                            <td style='width:50px; padding-right:10px; text-align:right;'>".intval($character->data['levelsea'])." <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'></td>
                            <td style='width:20px;'>{$guildgrade}</td>
                            <td>$gender <a href='".$florensia->outlink(array("characterdetails", $character->data['charname']))."'>".$florensia->escape($character->data['charname'])."</a></td>
                            <td style='width:160px'>".$florensia->escape($character->data['jobclass'])."</td>
                            <td style='width:150px'>".$florensia->escape($character->data['guild'])."</td>
                            <td style='width:90px'>".$florensia->escape($character->data['server'])."</td>
                            <td style='text-align:right; padding-right:3px; width:130px'>".$flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$character->data['lastupdate'], "m"))."</td>
                        </tr></table>
                    </div>
                    ";
                    
                    if (floclass_guild::check_privacy($guild['priv_activity_player'], $guild['guildid']) && $character->check_privacy("log_level")) {
                            $querylevellog = MYSQL_QUERY("SELECT level, timestamp, prelevel, pretimestamp, 'land' as type FROM flobase_character_log_level_land WHERE characterid='{$character->data['characterid']}' AND timestamp >='".bcsub(date("U"),60*60*$guildlatestupdates)."'
                                                         UNION
                                                         SELECT level, timestamp, prelevel, pretimestamp, 'sea' as type FROM flobase_character_log_level_sea WHERE characterid='{$character->data['characterid']}' AND timestamp >='".bcsub(date("U"),60*60*$guildlatestupdates)."'");
                            while ($levellog = MYSQL_FETCH_ARRAY($querylevellog)) {
                                if ($levellog['type']=="land") {
                                    $image = "<img src='{$florensia->layer_rel}/land.gif' alt='Land' style='height:11px;'>";
                                } else {
                                    $image = "<img src='{$florensia->layer_rel}/sealv.gif' alt='Sea' style='height:11px;'>";
                                }
                                if ($levellog['pretimestamp']) $timestamp = $flolang->sprintf($flolang->guild_recentupdates_levelup_timespan, timetamp2string(bcsub($levellog['timestamp'],$levellog['pretimestamp']), "d"));
                                else unset($timestamp);
                                $latestactivity[$levellog['timestamp']][] = $flolang->sprintf($flolang->guild_recentupdates_levelup, "<a href='".$florensia->outlink(array("characterdetails", $character->data['charname']))."'>".$florensia->escape($character->data['charname'])."</a>", " +".bcsub($levellog['level'], $levellog['prelevel'])." ({$levellog['level']}) {$image} {$timestamp}");
                            }
                    }
                }

                $tabbar['memberlist']['inactive'] = false;
                $tabbar['memberlist']['desc'] = $flolang->sprintf($flolang->tabbar_desc_guild_member, $overall['member']);

                $memberlist = "
                    <a name='memberlist'></a>
                    <div name='memberlist'>
                    
                        <div class='subtitle small' style='font-weight:normal; padding:1px; margin-bottom:10px;'>
                            <div id='guild_invaliddata_notice' class='small' style='font-weight:normal;'><a href='javascript:switchlayer(\"guild_invaliddata_notice,guild_invaliddata_form\", \"guild_invaliddata_form\")'>{$flolang->character_api_question_invaliddata}</a></div>
                            <div id='guild_invaliddata_form' class='small' style='font-weight:normal;'>
                                ".$flolang->sprintf($flolang->character_api_form_guild_forceupdate,
                                        "<a href='".$florensia->outlink(array('charapi'))."' target='_blank'>{$flolang->character_api_form_guild_forceupdate_url_readwhy}</a>",
                                        $florensia->quick_select("guilddetails", array('guildid'=>$_GET['guildid'], 'order'=>$_GET['order'], 'force'=>'character', 'anchor'=>'memberlist'), array($flolang->character_api_form_guild_forceupdate_chartitle=>"<input type='text' maxlength='13' name='forced_charname' style='width:110px;'>")),
                                        $florensia->quick_select("guilddetails", array('guildid'=>$_GET['guildid'], 'order'=>$_GET['order'], 'force'=>'guild', 'anchor'=>'memberlist'), array($flolang->character_api_form_guild_forceupdate_guildtitle=>"&nbsp;")),
                                        "<a href='".$florensia->outlink(array('charapi'))."' target='_blank'>{$flolang->character_api_form_guild_forceupdate_url_forcedlist}</a>"
                                )."
                            </div>
                            <script type='text/javascript'>
                                document.getElementById(\"guild_invaliddata_notice\").style.display=\"block\";
                                document.getElementById(\"guild_invaliddata_form\").style.display=\"none\";
                            </script>
                        </div>
                        
                        
                        <div class='bordered' style='font-weight:bold; margin-bottom:3px;'>
                                ".$florensia->quick_select("guilddetails", array('guildid'=>$_GET['guildid'], 'anchor'=>'memberlist'), array($flolang->statistic_ranking_quickselect_orderby=>$orderbyselect))."
                        </div>
                        <div class='subtitle' style='margin-bottom:4px;'>
                            <table style='width:100%'><tr>
                                    <td style='width:50px; text-align:right;'><img src='{$florensia->layer_rel}/land.gif' style='height:13px;' alt='Land'></td>
                                    <td style='width:50px; padding-right:10px; text-align:right;'><img src='{$florensia->layer_rel}/sealv.gif' style='height:13px;' alt='Sea'></td>
                                    <td style='width:20px;'></td>
                                    <td>{$flolang->character_title_charname}</td>
                                    <td style='width:160px'>{$flolang->character_title_jobclass}</td>
                                    <td style='width:150px'>{$flolang->character_title_guild}</td>
                                    <td style='width:90px'>{$flolang->character_title_server}</td>
                                    <td style='text-align:right; padding-right:3px; width:130px'>{$flolang->character_title_lastupdate}</td>
                            </tr></table>
                        </div>
                        {$memberlist}
                    </div>
                ";
                
                foreach ($overall['jobclass'] as $jobname => $amount) {
                    $joblist .= "{$amount} {$jobname}<br />";
                }
                $memberlistoverall = "
                            <tr><td>{$flolang->guild_title_member}</td><td>{$overall['member']} ({$overall['f']} <img src='{$florensia->layer_rel}/gender_female.gif' border='0' alt='male' style='height:12px;'> + {$overall['m']} <img src='{$florensia->layer_rel}/gender_male.gif' border='0' alt='male' style='height:12px;'>)</td></tr>
                            <tr><td>{$flolang->guild_title_maxmember}</td><td>".$flolang->sprintf($flolang->guild_title_maxmember_value, "{$guild['maxmember']} <img src='{$florensia->layer_rel}/icon_guild.gif' style='height:11px;' alt='Member'>", date("m.d.y", $guild['maxmembertimestamp'])." (".$flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$guild['maxmembertimestamp'], "d")).")")."</td></tr>
                            <tr><td>{$flolang->guild_title_levelland_average}</td><td>".bcdiv($overall['levelland'],$overall['member'], 2)." <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'></td></tr>
                            <tr><td>{$flolang->guild_title_levelsea_average}</td><td>".bcdiv($overall['levelsea'],$overall['member'], 2)." <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'></td></tr>
                            <tr><td>{$flolang->guild_title_jobclasses}</td><td>{$joblist}</td></tr>
                ";

                
                if (floclass_guild::check_privacy($guild['priv_activity_guild'], $guild['guildid'])) {
                    
                    $queryguildupdates = MYSQL_QUERY("SELECT l.characterid, charname, action, timestamp, l.oldguildgrade, l.newguildgrade FROM flobase_character_log_guild as l LEFT JOIN flobase_character as c ON (l.characterid=c.characterid) WHERE guildid='{$_GET['guildid']}' AND timestamp>".bcsub(date("U"),60*60*$guildlatestupdates));
                    while ($guildupdates = MYSQL_FETCH_ARRAY($queryguildupdates)) {
                        //fetch deleted chars
                        if (!$guildupdates['charname']) {
                            list($guildupdates['charname']) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT charname FROM flobase_character_archiv WHERE characterid='{$guildupdates['characterid']}'"));
                            $charlink = "<a href='".$florensia->outlink(array("characterdetails", $guildupdates['charname']), array('archivid'=>$guildupdates['characterid']))."' class='archiv'>".$florensia->escape($guildupdates['charname'])."</a>";
                        } else {
                            $charlink = "<a href='".$florensia->outlink(array("characterdetails", $guildupdates['charname']))."'>".$florensia->escape($guildupdates['charname'])."</a>";
                        }
                        
                        $oldguildgrade = $guildupdates['oldguildgrade'] ? class_character::guildgrade($guildupdates['oldguildgrade']) : "";
                        $newguildgrade = $guildupdates['newguildgrade'] ? class_character::guildgrade($guildupdates['newguildgrade']) : "";
                        
                        switch($guildupdates['action']) {
                            case "j": {
                                $latestactivity[$guildupdates['timestamp']][] = $flolang->sprintf($flolang->guild_recentupdates_joinguild, $charlink." ".$newguildgrade);
                                break;
                            }
                            case "l": {
                                $latestactivity[$guildupdates['timestamp']][] = $flolang->sprintf($flolang->guild_recentupdates_leftguild, $charlink." ".$oldguildgrade);
                                break;
                            }
                            case "a": {
                                $latestactivity[$guildupdates['timestamp']][] = $flolang->sprintf($flolang->guild_recentupdates_addguild, $charlink." ".$newguildgrade);
                                break;
                            }
                            case "d": {
                                $latestactivity[$guildupdates['timestamp']][] = $flolang->sprintf($flolang->guild_recentupdates_removeguild, $charlink." ".$oldguildgrade);
                                break;
                            }
                            case "g": {
                                $latestactivity[$guildupdates['timestamp']][] = $flolang->sprintf($flolang->guild_recentupdates_guildgrade, $charlink, $oldguildgrade, $newguildgrade);
                                break;
                            }
                        }
                    }
                
                    //general-log
                    $guildlastmembermax = 0;
                    $querygenerallog = MYSQL_QUERY("SELECT timestamp, attribute, oldvalue, newvalue FROM flobase_guild_log_general WHERE guildid='{$_GET['guildid']}' AND attribute='maxmember' AND timestamp>".bcsub(date("U"),60*60*$guildlatestupdates)." ORDER BY timestamp");
                    while ($generallog = MYSQL_FETCH_ARRAY($querygenerallog)) {
                        switch ($generallog['attribute']) {
                            case "maxmember": {
                                $timestamp = !$guildlastmembermax ? "" : $flolang->sprintf($flolang->guild_recentupdates_maxmember_previousrecord, $generallog['oldvalue'], $flolang->sprintf($flolang->character_lastupdate, timetamp2string($generallog['timestamp']-$guildlastmembermax, "m")), date("m.d.y", $guildlastmembermax));
                                $changetext = $flolang->sprintf($flolang->guild_recentupdates_maxmember, $generallog['newvalue'])." {$timestamp}"; 
                                $guildlastmembermax = $generallog['timestamp'];
                                break;
                            }
                            default: unset($changetext);
                        }
                        if ($changetext) $latestactivity[$generallog['timestamp']][] = $changetext;
                    }
                } #--end recent guild updates
                
                
                if (floclass_guild::check_privacy($guild['priv_activity_guild'], $guild['guildid']) OR floclass_guild::check_privacy($guild['priv_activity_player'], $guild['guildid'])) {
                    $activities = 0;
                    if (!$latestactivity) $latestactivity = $flolang->guild_recentupdates_nothingfound;
                    else {
                        $tmp = array();
                        krsort($latestactivity);
                        $c = "recentactivity_visible";
                        $i = 1;
                        foreach($latestactivity as $time => $values) {
                            if ($i==16) {
                                $tmp[$c][] = "
                                    <div id='recentactivity_visible'>
                                        <a href='javascript:switchlayer(\"recentactivity_visible,recentactivity_invisible\", \"recentactivity_invisible\")'>".$flolang->sprintf($flolang->notice_showmore, bcsub(count($latestactivity),15))."</a>
                                    </div> 
                                ";
                                $c="recentactivity_invisible";
                            }
                            $tmp[$c][] = "
                                    <table style='width:100%;'><tr>
                                        <td style='width:130px;'>".$flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$time))."</td>
                                        <td>".join("<br/>", $values)."</td>
                                    </tr></table>";
                            $i++;
                            $activities += count($values);
                        }
                        $latestactivity = join("", $tmp['recentactivity_visible']);
                        if ($tmp['recentactivity_invisible']) {
                            $latestactivity .= "
                                <div id='recentactivity_invisible' style='display:none;'>
                                    ".join("", $tmp['recentactivity_invisible'])."
                                    <a href='javascript:switchlayer(\"recentactivity_visible,recentactivity_invisible\", \"recentactivity_visible\")'>{$flolang->notice_showless}</a>
                                </div>
                            ";
                        }
                    }
                    
                    //tabbar+desc
                    $tabbar['activity']['inactive'] = false;
                    $tabbar['activity']['desc'] = $flolang->sprintf($flolang->tabbar_desc_guild_activity, $activities);
                    
                    $latestactivity = "
                        <a name='activity'></a>
                        <div name='activity'>
                            <div class='subtitle small' style='padding:10px;'>
                                ".$flolang->sprintf($flolang->guild_recentupdates_title, $guildlatestupdates)."
                                <div style='font-weight:normal;'>
                                {$latestactivity}
                                </div>
                            </div>
                        </div>
                    ";
                }
            } #-- end memberlist-privacy
            
            //contacts need only to displayed if the guild is still active (not in archiv)
            unset($guildcontactlist);
            $queryguildcontacts = MYSQL_QUERY("SELECT * FROM flobase_guild_contacts WHERE guildid='{$guild['guildid']}'");
            while ($guildcontacts = MYSQL_FETCH_ARRAY($queryguildcontacts)) {
                if (floclass_guild::check_privacy($guildcontacts['contactprivacy'], $guild['guildid'])) {
                    switch($guildcontacts['contacttype']) {
                        case "teamspeak2":
                            $contacttype = $flolang->guild_contacts_teamspeak2;
                            $contactvalue = $florensia->escape($guildcontacts['contactvalue']);
                            if (strlen($guildcontacts['contactpassword'])) $contactvalue .= " (".$florensia->escape($guildcontacts['contactpassword']).")";
                            break;
                        case "teamspeak3":
                            $contacttype = $flolang->guild_contacts_teamspeak3;
                            $contactvalue = $florensia->escape($guildcontacts['contactvalue']);
                            if (strlen($guildcontacts['contactpassword'])) $contactvalue .= " (".$florensia->escape($guildcontacts['contactpassword']).")";
                            break;
                        case "icq":
                            $contacttype = $flolang->guild_contacts_icq;
                            $contactvalue = $florensia->escape($guildcontacts['contactvalue']);
                            break;
                        case "msn":
                            $contacttype = $flolang->guild_contacts_msn;
                            $contactvalue = $florensia->escape($guildcontacts['contactvalue']);
                            break;
                        case "skype":
                            $contacttype = $flolang->guild_contacts_skype;
                            $contactvalue = $florensia->escape($guildcontacts['contactvalue']);
                            break;
                        case "forum":
                            $contacttype = $flolang->guild_contacts_forum;
                            if (!preg_match("/^http:\/\//", $guildcontacts['contactvalue'])) $guildcontacts['contactvalue'] = "http://".$guildcontacts['contactvalue'];
                            $contactvalue = "<a href='".$florensia->escape($guildcontacts['contactvalue'])."' target='_blank'>".$florensia->escape($guildcontacts['contactvalue'])."</a>";
                            if (strlen($guildcontacts['contactpassword'])) $contactvalue .= " (".$florensia->escape($guildcontacts['contactpassword']).")";
                            break;
                        case "website":
                            $contacttype = $flolang->guild_contacts_website;
                            if (!preg_match("/^http:\/\//", $guildcontacts['contactvalue'])) $guildcontacts['contactvalue'] = "http://".$guildcontacts['contactvalue'];
                            $contactvalue = "<a href='".$florensia->escape($guildcontacts['contactvalue'])."' target='_blank'>".$florensia->escape($guildcontacts['contactvalue'])."</a>";
                            if (strlen($guildcontacts['contactpassword'])) $contactvalue .= " (".$florensia->escape($guildcontacts['contactpassword']).")";
                            break;
                    }
                    
                    $guildcontactlist .= "
                        <tr>
                            <td>{$contacttype}</td>
                            <td>{$contactvalue}</td>
                        </tr>
                    ";
                }
            }
            
            //wanted!-list needs only be displayed if not in archiv-mode
            unset($wantedlist);
            $queryguildwanted = MYSQL_QUERY("SELECT * FROM flobase_guild_wanted WHERE guildid='{$guild['guildid']}'");
            while ($guildwanted = MYSQL_FETCH_ARRAY($queryguildwanted)) {
                if ($guildwanted['searchclass']=="all") $guildwanted['searchclass'] = $flolang->guild_wanted_allclasses;
                if (!$guildwanted['level_land']) $guildwanted['level_land']="-";
                if (!$guildwanted['level_sea']) $guildwanted['level_sea']="-";
                $wantedlist .= "
                    <tr>
                        <td>".$florensia->escape($guildwanted['searchclass'])."</td>
                        <td><img src='{$florensia->layer_rel}/land.gif' style='height:11px;'> {$guildwanted['level_land']}</td>
                        <td><img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;'> {$guildwanted['level_sea']}</td>
                    </tr>
                ";
            }
            if ($wantedlist) {
                if ($guild['misc_wanted_age']) {
                    $wanted_additionalinfo[] = $flolang->sprintf($flolang->guild_wanted_notice_age, $guild['misc_wanted_age']);
                }
                if ($guild['misc_wanted_secondcharacter']) {
                    $wanted_additionalinfo[] = $flolang->guild_wanted_notice_secondcharacter_accept;
                } else $wanted_additionalinfo[] = $flolang->guild_wanted_notice_secondcharacter_denied;
                
                $wantedlist = "
                    <div  class='small subtitle' style='margin-top:10px; font-weight:normal; padding:5px;'>
                        <b>{$flolang->guild_wanted_titlenotice}</b>
                        <table class='shortinfo_1' style='border-left:none; border-right:none; width:100%;'>
                            <tr>
                                <td style='width:150px; font-weight:bold;'>{$flolang->guild_wanted_table_jobclass}</td>
                                <td style='font-weight:bold;'>{$flolang->guild_wanted_table_level_land}</td>
                                <td style='font-weight:bold;'>{$flolang->guild_wanted_table_level_sea}</td>
                            </tr>
                            {$wantedlist}
                        </table>
                        ".join("<br />",$wanted_additionalinfo)."
                    </div>
                ";
            }
            
            
        }
        //make sure we unset this array if nothing to display (still an array):
        if (is_array($latestactivity)) unset($latestactivity);
            
            //logo?
            if ($guild['avatar_size']) {
                list($avatar_width, $avatar_height) = explode("x", $guild['avatar_size']);
                $ifile = "pictures/guild_avatars/{$guild['guildid']}";
                $ifile = "{$florensia->root}/{$ifile}?".filectime("{$florensia->root_abs}/{$ifile}");
                $guildlogo = "<div class='subtitle' style='margin:auto; margin-bottom:10px; width:{$avatar_width}px; height:{$avatar_height}px; background-image:url({$ifile}); background-repeat:no-repeat; background-position:center bottom;''></div>";
            }
            //description?
            if ($guild['misc_description']) {
                //maybe we can it display on the side of the guildlogo?
                if ($guild['avatar_size'] && $avatar_width<=500) {
                    $guildlogo = "
                        <div class='subtitle small' style='font-weight:normal; padding:5px;  margin-bottom:10px; min-height:{$avatar_height}px;'>
                            <div style='float:left; padding-right:10px;'>{$guildlogo}</div>
                            ".$parser->parse_message($guild['misc_description'], $florensia->default_parser_options)."
                        </div>
                    ";
                } else { //logo not available or too big
                    $guilddescription = "<div class='subtitle small' style='font-weight:normal; padding:10px'>".$parser->parse_message($guild['misc_description'], $florensia->default_parser_options)."</div>";
                }
                
            }
            //need to search for an owner to verify?
            if ($leader = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT d.ownerid, c.charname FROM flobase_character as c, flobase_character_data as d WHERE c.characterid=d.characterid AND d.guildgrade='5' AND d.guildid='{$guild['guildid']}'"))) {
                if (!$leader['ownerid']) $verifiednotice = "<a href='".$florensia->outlink(array("charapi", "verify", $leader['charname']))."'>{$flolang->character_api_verify_link_fromguilddetails}</a>";
                elseif ($flouser->get_permission("guild", "moderate")) $verifiednotice = $flouserdata->get_username($leader['ownerid']);
            }
            
            
            $tabbar = $florensia->tabbar($tabbar);
            
            $guildoverview = "
            <div class='small' style='float:right; font-weight:bold;'>{$verifiednotice}</div>
            <div style='margin-top:10px;'>{$tabbar['tabbar']}</div>
            <a name='details'></a>
            <div name='details'>
                {$guildlogo}
                <div class='subtitle small' style='min-height:190px; padding:5px;'>
                {$archivimage}
                {$guildranking}
                    <div style='margin-right:150px;'>
                        <table style='width:100%;'>
                            <tr><td style='width:150px;'>{$flolang->guild_title_guildname}</td><td>".$florensia->escape($guild['guildname'])." ".floclass_guild::get_language_pic($guild['misc_language'])."</td></tr>
                            <tr><td>{$flolang->guild_title_server}</td><td><a href='{$florensia->root}/statistics/".$florensia->escape($guild['server'])."'>".$florensia->escape($guild['server'])."</a></td></tr>
                            {$deletedguildnotice}
                            {$memberlistoverall}
                            {$guildcontactlist}
                        </table>
                    </div>
                {$privacynotice}
                </div>
                {$guilddescription}
                {$wantedlist}
                ".$florensia->adsense(0)."
            </div>
            {$memberlist}
            {$latestactivity}
            {$guildsettings}
            {$tabbar['jscript']}
            ";
            
        
            $pagetitle = "<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/guilddetails'>{$flolang->guild_sitetitle}</a> &gt; <a href='".$florensia->outlink(array('guildranking'), array('server'=>$guild['server'], 'order'=>'name'))."'>".$florensia->escape($guild['server'])."</a> &gt; ".$florensia->escape($guild['guildname'])."</div>";
            $florensia->sitetitle($florensia->escape($guild['server']));
            $florensia->sitetitle($florensia->escape($guild['guildname']));
    }
    else {
        $pagetitle = "<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/guilddetails'>{$flolang->guild_sitetitle}</a></div>";
        $guildoverview = "<div class='warning'>".$flolang->sprintf($flolang->guild_error_notfound, "<a href='".$florensia->outlink(array('charapi'))."' target='_blank'>{$flolang->character_api_form_guild_forceupdate_url_readwhy}</a>")."</div>";
    }
}
elseif (strlen($_GET['search'])>0) {  
    /*if (strlen($_GET['notfound']) OR strlen($_GET['search'])) {
    if ($_GET['notfound']) {
        $_GET['search'] = $_GET['notfound'];
        $notfoundnotice = "<b>".$flolang->sprintf($flolang->character_api_notfound_long, $florensia->escape($_GET['notfound']))."</b>";
    }*/
        $cachelimit = 100;
        $queryguildsearch = MYSQL_QUERY("SELECT memberamount, server, guildname, guildid FROM flobase_guild WHERE guildname LIKE '%".get_searchstring($_GET['search'],0)."%' AND memberamount!='0' ORDER BY guildname LIMIT {$cachelimit}");
        for ($i=0; $guildsearch = MYSQL_FETCH_ASSOC($queryguildsearch); $i++) {
            $cachedlist[$i%3] .= "<a href='".$florensia->outlink(array('guilddetails', $guildsearch['guildid'], $guildsearch['server'], $guildsearch['guildname']))."'>".$florensia->escape($guildsearch['guildname'])."</a>, ".$florensia->escape($guildsearch['server'])." ({$guildsearch['memberamount']} Member)<br />";
        }
        
        if ($cachedlist) {
            $cachedlist = "
            <div style='margin-top:10px;'>".$flolang->sprintf($flolang->guild_api_notfound_cachedline, $cachelimit)."</div>
            <div>
                <table style='width:100%; margin-top:5px; font-weight:normal;' class='subtitle'>
                <tr><td style='width:33%;'>{$cachedlist[0]}</td><td style='width:33%;'>{$cachedlist[1]}</td><td>{$cachedlist[2]}</td><td>{$cachedlist[3]}</td></tr>
            </table>
            </div>";
        }
        $searched = "<div class='borderd small' style='margin-top:15px;'>
            <div class='warning'>".$flolang->sprintf($flolang->guild_error_notfound, "<a href='".$florensia->outlink(array('charapi'))."' target='_blank'>{$flolang->character_api_form_guild_forceupdate_url_readwhy}</a>")."</div>
            {$cachedlist}
        </div>";
            
        $pagetitle = "<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/guilddetails'>{$flolang->guild_sitetitle}</a></div>";
        $florensia->sitetitle("Search");
} else {
        $tmptime = date("U");
        $queryrecent = MYSQL_QUERY("SELECT c.charname, l.action, l.guildid, g.guildname, g.server, l.timestamp, g.memberamount, l.oldguildgrade, l.newguildgrade FROM flobase_character_log_guild as l, flobase_guild as g, flobase_character as c , flobase_character_data as d WHERE timestamp>".bcsub(date("U"), 60*60*48)." AND l.guildid=g.guildid AND c.characterid=l.characterid AND d.characterid=l.characterid AND (g.priv_activity_guild='a' OR g.priv_activity_guild='') AND (d.priv_log_guild='' OR d.priv_log_guild='a') AND (l.action='l' OR l.action='a' OR l.action='j') ORDER BY timestamp DESC LIMIT 30");
        while ($recent = MYSQL_FETCH_ARRAY($queryrecent)) {

                    if ($recent['memberamount']) $guildlink = "<a href='".$florensia->outlink(array("guilddetails", $recent['guildid'], $recent['server'], $recent['guildname']))."'>".$florensia->escape($recent['guildname'])."</a>";
                    else $guildlink = "<a href='".$florensia->outlink(array("guilddetails", $recent['guildid'], $recent['server'], $recent['guildname']))."' class='archiv'>".$florensia->escape($recent['guildname'])."</a>";
                    
                    $charname = "<a href='".$florensia->outlink(array("characterdetails", $recent['charname']))."'>".$florensia->escape($recent['charname'])."</a>";
                    $serverlink = "<a href='".$florensia->outlink(array("statistics", $recent['server']))."'>".$florensia->escape($recent['server'])."</a>";

                    if ($tmptime>$recent['timestamp']) {
                        $timestamp = $flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$recent['timestamp']));
                        $tmptime = $recent['timestamp'];
                    } else $timestamp = "";
                    
                    $oldguildgrade = $recent['oldguildgrade'] ? class_character::guildgrade($recent['oldguildgrade']) : "";
                    $newguildgrade = $recent['newguildgrade'] ? class_character::guildgrade($recent['newguildgrade']) : "";
                    
                    switch($recent['action']) {
                        case "j": {
                            $recentupdates .= "<tr><td style='width:110px;'>{$timestamp}</td><td>".$flolang->sprintf($flolang->character_recentupdates_joinguild, $charname, $guildlink." ".$newguildgrade)." ({$serverlink})</td></tr>";
                            break;
                        }
                        case "l": {
                            $recentupdates .= "<tr><td style='width:110px;'>{$timestamp}</td><td>".$flolang->sprintf($flolang->character_recentupdates_leftguild, $charname, $guildlink." ".$oldguildgrade)." ({$serverlink})</td></tr>";
                            break;
                        }
                        case "a": {
                            $recentupdates .= "<tr><td style='width:110px;'>{$timestamp}</td><td>".$flolang->sprintf($flolang->character_recentupdates_addguild, $charname, $guildlink." ".$newguildgrade)." ({$serverlink})</td></tr>";
                            break;
                        }
                    }
        }
        $recentupdates = "
            <div class='small subtitle' style='margin-top:15px;'>
                {$flolang->character_overview_recentupdates}
                <table style='width:100%; font-weight:normal;'>
                    {$recentupdates}
                </table>
            </div>";
    
    $pagetitle = "<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/guilddetails'>{$flolang->guild_sitetitle}</a></div>";
}

    //$florensia->notice("<div class='warning' style='margin-bottom:10px;'>{$flolang->signature_api_failnotice}</div>");
    $content = "
    {$pagetitle}
    {$jumptoguild}
    {$guildoverview}
    {$searched}
    {$recentupdates}
    ";
    $florensia->output_page($content);

?>
