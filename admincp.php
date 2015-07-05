<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

if (!$flouser->get_permission("access_admincp")) { $florensia->output_page($flouser->noaccess()); }
$florensia->sitetitle("AdminCP");

$notesamount = array();
foreach ($flolang->lang as $langkey => $langname) {
	if (!$flouser->get_permission("mod_usernotes", $langkey)) continue;
	$queryusernotes = MYSQL_QUERY("SELECT id FROM flobase_usernotes WHERE language='{$langkey}' AND moderated=0");
	$amount = MYSQL_NUM_ROWS($queryusernotes);
	if ($amount) $amount = "<span style='color:#FF0000;'>$amount</span>";
	$notesamount[] = "$amount/$langkey";
}


if ($flouser->get_permission("mod_language")) {
	foreach (array_keys($flolang->lang) as $langkey) {
		if (!$flouser->get_permission("mod_language", $langkey)) continue;
		$queryusernotes = MYSQL_QUERY("SELECT varname FROM flobase_languagefiles WHERE lang_{$langkey}_flag=1");
		$amount = MYSQL_NUM_ROWS($queryusernotes);
		if ($amount) $florensia->notice("Language {$langkey}: There are <a href='{$florensia->root}/adminlang.php?lang={$langkey}&amp;new=1'>{$amount} new variable(s)</a> which need to be translated", "warning");
	}
}


$content = "
<div class='subtitle'>AdminCP</div>
<div style='margin-bottom:10px; font-weight:normal;' class='subtitle'>
	<div><a href='{$florensia->root}/adminlang.php'>Language Files</a></div>
	<div><a href='{$florensia->root}/adminstartpage.php'>News (Startpage)</a></div>
	<div><a href='{$florensia->root}/adminmenubar.php?classname=menubar'>Menubar</a></div>
	<div><a href='{$florensia->root}/adminmenubar.php?classname=userbar'>Userbar</a></div>
	<div><a href='{$florensia->root}/adminusernotes.php'>Usernotes Moderation</a> <span class='small'>(".join(" ", $notesamount).")</span></div>
	<div><a href='{$florensia->root}/admincharacterverification.php'>Characterverification</a></div>
	<div><a href='{$florensia->root}/adminreactivatecharacter.php'>Characterreactivation</a></div>
</div>	
<div style='margin-bottom:10px; font-weight:normal;' class='subtitle'>
	<div><a href='{$florensia->root}/adminlog'>FloBase Logfile</a></div>
        <div><a href='{$florensia->root}/admincharapilog.php'>CharAPI Logfile</a></div>
        <div><a href='{$florensia->root}/admincharcrawlerlog.php'>CharCrawler Logfile</a></div>
	<div><a href='{$florensia->root}/admintools/npcflags'>NPC-Flags</a></div>
	<div><a href='{$florensia->root}/admintools/itemflags'>Item-Flags</a> (ToDo)</div>
	<div><a href='{$florensia->root}/admintools/itemflags'>Quest-Flags</a> (ToDo)</div>
	<div><a href='{$florensia->root}/admintools/npcimages'>NPCs without images</a> (ToDo)</div>
	<div><a href='{$florensia->root}/admintools/itemimages'>Items without images</a> (ToDo)</div>
</div>
<div style='margin-bottom:10px; font-weight:normal;' class='subtitle'>
	<div><a href='{$florensia->root}/adminteamspeak.php'>Teamspeak</a></div>
	<div><a href='{$florensia->root}/adminpermission.php'>User-Permissions</a></div>
	<div><a href='{$florensia->root}/adminhistory.php'>Version History</a></div>
</div>";

$florensia->output_page($content);

?>