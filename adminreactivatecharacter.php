<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("access_admincp") OR !$flouser->get_permission("character", "moderate")) { $florensia->output_page($flouser->noaccess()); }

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Reactivate Character");


if (isset($_POST['charactername'])) {
  if ($character_archive = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM flobase_character_data as d, flobase_character_archiv as a WHERE a.characterid=d.characterid AND a.charname LIKE '".mysql_escape_string($_POST['charactername'])."' ORDER BY a.timestamp DESC LIMIT 1"))) {
    //we have one, so delete a possible active character and replace him
    if ($character_active = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM flobase_character_data as d, flobase_character as c WHERE c.characterid=d.characterid AND c.charname LIKE '".mysql_escape_string($_POST['charactername'])."' LIMIT 1"))) {
      MYSQL_QUERY("DELETE FROM flobase_character_data WHERE characterid = '{$character_active['characterid']}'");
      MYSQL_QUERY("DELETE FROM flobase_character WHERE characterid = '{$character_active['characterid']}'");
      MYSQL_QUERY("DELETE FROM flobase_character_log_general WHERE characterid = '{$character_active['characterid']}'");
      MYSQL_QUERY("DELETE FROM flobase_character_log_guild WHERE characterid = '{$character_active['characterid']}'");
      MYSQL_QUERY("DELETE FROM flobase_character_log_level_land WHERE characterid = '{$character_active['characterid']}'");
      MYSQL_QUERY("DELETE FROM flobase_character_log_level_sea WHERE characterid = '{$character_active['characterid']}'");
      MYSQL_QUERY("DELETE FROM flobase_character_log_banned WHERE characterid = '{$character_active['characterid']}'");
    }
    //move archived character back to business
    
    MYSQL_QUERY("INSERT INTO flobase_character (characterid, charname, lastupdate, updatepriority) VALUES('{$character_archive['characterid']}', '".mysql_real_escape_string($character_archive['charname'])."', '{$character_archive['timestamp']}', '10')");
    MYSQL_QUERY("DELETE FROM flobase_character_archiv WHERE characterid = '{$character_archive['characterid']}'");
    MYSQL_QUERY("DELETE FROM flobase_character_log_guild WHERE characterid = '{$character_archive['characterid']}' AND action='d'");
    MYSQL_QUERY("UPDATE flobase_guild SET forceupdate='1' WHERE guildid='{$character_archive['guildid']}'");
  
    $result = "Character has been reactivated.";
    unset($_POST['charactername']);
  } else {
    $result = "No archived character with this name was found.";
  }
}

$content = "
	<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; Reactivate Character</div>
	<div style='padding:10px; margin-bottom:10px;'>
		<form action='{$florensia->root}/adminreactivatecharacter.php' method='POST'>
			<div>Charactername: <input type='text' name='charactername' value='".$florensia->escape($_POST['charactername'])."' style='width:300px;'></div>
			<div><input type='submit' name='reactivate' value='Reactivate'></div>
		</form>
	</div>

	 <div>
	 {$result}
	 </div>
";
$florensia->output_page($content);
?>
