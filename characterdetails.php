<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("statistic,character,guild");
    
if ($_GET['character']) {
    
    $getcharacter = explode("-", $_GET['character']);

    if (count($getcharacter)==1) {
        if (intval($_GET['archivid'])) $character = new class_character(intval($_GET['archivid']), array('archivid'=>true));
        else $character = new class_character($getcharacter[0]);
        if (!$character->is_valid()) {
            
            if ($character->get_error=="timeout") {
                $charoverview = "<div class='warning'>{$flolang->character_api_error_timeout}</div>";
            } else {
                header("Location: ".$florensia->outlink(array('characterdetails'), array('notfound'=>$getcharacter[0]), array("language"=>FALSE, "escape"=>FALSE)));
                die;
                //$charoverview = "<div class='warning'>{$flolang->character_api_error_notfound}</div>";
            }
        } else {
            
            if ($character->data['gender']=="m") $gender = "<img src='{$florensia->layer_rel}/gender_male.gif' border='0' alt='male' style='height:12px;'>";
            else  $gender = "<img src='{$florensia->layer_rel}/gender_female.gif' border='0' alt='male' style='height:12px;'>";
    
            if (strlen($character->data['guild'])) {
                if ($character->data['guildid']) $guild = "<a href='".$florensia->outlink(array('guilddetails', $character->data['guildid'], $character->data['server'], $character->data['guild']))."'>".$florensia->escape($character->data['guild'])."</a>";
                else $guild = $florensia->escape($character->data['guild']);
                
                if ($character->data['guildgrade']) $guild .= " ".class_character::guildgrade($character->data['guildgrade']);
            }
            else $guild = "-";

            if (!$character->is_archiv()) {

                $ranking = array(
                  array('rankingtype'=>'g', 'leveltype'=>'levelsum', 'level'=>$character->data['levelsum']),
                  array('rankingtype'=>'s', 'leveltype'=>'levelsea', 'level'=>$character->data['levelsea']),
                  array('rankingtype'=>'l', 'leveltype'=>'levelland', 'level'=>$character->data['levelland'])
                );
                $rankingresults = array('local'=>array(), 'global'=>array());
                //global ranking
                foreach($ranking as $s) {
                  if (!$result = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT rank, lastupdated from flobase_character_ranking_global WHERE type='{$s['rankingtype']}' AND level='{$s['level']}'"))
                        OR $result['lastupdated']<(bcsub(date("U"), 60*60*24))
                  ) {
                    list($result) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(characterid) FROM flobase_character_data WHERE {$s['leveltype']}>'{$s['level']}'"));
                    $result++;
                    $rankingresults['global'][$s['leveltype']] = $result;
                    MYSQL_QUERY("REPLACE INTO flobase_character_ranking_global (type, level, rank, lastupdated) VALUES('{$s['rankingtype']}', '{$s['level']}', '{$result}', '".date("U")."')");
                  } else {
                    $rankingresults['global'][$s['leveltype']] = $result['rank'];
                  }
                }
                //local ranking
                foreach($ranking as $s) {
                  if (!$result = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT rank, lastupdated from flobase_character_ranking_local WHERE server='{$character->data['server']}' AND type='{$s['rankingtype']}' AND level='{$s['level']}'"))
                        OR $result['lastupdated']<(bcsub(date("U"), 60*60*24))
                  ) {
                    list($result) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(characterid) FROM flobase_character_data WHERE server='{$character->data['server']}' AND {$s['leveltype']}>'{$s['level']}'"));
                    $result++;
                    $rankingresults['local'][$s['leveltype']] = $result;
                    MYSQL_QUERY("REPLACE INTO flobase_character_ranking_local (type, server, level, rank, lastupdated) VALUES('{$s['rankingtype']}', '{$character->data['server']}', '{$s['level']}', '{$result}', '".date("U")."')");
                  } else {
                    $rankingresults['local'][$s['leveltype']] = $result['rank'];
                  }
                }
                
                if ($character->data['firstappearance']) {
                    $firstappearance = "
                            <b>{$flolang->character_title_firstappearance}</b><br />
                            ".date("m.d.y", $character->data['firstappearance'])." (".$flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$character->data['firstappearance'], "d")).")<br />
                            <br />
                    ";
                }
                else unset($firstappearance);
                
                $rankingoverview = "
                    <div style='float:right; text-align:right;'>
                            {$firstappearance}
                            <b>{$flolang->character_ranking_global_title}</b><br/>
                            <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'>+<img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'> # {$rankingresults['global']['levelsum']}<br />
                            <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'> # {$rankingresults['global']['levelland']}<br />
                            <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'> # {$rankingresults['global']['levelsea']}<br />
                            <b>{$flolang->character_ranking_server_title}</b><br/>
                            <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'>+<img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'> # {$rankingresults['local']['levelsum']}<br />
                            <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'> # {$rankingresults['local']['levelland']}<br />
                            <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'> # {$rankingresults['local']['levelsea']}<br />
                    </div>
                ";
                
                if ($character->data['forceupdate']>=1) {
                    $characterforceupdateform = "
                        <div class='small' style='font-weight:normal; margin-top:10px;'>
                            {$flolang->character_api_form_character_forceupdate_running}<br />
                            <a href='".$florensia->outlink(array('charapi'))."' target='_blank'>{$flolang->character_api_form_guild_forceupdate_url_forcedlist}</a>
                        </div>
                    ";
                } else {
                    $characterforceupdateform = "
                        <div id='character_invaliddata_notice' class='small' style='font-weight:normal; margin-top:10px;'><a href='javascript:switchlayer(\"character_invaliddata_notice,character_invaliddata_form\", \"character_invaliddata_form\")'>{$flolang->character_api_question_invaliddata_char}</a></div>
                        <div id='character_invaliddata_form' class='small' style='font-weight:normal; margin-top:10px;'>
                            ".$flolang->sprintf($flolang->character_api_form_character_forceupdate,
                                    "<a href='".$florensia->outlink(array('charapi'))."' target='_blank'>{$flolang->character_api_form_guild_forceupdate_url_readwhy}</a>",
                                    $florensia->quick_select("characterdetails", array('character'=>$_GET['character'], 'force'=>'character'), array($flolang->character_api_form_character_forceupdate_title=>"&nbsp;")),
                                    "<a href='".$florensia->outlink(array('charapi'))."' target='_blank'>{$flolang->character_api_form_guild_forceupdate_url_forcedlist}</a>"
                            )."
                        </div>
                        <script type='text/javascript'>
                            document.getElementById(\"character_invaliddata_notice\").style.display=\"block\";
                            document.getElementById(\"character_invaliddata_form\").style.display=\"none\";
                        </script>
                    ";
                }
                //$characterhidenotice = "<div style='margin-top:7px; font-weight:normal;'>".$flolang->sprintf($flolang->character_hidedetails_notice, $flouserdata->get_username(1))."</div>";
            } else {
                //archiv-character
                $archivimage = "
                    <div style='float:right; text-align:center; vertical-align:middle; width:130px; height:123px; background-image:url({$florensia->layer_rel}/archiv_icon.png);'>
                        <div style='margin-top:53px;'>Archive<br />{$character->data['characterid']}</div>
                    </div>
                ";
            }
             /*last activity*/
            if ($character->check_privacy("log_level")) {
                #level-log
                $querylevellog = MYSQL_QUERY("SELECT level, prelevel, timestamp, pretimestamp, 'land' as type FROM flobase_character_log_level_land WHERE characterid='{$character->data['characterid']}' AND timestamp!='0'
                                              UNION
                                              SELECT level, prelevel, timestamp, pretimestamp, 'sea' as type FROM flobase_character_log_level_sea WHERE characterid='{$character->data['characterid']}' AND timestamp!='0'");
                while ($levellog = MYSQL_FETCH_ARRAY($querylevellog)) {
                    if ($levellog['type']=="land") {
                        $image = "<img src='{$florensia->layer_rel}/land.gif' alt='Land' style='height:11px;'>";
                    } else {
                        $image = "<img src='{$florensia->layer_rel}/sealv.gif' alt='Sea' style='height:11px;'>";
                    }
                    if ($levellog['pretimestamp']) $timestamp = $flolang->sprintf($flolang->guild_recentupdates_levelup_timespan, timetamp2string(bcsub($levellog['timestamp'],$levellog['pretimestamp']), "d"));
                    else unset($timestamp);
                    $lastactivity[$levellog['timestamp']][] = "LevelUp +".bcsub($levellog['level'], $levellog['prelevel'])." ({$levellog['level']}) {$image} {$timestamp}";
                }
                
                #general-log
                $querygenerallog = MYSQL_QUERY("SELECT timestamp, attribute, oldvalue, newvalue FROM flobase_character_log_general WHERE characterid='{$character->data['characterid']}'");
                while ($generallog = MYSQL_FETCH_ARRAY($querygenerallog)) {
                    switch ($generallog['attribute']) {
                        case "jobclass": {
                            $changetext = $flolang->sprintf($flolang->character_recentupdates_general_jobclass, $generallog['oldvalue'], $generallog['newvalue']);
                            break;
                        }
                        case "gender": {
                            $generallog['oldvalue'] = $generallog['oldvalue']=="m" ? "<img src='{$florensia->layer_rel}/gender_male.gif' border='0' alt='male' style='height:12px;'>" : "<img src='{$florensia->layer_rel}/gender_female.gif' border='0' alt='female' style='height:12px;'>";
                            $generallog['newvalue'] = $generallog['newvalue']=="m" ? "<img src='{$florensia->layer_rel}/gender_male.gif' border='0' alt='male' style='height:12px;'>" : "<img src='{$florensia->layer_rel}/gender_female.gif' border='0' alt='female' style='height:12px;'>";
                            $changetext = $flolang->sprintf($flolang->character_recentupdates_general_gender, $generallog['oldvalue'], $generallog['newvalue']);
                            break;
                        }
                        default: unset($changetext);
                    }
                    if ($changetext) $lastactivity[$generallog['timestamp']][] = $changetext;
                }
            }
            
            if ($character->check_privacy("log_guild")) {
                #guildlog
                $deleted = false;
                $queryguildupdates = MYSQL_QUERY("SELECT l.action, l.timestamp, l.guildid, g.guildname, g.server, g.memberamount, l.oldguildgrade, l.newguildgrade FROM flobase_character_log_guild as l, flobase_guild as g WHERE l.characterid='{$character->data['characterid']}' AND l.guildid=g.guildid");
                while ($guildupdates = MYSQL_FETCH_ARRAY($queryguildupdates)) {                        
                    if ($guildupdates['memberamount']) $guildlink = "<a href='".$florensia->outlink(array("guilddetails", $guildupdates['guildid'], $guildupdates['server'], $guildupdates['guildname']))."'>".$florensia->escape($guildupdates['guildname'])."</a>";
                    else $guildlink = "<a href='".$florensia->outlink(array("guilddetails", $guildupdates['guildid'], $guildupdates['server'], $guildupdates['guildname']))."' class='archiv'>".$florensia->escape($guildupdates['guildname'])."</a>";
                    
                    $oldguildgrade = $guildupdates['oldguildgrade'] ? class_character::guildgrade($guildupdates['oldguildgrade']) : "";
                    $newguildgrade = $guildupdates['newguildgrade'] ? class_character::guildgrade($guildupdates['newguildgrade']) : "";
                    
                    switch($guildupdates['action']) {
                        case "j": {
                            $lastactivity[$guildupdates['timestamp']][] = $flolang->sprintf($flolang->character_recentupdates_joinguild, $florensia->escape($character->data['charname']), $guildlink." ".$newguildgrade);
                            break;
                        }
                        case "l": {
                            $lastactivity[$guildupdates['timestamp']][] = $flolang->sprintf($flolang->character_recentupdates_leftguild, $florensia->escape($character->data['charname']), $guildlink." ".$oldguildgrade);
                            break;
                        }
                        case "a": {
                            $lastactivity[$guildupdates['timestamp']][] = $flolang->sprintf($flolang->character_recentupdates_addguild, $florensia->escape($character->data['charname']), $guildlink." ".$newguildgrade);
                            break;
                        }
                        case "g": {
                            $lastactivity[$guildupdates['timestamp']][] = $flolang->sprintf($flolang->character_recentupdates_guildgrade, $florensia->escape($character->data['charname']), $oldguildgrade, $newguildgrade, $guildlink);
                            break;
                        }
                        case "d" : {
                            $deleted = true;
                            break;
                        }
                    }
                    if ($deleted) break;
                }
            }

                if (!$lastactivity) $lastactivity = "{$flolang->character_notice_nolastactivity} <span class='small' style='font-weight:normal;'>(".$flolang->sprintf($flolang->character_lastupdate_priority, $character->data['updatepriority']).")</span>";
                else {
                    $tmp = array();
                    krsort($lastactivity);
                    $c = "recentactivity_visible";
                    $i = 1;
                    foreach($lastactivity as $time => $values) {
                        if ($i==10) {
                            $tmp[$c] .= "
                                <div id='recentactivity_visible'>
                                    <a href='javascript:switchlayer(\"recentactivity_visible,recentactivity_invisible\", \"recentactivity_invisible\")'>".$flolang->sprintf($flolang->notice_showmore, bcsub(count($lastactivity),9))."</a>
                                </div> 
                            ";
                            $c="recentactivity_invisible";
                        }
                        $time = intval($time);
                        $tmp[$c] .= "<table style='width:100%;'><tr>
                                    <td style='width:80px;'>".date("m.d.Y", $time)."</td>
                                    <td>".join("<br/>", $values)."</td>
                                </tr></table>";
                        $i++;
                    }

                    $lastactivity = "
                        {$flolang->character_title_lastactivity} (".$flolang->sprintf($flolang->character_lastupdate_priority, $character->data['updatepriority']).")
                        <div style='font-weight:normal;'>{$tmp['recentactivity_visible']}</div>
                    ";
                    if ($tmp['recentactivity_invisible']) {
                        $lastactivity .= "
                            <div id='recentactivity_invisible' style='font-weight:normal; display:none;'>
                                {$tmp['recentactivity_invisible']}
                                <a href='javascript:switchlayer(\"recentactivity_visible,recentactivity_invisible\", \"recentactivity_visible\")'>{$flolang->notice_showless}</a>
                            </div>
                        ";
                    }
                }
            
            //else $lastactivity = "<div style='font-weight:normal;'>{$flolang->character_recentupdates_invisiblenotice}</div>";
            
            $lastactivity = "
                <div class='subtitle small' style='padding:10px; margin-bottom:15px;'>
                {$lastactivity}
                </div>
            ";
            
            //any uploaded pictures?
            list($characterimages) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(*) FROM flobase_character_gallery WHERE characterid='{$character->data['characterid']}'"));
            $galleryoptlink = $character->merge_opt_link(array());
	    $tabbar['details'] = array("anchor"=>"details", "name"=>$flolang->tabbar_title_characterdetails, "desc"=>$flolang->tabbar_desc_characterdetails);
	    $tabbar['gallery'] = array("link"=>$florensia->outlink(array("gallery", "c", $character->data['charname']), $galleryoptlink), "name"=>$flolang->tabbar_title_gallery, "desc"=>$flolang->sprintf($flolang->tabbar_desc_gallery, $characterimages));
	    //$tabbar['friends'] = array("anchor"=>"friends", "name"=>$flolang->tabbar_title_character_friends, "desc"=>$flolang->sprintf($flolang->tabbar_desc_character_friends, "xxx");
            
            //verified? if not show link to verify, otherwhise verified-notice and, if user is the registered and verified owner, show settings-tag
            if (!$character->data['ownerid'] && !$character->is_archiv()) {
                $verifiednotice = "<a href='".$florensia->outlink(array("charapi", "verify", $character->data['charname']))."'>{$flolang->character_api_verify_link_fromcharacterdetails}</a>";
            }
            if ($character->is_owner()) {
                //settings-tab
                $tabbar['settings'] = array("anchor"=>"settings", "name"=>$flolang->tabbar_title_character_settings);
                if ($flouser->get_permission("character", "moderate") && $character->data['ownerid']) $verifiednotice = $flouserdata->get_username($character->data['ownerid']);
                //saving settings
                if ($_POST['save_settings']) {
                    //handling of avatar
                    $avatarpath = "{$florensia->root_abs}/pictures/character_avatars/{$character->data['characterid']}";
                    if ($_POST['rm_avatar'] && is_file($avatarpath)) {
                        if (unlink($avatarpath)) {
                            if (!MYSQL_QUERY("UPDATE flobase_character_data SET avatar_size='' WHERE characterid='{$character->data['characterid']}'")) $error=true;
                            $character->data['avatar_size'] = "";
                        }
                    } elseif ($_FILES['file_avatar']['tmp_name']) {
                        $image = $_FILES['file_avatar']['tmp_name'];            
                        list($width, $height, $type, $attr) = getimagesize($image);
                        if ($height<=0 OR $width<=0 OR !in_array($type, array(
                                       1, //'IMAGETYPE_GIF'
                                       2, //'IMAGETYPE_JPEG'
                                       3 //'IMAGETYPE_PNG'
                                ))) {
                            $florensia->notice($flolang->character_edit_error_avatar, "warning");
                        } else {
                            //if it's already in the right size, just copy it
                            if ($height<=400 && $width<=300) @rename($image, $avatarpath);
                            else {
                                
                                $theight = $height;
                                $twidth = $width;
                                if ($theight>400) { 
                                    $twidth = ceil($twidth*(400/$theight));
                                    $theight = 400;
                                }
                                if ($twidth>300) { 
                                    $theight = ceil($theight*(300/$twidth));
                                    $twidth = 300;
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
                            $character->data['avatar_size'] = "{$width}x{$height}";
                            if (!MYSQL_QUERY("UPDATE flobase_character_data SET avatar_size = '{$width}x{$height}' WHERE characterid='{$character->data['characterid']}'")) $error=true;
                        }
                    }
                    
                    //handling of privacy
                    #guild-logs
                    if (preg_match("/^[nrbgf]+$/", join("", $_POST['privacy_guild'])) && join("", $_POST['privacy_guild'])) {
                        //we got some special privacy
                        if (in_array("n", $_POST['privacy_guild'])) $character->data['priv_log_guild'] = "n";
                        elseif (in_array("r", $_POST['privacy_guild'])) $character->data['priv_log_guild'] = "r";
                        else $character->data['priv_log_guild'] = join("", array_unique($_POST['privacy_guild']));
                    } else {
                        //defaults
                        $character->data['priv_log_guild'] = "a";
                    }
                    
                    #levelup-logs
                    if (preg_match("/^[nrbgf]+$/", join("", $_POST['privacy_level'])) && join("", $_POST['privacy_level'])) {
                        //we got some special privacy
                        if (in_array("n", $_POST['privacy_level'])) $character->data['priv_log_level'] = "n";
                        elseif (in_array("r", $_POST['privacy_level'])) $character->data['priv_log_level'] = "r";
                        else $character->data['priv_log_level'] = join("", array_unique($_POST['privacy_level']));
                    } else {
                        //defaults
                        $character->data['priv_log_level'] = "a";
                    }
                    
                    /*
                    #friendlist
                    if (preg_match("/^[nrbgf]+$/", join("", $_POST['privacy_friendlist'])) && join("", $_POST['privacy_friendlist'])) {
                        //we got some special privacy
                        if (in_array("n", $_POST['privacy_friendlist'])) $character->data['priv_friends'] = "n";
                        elseif (in_array("r", $_POST['privacy_friendlist'])) $character->data['priv_friends'] = "r";
                        else $character->data['priv_friends'] = join("", array_unique($_POST['privacy_friendlist']));
                    } else {
                        //defaults
                        $character->data['priv_friends'] = "a";
                    }
                    //handling of friendrequests
                    switch($_POST['handle_friendrequest']) {
                        case "accept":
                            $character->data['handle_friends'] = "a";
                            break;
                        case "deny":
                            $character->data['handle_friends'] = "d";
                            break;
                        default: //also notice
                            $character->data['handle_friends'] = "n";
                    }
                    */
                    
                    if (MYSQL_QUERY("UPDATE flobase_character_data SET
                                priv_log_guild='{$character->data['priv_log_guild']}',
                                priv_log_level='{$character->data['priv_log_level']}',
                                handle_friends='{$character->data['handle_friends']}',
                                priv_friends='{$character->data['priv_friends']}'
                                WHERE characterid='{$character->data['characterid']}'")) {
                        $florensia->notice($flolang->character_edit_successful, "successful");
                    } else {
                        $error=true;
                    }
                    if ($error) $florensia->notice($flolang->character_edit_error, "error");
                }
                //some defaults if not set sometimes yet
                if (!$character->data['priv_log_level']) $character->data['priv_log_level'] = "a";
                if (!$character->data['priv_log_guild']) $character->data['priv_log_guild'] = "a";
                if (!$character->data['priv_friends']) $character->data['priv_friends'] = "a";
                if (!$character->data['handle_friends']) $character->data['handle_friends'] = "n";
                
                
                //set display of settings
                foreach (str_split($character->data['priv_log_level']) as $flag) {
                    $privacy_level['checked'][$flag] = "checked='checked'";
                }
                foreach (str_split($character->data['priv_log_guild']) as $flag) {
                    $privacy_guild['checked'][$flag] = "checked='checked'";
                }
                foreach (str_split($character->data['priv_friends']) as $flag) {
                    $privacy_friendlist['checked'][$flag] = "checked='checked'";
                }

                $handle_friendrequest['checked'][$character->data['handle_friends']] = "checked='checked'";

                $charactersettings = "
                    <a name='settings'></a>
                    <div name='settings'>
                    <form action='".$florensia->escape($florensia->request_uri(array(), 'settings'))."' method='post' enctype='multipart/form-data'>
                        <table class='subtitle small' style='font-weight:normal; width:100%;'>
                            <tr><td colspan='2' style='border-bottom:1px solid;'>
                                <span style='font-weight:bold;'>{$flolang->character_edit_avatar_title}</span><br />
                                {$flolang->character_edit_avatar_desc}
                            </td></tr>
                            <tr>
                                <td style='width:50%;'>
                                    ".$flolang->sprintf($flolang->character_edit_avatar_uploadnotice, 300, 400)."
                                </td>
                                <td>
                                    <input type='file' name='file_avatar'><br />
                                    <input type='checkbox' name='rm_avatar' value='1'> {$flolang->character_edit_avatar_deletecurrent}
                                </td>
                            </tr>
                        </table>
                        
                        <table class='subtitle small' style='font-weight:normal; width:100%;'>
                            <tr><td colspan='2' style='border-bottom:1px solid;'>
                                <span style='font-weight:bold;'>{$flolang->character_edit_privacy_title}</span><br />
                                {$flolang->character_edit_privacy_desc}    
                            </td></tr>
                            <tr>
                                <td style='width:50%;'>
                                    {$flolang->character_edit_privacy_log_guild}
                                </td>
                                <td>
                                    <ul style='list-style-type: none; padding:0px; margin:0px;'>
                                    <li style='padding-bottom:6px;'><input type='checkbox' name='privacy_guild[]' style='float:left;' value='a' {$privacy_guild['checked']['a']}> {$flolang->character_privacy_all}</li>
                                    <li style='padding-bottom:6px; margin-left:15px;'><input type='checkbox' name='privacy_guild[]' style='float:left;' value='r' {$privacy_guild['checked']['r']}> {$flolang->character_privacy_registered}</li>
                                    <li style='padding-bottom:6px; margin-left:30px;'><input type='checkbox' name='privacy_guild[]' style='float:left;' value='g' {$privacy_guild['checked']['g']}> {$flolang->character_privacy_guildmates}</li>
                                    
                                    <li style='padding-bottom:6px; margin-left:30px;'><input type='checkbox' name='privacy_guild[]' style='float:left;' value='b' {$privacy_guild['checked']['b']}> <a href='{$florensia->forumurl}/usercp.php?action=editlists' target='_blank'>{$flolang->character_privacy_buddylist}</a></li>
                                    <li style='margin-left:45px;'><input type='checkbox' name='privacy_guild[]' style='float:left;' value='n' {$privacy_guild['checked']['n']}> {$flolang->character_privacy_nobodybutme}</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {$flolang->character_edit_privacy_log_level}
                                </td>
                                <td>
                                    <ul style='list-style-type: none; padding:0px; margin:0px;'>
                                    <li style='padding-bottom:6px;'><input type='checkbox' name='privacy_level[]' style='float:left;' value='a' {$privacy_level['checked']['a']}> {$flolang->character_privacy_all}</li>
                                    <li style='padding-bottom:6px; margin-left:15px;'><input type='checkbox' name='privacy_level[]' style='float:left;' value='r' {$privacy_level['checked']['r']}> {$flolang->character_privacy_registered}</li>
                                    <li style='padding-bottom:6px; margin-left:30px;'><input type='checkbox' name='privacy_level[]' style='float:left;' value='g' {$privacy_level['checked']['g']}> {$flolang->character_privacy_guildmates}</li>
                                    
                                    <li style='padding-bottom:6px; margin-left:30px;'><input type='checkbox' name='privacy_level[]' style='float:left;' value='b' {$privacy_level['checked']['b']}> <a href='{$florensia->forumurl}/usercp.php?action=editlists' target='_blank'>{$flolang->character_privacy_buddylist}</a></li>
                                    <li style='margin-left:45px;'><input type='checkbox' name='privacy_level[]' style='float:left;' value='n' {$privacy_level['checked']['n']}> {$flolang->character_privacy_nobodybutme}</li>
                                    </ul>
                                </td>
                            </tr>
                        </table>";
                        
                        
                        /*
                         <li style='padding-bottom:6px; margin-left:30px;'><input type='checkbox' name='privacy_guild[]' style='float:left;' value='f' {$privacy_guild['checked']['f']}> {$flolang->character_privacy_friends}</li>
                         
                         <li style='padding-bottom:6px; margin-left:30px;'><input type='checkbox' name='privacy_level[]' style='float:left;' value='f' {$privacy_level['checked']['f']}> {$flolang->character_privacy_friends}</li>
                         
                         
                         
                        <table class='subtitle small' style='font-weight:normal; width:100%;'>
                            <tr><td colspan='2' style='border-bottom:1px solid;'>
                                <span style='font-weight:bold;'>{$flolang->character_edit_friends_title}</span><br />
                                {$flolang->character_edit_friends_desc}
                            </td></tr>
                            <tr>
                                <td style='width:50%;'>
                                    {$flolang->character_edit_friends_requesthandling}
                                </td>
                                <td>
                                <ul style='list-style-type: none; padding:0px; margin:0px;'>
                                    <li style='padding-bottom:4px;'><input type='radio' name='handle_friendrequest' style='float:left;' value='accept' {$handle_friendrequest['checked']['a']}>{$flolang->character_edit_friends_requesthandling_autoaccept}</li>
                                    <li style='padding-bottom:4px;'><input type='radio' name='handle_friendrequest' style='float:left;' value='notice' {$handle_friendrequest['checked']['n']}>{$flolang->character_edit_friends_requesthandling_notice}</li>
                                    <li><input type='radio' name='handle_friendrequest' style='float:left;' value='deny' {$handle_friendrequest['checked']['d']}>{$flolang->character_edit_friends_requesthandling_autodeny}</li>
                                </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {$flolang->character_edit_friends_display}
                                </td>
                                <td>
                                    <ul style='list-style-type: none; padding:0px; margin:0px;'>
                                    <li style='padding-bottom:6px;'><input type='checkbox' name='privacy_friendlist[]' style='float:left;' value='a' {$privacy_friendlist['checked']['a']}> {$flolang->character_privacy_all}</li>
                                    <li style='padding-bottom:6px; margin-left:15px;'><input type='checkbox' name='privacy_friendlist[]' style='float:left;' value='r' {$privacy_friendlist['checked']['r']}> {$flolang->character_privacy_registered}</li>
                                    <li style='padding-bottom:6px; margin-left:30px;'><input type='checkbox' name='privacy_friendlist[]' style='float:left;' value='g' {$privacy_friendlist['checked']['g']}> {$flolang->character_privacy_guildmates}</li>
                                    <li style='padding-bottom:6px; margin-left:30px;'><input type='checkbox' name='privacy_friendlist[]' style='float:left;' value='f' {$privacy_friendlist['checked']['f']}> {$flolang->character_privacy_friends}</li>
                                    <li style='padding-bottom:6px; margin-left:30px;'><input type='checkbox' name='privacy_friendlist[]' style='float:left;' value='b' {$privacy_friendlist['checked']['b']}> <a href='{$florensia->forumurl}/usercp.php?action=editlists' target='_blank'>{$flolang->character_privacy_buddylist}</a></li>
                                    <li style='margin-left:45px;'><input type='checkbox' name='privacy_friendlist[]' style='float:left;' value='n' {$privacy_friendlist['checked']['n']}> {$flolang->character_privacy_nobodybutme}</li>
                                    </ul>
                                </td>
                            </tr>
                        </table> */
                        $charactersettings .= "
                        <div class='subtitle small' style='text-align:right; padding:2px;'>
                            <input type='submit' name='save_settings' value='{$flolang->character_edit_save}'>
                        </div>
                    </form>
                    </div>
                ";
            } //-end owner-settings

            $tabbar = $florensia->tabbar($tabbar);
            
            #should we display our last updatetime?
            if (!$character->is_archiv()) {
                $lastupdate = "
                            <tr>
                                <td>{$flolang->character_title_lastupdate}</td>
                                <td>".$flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$character->data['lastupdate'], "m"))."</td>
                            </tr>
                ";
            }
            else unset($lastupdate);
            
            #character picture
            #if ($character->data['forceupdate']<0 || $character->data['lastupdate']<bcsub(date("U"), 60*60*24*3)) {
            if ($character->data['forceupdate']<0 || $character->data['firsterror']!=0) {
                $avatar_width = 300;
                $avatar_height = 54;
                $character_avatar = "
                  <div class='small subtitle warning' style='font-weight:normal; padding: 3px; float:right; width:{$avatar_width}px; background-image:url({$florensia->layer_rel}/gushiptes.png); background-repeat:no-repeat; background-position:120px 5px;'>
                    <div style='padding-left:5px; padding-top:55px;'>{$flolang->character_notice_bannedorabandoned}</div>
                  </div>";
            }
            elseif ($character->data['avatar_size']) {
                list($avatar_width, $avatar_height) = explode("x", $character->data['avatar_size']);
                $ifile = "pictures/character_avatars/{$character->data['characterid']}";
                $ifile = "{$florensia->root}/{$ifile}?".filectime("{$florensia->root_abs}/{$ifile}");
                $character_avatar = "<div class='small subtitle' style='float:right; width:{$avatar_width}px; height:{$avatar_height}px; background-image:url({$ifile}); background-repeat:no-repeat; background-position:center bottom;'></div>";
            } else {
                $avatar_width = 300;
                $avatar_height = 400;
                $character_avatar = "<div class='small subtitle' style='float:right; width:{$avatar_width}px; height:{$avatar_height}px; background-image:url({$florensia->layer_rel}/placeholder/placeholder_characterphoto_".rand(1,121).".png); background-repeat:no-repeat; background-position:center bottom;'></div>";
            }
                
            $charoverview = "
            <div class='small' style='float:right; font-weight:bold;'>{$verifiednotice}</div>
            {$tabbar['tabbar']}
            {$character_avatar}
            <div style='min-height:{$avatar_height}px; margin-right:".bcadd($avatar_width, 15)."px;'>
                <a name='details'></a>
                <div name='details'>
                        <div class='subtitle small' style='padding:10px; margin-bottom:15px; min-height:160px;'>
                            {$archivimage}
                            {$rankingoverview}
                            <div>
                                <table style='width:100%'>
                                    <tr>
                                        <td style='width:150px'>{$flolang->character_title_charname}</td>
                                        <td>".$character->get_link()." ($gender)</td>
                                    </tr>
                                    <tr>
                                        <td>{$flolang->character_title_landlevel}</td>
                                        <td>".intval($character->data['levelland'])." <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'></td>
                                    </tr>
                                    <tr>
                                        <td>{$flolang->character_title_sealevel}</td>
                                        <td>".intval($character->data['levelsea'])." <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'></td>
                                    </tr>
                                    <tr>
                                        <td>{$flolang->character_title_jobclass}</td>
                                        <td>".$florensia->escape($character->data['jobclass'])."</td>
                                    </tr>
                                    <tr>
                                        <td>{$flolang->character_title_guild}</td>
                                        <td>{$guild}</td>
                                    </tr>
                                    <tr>
                                        <td>{$flolang->character_title_server}</td>
                                        <td><a href='".$florensia->outlink(array('statistics', $florensia->escape($character->data['server'])))."'>".$florensia->escape($character->data['server'])."</a></td>
                                    </tr>
                                    {$lastupdate}
                                </table>
                            </div>
        
                            {$characterforceupdateform}
                        </div>
                        ".$florensia->adsense(0)."
                        {$lastactivity}
                </div>
                {$charactersettings}
            </div>
            {$tabbar['jscript']}";
        }
    /*
            <div class='subtitle' style='padding:10px; margin-bottom:15px; margin-right:315px;'>
                <div style='margin:auto; height:164px; width:279px; background-image:url({$florensia->layer_rel}/character_inv.png); background-position:center; background-repeat:no-repeat;'></div>
            </div>
    */
        $content = "
        <div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/characterdetails'>{$flolang->character_sitetitle}</a> &gt; ".$florensia->escape($character->data['charname'])."</div>
        <div class='subtitle' style='text-align:center; margin-bottom:15px;'>{$flolang->character_jumpto} ".$florensia->quicksearch()."</div>
        {$charoverview}
        ";
        
        $florensia->sitetitle("Characterdetails");
        $florensia->sitetitle($florensia->escape($character->data['charname']));
        $florensia->output_page($content);
    } else {
        foreach($getcharacter as $charname) {
            if ($charname=="0") continue; #ignore placeholder
            
            $char = new class_character($charname);
            
            if (!$char->is_valid()) {
                $errorcharnotice = $char->get_errormsg();
                $char->data['jobclass'] = $guild = $server = $char->data['levelsea'] = $char->data['levelland'] = $char->data['updatepriority'] = "-";
                $char->data['lastupdate'] = date("U");
                $charname = $florensia->escape($charname) . "<br /><span style='color:#FF0000;'>(</span>{$errorcharnotice}<span style='color:#FF0000;'>)</span>";
            } else {
                if ($char->data['gender']=="m") $gender = "<img src='{$florensia->layer_rel}/gender_male.gif' border='0' alt='male' style='height:12px;'>";
                else  $gender = "<img src='{$florensia->layer_rel}/gender_female.gif' border='0' alt='female' style='height:12px;'>";
            
                $charname = "$gender <a href='".$florensia->outlink(array("characterdetails", $char->data['charname']))."'>".$florensia->escape($char->data['charname'])."</a>";
                $server = "<a href='{$florensia->root}/statistics/".$florensia->escape($char->data['server'])."'>".$florensia->escape($char->data['server'])."</a>";
                if ($char->data['guildid']) $guild = "<a href='".$florensia->outlink(array("guilddetails", $char->data['guildid'], $char->data['server'], $char->data['guild']))."'>".$florensia->escape($char->data['guild'])."</a>";
                elseif ($char->data['guild']) $guild = $florensia->escape($char->data['guild']);
                else unset($guild);
                
                if ($guild && $char->data['guildgrade']) $guild .= " ".class_character::guildgrade($char->data['guildgrade']);
            }

            $charlist .= "
                <div class='small shortinfo_".$florensia->change()."'>
                    <table style='width:100%'><tr>
                        <td style='width:50px; text-align:right;'>{$char->data['levelland']} <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'></td>
                        <td style='width:50px; padding-right:10px; text-align:right;'>{$char->data['levelsea']} <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'></td>
                        <td>{$charname}</td>
                        <td style='width:170px'>".$florensia->escape($char->data['jobclass'])."</td>
                        <td style='width:150px'>{$guild}</td>
                        <td style='width:100px'>{$server}</td>
                        <td style='text-align:right; padding-right:3px; width:130px'>".$flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$char->data['lastupdate'], "m"))."</td>
                    </tr></table>
                </div>
            ";
        }
        
        $content = "
            <div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/characterdetails'>{$flolang->character_sitetitle}</a></div>
            <div class='small' style='font-weight:bold; margin-bottom:10px; text-align:center;'>{$flolang->character_overview_selectchar}</div>
            <div class='subtitle' style='margin-bottom:7px;'>
                    <table style='width:100%'><tr>
                            <td style='width:50px; text-align:right;'><img src='{$florensia->layer_rel}/land.gif' style='height:13px;' alt='Land'></td>
                            <td style='width:50px; padding-right:10px; text-align:right;'><img src='{$florensia->layer_rel}/sealv.gif' style='height:13px;' alt='Sea'></td>
                            <td>{$flolang->character_title_charname}</td>
                            <td style='width:170px'>{$flolang->character_title_jobclass}</td>
                            <td style='width:150px'>{$flolang->character_title_guild}</td>
                            <td style='width:100px'>{$flolang->character_title_server}</td>
                            <td style='text-align:right; padding-right:3px; width:130px'>{$flolang->character_title_lastupdate}</td>
                    </tr></table>
            </div>
            {$charlist}
            
            <div class='subtitle' style='text-align:center; margin-top:20px;'>{$flolang->character_jumpto} ".$florensia->quicksearch()."</div>
        ";
        $florensia->sitetitle("Characterdetails");
        $florensia->output_page($content);
    }
} else {
    if (strlen($_GET['notfound']) OR strlen($_GET['search'])) {
        if ($_GET['notfound']) {
            $_GET['search'] = $_GET['notfound'];
            $notfoundnotice = "
                  <div class='small subtitle warning' style='font-weight:normal; padding: 3px; background-image:url({$florensia->layer_rel}/gushiptes.png); background-repeat:no-repeat; background-position:780px 5px;'>
                    <div style='padding-left:5px; padding-top:5px;'>".$flolang->sprintf($flolang->character_api_notfound_long, $florensia->escape($_GET['notfound']))."</div>
                  </div>";
        }
        $cachelimit = 99;
        $querycharsearch = MYSQL_QUERY("SELECT charname, server, guild, guildid FROM flobase_character_data as d, flobase_character as c WHERE c.characterid=d.characterid AND charname LIKE '".get_searchstring($_GET['search'],0)."%' ORDER BY charname LIMIT {$cachelimit}");
        for ($i=0; $charsearch = MYSQL_FETCH_ASSOC($querycharsearch); $i++) {
            if ($charsearch['guildid']) $charsearch['guild'] = ", <a href='".$florensia->outlink(array('guilddetails', $charsearch['guildid'], $charsearch['server'], $charsearch['guild']))."'>".$florensia->escape($charsearch['guild'])."</a>";
            elseif ($charsearch['guild']) $charsearch['guild'] = ", ".$florensia->escape($charsearch['guild']);
            $cachedlist[$i%3] .= "<a href='".$florensia->outlink(array('characterdetails', $charsearch['charname']))."'>".$florensia->escape($charsearch['charname'])."</a>, ".$florensia->escape($charsearch['server'])."{$charsearch['guild']}<br />";
        }
        
        if ($cachedlist) {
            if ($_GET['notfound']) $notfoundnotice .= "<div style='margin-top:10px;'>".$flolang->sprintf($flolang->character_api_notfound_cachedline, $cachelimit)."</div>";
            $cachedlist = "
            <div>
                <table style='width:100%; margin-top:5px; font-weight:normal;' class='subtitle'>
                <tr><td style='width:33%;'>{$cachedlist[0]}</td><td style='width:33%;'>{$cachedlist[1]}</td><td>{$cachedlist[2]}</td></tr>
                </table>
            </div>";
        }
        $searched = "<div class='borderd small' style='margin-top:15px;'>
            {$notfoundnotice}
            {$cachedlist}
        </div>";
    } else {
	    $recentupdates = array();
            $tmptime = date("U");
            $queryrecent = MYSQL_QUERY("(SELECT c.charname, l.level, l.prelevel, l.timestamp, l.pretimestamp, d.guild, d.guildid, 'land' as type FROM flobase_character_log_level_land as l, flobase_character as c, flobase_character_data as d WHERE l.characterid=c.characterid AND l.characterid=d.characterid AND l.timestamp>'".bcsub(date("U"), 60*60*24)."' AND l.pretimestamp!='0' AND (d.priv_log_level='a' OR d.priv_log_level='') ORDER BY l.timestamp DESC LIMIT 20)
                                       UNION
                                       (SELECT c.charname, l.level, l.prelevel, l.timestamp, l.pretimestamp, d.guild, d.guildid, 'sea' as type FROM flobase_character_log_level_sea as l, flobase_character as c, flobase_character_data as d WHERE l.characterid=c.characterid AND l.characterid=d.characterid AND l.timestamp>'".bcsub(date("U"), 60*60*24)."' AND l.pretimestamp!='0' AND (d.priv_log_level='a' OR d.priv_log_level='') ORDER BY l.timestamp DESC LIMIT 20)");
            while ($recent = MYSQL_FETCH_ARRAY($queryrecent)) {
                if ($recent['type']=="land") {
                    $levelsymbol = "<img src='{$florensia->layer_rel}/land.gif' alt='Land' style='height:11px;'>";
                } else {
                    $levelsymbol = "<img src='{$florensia->layer_rel}/sealv.gif' alt='Sea' style='height:11px;'>";
                }
                    
                $charname = "<a href='".$florensia->outlink(array("characterdetails", $recent['charname']))."'>".$florensia->escape($recent['charname'])."</a>";
                
                if ($recent['guildid']) $charname .= " (<a href='".$florensia->outlink(array("guilddetails", $recent['guildid'], $recent['guild']))."'>".$florensia->escape($recent['guild'])."</a>)";
                elseif ($recent['guild']) $charname .= " (".$florensia->escape($recent['guild']).")";
                
                $recentupdates[$recent['timestamp']][] = $flolang->sprintf($flolang->guild_recentupdates_levelup, $charname, " +".bcsub($recent['level'], $recent['prelevel'])." ({$recent['level']}) {$levelsymbol} ".$flolang->sprintf($flolang->guild_recentupdates_levelup_timespan, timetamp2string(bcsub($recent['timestamp'],$recent['pretimestamp']), "d")));
            }
        krsort($recentupdates);
        foreach ($recentupdates as $time => $values) {
            $recentupdateslist .= "<tr><td style='width:110px;'>".$flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$time))."</td><td>".join("<br />", $values)."</td></tr>";
        }
        $recentupdates = "
            <div class='small subtitle' style='margin-top:15px;'>
                {$flolang->character_overview_recentupdates}
                <table style='width:100%; font-weight:normal;'>
                    {$recentupdateslist}
                </table>
            </div>";
    }
    
    $content = "
        <div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/characterdetails'>{$flolang->character_sitetitle}</a></div>
        <div class='subtitle' style='text-align:center;'>{$flolang->character_jumpto} ".$florensia->quicksearch()."</div>
        {$searched}
        {$recentupdates}
    ";
    
    //$florensia->notice("<div class='warning' style='margin-bottom:10px;'>{$flolang->signature_api_failnotice}</div>");
    $florensia->sitetitle("Characterdetails");
    $florensia->sitetitle("Search");
    $florensia->output_page($content);
}

?>
