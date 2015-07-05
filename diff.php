<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$florensia->sitetitle("Game Version Differences");
$flolang->load("versiondifferences");

require_once("./class_diff.php");

	$difffiles = scandir("./diffs/", 1);
	$selected_difffile[$_GET['diff']] = "selected='selected'";
	foreach ($difffiles as $difffile) {
		if (!preg_match('/~$/', $difffile) && preg_match('/^sqldiff_([0-9]{10})\.php$/', $difffile, $timestamp)) {
			if (!isset($_GET['diff'])) $_GET['diff']  = $timestamp[1];
			$files .= "<option value='".$timestamp[1]."' ".$selected_difffile[$timestamp[1]].">".date("m.d.Y", $timestamp[1])."</option>";
		}
	}

	$selectform = "
		<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='GET'>
			<div class='small bordered' style='margin-bottom:10px;'>
			{$flolang->diff_select_title} 
				<select name='diff'>{$files}</select>
				&nbsp;<input class='quicksubmit' type='submit' value=''>
			</div>
		</form>
	";

	$selected_file = "{$florensia->root_abs}/diffs/sqldiff_".$_GET['diff'].".php";
	if (is_file($selected_file)) {
		$sqldiff = new class_cache;
		$sqldiff->load_cachefile($selected_file);
		if ($sqldiff->get_cache("timestamp")) {
				foreach ($sqldiff->get_cache("sql", FALSE) as $mainentry => $mainvalue) {
					foreach ($sqldiff->get_cache("sql,{$mainentry}", FALSE) as $subentry => $subvalue) {
						$diff->create_diff_overview($subentry, $subvalue);
					}
				}
				$content = "
					<div>
						<table style='width:100%;'><tr>
							<td style='width:33%' class='diff_new'>{$flolang->diff_legend_newentries}</td>
							<td style='width:33%;' class='diff_changed'>{$flolang->diff_legend_changedentries}</td>
							<td class='diff_deleted'>{$flolang->diff_legend_removedentries}</td>
						</tr></table>
					</div>
					<div class='subtitle' style='text-align:center; margin-bottom:5px; margin-top:10px;'>".date("m.d.Y", $sqldiff->get_cache("timestamp"))."</div>
					".$diff->watch_diff_overview();
		}
		else {$florensia->notice($flolang->diff_error_loadfile, "warning"); }
	}
	else { $florensia->notice($flolang->diff_error_notfound, "warning");}

$content = $selectform.$content;

$florensia->output_page($content);

?>