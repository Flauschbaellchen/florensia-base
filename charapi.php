<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$florensia->sitetitle("CharacterAPI");

$flolang->load("character");

if (strlen($_GET['verify'])) {

if (!$flouser->userid) $verificationbar = "<span style='color:#FF0000'>{$flolang->character_api_verify_error_notloggedin}</span>";
else {
    $character = new class_character($_GET['verify']);
    if (!$character->is_valid()) $verificationbar = "<span style='color:#FF0000'>".$character->get_errormsg()."</span>";
    elseif ($character->data['ownerid']) $verificationbar = $flolang->character_api_already_verified;
    elseif (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_character_verification WHERE userid='{$flouser->userid}' AND characterid='{$character->data['characterid']}' AND accepted='-1'"))) $verificationbar = $flolang->character_api_verify_pending;
    else {
        if ($_POST['do_upload'] && $_FILES['screenshot']['tmp_name']) {
            //verify if the screen is the original one.
            $screen = fopen($_FILES['screenshot']['tmp_name'], "rb");
            $header = fread($screen, 163);
            
            $origheader = "\xFF\xD8\xFF\xE0\x00\x10\x4A\x46\x49\x46\x00\x01\x01\x00\x00\x01\x00\x01\x00\x00\xFF\xDB\x00\x43\x00\x08\x06\x06\x07\x06\x05\x08\x07\x07\x07\x09\x09\x08\x0A\x0C\x14\x0D\x0C\x0B\x0B\x0C\x19\x12\x13\x0F\x14\x1D\x1A\x1F\x1E\x1D\x1A\x1C\x1C\x20\x24\x2E\x27\x20\x22\x2C\x23\x1C\x1C\x28\x37\x29\x2C\x30\x31\x34\x34\x34\x1F\x27\x39\x3D\x38\x32\x3C\x2E\x33\x34\x32\xFF\xDB\x00\x43\x01\x09\x09\x09\x0C\x0B\x0C\x18\x0D\x0D\x18\x32\x21\x1C\x21\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\x32\xFF\xC0\x00\x11\x08";
            if ($header==$origheader) {
                MYSQL_QUERY("INSERT INTO flobase_character_verification (userid, characterid, timestamp) VALUES('{$flouser->userid}', '{$character->data['characterid']}', '".date("U")."')");
                
                @rename($_FILES['screenshot']['tmp_name'], "{$florensia->root_abs}/pictures/characterverification/".mysql_insert_id());
		chmod("{$florensia->root_abs}/pictures/characterverification/".mysql_insert_id(), 0755);
                $verificationbar = $flolang->character_api_verify_pending;
                $florensia->notice($flolang->character_api_verify_upload_successfully, "successful");
                $flolog->add("character:verification:request", "{user:{$flouser->userid}} created {characterverification:".mysql_insert_id()."} for {characterid:{$character->data['characterid']}}");
            } else {
                $florensia->notice($flolang->character_api_verify_error_nooriginalimage, "warning");
                @unlink($_FILES['imagefile']['tmp_name']);
            }
        }

        if (!strlen($flouser->user['flobase_characterkey'])) {
            $key = create_characterkey();
            MYSQL_QUERY("UPDATE forum_users SET flobase_characterkey='{$key}' WHERE uid='{$flouser->userid}' LIMIT 1");
            $flouser->user['flobase_characterkey'] = $key;
        }
        if (!$verificationbar) $verificationbar = "
            <table style='width:100%;'>
                <tr>
                    <td style='width:150px;'>{$flolang->character_api_verify_form_code}</td>
                    <td><input type='text' readonly='readonly' value='{$flouser->user['flobase_characterkey']}' style='width:200px;'></td>
                </tr>
                <tr>
                    <td style='width:150px;'>{$flolang->character_api_verify_form_screenshot}</td>
                    <td>
                        <form action='".$florensia->escape($florensia->request_uri())."' method='post' enctype='multipart/form-data'>
                            <input type='file' name='screenshot'><br />
                            <input type='submit' name='do_upload' value='{$flolang->character_api_verify_form_submit}'>
                        </form>
                    </td>
                </tr>
            </table>";
    }
}
if ($flouser->userid) $profilelink = "<br /><a href='".$florensia->outlink(array("usercharacter", $flouser->userid, $flouserdata->get_username($flouser->userid, array("rawoutput"=>true))))."'>{$flolang->character_api_verify_linktoprofile}</a>";
$content = "<div class='small subtitle' style='font-weight:normal; padding:8px;'>{$flolang->character_api_verify_introduction}<br />{$profilelink}</div>
<div class='subtitle small' style='font-weight:normal; margin-top:20px; padding:8px;'><b>".$flolang->sprintf($flolang->character_api_verify_form_title, "<a href='".$florensia->outlink(array("characterdetails", $_GET['verify']))."'>".$florensia->escape($_GET['verify']))."</a></b><br />
<br />
{$verificationbar}
</div>
";

    $florensia->sitetitle("Characterverification");
    $florensia->sitetitle($florensia->escape($_GET['verify']));
    $florensia->output_page($content);
}



//$florensia->notice("<div class='warning' style='margin-bottom:10px;'>{$flolang->signature_api_failnotice}</div>");

if (!is_file("{$florensia->charapi}/ranking.keepup2date.pid")) {
    $running = "<div class='warning subtitle' style='padding:2px;'>{$flolang->character_api_notice_notrunning}</div>";
    $forcedupdated = "<div class='warning' style='padding:2px;'>{$flolang->character_api_notice_notrunning}</div>";
}
else {
    $running = "<div class='successful subtitle' style='padding:2px;'>{$flolang->character_api_notice_running}</div>";

    $queryforced = MYSQL_QUERY("SELECT charname FROM flobase_character WHERE forceupdate='1' ORDER BY lastupdate DESC LIMIT 15");
    for ($i=1; $forced = MYSQL_FETCH_ARRAY($queryforced); $i++) {
        $forcedupdated .= "<a href='".$florensia->outlink(array('characterdetails', $forced['charname']))."'>".$florensia->escape($forced['charname'])."</a><br />";
        if ($i>=15) $forcedupdated .= "...";
    }
    if (!$forcedupdated) $forcedupdated = $flolang->character_api_notice_forcedupdatechars_noentry;
}

$faqs = array(
    'de'=>5604,
    'en'=>5605,
    'fr'=>5610,
    'es'=>5624,
    'pt'=>5714
);

foreach($faqs as $lang => $tid) {
    $tid = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT tid, subject FROM forum_threads WHERE tid={$tid}"));
    if (!$tid) continue;
    $faqlist .= "<div><img src='{$florensia->layer_rel}/flags/png/".$flolang->lang[$lang]->flagid.".png' alt='".$flolang->lang[$lang]->languagename."' title='".$flolang->lang[$lang]->languagename."' border='0'> - <a href='{$florensia->forumurl}/thread-{$tid['tid']}.html' target='_blank'>{$tid['subject']}</a></div>";
}

$content = "
    <div class='small'>
        {$running}
        <div style='height:10px;'></div>
        <div class='bordered' style='float:right; width:200px; padding:6px; margin:0px 0px 10px 10px;'>
            <b>{$flolang->character_api_title_forcedupdatechars}</b><br />
            {$forcedupdated}
        </div>
        {$faqlist}
    </div>
        
";
$florensia->output_page($content);

?>
