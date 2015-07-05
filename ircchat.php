<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("ircchat");

if (!$_POST['do_chat_java'] && !$_POST['do_chat_javascript']) {
	$channellisttmp = array();
	foreach ($flolang->lang as $langkey => $langname) {
		if (!$flolang->lang[$langkey]->ircchannel || in_array($flolang->lang[$langkey]->ircchannel, $channellisttmp)) continue;
			if ($flolang->language == $langkey) $selected = "selected='selected'";
			else unset($selected);
			$channellist .= "<option value='".$flolang->lang[$langkey]->ircchannel."' $selected>".$flolang->lang[$langkey]->ircchannel."</option>";
			$channellisttmp[] = $flolang->lang[$langkey]->ircchannel;
	}
	$content = "
		<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
			<div class='subtitle small' style='font-weight:normal; padding:7px;'>{$flolang->ircchat_intro}</div>
			<div style='margin-top:10px; margin-bottom:20px;'>

				<table style='margin:auto;'>
					<tr><td>Nickname:</td><td><input type='text' name='irc_nickname' maxlength='30' style='width:150px;'></td></tr>
					<tr><td>Channel:</td>
						<td><select name='ircchannel'>
							<option value='#florensia-base'>#florensia-base</option>
							$channellist
						</select>
						</td>
					</tr>
					<tr><td colspan='2'><input type='Submit' name='do_chat_java' value='Chat! (Java)'> - <input type='Submit' name='do_chat_javascript' value='Chat! (JavaScript)'></td></tr>
				</table>
			</div>
		</form>
	";
	$florensia->sitetitle("IRC Chat");
	$florensia->output_page($content);
}
else {
	if (!preg_match('/^[a-z0-9-_\|]+$/i', $_POST['irc_nickname'])) $_POST['irc_nickname'] = "FloBase-User..";
	if (!preg_match('/^#[a-z.-_]+$/i', $_POST['ircchannel'])) $_POST['ircchannel']="#florensia-base";
	
	MYSQL_QUERY("INSERT INTO flobase_log_irc (timestamp, userid, ip, nickname, channel) VALUES(".date("U").", {$flouser->userid}, '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."', '".mysql_real_escape_string($_POST['irc_nickname'])."', '".mysql_real_escape_string($_POST['ircchannel'])."')");
	
	if ($_POST['do_chat_javascript']) {
		$content .= "
			<iframe class='subtitle' src='http://webchat.euirc.net?nick=".$florensia->escape($_POST['irc_nickname'])."&channels=".$florensia->escape($_POST['ircchannel'])."' style='width:100%; height:500px; border:none;'></iframe>
		";
	} else {
	$content .= '
	<applet code="IRCApplet.class" archive="irc.jar,pixx.jar" width="100%" height="600" codebase="'.$florensia->root.'/pjirc">
		<param name="CABINETS" value="irc.cab,securedirc.cab,pixx.cab">
		<param name="language" value="english">
		<param name="host" value="irc.euirc.net">
		<param name="gui" value="pixx">
		<param name="port" value="6669">
		<param name="quitmessage" value="Visit: www.florensia-base.com | #florensia-base">
		<param name="pixx:timestamp" value="true">
		<param name="pixx:highlight" value="true">
		<param name="pixx:highlightnick" value="true">
		<param name="pixx:nickfield" value="true"> 
		<param name="style:backgroundimage" value="true">
		<!--<param name="style:backgroundimage1" value="none+Channel all 771 irc/img/irc_chat.gif">
		<param name="style:backgroundimage2" value="none+Query all 771 irc/img/irc_chat.gif">
		<param name="helppage" value="http://www.penya.de/forum/forumdisplay.php?fid=25">-->
		<param name="automaticqueries" value="true">
		<param name="showabout" value="false">
		<param name="autoconnection" value="true">
		
		<param name="coding" value="2">
		<param name="languageencoding" value="Unicode">

		<param Name="authorizedcommandlist" value="all-raw-quote-list">

		
		<param name="nick" value="'.$florensia->escape($_POST['irc_nickname']).'">
		<param name="alternatenick" value="'.$florensia->escape($_POST['irc_nickname']).'??">
		<param name="name" value="florensia-base.com">
		<param name="userid" value="flobase">
		<param name="command1" value="/join '.$florensia->escape($_POST['ircchannel']).'">
	</applet>';
	}
//<param name="authorizedjoinlist" value="none+'.str_replace('-', '\-', $_POST['ircchannel']).'+#florensia+#florensia.de+#florensia.it+#florensia.tr+#florensia.es+#florensia.fr+#florensia.pl+#florensia.ru+#florensia\-base+#loco">
	$florensia->sitetitle("IRC Chat");
	$florensia->output_page($content, array('blanklinks'=>1));
}



?>
