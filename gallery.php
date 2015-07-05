<?php
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$florensia->sitetitle("Gallery");
$flolang->load("gallery,signature,character");

if (isset($_GET['upload']) OR $_GET['edit']) {
    if (isset($_GET['upload']) && !$flouser->get_permission("gallery", "upload")) { $florensia->output_page($flouser->noaccess()); }

if ($_POST['do_it'] OR $_POST['do_delete']) {
    $link = array('character'=>array(), 'guild'=>array(), 'user'=>array());
    unset($server);

    if ($_POST['pid']) {
        //load image
        $image = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM flobase_gallery WHERE galleryid='".intval($_POST['pid'])."' LIMIT 1"));
        if (!$image) {
            $florensia->notice($flolang->gallery_error_nosuchid, "warning");
            $florensia->output_page("");
        }
    }
    
    //image delete
    if ($_POST['check_delete'] && $_POST['do_delete'] && ($image['userid']==$flouser->userid OR $flouser->get_permission("gallery", "moderate"))) {
        //reset all character-links
        MYSQL_QUERY("DELETE FROM flobase_character_gallery WHERE galleryid='{$image['galleryid']}'");
        //reset all guild-links
        MYSQL_QUERY("DELETE FROM flobase_guild_gallery WHERE galleryid='{$image['galleryid']}'");
        //delete usernotes
        MYSQL_QUERY("DELETE FROM flobase_usernotes WHERE section='gallery' AND sectionid='{$image['galleryid']}'");        
        
        //delete files                
        @unlink("{$florensia->root_abs}/pictures/gallery/{$image['galleryid']}_thumb");
        @unlink("{$florensia->root_abs}/pictures/gallery/{$image['galleryid']}");
        
        
        if (MYSQL_QUERY("DELETE FROM flobase_gallery WHERE galleryid='{$image['galleryid']}'")) {
            $flolog->add("gallery:delete", "{user:{$flouser->userid}} deleted image {$image['name']} uploaded by {user:{$image['userid']}} on {timestamp:{$image['timestamp']}}. (Flags:{$image['pflags']}, Voting:{$image['thumpsup']}/{$image['thumpsdown']})");
            $florensia->notice($flolang->gallery_delete_successful." <a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_backtogallery}</a>.", "successful");
            $florensia->output_page("");
        } else {
            $florensia->notice($flolang->gallery_delete_error, "warning");
            $florensia->output_page("");
        }
    }
    
    
    //working on image name
    $_POST['imagename'] = trim($_POST['imagename']);
    
    //working on tags
    $imagetags = array();
    foreach (explode(" ", $_POST['imagetags']) as $tag) {
        if (preg_match("/^[-a-z0-9_]{3,}$/i", $tag)) $imagetags[] = strtolower($tag);
    }
    
    //get characterlinkedlist
    $i=-1;
    for ($start['characterlist']=0; true; $start['characterlist']++) {
        $i++;
        if (!isset($_POST['characterlist_'.$i])) {
            break;
        } elseif (!strlen($_POST['characterlist_'.$i])) {
            $start['characterlist']--;
            continue;
        }
        
        unset($errorcharnotice);
        #check if it's an archiv character
        preg_match("/^A:([1-9][0-9]*)$/", $_POST['characterlist_'.$i], $archiv);
        if (intval($archiv[1])) $character = new class_character(intval($archiv[1]), array('archivid'=>true));
        else $character = new class_character($_POST['characterlist_'.$i]);
	if (!$character->is_valid()) {
		$errorcharnotice = $character->get_errormsg();
        }
        else { //character was found!
            //save server if this is the first time
            if (!$server OR $server==$character->data['server']) {
                $server = $character->data['server'];
                $link['character'][] = $character->data['characterid'];
            }
            //if not, check if character is on the same server
            elseif ($server!=$character->data['server']) {
                $errorcharnotice = "{$flolang->gallery_edit_linkedchar_error_server} (".$florensia->escape($server)."/".$florensia->escape($character->data['server']).")";
            }
        }
        if ($errorcharnotice) $errorcharnotice = "<span class='small' style='font-weight:normal;'><span style='color:#FF0000;'>(</span>{$errorcharnotice}<span style='color:#FF0000;'>)</span></span>";
        $input['characterlist'] .= "<input type='text' name='characterlist_{$start['characterlist']}' value='".$florensia->escape($_POST['characterlist_'.$i])."' style='width:130px;' maxlength='13'> {$errorcharnotice}<br />";
    }

   // if (!$server) $florensia->notice($flolang->gallery_edit_error_invalidchars, "warning");

    //get our guildlist
    $i=-1;
    for ($start['guildlist']=0; true; $start['guildlist']++) {
        $i++;
        if (!isset($_POST['guildlist_'.$i])) {
            break;
        } elseif (!strlen($_POST['guildlist_'.$i])) {
            $start['guildlist']--;
            continue;
        }
        unset($errorcharnotice);
        
        if ($server) $dbguildserver = "AND server='".mysql_real_escape_string($server)."'";
        else unset($dbguildserver);
        
        preg_match("/^A:([1-9][0-9]*)$/", $_POST['guildlist_'.$i], $archiv);
        if (intval($archiv[1])) $queryguild = "SELECT guildid, server FROM flobase_guild WHERE guildid='".intval($archiv[1])."' {$dbguildserver}";
        else $queryguild = "SELECT guildid, server FROM flobase_guild WHERE guildname='".mysql_real_escape_string($_POST['guildlist_'.$i])."' AND memberamount!='0' {$dbguildserver}";

        $queryguild = MYSQL_FETCH_ARRAY(MYSQL_QUERY($queryguild));
        if (!$queryguild) $errorcharnotice = $flolang->gallery_edit_linkedguild_error;
        else {
            if (!$server) $server = $queryguild['server'];
            $link['guild'][] = $queryguild['guildid'];
        }
	if ($errorcharnotice) $errorcharnotice = "<span class='small' style='font-weight:normal;'><span style='color:#FF0000;'>(</span>{$errorcharnotice}<span style='color:#FF0000;'>)</span></span>";
        
        $input['guildlist'] .= "<input type='text' name='guildlist_{$start['guildlist']}' value='".$florensia->escape($_POST['guildlist_'.$i])."' style='width:130px;' maxlength='13'> {$errorcharnotice}<br />";
    }
    
    
    //privacy
    if ($_POST['publiccommenting']) {
        $privacy['checked']['comment'] = "checked='checked'";
        $commenting = 1;
    } else $commenting = 0;
    if ($_POST['publicvoting']) {
        $privacy['checked']['vote'] = "checked='checked'";
        $voting = 1;
    } else $voting = 0;
    if ($_POST['publicediting']) {
        $privacy['checked']['edit'] = "checked='checked'";
        $editing = 1;
    } else $editing = 0;
    
    //userlist if available
    for ($start['userlist']=0; true; $start['userlist']++) {
        if (!$_POST['userlist_'.$start['userlist']]) {
            break;
        } elseif (!strlen($_POST['userlist_'.$start['userlist']])) {
            $start['userlist']--;
            continue;
        }
        unset($errorcharnotice);

        $queryuser = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT uid FROM forum_users WHERE username='".mysql_real_escape_string($_POST['userlist_'.$start['userlist']])."'"));
        if (!$queryuser) $errorcharnotice = $flolang->gallery_edit_linkedchar_error_notfound;
        elseif ($queryuser['uid']!=$flouser->userid) {
            $link['user'][] = $queryuser['uid'];
        }
	if ($errorcharnotice) $errorcharnotice = "<span class='small' style='font-weight:normal;'><span style='color:#FF0000;'>(</span>{$errorcharnotice}<span style='color:#FF0000;'>)</span></span>";
        $input['userlist'] .= "<input type='text' name='userlist_{$start['userlist']}' value='".$florensia->escape($_POST['userlist_'.$start['userlist']])."' style='width:130px; margin-left:60px;'> {$errorcharnotice}<br />";
    }
    
    if (preg_match("/^[rcgbu]+$/", join("", $_POST['privacy']))) {
        //we got some special privacy
        $privacy['pflags'] = join("", $_POST['privacy']);
        if (in_array("u", $_POST['privacy'])) {
            //userlist
            $privacy['pusers'] = join(",", $link['user']);
        }
        foreach($_POST['privacy'] as $flag) {
            //set prechecked
            $privacy['checked'][$flag] = "checked='checked'";
        }
    } else {
        //defaults (all users can see that image)
        $privacy['pflags'] = "a";
        $privacy['pusers'] = "";
        $privacy['checked']['a'] = "checked='checked'";
    }
    
    //new image or update?
    if (!$_POST['pid']) {//new image
        $privacyform = true;
        $uploaderror = false;
        
        //check if any file was uploaded
        if (!$_FILES['imagefile']['tmp_name']) {
            $florensia->notice($flolang->gallery_upload_error_nofile, "warning");
            $uploaderror = true;
        } else {
            $image = $_FILES['imagefile']['tmp_name'];            
            list($width, $height, $type, $attr) = getimagesize($image);
            //check, if image is one of our accepted mime-typed and has correct size
            if ($height<=0 OR $width<=0 OR !in_array($type, array(
                                       1, //'IMAGETYPE_GIF'
                                       2, //'IMAGETYPE_JPEG'
                                       3 //'IMAGETYPE_PNG'
                                ))) {
                $florensia->notice($flolang->gallery_upload_error_invalidfile, "warning");
                $uploaderror = true;
            } else {
                //check, if the same image already exists in our database by using sha2-256 hash
                $imagefilehash = hash_file("sha256", $image);
                
                $imageclone = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT galleryid, name, userid, timestamp FROM flobase_gallery WHERE filehash='{$imagefilehash}'"));
                if ($imageclone) {
                    $florensia->notice($flolang->sprintf($flolang->gallery_upload_error_duplicate, "<a href='".$florensia->outlink(array("gallery", "i", $imageclone['galleryid'], $imageclone['name']))."'>".$florensia->escape($imageclone['name'])."</a>", $flouserdata->get_username($imageclone['userid']), date("m.d.y", $imageclone['timestamp'])), "warning");
                    $uploaderror = true;
                }
            }
        }
            //elseif ($server) {
        if ($uploaderror) {
                $florensia->sitetitle("Upload");
                @unlink($_FILES['imagefile']['tmp_name']);
                $input['imagefile'] = "<input type='file' name='imagefile'>";
        } else {
                $deleteform = true;
                //look up the width/height of the thumbnail
                $theight = $height;
                $twidth = $width;
                if ($theight>200) { 
                    $twidth = ceil($twidth*(200/$theight));
                    $theight = 200;
                }
                if ($twidth>200) { 
                    $theight = ceil($theight*(200/$twidth));
                    $twidth = 200;
                }
                    
                //all things gone right.. at least one char is valid, we got all permissions, size and so on.. make thumbnail, save and redirect to edit page.
                if (MYSQL_QUERY("INSERT INTO flobase_gallery (name, userid, timestamp, imagetype, filehash, width, height, twidth, theight, filesize, voting, commenting, editing, server, tags, pflags, pusers) VALUES(
                              '".mysql_real_escape_string($_POST['imagename'])."',
                              ".$flouser->userid.",
                              ".date("U").",
                              {$type},
                              '{$imagefilehash}',
                              {$width},
                              {$height},
                              {$twidth},
                              {$theight},
                              ".filesize($_FILES['imagefile']['tmp_name']).",
                              '{$voting}',
                              '{$commenting}',
                              '{$editing}',
                              '".mysql_real_escape_string($server)."',
                              '".mysql_real_escape_string(join(" ", $imagetags))."',
                              '{$privacy['pflags']}',
                              '{$privacy['pusers']}'                          
                              )")) {
                    //saving successfully
                    $insertedid = mysql_insert_id();
                    
                    //saveing character-links
                    if (count($link['character'])) {
                        foreach($link['character'] as &$clink) {
                            $clink = "('{$insertedid}', '{$clink}')";
                        }
                        if (!MYSQL_QUERY("INSERT INTO flobase_character_gallery (galleryid, characterid) VALUES ".join(", ", $link['character']))) {
                            $flolog->add("error:gallery", "MySQL-Insert-Error while trying to add characterlinks ({$_POST['imagename']} [{$insertedid}]).");
                        }
                    }
                    //saveing guild-links
                    if (count($link['guild'])) {
                        foreach($link['guild'] as &$clink) {
                            $clink = "('{$insertedid}', '{$clink}')";
                        }
                        if (!MYSQL_QUERY("INSERT INTO flobase_guild_gallery (galleryid, guildid) VALUES ".join(", ", $link['guild']))) {
                            $flolog->add("error:gallery", "MySQL-Insert-Error while trying to add guildlinks ({$_POST['imagename']} [{$insertedid}]).");
                        }
                    }
                    
                    //copy image to folder
                    @rename($_FILES['imagefile']['tmp_name'], "{$florensia->root_abs}/pictures/gallery/{$insertedid}");
                    
                    if ($height!=$theight OR $width!=$twidth) {
                        //need to create thumbnail
                        switch($type) {
                            case 1: {
                                $source = @imagecreatefromgif("{$florensia->root_abs}/pictures/gallery/{$insertedid}");
                                break;
                            }
                            case 2: {
                                $source = @imagecreatefromjpeg("{$florensia->root_abs}/pictures/gallery/{$insertedid}");
                                break;
                            }
                            case 3: {
                                $source = @imagecreatefrompng("{$florensia->root_abs}/pictures/gallery/{$insertedid}");
                                break;
                            }
                        }
                        
                        $thumbnail = @imagecreatetruecolor($twidth,$theight);
                        @imagecopyresized($thumbnail,$source,0,0,0,0,$twidth, $theight,$width,$height);
                        @imagesavealpha($thumbnail, TRUE);
                        @imagepng($thumbnail, "{$florensia->root_abs}/pictures/gallery/{$insertedid}_thumb");
                        @imagedestroy($thumbnail);
                        @imagedestroy($source);
                    } else {
                        //woah.. really small image, isn't it? save it also as thumbnail
                        @copy("{$florensia->root_abs}/pictures/gallery/{$insertedid}", "{$florensia->root_abs}/pictures/gallery/{$insertedid}_thumb");
                    }
                    
                    $flolog->add("gallery:add", "{user:{$flouser->userid}} added {gallery:{$insertedid}} ({$_POST['imagename']} Flags:{$privacy['pflags']}, V:{$voting},C:{$commenting},E:{$editing})");
                    $florensia->notice($flolang->gallery_upload_successful, "successful");
                    $florensia->sitetitle("Edit");
                    $florensia->sitetitle($florensia->escape($_POST['imagename']));
                    $input['imagefile'] = "<div class='bordered' style='height:{$theight}px; width:{$twidth}px;'><a href='".$florensia->outlink(array("gallery", "i", $insertedid, $_POST['imagename']))."'><img src='{$florensia->root}/pictures/gallery/{$insertedid}_thumb' style='border:0px;'></a></div>";
                    $input['submit'] = "<input type='hidden' name='pid' value='{$insertedid}'><input type='submit' name='do_it' value='{$flolang->gallery_edit_submit}'> - <a href='".$florensia->outlink(array("gallery", "i", $insertedid, $_POST['imagename']))."'>{$flolang->gallery_edit_finish}</a>";
                    
                    
                } else {
                    $flolog->add("error:gallery", "MySQL-Insert-Error while trying to add an image ({$_POST['imagename']} Flags:{$privacy['pflags']}, V:{$voting},C:{$commenting},E:{$editing}).");
                    $florensia->notice($flolang->gallery_upload_error, "warning");
                    $florensia->sitetitle("Upload");
                }
        }/* else {
                $input['imagefile'] = "<input type='file' name='imagefile'>";
                $florensia->sitetitle("Upload");
        }*/
    } else {//updating
        $florensia->sitetitle("Edit");
        $florensia->sitetitle($florensia->escape($image['name']));
    
    //    if (count($link['character'])) {
            $dbupdate=array();
            $changes = array();
            //permission to edit linked lists, keywords?
            if ($image['userid']==$flouser->userid OR ($image['editing'] && $flouser->userid) OR $flouser->get_permission("gallery", "moderate")) {
                #update characters
                    #always purge old entries.. we readd them, if needed
                    MYSQL_QUERY("DELETE FROM flobase_character_gallery WHERE galleryid='{$image['galleryid']}'");
                    $preload = mysql_affected_rows();
                    if (count($link['character'])) {
                        foreach($link['character'] as &$clink) {
                            $clink = "('{$image['galleryid']}', '{$clink}')";
                        }
                        if (!MYSQL_QUERY("INSERT INTO flobase_character_gallery (galleryid, characterid) VALUES ".join(", ", $link['character']))) {
                            $flolog->add("error:gallery", "MySQL-Insert-Error while trying to update characterlinks ({gallery:{$image['galleryid']}}).");
                        } elseif ($preload!=mysql_affected_rows()) {
                            $changes[] = "characterlinkedlist";
                        }
                    }
                #update guilds
                    #always purge old entries.. we readd them, if needed
                    MYSQL_QUERY("DELETE FROM flobase_guild_gallery WHERE galleryid='{$image['galleryid']}'");
                    $preload = mysql_affected_rows();
                    if (count($link['guild'])) {
                        foreach($link['guild'] as &$clink) {
                            $clink = "('{$image['galleryid']}', '{$clink}')";
                        }
                        if (!MYSQL_QUERY("INSERT INTO flobase_guild_gallery (galleryid, guildid) VALUES ".join(", ", $link['guild']))) {
                            $flolog->add("error:gallery", "MySQL-Insert-Error while trying to update guildlinks ({gallery:{$image['galleryid']}}).");
                        } elseif ($preload!=mysql_affected_rows()) {
                            $changes[] = "guildlinkedlist";
                        }
                    }
                $dbupdate[] = "tags='".mysql_real_escape_string(join(" ", $imagetags))."'";
                if ($image['tags']!=join(" ", $imagetags)) $changes[] = "tags";
                $dbupdate[] = "server='".mysql_real_escape_string($server)."'";
                if ($image['server']!=$server) $changes[] = "server";
            }
            
            //creator - permission to edit name, privacy
            if ($image['userid']==$flouser->userid OR $flouser->get_permission("gallery", "moderate")) {
                $privacyform = true;
                $deleteform = true;
                $dbupdate[] = "name='".mysql_real_escape_string($_POST['imagename'])."'";
                if ($image['name']!=$_POST['imagename']) $changes[] = "name";
                $dbupdate[] = "pflags='{$privacy['pflags']}'";
                if ($image['pflags']!=$privacy['pflags']) $changes[] = "pflags";
                $dbupdate[] = "pusers='{$privacy['pusers']}'";
                if ($image['pusers']!=$privacy['pusers']) $changes[] = "pusers";
                $dbupdate[] = "voting='{$voting}'";
                if ($image['voting']!=$voting) $changes[] = "voting";
                $dbupdate[] = "commenting='{$commenting}'";
                if ($image['commenting']!=$commenting) $changes[] = "commenting";
                $dbupdate[] = "editing='{$editing}'";
                if ($image['editing']!=$editing) $changes[] = "editing";
            }
            
            if (count($dbupdate)==0 || MYSQL_QUERY("UPDATE flobase_gallery SET ".join(", ", $dbupdate)." WHERE galleryid='{$image['galleryid']}'")) {  
                $florensia->notice($flolang->gallery_edit_successful, "successful");
                if (count($changes)) $flolog->add("gallery:edit", "{user:{$flouser->userid}} edited {gallery:{$image['galleryid']}} uploaded by {user:{$image['userid']}} and made ".count($changes)." changes. (".join(", ", $changes).")");
            } elseif (count($dbupdate)>0) {
                $flolog->add("error:gallery", "MySQL-Update-Error while trying to edit {gallery:{$image['galleryid']}} with ".count($changes)." changes. (".join(", ", $changes).")");
                $florensia->notice($flolang->gallery_edit_error, "warning"); 
            }
      //  }
        $input['imagefile'] = "<div class='bordered' style='height:{$image['theight']}px; width:{$image['twidth']}px;'><a href='".$florensia->outlink(array("gallery", "i", $image['galleryid'], $_POST['imagename']))."'><img src='{$florensia->root}/pictures/gallery/{$image['galleryid']}_thumb' style='border:0px;'></a></div>";
        $input['submit'] = "<input type='hidden' name='pid' value='{$image['galleryid']}'><input type='submit' name='do_it' value='{$flolang->gallery_edit_submit}'> - <a href='".$florensia->outlink(array("gallery", "i", $image['galleryid'], $_POST['imagename']))."'>{$flolang->gallery_edit_finish}</a>";
    }
    
    
} elseif ($_GET['edit']) {
    //load image
    $image = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM flobase_gallery WHERE galleryid='".intval($_GET['edit'])."' LIMIT 1"));
    if ($image) { $privacyaccess = $classgallery->check_privacy($image); }
    if (!$image) {
        $error = $flolang->gallery_error_nosuchid;
    } elseif (!($image['userid']==$flouser->userid OR ($image['editing'] && $flouser->userid) OR $flouser->get_permission("gallery", "moderate"))) {
        $error = $flolang->gallery_edit_error_nopermission;
    }
    if ($error) {
            $content = "<div class='subtitle' style='margin-bottom:10px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a></div>
            <div class='warning small' style='text-align:center;'>
                {$error}
            </div>";
            $florensia->output_page($content); 
    }
    
    $florensia->sitetitle("Edit");
    $florensia->sitetitle($florensia->escape($image['name']));
    
    //creator?
    if ($flouser->userid==$image['userid'] OR $flouser->get_permission("gallery", "moderate")) {
        $privacyform = true;
        $deleteform = true;
    }
    
    $input['imagefile'] = "<div class='bordered' style='height:{$image['theight']}px; width:{$image['twidth']}px;'><a href='".$florensia->outlink(array("gallery", "i", $image['galleryid'], $image['name']))."'><img src='{$florensia->root}/pictures/gallery/{$image['galleryid']}_thumb' style='border:0px;'></a></div>";
    $_POST['imagename'] = $image['name'];
    $imagetags = explode(" ", $image['tags']);
    
    $start['characterlist']=0;
    $querylinkedchar = MYSQL_QUERY("SELECT g.characterid, charname from flobase_character_gallery as g LEFT JOIN flobase_character as c ON (c.characterid=g.characterid) WHERE galleryid='{$image['galleryid']}'");
    while ($linkedchar = MYSQL_FETCH_ARRAY($querylinkedchar)) {
        if (is_null($linkedchar['charname'])) $linkedchar['charname'] = "A:{$linkedchar['characterid']}";
        $input['characterlist'] .= "<input type='text' name='characterlist_{$start['characterlist']}' value='".$florensia->escape($linkedchar['charname'])."' style='width:130px;' maxlength='13'><br />";
        $start['characterlist']++;
    }
    
    $start['guildlist']=0;
    $querylinkedguild = MYSQL_QUERY("SELECT g.guildid, guildname, memberamount FROM flobase_guild_gallery as g, flobase_guild as c WHERE galleryid='{$image['galleryid']}' AND c.guildid=g.guildid");
    while ($linkedguild = MYSQL_FETCH_ARRAY($querylinkedguild)) {
        if (!$linkedguild['memberamount']) $linkedguild['guildname'] = "A:{$linkedguild['guildid']}";
        $input['guildlist'] .= "<input type='text' name='guildlist_{$start['guildlist']}' value='".$florensia->escape($linkedguild['guildname'])."' style='width:130px;' maxlength='13'><br />";
        $start['guildlist']++;
    }
    
    $start['userlist']=0;
    if (strlen($image['pusers'])) {
        foreach (explode(",", $image['pusers']) as $user) {
            $user = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT username FROM forum_users WHERE uid='{$user}'"));
            $input['userlist'] .= "<input type='text' name='userlist_{$start['userlist']}' value='".$florensia->escape($user['username'])."' style='width:130px; margin-left:60px;'><br />";
            $start['userlist']++;
        }
    }

    foreach(str_split($image['pflags']) as $flag) {
        $privacy['checked'][$flag] = "checked='checked'";
    }

    if ($image['voting']) $privacy['checked']['vote'] = "checked='checked'";
    if ($image['commenting']) $privacy['checked']['comment'] = "checked='checked'";
    if ($image['editing']) $privacy['checked']['edit'] = "checked='checked'";

    $input['submit'] = "<input type='hidden' name='pid' value='{$image['galleryid']}'><input type='submit' name='do_it' value='{$flolang->gallery_edit_submit}'> - <a href='".$florensia->outlink(array("gallery", "i", $image['galleryid'], $image['name']))."'>{$flolang->gallery_edit_finish}</a>";

} else {
    //defaults
    $florensia->sitetitle("Upload");
    unset($charname, $guild);
    if ($_GET['character']) {
        if (intval($_GET['archivid'])) {
            $character = new class_character(intval($_GET['archivid']));
            $charname = "A:".$_GET['archivid'];
            if ($character->data['guildid']) {
                $guild = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT guildname, memberamount FROM flobase_guild WHERE guildid='".intval($character->data['guildid'])."'"));
                if ($guild['memberamount']) $guild = $guild['guildname'];
                else $guild = "A:".intval($_GET['guild']);
            }
        }
        else {
            $character = new class_character($_GET['character']);
            $charname = $character->data['charname'];
            $guild = $character->data['guild'];
        }
    } elseif ($_GET['guild']) {
        $guild = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT guildname, memberamount FROM flobase_guild WHERE guildid='".intval($_GET['guild'])."'"));
        if ($guild['memberamount']) $guild = $guild['guildname'];
        else $guild = "A:".intval($_GET['guild']);
    }
    
    $input['characterlist'] = "<input type='text' name='characterlist_0' value='".$florensia->escape($charname)."' style='width:130px;' maxlength='13'>";
    $input['guildlist'] = "<input type='text' name='guildlist_0' value='".$florensia->escape($guild)."' style='width:130px;' maxlength='13'>";
    $input['userlist'] = "<input type='text' name='userlist_0' style='width:130px; margin-left:60px;' maxlength='13'>";
    $start['characterlist'] = 1;
    $start['guildlist'] = 1;
    $start['userlist'] = 1;
    $privacy['checked']['a'] = "checked='checked'";
    $privacy['checked']['vote'] = "checked='checked'";
    $privacy['checked']['comment'] = "checked='checked'";
    $privacy['checked']['edit'] = "";
    $imagetags = array();
    $privacyform = true;
}

if (!$input['submit']) $input['submit'] = "<input type='submit' name='do_it' value='{$flolang->gallery_upload_submit}'>";
if (!$input['imagefile']) $input['imagefile'] = "<input type='file' name='imagefile'>";

if ($privacyform) {
    $privacyform = "
        <tr>
            <td style='border-bottom:1px solid;'>{$flolang->gallery_edit_title_privacy}<br />
                <span class='small' style='font-weight:normal;'>{$flolang->gallery_edit_title_privacy_desc}</span>
            </td>
            <td class='small' style='font-weight:normal; border-bottom:1px solid;'>
                <input type='checkbox' name='privacy[]' value='a' {$privacy['checked']['a']}> {$flolang->gallery_edit_privacy_alluser}<br />
                <input type='checkbox' name='privacy[]' value='r' {$privacy['checked']['r']} style='margin-left:20px;'> {$flolang->gallery_edit_privacy_registereduser}<br />
                <input type='checkbox' name='privacy[]' value='c' {$privacy['checked']['c']} style='margin-left:40px;'> {$flolang->gallery_edit_privacy_linkedcharacter}<br />
                <input type='checkbox' name='privacy[]' value='g' {$privacy['checked']['g']} style='margin-left:40px;'> {$flolang->gallery_edit_privacy_linkedguild}<br />
                <input type='checkbox' name='privacy[]' value='b' {$privacy['checked']['b']} style='margin-left:40px;'> <a href='{$florensia->forumurl}/usercp.php?action=editlists' target='_blank'>{$flolang->gallery_edit_privacy_buddylist}</a><br />
                <input type='checkbox' name='privacy[]' value='u' {$privacy['checked']['u']} style='margin-left:40px;'> {$flolang->gallery_edit_privacy_linkeduser}:<br />
                    {$input['userlist']}
                    <div id='userlist' style='margin-left:60px;'></div>
                    <span class='small' style='font-weight:normal; margin-left:60px;'><a href='javascript:moar(\"userlist\")'>+ {$flolang->gallery_edit_privacy_linkeduser_addmore}</a></span>
            </td>
        </tr>
        
        
        <tr>
            <td>{$flolang->gallery_edit_title_voting}<br />
                <span class='small' style='font-weight:normal;'>{$flolang->gallery_edit_title_voting_desc}</span>
            </td>
            <td><input type='checkbox' name='publicvoting' value='1' {$privacy['checked']['vote']}></td>
        </tr>
        <tr>
            <td>{$flolang->gallery_edit_title_commenting}<br />
                <span class='small' style='font-weight:normal;'>{$flolang->gallery_edit_title_commenting_desc}</span>
            </td>
            <td><input type='checkbox' name='publiccommenting' value='1' {$privacy['checked']['comment']}></td>
        </tr>
        <tr>
            <td style='border-bottom:1px solid;'>{$flolang->gallery_edit_title_editing}<br />
                <span class='small' style='font-weight:normal;'>{$flolang->gallery_edit_title_editing_desc}</span>
            </td>
            <td style='border-bottom:1px solid;'><input type='checkbox' name='publicediting' value='1' {$privacy['checked']['edit']}></td>
        </tr>
    ";
    $imagenameform = "
        <tr>
            <td>{$flolang->gallery_edit_title_imagename}</td>
            <td><input type='text' name='imagename' maxlength='255' style='width:300px;' value='".$florensia->escape($_POST['imagename'])."'></td>
        </tr>
    ";
}

if ($deleteform) {
    $deleteform = "
        <div style='float:right;'><input type='checkbox' name='check_delete' value='1'> <input type='submit' name='do_delete' value='{$flolang->gallery_delete_submit}'></div>
    ";
}


$content = "  
<script type=\"text/javascript\">
    var c = new Array();
    c[\"characterlist\"] = {$start['characterlist']};
    c[\"guildlist\"] = {$start['guildlist']};
    c[\"userlist\"] = {$start['userlist']};

    function moar(a) {
            var newFields = document.getElementById(a+\"_template\").cloneNode(true);
            newFields.id = \"\";
            newFields.style.display = \"block\";
            var newField = newFields.childNodes;
            for (var i=0;i<newField.length;i++) {
                    var fieldname = newField[i].name;
                    if (fieldname) newField[i].name = fieldname + \"_\"+c[a];
            }
            var insertHere = document.getElementById(a);
            insertHere.parentNode.insertBefore(newFields, insertHere);
            c[a]++;
    }
</script>

<div id='characterlist_template' style='display:none;'><input type='text' name='characterlist' style='width:130px;'></div>
<div id='guildlist_template' style='display:none;'><input type='text' name='guildlist' style='width:130px;'></div>
<div id='userlist_template' style='display:none; margin-left:60px;'><input type='text' name='userlist' style='width:130px;'></div>

<div style='float:right; margin-right:3px; margin-top:2px; font-weight:bold;' class='small'><a href='".$florensia->outlink(array("gallery", "upload"))."'>{$flolang->gallery_link_uploadimages}</a></div>
<div class='subtitle' style='margin-bottom:5px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a></div>
<div class='bordered small' style='margin-bottom:5px;'>".str_replace("http://flobase-charapi", "{$florensia->root}/charapi", $flolang->gallery_edit_desc)."</div>

<div class='subtitle' style='margin-bottom:10px; padding:3px;'>
    <form action='".$florensia->escape($florensia->request_uri())."' method='post' enctype='multipart/form-data'>
    {$deleteform}
    <table style='width:100%;'>
        <tr>
            <td style='width:50%;'>
                {$flolang->gallery_edit_title_image}<br />
                <span class='small' style='font-weight:normal;'>{$flolang->gallery_edit_title_image_desc}</span>
            </td>
            <td>{$input['imagefile']}</td>
        </tr>
        {$imagenameform}
        <tr>
            <td style='border-bottom:1px solid;'>{$flolang->gallery_edit_title_tags}<br />
                <span class='small' style='font-weight:normal;'>{$flolang->gallery_edit_title_tags_desc}</span>
            </td>
            <td style='border-bottom:1px solid;'><textarea name='imagetags' style='width:300px; height:50px;'>".$florensia->escape(join(" ", $imagetags))."</textarea></td>
        </tr>
        
        <tr>
            <td>{$flolang->gallery_edit_title_linkedcharacter}</td>
            <td>
                {$input['characterlist']}
                <div id='characterlist'></div>
                <span class='small' style='font-weight:normal;'><a href='javascript:moar(\"characterlist\")'>+ {$flolang->gallery_edit_linkedcharacter_more}</a></span>
            </td>
        </tr>
        <tr>
            <td style='border-bottom:1px solid;'>{$flolang->gallery_edit_title_linkedguild}</td>
            <td style='border-bottom:1px solid;'>
                {$input['guildlist']}
                <div id='guildlist'></div>
                <span class='small' style='font-weight:normal;'><a href='javascript:moar(\"guildlist\")'>+ {$flolang->gallery_edit_linkedguild_more}</a></span>
            </td>
        </tr>
        {$privacyform}
    </table>
    <div style='margin-top:15px; text-align:center;'>{$input['submit']}</div>
    </form>
</div>
";

$florensia->output_page($content);

} elseif ($_GET['image'] OR $_GET['show']) {
    //image details
    $galleryid = $_GET['image'];
    if ($_GET['show']) $galleryid = $_GET['show'];
    $image = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM flobase_gallery WHERE galleryid='".intval($galleryid)."' LIMIT 1"));
    if (!$image) {
        $florensia->notice($flolang->gallery_error_nosuchid, "warning");
        $florensia->output_page("");
    }
    
    //privacy
    $privacyaccess = $classgallery->check_privacy($image);
    if (!$privacyaccess['access']) {
        $content = "
            <div style='float:right; margin-right:3px; margin-top:2px; font-weight:bold;' class='small'><a href='".$florensia->outlink(array("gallery", "upload"))."'>{$flolang->gallery_link_uploadimages}</a></div>
            <div class='subtitle' style='margin-bottom:10px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a> &gt; {$flolang->gallery_pagetitle_details} &gt; ".$florensia->escape($image['name'])."</div>
            <div class='warning small' style='text-align:center;'>".$flolang->sprintf($flolang->gallery_error_nopermission, join("<br />", $privacyaccess['reason']))."</div>
        ";
        $florensia->output_page($content); 
    }
  
    //now show and details are splitting up
    if ($_GET['image']) {
        
        MYSQL_QUERY("UPDATE flobase_gallery SET views=views+1 WHERE galleryid='{$image['galleryid']}'");
        
        //linked characters
        $linkedcharacters = array();
        $querycharacter = MYSQL_QUERY("SELECT charname, g.characterid FROM flobase_character_gallery as g LEFT JOIN flobase_character as c ON (g.characterid=c.characterid) WHERE galleryid='{$image['galleryid']}'");
        while ($character = MYSQL_FETCH_ARRAY($querycharacter)) {
            if (!$character['charname']) {
                list($character['charname']) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT charname FROM flobase_character_archiv WHERE characterid='{$character['characterid']}'"));
                $linkedcharacters[] = "<a class='archiv' href='".$florensia->outlink(array("characterdetails", $character['charname']), array("archivid"=>$character['characterid']))."'>".$florensia->escape($character['charname'])."</a>";
                continue;   
            }
            $linkedcharacters[] = "<a href='".$florensia->outlink(array("characterdetails", $character['charname']))."'>".$florensia->escape($character['charname'])."</a>";
        }
        if (!count($linkedcharacters)) $linkedcharacters[] = "-";
        
        //linked guilds
        $linkedguilds = array();
        $queryguild = MYSQL_QUERY("SELECT g.guildid, server, guildname, memberamount FROM flobase_guild_gallery as g, flobase_guild as d WHERE g.guildid=d.guildid AND galleryid='{$image['galleryid']}'");
        while ($guild = MYSQL_FETCH_ARRAY($queryguild)) {
            $archiv = !intval($guild['memberamount']) ? "class='archiv'" : "";
            $linkedguilds[] = "<a {$archiv} href='".$florensia->outlink(array("guilddetails", $guild['guildid'], $guild['server'], $guild['guildname']))."'>".$florensia->escape($guild['guildname'])."</a>";
        }
        if (!count($linkedguilds)) $linkedguilds[] = "-";
        
        
        //imagetags
        $imagetags = array();
        if (!strlen($image['tags'])) $imagetags[] = "-";
        else {
            foreach(explode(" ", $image['tags']) as $tag) {
                $imagetags[] = "<a href='".$florensia->outlink(array("gallery", "t", $tag))."'>{$tag}</a>";
            }
        }
        
        //if user get the permission to see the edit-box
        if ($image['userid']==$flouser->userid OR ($image['editing'] && $flouser->userid) OR $flouser->get_permission("gallery", "moderate")) {
            $admineditoptions = "<div class='subtitle'><a href='".$florensia->outlink(array("gallery", "edit", $image['galleryid'], $image['name']))."'>{$flolang->gallery_link_editimage}</a></div>";
            $minheight_infobox_add = 20;
        } else  $minheight_infobox_add = 0;
        
        //usernotes
        if ($image['commenting']) {
            $notesform = $classusernote->display($image['galleryid'], "gallery");
            $notescount = $classusernote->get_tabdesc($image['galleryid'], "gallery");
        } else { $notescount = "Disabled"; }
        
        //server
        if (!strlen($image['server'])) $server = "-";
        else $server = "<a href='".$florensia->outlink(array("statistics", $image['server']))."'>".$florensia->escape($image['server'])."</a>";
        
        //voting
        if ($image['voting']) {
            $image['thumpsdown'] = 0-$image['thumpsdown'];				
                                            
            if ($flouser->userid) {
                if ($flouser->userid == $image['userid']) {
                    $verifydetails = "{$image['thumpsup']}/{$image['thumpsdown']}";                    
                } else {
                    //db-work
                    $flolang->load("guides");
                    $needupdate = false;
                    foreach ($_POST as $postkey => $postvalue) {
                            if (preg_match('/^gallery_(thumpsup|thumpsdown|withdraw)_([1-9][0-9]*)_x$/', $postkey, $dbkey)) {
                                    $florensia->notice($classgallery->updateentry($dbkey[2], $dbkey[1]));
                                    $needupdate = true;
                            }
                    }
                    if ($needupdate) $image = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM flobase_gallery WHERE galleryid='{$image['galleryid']}' LIMIT 1"));

                    $uservotestatus = $classvote->votestatus($image['userlist']);
                    if ($uservotestatus) {				
                            if ($uservotestatus['vote']) $image['thumpsup'] = "<span style='text-decoration:underline;'>{$image['thumpsup']}</span>";
                            else $image['thumpsdown'] = "<span style='text-decoration:underline;'>{$image['thumpsdown']}</span>";
                                    
                            $verifydetails = "
                                    {$image['thumpsup']}/{$image['thumpsdown']}
                                    <input type='image' name='gallery_withdraw_{$image['galleryid']}' src='{$florensia->layer_rel}/withdraw.gif' style='background-color:transparent; border:0px; height:14px;'>
                                    <span style='font-weight:normal;'>(".$flolang->sprintf($flolang->gallery_quick_verify_alreadyverified_notice, date("m.d.Y", $uservotestatus['dateline'])).")</span>    
                            ";
                    }
                    else {
                            $verifydetails = "
                                            <input type='image' name='gallery_thumpsup_{$image['galleryid']}' src='{$florensia->layer_rel}/icon_thumpsup.gif' style='background-color:transparent; border:0px;'>
                                            <span style='margin-left:4px; margin-right:4px;'>{$image['thumpsup']}/{$image['thumpsdown']}</span>
                                            <input type='image' name='gallery_thumpsdown_{$image['galleryid']}' src='{$florensia->layer_rel}/icon_thumpsdown.gif' style='background-color:transparent; border:0px;'>
                            ";
                    }
                }
            }
            else { $verifydetails = "<span style='margin-left:4px; margin-right:4px;'>{$image['thumpsup']}/{$image['thumpsdown']}</span><br />{$flolang->gallery_quick_notloggedin}"; }            

            if ($flouser->get_permission("gallery", "moderate")) {
                    $votestats = $classvote->votestats($image['userlist']);
                    $adminmoderatelist[] = "<span ".popup("<div class='shortinfo_1' style='width:300px'>{$votestats['display']}</div>", "LEFT, MOUSEOFF, STICKY").">C</span>";
            }
        
            $voting = "
            <div class='small' style='margin-top:15px; font-weight:bold;'>
                <form action='".$florensia->escape($florensia->request_uri())."' method='POST'>
                    <span style='padding-right:6px;'>{$flolang->gallery_vote_likeit}</span> {$verifydetails}
                </form>
            </div> 
            ";
        } else $voting = "<div class='small' style='margin-top:15px; font-weight:normal;'>{$flolang->gallery_details_voting_disabled}</div>";
        
        
        if ($flouser->get_permission("gallery", "moderate")) {
            $adminmoderatelist[] = "<a href='{$florensia->root}/adminlog?section=gallery&amp;logvalue=".urlencode("{gallery:{$image['galleryid']}}")."' target='_blank'>L</a>";
        }
        
        if (count($adminmoderatelist)) $adminmoderatelist = "<span class='small'>[".join("|", $adminmoderatelist)."]</span>";
        else unset($adminmoderatelist);
        
        $content = "
            <div style='float:right; margin-right:3px; margin-top:2px; font-weight:bold;' class='small'><a href='".$florensia->outlink(array("gallery", "upload"))."'>{$flolang->gallery_link_uploadimages}</a></div>
            <div class='subtitle' style='margin-bottom:10px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a> &gt; {$flolang->gallery_pagetitle_details} &gt; ".$florensia->escape($image['name'])." {$adminmoderatelist}</div>
            <div class='small' style='float:left;'>
                <div class='subtitle' style='height:{$image['theight']}px; width:{$image['twidth']}px;'><a href='".$florensia->outlink(array("gallery", "s", $image['galleryid'], $image['name']))."' target='_blank'><img src='{$florensia->root}/pictures/gallery/{$image['galleryid']}_thumb' style='border:0px;'></a></div>
                {$admineditoptions}
            </div>
            <div class='subtitle' style='margin-left:".bcadd($image['twidth'],10)."px; min-height:".bcadd($image['theight'],$minheight_infobox_add)."px;'>
                <table class='small' style='width:100%; font-weight:normal;'>
                    <tr>
                        <td style='font-weight:bold; border-bottom:1px solid;' colspan='2'>{$flolang->gallery_details_title_information}</td>
                        <td style='font-weight:bold; border-bottom:1px solid; width:150px;'>{$flolang->gallery_details_title_linkedcharacter}</td>
                        <td style='font-weight:bold; border-bottom:1px solid; width:150px;'>{$flolang->gallery_details_title_linkedguild}</td>
                    </tr>
                    <tr>
                        <td style='width:100px; vertical-align:top;'>
                            {$flolang->gallery_details_uploadeduser}<br />
                            {$flolang->gallery_details_timestamp}<br />
                            {$flolang->gallery_details_views}<br />
                            {$flolang->gallery_details_privacy}<br />
                            {$flolang->gallery_details_server}<br />
                            <br />
                            {$flolang->gallery_details_imagesize}<br />
                            {$flolang->gallery_details_usernotesamount}<br />
                            {$flolang->gallery_details_tags}
                        </td>
                        <td style='vertical-align:top;'>
                            ".$flouserdata->get_username($image['userid'])." (<a href='".$florensia->outlink(array("gallery", "u", $image['userid'], $flouserdata->get_username($image['userid'], array('rawoutput'=>1))))."'>{$flolang->gallery_details_uploadeduser_linktousergallery}</a>)<br />
                            ".date("m.d.Y H:i", $image['timestamp'])."<br />
                            ".bcadd($image['views'],1)."/{$image['fullviews']}<br />
                            {$image['pflags']}<br />
                            {$server}<br />
                            <br />
                            {$image['width']}x{$image['height']}<br />
                            {$notescount}<br />
                            ".join(" ", $imagetags)."
                        </td>
                       <td style='vertical-align:top;'>
                        ".join("<br />", $linkedcharacters)."
                       </td>
                       <td style='vertical-align:top;'>
                        ".join("<br />", $linkedguilds)."
                       </td>
                    </tr>            
                </table>
                {$voting}
            </div>
            <div style='margin-top:20px;'>{$notesform}</div>
        ";
        
        $florensia->sitetitle("Details");
        $florensia->sitetitle($florensia->escape($image['name']));
        $florensia->output_page($content);
    } else {
        //image itself
        MYSQL_QUERY("UPDATE flobase_gallery SET fullviews=fullviews+1 WHERE galleryid='{$image['galleryid']}'");
        switch($image['imagetype']) {
            case 1: {
                $source = @imagecreatefromgif("{$florensia->root_abs}/pictures/gallery/{$image['galleryid']}");
                break;
            }
            case 2: {
                $source = @imagecreatefromjpeg("{$florensia->root_abs}/pictures/gallery/{$image['galleryid']}");
                break;
            }
            case 3: {
                $source = @imagecreatefrompng("{$florensia->root_abs}/pictures/gallery/{$image['galleryid']}");
                break;
            }
        }
	$watermark = @imagecreatefromgif($cfg['layer_abs']."/watermark_small.gif");
	@imagecopymerge($source,$watermark,bcsub(imagesx($source),imagesx($watermark)),bcsub(imagesy($source),bcadd(imagesy($watermark),1)),0,0,imagesx($watermark),imagesy($watermark),25);
        
        header('Content-Type: image/png');
        @imagesavealpha($source, TRUE);
        @imagepng($source);
        @imagedestroy($source);
        die();
    }
} else {
        $dbwhere = array();
        $pageselectoption = array();
	$galleryimagelimit = 21;
        
        switch ($_GET['server']) {
            case "all": break;
            case "allserver": {
                $dbwhere[] = "server!=''";
                $pageselectoption['server'] = "allserver";
                break;
            }
            case "other": {
                $dbwhere[] = "server=''";
                $pageselectoption['server'] = "other";
                break;
            }
            default: {
                if (intval($_GET['server'])) {
                    $dbwhere[] = "server='".mysql_real_escape_string($florensia->get_server(intval($_GET['server'])))."'";
                    $pageselectoption['server'] = intval($_GET['server']);
                }
            }
        }
        $preselectedserver[$_GET['server']] = "selected='selected'";
    
        if (!in_array($_GET['order'], array("newest", "oldest", "best", "worst", "clicks", "views"))) $_GET['order'] = "newest";
        elseif ($_GET['order']!="newest") $pageselectoption['order'] = $_GET['order'];
        
        $preselectedorderby[$_GET['order']] = "selected='selected'";
        switch ($_GET['order']) {
            case "newest": {
                $dborderby = "ORDER BY timestamp DESC";
                break;
            }
            case "oldest": {
                $dborderby = "ORDER BY timestamp";
                break;
            }
            case "best": {
                $dborderby = "ORDER BY thumpsup DESC, thumpsdown";
                $dbwhere[] = "voting='1'";
                break;
            }
            case "worst": {
                $dborderby = "ORDER BY thumpsdown DESC, thumpsup";
                $dbwhere[] = "voting='1'";
                break;
            }
            case "clicks": {
                $dborderby = "ORDER BY views DESC, fullviews DESC";
                break;
            }
            case "views": {
                $dborderby = "ORDER BY fullviews DESC, views DESC";
                break;
            }
            default: $dborderby="";
        }
    
    if ($_GET['character'] OR isset($_GET['guild'])) {
        if ($_GET['character']) {
            
            if (intval($_GET['archivid'])) $character = new class_character(intval($_GET['archivid']));
            else $character = new class_character($_GET['character']);
            
            if (!$character->is_valid()) {
                $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a> &gt; {$flolang->gallery_pagetitle_character}</div>";
                $imagelist = "<div class='small' style='margin-top:10px; border-bottom:1px solid; font-weight:bold;'>".$character->get_errormsg()."</div>";
            }
            else {
                list($linkedimages) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(galleryid) FROM flobase_character_gallery WHERE characterid='{$character->data['characterid']}'"));
                $linkedimages = intval($linkedimages);
                
                #var_dump(count($linkedimages));
                if ($linkedimages<1) {
                    $imagelist = "<div class='small'>".$flolang->sprintf($flolang->gallery_overview_error_character_noimages, $florensia->escape($character->data['charname']))." <a href='".$florensia->outlink(array("gallery", "upload"), $character->merge_opt_link(array("character"=>$character->data['charname'])))."'>{$flolang->gallery_link_uploadimages}</a>.</div>";
                    $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a> &gt; {$flolang->gallery_pagetitle_character} &gt; <a href='".$florensia->outlink(array("characterdetails", $character->data['charname']), $character->merge_opt_link())."'>".$florensia->escape($character->data['charname'])."</a></div>";
                } else {
                    //images found!
                    $pageselect = $florensia->pageselect($linkedimages, array("gallery", "c", $character->data['charname']), $pageselectoption, $galleryimagelimit);
                    $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a> &gt; {$flolang->gallery_pagetitle_character} &gt; <a href='".$florensia->outlink(array("characterdetails", $character->data['charname']), $character->merge_opt_link())."'>".$florensia->escape($character->data['charname'])."</a></div>";
                    $dbimagequery = "SELECT * FROM flobase_character_gallery as c, flobase_gallery as g WHERE c.galleryid=g.galleryid AND c.characterid='{$character->data['characterid']}' {$dborderby} LIMIT ".$pageselect['pagestart'].",{$galleryimagelimit}";
                }
                $tabbar['details'] = array("link"=>$florensia->outlink(array("characterdetails", $character->data['charname']), $character->merge_opt_link()), "name"=>$flolang->tabbar_title_characterdetails, "desc"=>$flolang->tabbar_desc_characterdetails);
                $tabbar['gallery'] = array("anchor"=>"gallery", "name"=>$flolang->tabbar_title_gallery, "desc"=>$flolang->sprintf($flolang->tabbar_desc_gallery, $linkedimages));
            //    $tabbar['friends'] = array("link"=>$florensia->outlink(array("characterdetails", $character->data['charname']), $character->merge_opt_link(), array("anchor"=>"friends")), "name"=>$flolang->tabbar_title_character_friends, "desc"=>$flolang->sprintf($flolang->tabbar_desc_character_friends, "xxx"));
                if ($character->is_owner() || $flouser->get_permission("character", "owneroverride")) $tabbar['settings'] = array("link"=>$florensia->outlink(array("characterdetails", $character->data['charname']), $character->merge_opt_link(), array('anchor'=>'settings')), "name"=>$flolang->tabbar_title_character_settings);
                $tabbar = $florensia->tabbar($tabbar, array("starttab"=>"gallery"));
                $florensia->sitetitle("Character");
                $florensia->sitetitle($character->data['charname']);
            }
            $pagetitle .= "<div class='subtitle' style='text-align:center; margin-bottom:15px; margin-top:10px;'>{$flolang->character_jumpto} ".$florensia->quicksearch()."</div>";
        } else {
            //guild's images
            if (!intval($_GET['guild'])) {
                $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a> &gt; {$flolang->gallery_pagetitle_guild}</div>";
                $notice = "<div class='small' style='margin-top:10px; border-bottom:1px solid; font-weight:bold;'>{$flolang->gallery_overview_error_guild_nosuchguild}</div>";
            } else {
                $guild = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT guildid, guildname, server FROM flobase_guild WHERE guildid='".intval($_GET['guild'])."'"));
                list($linkedimages) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(galleryid) FROM flobase_guild_gallery WHERE guildid='{$guild['guildid']}'"));
                $linkedimages = intval($linkedimages);                
                
                if (!$guild) {
                    $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a> &gt; {$flolang->gallery_pagetitle_guild}</div>";
                    $imagelist = "<div class='small' style='margin-top:10px; border-bottom:1px solid; font-weight:bold;'>{$flolang->gallery_overview_error_guild_nosuchguild}</div>";
                }
                elseif (!$linkedimages) {
                    $imagelist = "<div class='small'>".$flolang->sprintf($flolang->gallery_overview_error_guild_noimages, "{$guild['guildname']}/{$guild['server']}")." <a href='".$florensia->outlink(array("gallery", "upload"), array("guild"=>$guild['guildid']))."'>{$flolang->gallery_link_uploadimages}</a>.</div>";
                    $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a> &gt; {$flolang->gallery_pagetitle_guild} &gt; <a href='".$florensia->outlink(array("statistics", $guild['server']))."'>".$florensia->escape($guild['server'])."</a> &gt; <a href='".$florensia->outlink(array("guilddetails", $guild['guildid'], $guild['server'], $guild['guildname']))."'>".$florensia->escape($guild['guildname'])."</a></div>";
                } else {
                    //images found!
                    $pageselect = $florensia->pageselect($linkedimages, array("gallery", "g", $guild['guildid'], $guild['server'], $guild['guildname']), $pageselectoption, $galleryimagelimit);
                    $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a> &gt; {$flolang->gallery_pagetitle_guild} &gt; <a href='".$florensia->outlink(array("statistics", $guild['server']))."'>".$florensia->escape($guild['server'])."</a> &gt; <a href='".$florensia->outlink(array("guilddetails", $guild['guildid'], $guild['server'], $guild['guildname']))."'>".$florensia->escape($guild['guildname'])."</a></div>";
                    $dbimagequery = "SELECT * FROM flobase_guild_gallery as c, flobase_gallery as g WHERE c.galleryid=g.galleryid AND c.guildid='{$guild['guildid']}' {$dborderby} LIMIT ".$pageselect['pagestart'].",{$galleryimagelimit}";
                }
                    $tabbar['details'] = array("link"=>$florensia->outlink(array("guilddetails", $guild['guildid'], $guild['server'], $guild['guildname'])), "name"=>$flolang->tabbar_title_guild, "desc"=>$flolang->tabbar_desc_guild);
                    $tabbar['gallery'] = array("anchor"=>"gallery", "name"=>$flolang->tabbar_title_gallery, "desc"=>$flolang->sprintf($flolang->tabbar_desc_gallery, $linkedimages));
                    //$tabbar['usernotes'] = array("link"=>$florensia->outlink(array("guilddetails", $guild['id'], $guild['server'], $guild['guildname']), array(), array("anchor"=>"usernotes")), "name"=>"Guestbook", "desc"=>$classusernote->get_tabdesc($guild['id'], "guild"));
                    $tabbar = $florensia->tabbar($tabbar, array("starttab"=>"gallery"));
                    $pagetitle .= "
                        <div class='subtitle' style='text-align:center; margin-bottom:15px; margin-top:10px;'>
                            {$flolang->guild_jumpto}<br />
                            <input type='text' maxlength='13' name='search' value='".$florensia->escape($_GET['search'])."'> ".$florensia->get_serverselect()."
                        </div>
                    ";
                    $florensia->sitetitle("Guild");
                    $florensia->sitetitle($guild['server']);
                    $florensia->sitetitle($guild['guildname']);
            }
        }
        if (!$linkedimages && !$imagelist) {
            $notice = "<div class='small' style='margin-top:10px; border-bottom:1px solid; font-weight:bold;'>{$flolang->gallery_error_noimagesfound}</div>";
        }
    } elseif ($_GET['tag']) {
        //tagsearch
        if (preg_match("/^[-a-z0-9_]{3,}$/i", $_GET['tag'])) {
            array_push($dbwhere, "MATCH(tags) AGAINST('+".strtolower($_GET['tag'])."')");
            $imagesfound = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(galleryid) FROM flobase_gallery WHERE ".join(" AND ", $dbwhere)));
            if (!$imagesfound['COUNT(galleryid)']) {
                $notice = "<div class='small' style='margin-top:10px; border-bottom:1px solid; font-weight:bold;'>{$flolang->gallery_error_noimagesfound}</div>";
            } else {
                $pageselect = $florensia->pageselect($imagesfound['COUNT(galleryid)'], array("gallery", "t", strtolower($_GET['tag'])), $pageselectoption, $galleryimagelimit);
                $dbimagequery = "SELECT * FROM flobase_gallery WHERE ".join(" AND ", $dbwhere)." {$dborderby} LIMIT {$pageselect['pagestart']},{$galleryimagelimit}";
            }
            $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a> &gt; {$flolang->gallery_pagetitle_tags} &gt; {$_GET['tag']}</div>";
            $notice = "<div class='small' style='margin-top:10px; border-bottom:1px solid; font-weight:bold;'>".$flolang->sprintf($flolang->gallery_overview_tags_notice_foundimages, $imagesfound['COUNT(galleryid)'], $_GET['tag'])."</div>";
            $florensia->sitetitle("Tag");
            $florensia->sitetitle(strtolower($_GET['tag']));
        }
        else {
            $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a></div>";
            $notice = "<div class='small' style='margin-top:10px; border-bottom:1px solid; font-weight:bold;'>{$flolang->gallery_error_invalidtag}</div>";
        }
    } elseif (isset($_GET['user'])) {
            $userid = intval($_GET['user']);
            if (!$userid) {
                $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a></div>";
                $notice = "<div class='small' style='margin-top:10px; border-bottom:1px solid; font-weight:bold;'>{$flolang->gallery_error_invaliduser}</div>";
            } else {
                array_push($dbwhere, "userid='{$userid}'");
                $imagesfound = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(galleryid) FROM flobase_gallery WHERE ".join(" AND ", $dbwhere)));
                if (!$imagesfound['COUNT(galleryid)']) {
                    $notice = "<div class='small'>".$flolang->sprintf($flolang->gallery_overview_error_user_noimages, $flouserdata->get_username($userid))."</div>";
                } else {
                    $pageselect = $florensia->pageselect($imagesfound['COUNT(galleryid)'], array("gallery", "u", $userid, $flouserdata->get_username($userid, array('rawoutput'=>1))), $pageselectoption, $galleryimagelimit);
                    $dbimagequery = "SELECT * FROM flobase_gallery WHERE ".join(" AND ", $dbwhere)." {$dborderby} LIMIT {$pageselect['pagestart']},{$galleryimagelimit}";
                }
                $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a> &gt; {$flolang->gallery_pagetitle_user} &gt; ".$flouserdata->get_username($userid)."</div>";
                $notice = "<div class='small' style='margin-top:10px; border-bottom:1px solid; font-weight:bold;'>".$flolang->sprintf($flolang->gallery_overview_user_notice_foundimages, $imagesfound['COUNT(galleryid)'], $flouserdata->get_username($userid))."</div>";
                $florensia->sitetitle("User");
                $florensia->sitetitle($flouserdata->get_username($userid, array('rawoutput'=>1)));
            }
    } elseif ($_GET['search']) {
        //search overview
            $searchstring = array();
		foreach (explode(" ", $_GET['search']) as $keyword) {
			$searchstring[] = "name LIKE '%".get_searchstring($keyword,0)."%'";
		}
            array_push($dbwhere, "(".join(" AND ", $searchstring).")");
            
            if (count($dbwhere)) $dbwhere = "WHERE ".join(" AND ", $dbwhere);
            else unset($dbwhere);
            
            $imagesfound = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(galleryid) FROM flobase_gallery {$dbwhere}"));
            $pageselectoption['search'] = $_GET['search'];
            $pageselect = $florensia->pageselect($imagesfound['COUNT(galleryid)'], array("gallery"), $pageselectoption, $galleryimagelimit);

            $dbimagequery = "SELECT * FROM flobase_gallery {$dbwhere} {$dborderby} LIMIT {$pageselect['pagestart']},{$galleryimagelimit}";
            $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a></div>";
            $notice = "<div class='small' style='margin-top:10px; border-bottom:1px solid; font-weight:bold;'>".$flolang->sprintf($flolang->gallery_overview_search_notice_foundimages, $imagesfound['COUNT(galleryid)'])."</div>";
            $florensia->sitetitle("Search");
            $florensia->sitetitle($_GET['search']);
    } else {
        //normal overview
            if (count($dbwhere)) $dbwhere = "WHERE ".join(" AND ", $dbwhere);
            else unset($dbwhere);
            
            $imagesfound = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(galleryid) FROM flobase_gallery {$dbwhere}"));
            $pageselect = $florensia->pageselect($imagesfound['COUNT(galleryid)'], array("gallery"), $pageselectoption, $galleryimagelimit);

            $dbimagequery = "SELECT * FROM flobase_gallery {$dbwhere} {$dborderby} LIMIT {$pageselect['pagestart']},{$galleryimagelimit}";
            $pagetitle = "<div class='subtitle' style='margin-bottom:3px;'><a href='".$florensia->outlink(array("gallery"))."'>{$flolang->gallery_pagetitle_gallery}</a></div>";
            $notice = "<div class='small' style='margin-top:10px; border-bottom:1px solid; font-weight:bold;'>".$flolang->sprintf($flolang->gallery_overview_search_notice_foundimages, $imagesfound['COUNT(galleryid)'])."</div>";
    }
    
    if ($dbimagequery) {
        unset($images);
        $queryimage = MYSQL_QUERY($dbimagequery);
        while ($image = MYSQL_FETCH_ARRAY($queryimage)) {
            list($linkedchars) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(galleryid) FROM flobase_character_gallery WHERE galleryid='{$image['galleryid']}'"));
            list($linkedguilds) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(galleryid) FROM flobase_guild_gallery WHERE galleryid='{$image['galleryid']}'"));
           # if (strlen($image['characterlinkedlist'])) $linkedchars = count(explode(",",$image['characterlinkedlist']));
           # else $linkedchars = 0;
           # if (strlen($image['guildlinkedlist'])) $linkedguilds = count(explode(",",$image['guildlinkedlist']));
           # else $linkedguilds = 0;
            
            $privacyaccess = $classgallery->check_privacy($image);
            if (!$privacyaccess['access']) $thumpnail = "<img src='{$florensia->layer_rel}/gallery_locked.png' style='border:0px;' alt='{$flolang->gallery_thumbnail_noaccess}' ".popup("<div class='subtitle warning small' style='text-align:center; font-weight:normal;'>".$flolang->sprintf($flolang->gallery_error_nopermission, join("<br />", $privacyaccess['reason']))."</div>", "").">";
            else $thumpnail = "<img src='{$florensia->root}/pictures/gallery/{$image['galleryid']}_thumb' style='border:0px;' alt='".$florensia->escape($image['name'])."'>";

	    if (strlen($image['name'])>30) $imagetitle = substr($image['name'], 0, 30)."...";
            else $imagetitle = $image['name'];
            
            if ($image['commenting']) $comments = "
                    <tr>
                        <td style='text-align:center;'><img src='{$florensia->layer_rel}/icon_notes.gif' alt='Notes:'></td>
                        <td style='vertical-align:bottom;'>".MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_usernotes WHERE sectionid='".mysql_real_escape_string($image['galleryid'])."' AND section = 'gallery'"))."</td>
                    </tr>
                    ";
            else unset($comments);
            
            if ($image['voting']) $voting = "
                    <tr>
                        <td style='text-align:center;'><img src='{$florensia->layer_rel}/icon_thumpsup.gif' alt='Up:'></td>
                        <td style='vertical-align:bottom;'>{$image['thumpsup']}</td>
                    </tr>
                    <tr>
                        <td style='text-align:center;'><img src='{$florensia->layer_rel}/icon_thumpsdown.gif' alt='Down:'></td>
                        <td style='vertical-align:bottom;'>{$image['thumpsdown']}</td>
                    </tr>
                    ";
            else unset($voting);
            
            $images .= "
            <div class='gallerylist small'>
                <div style='font-weight:bold; min-height:12px;'>".$florensia->escape($imagetitle)."</div>
                <div class='thumbnail' style='float:left;'>
                    <a href='".$florensia->outlink(array("gallery", "i", $image['galleryid'], $image['name']))."'>
                        {$thumpnail}
                    </a>
                </div>
                <div>
                <table style='margin-left:{$image['twidth']}; border-spacing:1px;'>
                    <tr>
                        <td style='width:17px; text-align:center;'><img src='{$florensia->layer_rel}/icon_cursor.png' alt='C:' style='height:17px;'></td>
                        <td style='vertical-align:bottom;'>{$image['views']}/{$image['fullviews']}</td>
                    </tr>
                    {$voting}
                    <tr>
                        <td style='text-align:center;'><img src='{$florensia->layer_rel}/icon_character.gif' alt='Chars:'></td>
                        <td style='vertical-align:bottom;'>{$linkedchars}</td>
                    </tr>
                    <tr>
                        <td style='text-align:center;'><img src='{$florensia->layer_rel}/icon_guild.gif' alt='Guilds:'></td>
                        <td style='vertical-align:bottom;'>{$linkedguilds}</td>
                    </tr>
                    {$comments}
                </table>
                </div>
            </div>";
        }
        
        if ($images) $imagelist .= "
            <div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
            {$images}
            <div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
        ";
    }

    unset($serverselect);
    if (!($_GET['character'] OR isset($_GET['guild']))) {
        $serverfilter = array();
        $serverfilter[] = "<option value='all'>All images</option>";
        $serverfilter[] = "<option disabled='disabled'></option>";
        $serverfilter[] = "<option value='allserver' {$preselectedserver['allserver']}>All servers</option>";
        foreach ($florensia->validserver as $server) {
                $serverfilter[] = "<option value='".$florensia->get_server($server)."' ".$preselectedserver[$florensia->get_server($server)].">{$server}</option>";
        }
        $serverfilter[] = "<option disabled='disabled'></option>";
        $serverfilter[] = "<option value='other' {$preselectedserver['other']}>Others</option>";
        $serverselect = "<select name='server'>".join("\n", $serverfilter)."</select>";
    }
    
    
    if ($notice) $notice = "<div style='margin-top:45px;'>{$notice}</div>";
    $content = "
    <div style='float:right; margin-right:3px; margin-top:2px; font-weight:bold;' class='small'><a href='".$florensia->outlink(array("gallery", "upload"))."'>{$flolang->gallery_link_uploadimages}</a></div>
    {$pagetitle}
            <div style='float:right; margin-top:5px; text-align:right;'>
                ".$florensia->quicksearch()."
                <form action='".$florensia->escape($florensia->request_uri())."' method='get'>
                <select name='order'>
                    <option value='newest' {$preselectedorderby['newest']}>{$flolang->gallery_overview_orderby_newest}</option>
                    <option value='oldest' {$preselectedorderby['oldest']}>{$flolang->gallery_overview_orderby_oldest}</option>
                    <option value='best' {$preselectedorderby['best']}>{$flolang->gallery_overview_orderby_best}</option>
                    <option value='worst' {$preselectedorderby['worst']}>{$flolang->gallery_overview_orderby_worst}</option>
                    <option value='clicks' {$preselectedorderby['clicks']}>{$flolang->gallery_overview_orderby_clicks}</option>
                    <option value='views' {$preselectedorderby['views']}>{$flolang->gallery_overview_orderby_views}</option>
                </select>
                {$serverselect}
                <input class='quicksubmit' type='submit' value=''>
                </form>
            </div>
    {$notice}
    {$tabbar['tabbar']}
    {$imagelist}
    {$tabbar['jscript']}
    ";
    $florensia->output_page($content);
}

?>
