<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$florensia->sitetitle("Guilds");
$florensia->sitetitle("Blackboard");

$flolang->load("guild");

$tmpguild = 0;
$guildquery = MYSQL_QUERY("SELECT g.guildid, g.server, g.guildname, g.misc_language, g.misc_wanted_secondcharacter, g.misc_wanted_age, g.misc_language, w.searchclass, w.level_land, w.level_sea FROM flobase_guild_wanted as w, flobase_guild as g WHERE w.guildid=g.guildid ORDER BY w.timestamp DESC, g.guildname, w.searchclass");
while ($guild = MYSQL_FETCH_ARRAY($guildquery)) {
    if ($tmpguild!=$guild['guildid']) {
        //new guildentry
        $wantedoverview .= "
        <div>".$florensia->escape($guild['guildname'])."</div>
        ";
        $tmpguild=$guild['guildid'];
    }
    
    $wantedoverview .= "
        <div style='padding-left:30px;'>".$florensia->escape($guild['searchclass'])." - {$guild['level_land']}/{$guild['level_sea']}</div>
    ";
}

$content = "
<div class='subtitle' style='margin-bottom:7px;'>
    Blackboard
</div>
<div class='small'>
    {$wantedoverview}
</div>
";

$florensia->output_page($content);

?>