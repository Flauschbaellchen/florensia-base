<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$florensia->sitetitle("Downloads");

if ($_GET['downloadid']) {
	$downloadid = intval($_GET['downloadid']);
	/*
        MYSQL_QUERY("DELETE FROM downloadip WHERE time<".bcsub(date("U"), 3600));
	$downloadip = MYSQL_QUERY("SELECT ip FROM downloadip WHERE ip='".getenv("REMOTE_ADDR")."' AND downloadid='$downloadid' LIMIT 1");

	if (MYSQL_NUM_ROWS($downloadip)!=0){
		$content = "
		<div align='center' class='warning'>Du ha&szlig;t diese Datei bereits angefordert und bist f&uuml;r eine Stunde gesperrt.<br />
		<a href='{$mwf->root}/downloads.php'>zur&uuml;ck zum Downloadindex</a></div>";
		$mwf->output_page($content);
	}
	else {
          */
		$querydownload = MYSQL_QUERY("SELECT filesource, outmax, outgo FROM flobase_download WHERE id='$downloadid' LIMIT 1");
		if ($download = MYSQL_FETCH_ARRAY($querydownload)) {
			if ( $download['outgo']>=$download['outmax'] && $download['outmax']!=0) {
				$content = "
				<div align='center' class='warning'>Downloadlimit already reached.<br />
				<a href='".$florensia->outlink(array("downloads"))."'>Back to Downloadoverview</a></div>";
				$florensia->output_page($content);
			} elseif (!is_file($florensia->downloads_abs."/".$download['filesource'])) {
				$content = "
				<div align='center' class='warning'>File cannot be found. Please contact an administrator.<br />
				<a href='".$florensia->outlink(array("downloads"))."'>Back to Downloadoverview</a></div>";
				$florensia->output_page($content); 
                        }
			else {
				//MYSQL_QUERY("INSERT INTO downloadip (downloadid, ip, time) VALUES('$downloadid', '".getenv("REMOTE_ADDR")."', '".date("U")."')");
				MYSQL_QUERY("UPDATE flobase_download SET outgo = outgo+1 WHERE id='$downloadid'");
				clearstatcache();
				//header("Content-type: application/octet-stream");
				header("Content-type: application/x-bittorrent");
				header("Content-Length: ".filesize($florensia->downloads_abs."/".$download['filesource']));
				header("Content-Disposition: attachment; filename=".$download['filesource']);
				readfile($florensia->downloads_abs."/".$download['filesource']);
				exit;
			}
		}
		else { 
			$content = "
			<div align='center' class='warning'>No such DownloadID.<br />
			<a href='".$florensia->outlink(array("downloads"))."'>Back to Downloadoverview</a></div>";
			$florensia->output_page($content);
		}
	//}

}
else {
	$querydownloads = MYSQL_QUERY("SELECT filesource, timestamp, id, filesize, outgo, outmax FROM flobase_download ORDER BY timestamp DESC");
	while ($downloads = MYSQL_FETCH_ARRAY($querydownloads)) {
                $downloads['filesource'] = $florensia->escape($downloads['filesource']);
		if ($downloads['outgo']<$downloads['outmax'] OR $downloads['outmax']==0) $downloads['filesource'] = "<a href='".$florensia->outlink(array("downloads"), array("downloadid"=>$downloads['id']))."'>{$downloads['filesource']}</a>";
                if (!$downloads['outmax']) $downloads['outmax'] = "unlimited";
		$content .= "
			<div class='shortinfo_".$florensia->change()."' style='margin-bottom:15px;'>
				<table style='width:100%;'>
					<tr>
						<td style='font-weight:bold; vertical-align:top;'>{$downloads['filesource']}</td>
					</tr>
					<tr><td class='small'>Downloads: {$downloads['outgo']}/{$downloads['outmax']}</td></tr>
					<tr><td class='small'>Added: ".Date("m.d.y",$downloads['timestamp'])."</td></tr>
				</table>
			</div>
		";
	}
	if (!$content) $content = "<div style='text-align:center;' class='subtitle'>No downloads found.</div>";
	$florensia->output_page($content);
}

?>
