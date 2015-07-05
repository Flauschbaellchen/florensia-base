<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
$flolang->load("sealbreak");

	$querysealoption = MYSQL_QUERY("SELECT sealid, sealoption FROM server_seal_option");
	for ($i=0; $sealoption = MYSQL_FETCH_ARRAY($querysealoption); $i++) {
		$querysealtype = MYSQL_QUERY("SELECT name_{$flolang->language} FROM flobase_seal_optionlang WHERE sealid='".$sealoption['sealid']."'");
		$sealtype = MYSQL_FETCH_ARRAY($querysealtype);

		$attributestable[$i]['title'] = "<div class='bordered'>".$sealtype['name_'.$flolang->language]."</div>";
		$line = explode(',',$sealoption['sealoption']);
	
		for ($x=0; $x<=count($line); $x=$x+2) {
			if ($line[$x+1]==0) continue;
			if (!preg_match('/^svop([0-9]+)$/', $line[$x], $effectid)) continue;
			$effectquery = MYSQL_QUERY("SELECT name_".$flolang->language." FROM flobase_item_effect WHERE effectid='".intval($effectid[1])."'");
			$effect = MYSQL_FETCH_ARRAY($effectquery);
			$attributestable[$i]['list']  .= "<tr><td class='small' style='width:150px;'>".$effect['name_'.$flolang->language]."</td><td class='small' style='text-align:right;'>".bcdiv($line[$x+1], 100)."%</td></tr>";
		}
	}

	for ($x=0; $x<=$i; $x=$x+2) {
		$sealoptiontable .= "
			<tr><td style='width:50%;'>".$attributestable[$x]['title']."</td><td style='width:50%;'>".$attributestable[$x+1]['title']."</td></tr>
			<tr><td><table style='border-collapse:0px; border-spacing:0px; padding:0px;'>".$attributestable[$x]['list']."</table></td><td><table style='border-collapse:0px; border-spacing:0px; padding:0px;'>".$attributestable[$x+1]['list']."</table></td></tr>
		";
	}

	$seallistquery = MYSQL_QUERY("SELECT * FROM server_seal_breakcost");
	while ($seallist = MYSQL_FETCH_ARRAY($seallistquery)) {
		unset($needitem, $comma,$viewcount);
		for ($i=1; $i<=5; $i++) {
			if ($seallist['needitem'.$i]!="#") {
				$needitem .= $comma.$seallist['needitem'.$i.'count']." <a href='".$florensia->outlink(array("itemdetails", $seallist['needitem'.$i], $stringtable->get_string($seallist['needitem'.$i])))."'>".$stringtable->get_string($seallist['needitem'.$i], array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
				$comma=", ";
				$viewcount++;
				if ($viewcount==3) { $viewcount=0; $needitem.="<br />"; unset($comma);}
			}
		}
		$costtable .= "<div class='shortinfo_".$florensia->change()." small'><table style='width:100%;'><tr><td style='width:60px;'><b>Level: ".$seallist['itemlevel']."</b></td><td style='width:70px; text-align:right; padding-right:20px;'>".number_format($seallist['cost'], 0, '.', ',')." Gelt</td><td>$needitem</td></tr></table></div>";

	}

	$content = "
		<div style='margin-bottom:5px;' class='subtitle'>Tools &gt; Sealbreaking</div>	
		<div class='subtitle'>{$flolang->sealbonusattributes}</div>
		<div><table style='width:100%; border-collapse:0px; border-spacing:0px; padding:0px;'>$sealoptiontable</table></div>
		<div style='margin-top:10px;' class='subtitle'>{$flolang->sealbreakcost}</div>
		$costtable

	";

	$florensia->sitetitle("Sealbreaking");
	$florensia->output_page($content);

?>