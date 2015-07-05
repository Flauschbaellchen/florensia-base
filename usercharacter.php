<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("statistic,character,guild");
$userid = intval($_GET['userid']);

if (!$userid OR !($userid==$flouser->userid OR $flouser->get_permission("character", "moderate"))) $florensia->output_page($flouser->noaccess());

/*
if (!$userid OR !($user = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT username, uid, flobase_characterkey FROM forum_users WHERE uid='{$userid}'")))) {
	$content = "<div class='warning' style='text-align:center;'>No such user</div>";
} else {
*/

        $tabbar['characterlist'] = array("anchor"=>"characterlist", "name"=>$flolang->character_userprofile_overview_verifiedlist_title, "desc"=>false);
        $tabbar['requests'] = array("anchor"=>"requests", "name"=>$flolang->character_userprofile_overview_requestlist_title, "desc"=>false);


$characterlist = "";
$querycharacter = MYSQL_QUERY("SELECT * FROM flobase_character_data, flobase_character WHERE ownerid='{$userid}' AND flobase_character_data.characterid=flobase_character.characterid ORDER BY charname");
while ($character = MYSQL_FETCH_ASSOC($querycharacter)) {
        $character = new class_character($character);
	
                if ($character->data['gender']=="m") $gender = "<img src='{$florensia->layer_rel}/gender_male.gif' border='0' alt='male' style='height:12px;'>";
                else  $gender = "<img src='{$florensia->layer_rel}/gender_female.gif' border='0' alt='female' style='height:12px;'>";

                $server = "<a href='{$florensia->root}/statistics/".$florensia->escape($character->data['server'])."'>".$florensia->escape($character->data['server'])."</a>";
                if ($character->data['guildid']) $guild = "<a href='".$florensia->outlink(array("guilddetails", $character->data['guildid'], $character->data['server'], $character->data['guild']))."'>".$florensia->escape($character->data['guild'])."</a>";
                elseif ($character->data['guild']) $guild = $florensia->escape($character->data['guild']);
                else unset($guild);
                
                if ($guild && $character->data['guildgrade']) $guild .= " ".class_character::guildgrade($character->data['guildgrade']);
												   
	$characterlist .= "
	            <div class='small shortinfo_".$florensia->change()."'>
                        <table style='width:100%'><tr>
                            <td style='width:50px; text-align:right;'>".intval($character->data['levelland'])." <img src='{$florensia->layer_rel}/land.gif' style='height:11px;' alt='Land'></td>
                            <td style='width:50px; padding-right:10px; text-align:right;'>".intval($character->data['levelsea'])." <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;' alt='Sea'></td>
                            <td style='width:20px;'>{$guildgrade}</td>
                            <td>$gender ".$character->get_link()."</td>
                            <td style='width:160px'>".$florensia->escape($character->data['jobclass'])."</td>
                            <td style='width:150px'>{$guild}</td>
                            <td style='width:90px'>{$server}</td>
                            <td style='text-align:right; padding-right:3px; width:130px'>".$flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$character->data['lastupdate'], "m"))."</td>
                        </tr></table>
                    </div>";
}
if (!strlen($characterlist)) {
	$characterlist = "<div class='small shortinfo_".$florensia->change()."' style='text-align:center;'>{$flolang->character_userprofile_overview_verifiedlist_empty}</div>";
}

$qr = MYSQL_QUERY("SELECT charname, timestamp, accepted, moderated, comment FROM flobase_character_verification as v, flobase_character as c WHERE userid='{$userid}' AND v.characterid=c.characterid ORDER BY timestamp DESC");
unset($requestlist);
while ($r = MYSQL_FETCH_ARRAY($qr)) {
	list($moduserid, $modtimestamp) = explode("-", $r['moderated']);
	unset($status, $moderated, $comment);
	switch(intval($r['accepted'])) {
		case -1: {
			$status = $flolang->character_userprofile_overview_requestlist_pending;
			break;
		}
		case 0: {
			$c = "-";
			if (preg_match("/^[a-z]+$/i", $r['comment'])) eval("\$c = \$flolang->character_api_verify_moderate_error_{$r['comment']};");
			$status = "<span style='color:#FF0000;'>{$flolang->character_userprofile_overview_requestlist_denied}</span>";
			$moderated = "(".$flouserdata->get_username($moduserid).")";
			$comment = "<br />".$flolang->sprintf($flolang->character_userprofile_overview_requestlist_denied_reason, $c);
			break;
		}
		case 1: {
			$status = "<span style='color:#00EC10;'>{$flolang->character_userprofile_overview_requestlist_accepted}</span>";
			$moderated = "(".$flouserdata->get_username($moduserid).")";
			break;
		}
	}
	$requestlist .= "
		<div>
			<table class='shortinfo_".$florensia->change()." small' style='width:100%'>
				<tr><td><a href='".$florensia->outlink(array("characterdetails", $r['charname']))."'>".$florensia->escape($r['charname'])."</a>, ".$flolang->sprintf($flolang->character_userprofile_overview_requestlist_requesttimestamp, date("m.d.y H:i", $r['timestamp']))."</td></tr>
				<tr><td style='padding-left:10px;'>{$status} {$moderated} {$comment}</td></tr>
			</table>
		</div>";
}
if (!$requestlist) $requestlist = "<div class='small shortinfo_".$florensia->change()."' style='text-align:center;'>{$flolang->character_userprofile_overview_requestlist_empty}</div>";


	$tabbar = $florensia->tabbar($tabbar);
	$content = "
		<div style='margin-bottom:5px;' class='subtitle'>".$flouserdata->get_username($userid)." &gt; {$flolang->character_userprofile_overview_pagetitle}</div>
		<div style='margin-top:10px;'>{$tabbar['tabbar']}</div>
		<a name='characterlist'></a>
		<div name='characterlist'>
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
			{$characterlist}
		</div>
		<a name='requests'></a>
		<div name='requests'>
			{$requestlist}
		</div>
		{$tabbar['jscript']}
	";




$florensia->sitetitle($flouserdata->get_username($userid, array('rawoutput'=>1)));
$florensia->sitetitle("Characteroverview");
$florensia->output_page($content);


?>