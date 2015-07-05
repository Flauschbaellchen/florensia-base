<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("watch_log")) { $florensia->output_page($flouser->noaccess()); }

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Log");

if (!isset($_GET['timestamp']) && !isset($_GET['section']) && !isset($_GET['currentuser']) && !isset($_GET['currentip']) && !isset($_GET['logvalue']) && !isset($_GET['flagged'])) $_GET['timestamp'] = 7;

$timespanpreselected[$_GET['timestamp']] = " selected='selected'";
$timespan = "<option value='0'>-</option>";
for ($i=1; $i<=30; $i++) {
	$timespan .= "<option value='$i'{$timespanpreselected[$i]}>".$flolang->sprintf($flolang->market_quickform_timespan, $i)."</option>"; 
	if ($i>=7) $i=$i+6;
}
if ($_GET['flagged']) $checkedflagged = "checked='checked'";
else unset($checkedflagged);

$options = "
<div class='bordered' style='padding:10px; margin-bottom:10px; min-height:190px;'>
	<form action='".$florensia->root."/getposted.php' method='POST'>
		<table>
			<tr><td style='width:150px;'>Timeline:</td><td><select name='timestamp'>$timespan</select></td></tr>
			<tr><td>Section:</td><td><input type='text' name='section' value='".$florensia->escape($_GET['section'])."' style='width:300px;'></td></tr>
			<tr><td>Userid:</td><td><input type='text' name='currentuser' value='".$florensia->escape($_GET['currentuser'])."' style='width:300px;'></td></tr>
			<tr><td>IP-Adress:</td><td><input type='text' name='currentip' value='".$florensia->escape($_GET['currentip'])."' style='width:300px;'></td></tr>
			<tr><td>Message:</td><td><input type='text' name='logvalue' value='".$florensia->escape($_GET['logvalue'])."' style='width:300px;'></td></tr>
			<tr><td>Only flagged entries:</td><td><input type='checkbox' name='flagged' value='1' style='margin:1px;' $checkedflagged></td></tr>
			<tr><td></td><td><input type='submit' name='logsearch' value='Search'><input type='hidden' name='getposted' value='adminlog'></td></tr>
		</table>	
	</form>
</div>
";

$linkoption = array();
$timestamp = intval($_GET['timestamp']);
if ($timestamp) {
	$dbwhere[] = "timestamp>='".bcsub(date("U"),$timestamp*24*60*60)."'";
	$linkoption['timestamp'] = $timestamp;
}
if (strlen($_GET['section'])) {
	$dbwhere[] = "section LIKE '%".mysql_real_escape_string($_GET['section'])."%'";
	$linkoption['section'] = $_GET['section'];
}
if (strlen($_GET['currentuser'])) {
	$dbwhere[] = "currentuser='".intval($_GET['currentuser'])."'";
	$linkoption['currentuser'] = $_GET['currentuser'];
}
if (strlen($_GET['currentip'])) {
	$dbwhere[] = "currentip LIKE '%".mysql_real_escape_string($_GET['currentip'])."%'";
	$linkoption['currentip'] = $_GET['currentip'];
}
if (strlen($_GET['logvalue'])) {
	$dbwhere[] = "logvalue LIKE '%".mysql_real_escape_string($_GET['logvalue'])."%'";
	$linkoption['logvalue'] = $_GET['logvalue'];
}
if (intval($_GET['flagged'])) {
	$dbwhere[] = "flagged='1'";
	$linkoption['flagged'] = 1;
}
if ($dbwhere) $dbwhere = "WHERE ".join(" AND ", $dbwhere);


if (intval($_GET['page'])) $startpage = $_GET['page']*50-50;
else $startpage = 0;

$querylog = MYSQL_QUERY("SELECT SQL_CALC_FOUND_ROWS id, section, timestamp, currentuser, currentip, logvalue, flagged FROM flobase_log {$dbwhere} ORDER BY timestamp DESC LIMIT {$startpage},50");
list($entries) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT FOUND_ROWS()"));
$pageselect = $florensia->pageselect($entries, array("adminlog"), $linkoption, 50);
while ($log = MYSQL_FETCH_ARRAY($querylog)) {
	//change flags?
	if ($_POST['do_adminlog_flag'] && $_POST['adminlog_flagged_'.$log['id']] && !$log['flagged']) {
		$dbaddflag[] = "id='{$log['id']}'";
		$dbaddflagids[] = "{log:{$log['id']}}";
		$log['flagged'] = 1;
	}
	elseif ($_POST['do_adminlog_flag'] && !$_POST['adminlog_flagged_'.$log['id']] && $log['flagged']) {
		$dbremoveflag[] = "id='{$log['id']}'";
		$dbremoveflagids[] = "{log:{$log['id']}}";
		$log['flagged'] = 0;
	}
	
	if ($log['flagged']) { $checkedflagged = "checked='checked'"; $flaggedclass="warning"; }
	else { $flaggedclass="shortinfo_".$florensia->change(); unset($checkedflagged); }
	
	$loglist .= "
	<div style='float:right; width:15px;'><input type='checkbox' name='adminlog_flagged_{$log['id']}' value='1' $checkedflagged></div>
	<div class='small $flaggedclass' style='margin-right:20px;'>
		<table style='width:100%'>
		<tr>
			<td rowspan='2' style='width:140px; vertical-align:top;'><span style='font-weight:bold;'>".date("m.d.y H:i", $log['timestamp'])."</span><br />".$flouserdata->get_username($log['currentuser'])."</td>
			<td style='padding-left:2px; padding-right:2px; width:150px; vertical-align:top; font-weight:bold;'>{$log['section']}</td>
			<td style='padding-left:2px; font-weight:bold; text-align:right;'>{$log['currentip']}</td>
		</tr>
		<tr><td colspan='2' style='padding-left:2px;'>".$flolog->parse($log['logvalue'])."</td></tr>
		</table>
	</div>
	";
}
if ($loglist) {
	$loglist = "
		<form action='".$florensia->escape($florensia->request_uri())."' method='post'>
		<div style='text-align:right;'><input type='submit' name='do_adminlog_flag' value='Update flags'></div>
		{$loglist}
		<div style='text-align:right;'><input type='submit' name='do_adminlog_flag' value='Update flags'></div>
		</form>
	";	
}


if (count($dbaddflag)) {
	if (!MYSQL_QUERY("UPDATE flobase_log SET flagged=1 WHERE ".join(" OR ", $dbaddflag))) {
		$flolog->add("error:log", "MySQL-Update-Error while add flagged status on ".join(", ", $dbaddflagids));
	} else {
		$flolog->add("log:flag", "{user:{$flouser->userid}} set flagged status on ".join(", ", $dbaddflagids));
	}
}
if (count($dbremoveflag)) {
	if (!MYSQL_QUERY("UPDATE flobase_log SET flagged=0 WHERE ".join(" OR ", $dbremoveflag))) {
		$flolog->add("error:log", "MySQL-Update-Error while remove flagged status on ".join(", ", $dbremoveflagids));
	} else {
		$flolog->add("log:flag", "{user:{$flouser->userid}} removed flagged status on ".join(", ", $dbremoveflagids));
	}
}


$content = "
	<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; Log</div>
	<div class='bordered small'>
		<b>Searching:</b><br />
		For searching use the expressions displayed below on the right side and the IDs/numbers in brackets of each entry.<br />
		Because in this way it is much easier to keep all names and links synched with the database by parsing them afterwards.<br />
		You don't need to search always for the full expression, e.g. &quot;{npc:mtmaidne1}&quot;. Mostly you will be fine with &quot;mtmaidne1&quot; or &quot;:mtmaidne1&quot;<br />
		However sometimes it can be useful...<br />
		<br />
		<b>Flags:</b><br />
		Entries which shows something negative should be flagged, e.g. an user deliberately added wrong coordinates to the database.<br />
		This might be useful to find users which abuse the database.
	</div>
	<div class='small' style='float:right; text-align:right; margin:10px;'>
		<b>Used expressions</b><br />
		{user:<i>userid</i>}<br />
		{npc:<i>npcid</i>}<br />
		{item:<i>itemid</i>}<br />
		{quest:<i>questid</i>}<br />
		{map:<i>mapid</i>}<br />
		{usernote:<i>noteid</i>}<br />
		{guide:<i>guideid</i>}<br />
		{gallery:<i>galleryid</i>}<br />
		{character:<i>charactername</i>}<br />
		{characterid:<i>characterid</i>}<br />
		{characterverification:<i>requestid</i>}<br />
		{log:<i>logid</i>}<br />
		{timestamp:<i>unixtime</i>}
	</div>
	{$options}
	<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
	{$loglist}
	<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
";
$florensia->output_page($content);
?>
