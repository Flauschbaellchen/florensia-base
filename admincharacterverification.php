<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("access_admincp") OR !$flouser->get_permission("character", "moderate")) { $florensia->output_page($flouser->noaccess()); }

if (intval($_GET['s'])) {
	$verify = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT v.id, userid, charname, levelland, levelsea, guild, forceupdate, updatepriority, lastupdate FROM flobase_character_verification as v, flobase_character as c, flobase_character_data as d WHERE v.id='".intval($_GET['s'])."' AND v.characterid=c.characterid AND v.characterid=d.characterid"));
	if (!$verify) {
		$florensia->notice("No such request.", "warning");
		$florensia->output_page("<div class='bordered small'><a href='{$florensia->root}/admincharacterverification.php'>Back to requestlist.</a></div>");
	}
	$flolang->load("character");
	$requestuser = new class_user($verify['userid']);
	echo "
		<html>
			<head>
				<title>Characterverification - Screenshot</title>
			</head>
			<body>
				<form action='{$florensia->root}/admincharacterverification.php' method='POST'>
					<img src='{$florensia->root}/pictures/characterverification/{$verify['id']}' style='border:0px;'><br />
					<b>Verificationcode: {$requestuser->user['flobase_characterkey']}</b><br />
					<b>Charactername: <a href='".$florensia->outlink(array("characterdetails", $verify['charname']))."' target='_blank'>".$florensia->escape($verify['charname'])."</A></b><br />
					<b>Levels: {$verify['levelland']}/{$verify['levelsea']}</b><br />
                                        <b>Guild: ".$florensia->escape($verify['guild'])."</b><br />
                                        <b>Lastupdate: ".$flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$verify['lastupdate']))." (Priority: {$verify['updatepriority']}, Force: {$verify['forceupdate']})</b><br />
					<input type='hidden' name='requestid' value='{$verify['id']}'>
					<select name='reason'>
						<option value='novisiblekey'>{$flolang->character_api_verify_moderate_error_novisiblekey}</option>
						<option value='incorrectkey'>{$flolang->character_api_verify_moderate_error_incorrectkey}</option>
						<option value='wrongcharacter'>{$flolang->character_api_verify_moderate_error_wrongcharacter}</option>
						<option value='reworkedimage'>{$flolang->character_api_verify_moderate_error_reworkedimage}</option>
					</select> <input type='submit' name='moderate' value='---- Deny ----'> --- <input type='submit' name='moderate' value='Accept'>
				</form>
			</body>
		</html>
	";
	exit;
} elseif ($_POST['moderate'] && intval($_POST['requestid'])) {
	$verify = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT id, userid, v.characterid, charname FROM flobase_character_verification as v, flobase_character as c WHERE id='".intval($_POST['requestid'])."' AND v.characterid=c.characterid"));
	if (!$verify) {
		$florensia->notice("No such request.", "warning");
		$florensia->output_page("<div class='bordered small'><a href='{$florensia->root}/admincharacterverification.php'>Back to requestlist.</a></div>");
	}
	
	if ($_POST['moderate']=="Accept") {
		$requestuser = new class_user($verify['userid']);
		if (MYSQL_QUERY("UPDATE flobase_character_data SET ownerid='{$requestuser->userid}' WHERE characterid='{$verify['characterid']}'")) {
			MYSQL_QUERY("UPDATE flobase_character_verification SET accepted='1', moderated='{$flouser->userid}-".date("U")."' WHERE id='{$verify['id']}'");
			$florensia->notice("Request was successfully accepted", "successful");
			$flolog->add("character:verification:accept", "{user:{$flouser->userid}} accepted {characterverification:{$verify['id']}} of {user:{$verify['userid']}} for {characterid:{$verify['characterid']}}");
		} else {
			$florensia->notice("An error occured while accepting the request.", "successful");
			$flolog->add("error:character", "MySQL-Update-Error while trying to accept {characterverification:{$verify['id']}} of {user:{$verify['userid']}} for {character:{$verify['charname']}}");
		}
	} else {
		MYSQL_QUERY("UPDATE flobase_character_verification SET accepted='0', moderated='{$flouser->userid}-".date("U")."', comment='".mysql_real_escape_string($_POST['reason'])."' WHERE id='{$verify['id']}'");
		$florensia->notice("Request was successfully denied", "successful");
		$flolog->add("character:verification:denied", "{user:{$flouser->userid}} denied {characterverification:{$verify['id']}} of {user:{$verify['userid']}} for {characterid:{$verify['characterid']}} ({$_POST['reason']})");
	}
}

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Characterverification");

$characterquery = MYSQL_QUERY("SELECT id, charname, userid, timestamp FROM flobase_character_verification as v, flobase_character as c WHERE accepted='-1' AND v.characterid=c.characterid ORDER BY timestamp DESC");
while ($character = MYSQL_FETCH_ARRAY($characterquery)) {
	$requestlist .= "<div class='small shortinfo_".$florensia->change()."'>".date("m.d.Y H:i", $character['timestamp'])." - ".$flouserdata->get_username($character['userid'])." requested verification on <a href='".$florensia->outlink(array("characterdetails", $character['charname']))."'>".$florensia->escape($character['charname'])."</a>. [<a href='{$florensia->root}/admincharacterverification.php?s={$character['id']}' target='_blank'>Watch screenshot</a>]</div>";
}
if (!$requestlist) $requestlist = "<div class='small subtitle'>No pending requests found</div>";

$content = "
	<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; Characterverification</div>
	{$requestlist}
";
$florensia->output_page($content);
?>