<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("watch_log")) { $florensia->output_page($flouser->noaccess()); }

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("CharCrawler Log");

if (preg_match("/^([0-9]{4}).([0-9]{2})$/", $_GET['log'], $predefinedfile)) {
	if (is_file("{$florensia->charapi}/ranking.{$predefinedfile[1]}.{$predefinedfile[2]}")) {
		$predefinedfile = "{$predefinedfile[1]}.{$predefinedfile[2]}";
	} else $predefinedfile = false;
} else $predefinedfile = false;


$logs = "<select name='log'>";
foreach (scandir($florensia->charapi, 1) as $file) {
	if (!preg_match("/^ranking\.([0-9]{4})\.([0-9]{2})$/", $file, $filename)) continue;
	if (!$predefinedfile) $predefinedfile = "{$filename[1]}.{$filename[2]}";
	if ($predefinedfile=="{$filename[1]}.{$filename[2]}") $selected="selected='selected'";
	else unset($selected);
	$logs .= "<option value='{$filename[1]}.{$filename[2]}' $selected>{$filename[1]}-{$filename[2]}</option>";
}
$logs .= "</select>
<input class='quicksubmit' type='submit' value=''>";


$logcontent = nl2br($florensia->escape(file_get_contents("{$florensia->charapi}/ranking.{$predefinedfile}")));

$content = "
<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; CharCrawler Log</div>
<form action='{$florensia->root}/admincharcrawlerlog.php' method='GET'>
	$running
	<div class='subtitle' style='margin-top:7px; margin-bottom:15px;'>Logfile: $logs</div>
	<div class='bordered small'>$logcontent</div>
</form>";
$florensia->output_page($content);
?>