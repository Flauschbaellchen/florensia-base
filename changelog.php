<?PHP
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$florensia->sitetitle("Changelog");


$stringhistory = "SELECT id, date, changes FROM flobase_versionhistory ORDER BY date DESC, id DESC";

$pageselect = $florensia->pageselect(MYSQL_NUM_ROWS(MYSQL_QUERY($stringhistory)), array("changelog"), array(), 20);
$queryhistory = MYSQL_QUERY($stringhistory." LIMIT ".$pageselect['pagestart'].",20");
while ($history = MYSQL_FETCH_ARRAY($queryhistory)) {
	$historylist .= "
		<div class='shortinfo_".$florensia->change()."' style='margin-bottom:15px;'>
			<table style='width:100%;'>
				<tr><td style='font-weight:bold; border-bottom:1px solid;'>".date("m.d.Y", $history['date'])."</td></tr>
				<tr><td style='height:7px'></td></tr>
				<tr><td class='small'>".$parser->parse_message($history['changes'])."</td></tr>
			</table>
		</div>
	";
}

$content = "
<div class='subtitle' style='text-align:center; margin-bottom:5px;'>Florensia Base Changelog</div>
<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
$historylist
<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
";

$florensia->output_page($content);
?>