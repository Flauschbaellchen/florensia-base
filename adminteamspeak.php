<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("access_admincp") OR !$flouser->get_permission("teamspeak")) { $florensia->output_page($flouser->noaccess()); }


if ($flouser->get_permission("teamspeak", "moderate")) {
	if (isset($_POST['do_remove'])) {
		foreach($_POST as $postkey => $postvalue) {
			if (preg_match("/remove_[0-9]+/", $postkey) && $postkey === "remove_{$postvalue}") {
				MYSQL_QUERY("DELETE FROM flobase_teamspeak WHERE guildid='{$postvalue}'");
				if (MYSQL_AFFECTED_ROWS()) {
					$florensia->notice("Guild with ID {$postvalue} was successfully removed.", "successful");
					$flolog->add("teamspeak:guild:delete", "{user:{$flouser->userid}} removed {guild:{$postvalue}}");
				}
				else $florensia->notice("Guild with ID {$postvalue} does not exists in this list.", "warning");
			}
		}
	} elseif (isset($_POST['do_add'])) {
		if (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT guildid FROM flobase_guild WHERE guildid='".intval($_POST['guildid'])."'"))) {
			if (MYSQL_QUERY("INSERT IGNORE INTO flobase_teamspeak (guildid, timestamp, creator) VALUES('".intval($_POST['guildid'])."', '".date("U")."', '{$flouser->userid}')")) {
				$florensia->notice("Guild with ID ".intval($_POST['guildid'])." was successfully added.", "successful");
				$flolog->add("teamspeak:guild:add", "{user:{$flouser->userid}} added {guild:".intval($_POST['guildid'])."}");
			} else $florensia->notice("An error occurred while adding guild with ID ".intval($_POST['guildid'])." to our list.", "warning");
		} else $florensia->notice("Guild with ID ".intval($_POST['guildid'])." does not exists.", "warning");
	}
}

$queryentry = MYSQL_QUERY("SELECT ts.creator, ts.timestamp, g.guildname, g.guildid, g.server, g.memberamount, c.charname, d.guildgrade, u.uid, u.email
FROM flobase_teamspeak AS ts
INNER JOIN flobase_guild AS g ON ( g.guildid = ts.guildid )
LEFT JOIN (
flobase_character_data AS d
INNER JOIN flobase_character AS c ON ( d.characterid = c.characterid AND (guildgrade='5' OR ownerid!='0'))
LEFT JOIN forum_users AS u ON ( u.uid = d.ownerid )
) ON ( ts.guildid = d.guildid ) ORDER BY g.guildname, d.guildgrade DESC"); //hell ya! - you know what I mean, don't you!?

$tmpguild = 0;
while ($entry = MYSQL_FETCH_ARRAY($queryentry)) {
	$linkclass = "";
	if (!$entry['memberamount']) $linkclass = "class='archiv'";
	$guildlink = "<a {$linkclass} href='".$florensia->outlink(array("guilddetails", $entry['guildid'], $entry['server'], $entry['guildname']))."'>".$florensia->escape($entry['guildname'])."</a>";
	
	if ($tmpguild!=$entry['guildid']) {
		$bg = ($bg=="background-color:#396087;") ? "background-color:#496f96;" : "background-color:#396087;";
		$removecheckbox = "<input type='checkbox' name='remove_{$entry['guildid']}' value='{$entry['guildid']}' style='padding:0px; margin:0px;'>";
		$addedby = $flouserdata->get_username($entry['creator']);
		$addeddate = date("m.d.y", $entry['timestamp']);
		$tmpguild=$entry['guildid'];
	} else {
		unset($guildlink, $entry['server'], $entry['memberamount'], $removecheckbox, $addedby, $addeddate);
	}
	
	$username = $entry['uid'] ? $flouserdata->get_username($entry['uid']) : "";
	if (!$flouser->get_permission("teamspeak", "mail")) unset($entry['email']);
	
	$list .= "
	<tr>
		<td style='{$bg} text-align:right; padding-right:5px;'>{$removecheckbox}</td>
		<td style='{$bg}'>{$guildlink}</td>
		<td style='{$bg}'>".$florensia->escape($entry['server'])."</td>
		<td style='{$bg} text-align:right; padding-right:5px;'>{$entry['memberamount']}</td>
		<td style='{$bg}'>".class_character::guildgrade($entry['guildgrade'])." <a href='".$florensia->outlink(array("characterdetails", $entry['charname']))."'>".$florensia->escape($entry['charname'])."</a></td>
		<td style='{$bg}'>{$username}</td>
		<td style='{$bg}'>".$florensia->escape($entry['email'])."</td>
		<td style='{$bg}'>{$addedby}</td>
		<td style='{$bg} text-align:right; padding-right:5px;'>{$addeddate}</td>
	</tr>	
	";
}

$content = "
<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; Teamspeak</div>
<form action='{$florensia->root}/adminteamspeak.php' method='POST'>
	<table style='width:100%; font-weight:normal;' class='small subtitle'>
		<tr>
			<td style='border-bottom:1px solid; font-weight:bold; width:15px;'></td>
			<td style='border-bottom:1px solid; font-weight:bold;'>Guildname</td>
			<td style='border-bottom:1px solid; font-weight:bold;'>Server</td>
			<td style='border-bottom:1px solid; font-weight:bold; text-align:right; padding-right:5px;'>Member</td>
			<td style='border-bottom:1px solid; font-weight:bold;'>Leader</td>
			<td style='border-bottom:1px solid; font-weight:bold;'>Owner</td>
			<td style='border-bottom:1px solid; font-weight:bold;'>Email</td>
			<td style='border-bottom:1px solid; font-weight:bold;'>Added by</td>
			<td style='border-bottom:1px solid; font-weight:bold; text-align:right; padding-right:5px;'>Created</td>
		</tr>
		{$list}
	</table>
	<div style='float:right; padding:3px; padding-top:5px;'><input type='submit' name='do_remove' value='Remove selected'></div>
	<div class='subtitle small' style='padding:2px;'>
			<input type='text' name='guildid'>
			<input type='submit' name='do_add' value='Add GuildID to list'>
	</div>
</form>
";

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Teamspeak");
$florensia->output_page($content);
?>