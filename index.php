<?PHP
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
$flolang->load("index, market, donate");
	$newstemplate = '
	<tr>
		<td style=\'width:70px; vertical-align:top;\'>$dateline</td>
		<td>$news_title</td>
		<td style=\'width:90px; text-align:right; vertical-align:top;\'>$news_replies</td>
		<td style=\'width:100px; text-align:right; vertical-align:top;\'>$news_views</td>
	</tr>
	';
	foreach (explode('|', $flolang->lang[$flolang->language]->startpage) as $startpagevalue) {
		preg_match('/^([0-9]+)-(.+)$/', $startpagevalue, $startpagethread);

		unset($news_temp);
		$queryforumnews = MYSQL_QUERY("SELECT * FROM forum_threads WHERE fid='".intval($startpagethread[1])."' AND visible>-2 ORDER BY dateline DESC LIMIT 5");
		while ($forumnews = MYSQL_FETCH_ARRAY($queryforumnews)) {
			if ($forumnews['dateline']>bcsub(date("U"),60*60*24*4)) $dateline = "<span style='font-weight:bold; color:#6d0601;'>".date("m.d.Y", $forumnews['dateline'])."</span>";
			else $dateline = date("m.d.Y", $forumnews['dateline']);
			$news_title = $flolang->sprintf($flolang->news_title, "<a href='".$florensia->forumurl."/thread-{$forumnews['tid']}.html' target='_blank'>{$forumnews['subject']}</a>", $forumnews['username']);
			$news_replies = $flolang->sprintf($flolang->news_replies, $forumnews['replies']);
			$news_views = $flolang->sprintf($flolang->news_views, $forumnews['views']);
			eval("\$news_temp .= \"$newstemplate\";");
		}
		$news .= "
		<div style='border-bottom:1px #88a9d4 solid; font-weight:bold; margin-top:5px;'>".$startpagethread[2]." <a href='{$florensia->forumurl}/syndication.php?fid=".intval($startpagethread[1])."&limit=15'><img src='{$florensia->layer_rel}/rssfeed.png' border='0' style='height:13px;'></a></div>
		<div>
			<table style='width:100%; border-collapse:0px; border-spacing:0px; padding:0px;' class='small'>
				$news_temp
			<tr>
				<td style='text-align:right' colspan='4'><a href='http://forum.florensia-base.com/forum-".intval($startpagethread[1]).".html' target='_blank'>{$flolang->news_listall}</a></td>
			</tr>
			</table>
		</div>";
	}


//---------- market

	$classusermarket->refresh();
	for ($i=1; $i<=2; $i++) {
		if ($i==1) $exchangetype="sell";
		else $exchangetype="buy";

		$querynewest = MYSQL_QUERY("SELECT id as marketid, itemid FROM flobase_usermarket WHERE exchangetype='$exchangetype' ORDER BY createtime DESC LIMIT 4");
		while ($newest = MYSQL_FETCH_ARRAY($querynewest)) {
			$item = new floclass_item($newest['itemid']);
			$newestlist[$exchangetype] .= "<div class='shortinfo_".$florensia->change()."'>".$item->shortinfo(array("marketid"=>$newest['marketid'], "namemaxlength"=>30))."</div>";
		}
	if (!$newestlist[$exchangetype]) $newestlist[$exchangetype] = "<div class='bordered' style='text-align:center;'>{$flolang->market_noentries}</div>";
	}

	//newest
	$market = "
		<div class='subtitle'style='margin-top:30px;'><a href='".$florensia->outlink(array("market"))."'>{$flolang->market_title_main}</a> &gt; {$flolang->market_title_newest} <a href='{$florensia->root}/market_en.rss'><img src='{$florensia->layer_rel}/rssfeed.png' border='0' style='height:13px;'></a></div>
		<div>
			<table style='width:100%;' class='small'>
				<tr><td class='bordered' style='text-align:center; width:49%; font-weight:bold;'>{$flolang->market_subtitle_sell}</td><td></td><td class='bordered' style='text-align:center; width:49%; font-weight:bold;'>{$flolang->market_subtitle_buy}</td></tr>
				<tr><td colspan='3' style='height:2px;'></td></tr>
				<tr><td>{$newestlist['sell']}</td><td></td><td>{$newestlist['buy']}</td></tr>
			</table>
		</div>
	";


//$florensia->notice("<div class='warning' style='margin-bottom:10px;'>{$flolang->signature_api_failnotice}</div>");

$searchlangtmp = array('fr', 'nl', 'pl', 'it', 'ru');
foreach ($searchlangtmp as $langkey) {
	$searchlang[] = "<img src='{$florensia->layer_rel}/flags/png/".$flolang->lang[$langkey]->flagid.".png' alt='".$flolang->lang[$langkey]->languagename."' title='".$flolang->lang[$langkey]->languagename."' style='border:none; margin-top:3px; margin-bottom:3px;'>";
}
$searchlang[] = "<img src='{$florensia->layer_rel}/flags/png/jp.png' alt='Japanese' title='Japanese' style='border:none; margin-top:3px; margin-bottom:3px;'>";

##### donate-graphic
$dheight = 150;
$dwidth = 20;
$dlastgot = 0;
$dcurrentgot = 0;

//get current goal
list($currentstarttime, $dcurrentgoal) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT starttime, goal FROM flobase_donate_goals WHERE starttime<='".date("U")."' ORDER BY starttime DESC LIMIT 1"));
//get timestamp of last second of last month
$lastmonth = mktime(0, 0, 0, date("m"),1,date("Y"))-1;
//copy the goal to $lastmonth if the currentstarttime includes it, too.
if ($currentstarttime<=$lastmonth) $dlastgoal = $dcurrentgoal;
else list($dlastgoal) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT goal FROM flobase_donate_goals WHERE starttime<='{$lastmonth}' ORDER BY starttime DESC LIMIT 1"));

//get all donators in the current month
$querycurrentdonators = MYSQL_QUERY("SELECT * FROM flobase_donate_donators WHERE timestamp>='".mktime(0, 0, 0, date("m"),1,date("Y"))."' ORDER BY amount DESC");
while ($correntdonators = MYSQL_FETCH_ARRAY($querycurrentdonators)) {
	if (!strlen($correntdonators['name'])) $correntdonators['name'] = $flolang->donate_anonym;
	$dcurrentdonatorlist .= $florensia->escape($correntdonators['name'])." - ".$correntdonators['amount']."&euro;<br />";
	$dcurrentgot += $correntdonators['amount'];
}

//timeline of last month and get donators of it
$startlastmonth = mktime(0, 0, 0, date("m")-1,1,date("Y"));
$dayslastmonth = intval(date("t", $startlastmonth));
$endlastmonth = mktime(23, 59, 59, date("m")-1, $dayslastmonth,date("Y"));
$querylastdonators = MYSQL_QUERY("SELECT * FROM flobase_donate_donators WHERE timestamp>='{$startlastmonth}' AND timestamp<='{$endlastmonth}' ORDER BY amount DESC");
while ($lastdonators = MYSQL_FETCH_ARRAY($querylastdonators)) {
	if (!strlen($lastdonators['name'])) $lastdonators['name'] = $flolang->donate_anonym;
	$dlastdonatorlist .= $florensia->escape($lastdonators['name'])." - ".$lastdonators['amount']."&euro;<br />";
	$dlastgot += $lastdonators['amount'];
}

if ($dlastgot>=$dlastgoal) { $dlastcolor = "#12cf1f"; $dlastgotgraphic = $dlastgoal; }
else { $dlastcolor = "#6d0601"; $dlastgotgraphic = $dlastgot; }

/*/overrun of last month? - add it to current month!
if ($dlastgot>$dlastgoal) {
	$overrun = bcsub($dlastgot, $dlastgoal);
	$dcurrentdonatorlist .= "<span style='color:#c0c0c0;'>{$flolang->donate_lastmonth_title} - {$overrun}&euro;</span>";
	$dcurrentgot += $overrun;
}*/

if ($dcurrentgot>=$dcurrentgoal) { $dcurrentcolor = "#12cf1f"; $dcurrentgotgraphic = $dcurrentgoal; }
else { $dcurrentcolor = "#6d0601"; $dcurrentgotgraphic = $dcurrentgot; }

//always display a graph
if (!$dlastgotgraphic) $dlastgotgraphic = 1;
if (!$dcurrentgotgraphic) $dcurrentgotgraphic = 1;

//<td style='width:33%; font-weight:normal; padding:3px;' class='subtitle'>".$flolang->sprintf($flolang->intro, "Florensia Base", "<a href='http://www.florensia-online.com' target='_blank'>Florensia</a>")."</td>

/*
<table class='small' style='width:100%; margin-bottom:10px; border-spacing:3px;'>
<tr>
	<!--<td style='width:33%; font-weight:normal; padding:3px;' class='subtitle'>".$flolang->sprintf($flolang->language_searching_announce, join(" ", $searchlang), $flouserdata->get_username(1))."</td>-->
	<td style='width:33%; font-weight:bold; padding:30px 10px 10px 10px;' class='subtitle'>
		<img src='{$florensia->layer_rel}/flags/png/us.png' alt='us' title='en' style='border:none; margin-top:3px;'> <a href='http://forum.florensia-base.com/thread-7777.html'>Retirement of Noxx / The closing of FloBase</a><br />
		<br />
		<img src='{$florensia->layer_rel}/flags/png/de.png' alt='de' title='de' style='border:none; margin-top:3px;'> <a href='http://forum.florensia-base.com/thread-7759.html'>R&uuml;cktritt von Noxx / Stilllegung von FloBase</a><br />
		<img src='{$florensia->layer_rel}/flags/png/de.png' alt='de' title='de' style='border:none; margin-top:3px;'> <a href='http://forum.florensia-base.com/thread-7759-post-11662.html#pid11662'>Letzte Email an Burda</a> - <a href='http://forum.florensia-base.com/thread-7759-post-11784.html#pid11784'>&quot;Antwort&quot;</a><br />
		<br />
		<img src='{$florensia->layer_rel}/flags/png/fr.png' alt='fr' title='fr' style='border:none; margin-top:3px;'> <a href='http://forum.florensia-base.com/thread-7875.html'>Noxx prend sa retraite / Fin de FloBase</a><br />
	</td>
	<td style='width:33%; font-weight:normal; padding:3px;' class='subtitle'>
	<div style='min-height:".($dheight-10)."px'>
		<div style='float:left; padding:3px; border:1px #1c3e54 solid; height:{$dheight}px'>
			<div style='background-color:{$dlastcolor}; width:{$dwidth}px; margin-top:".($dheight-$dheight*($dlastgotgraphic/$dlastgoal))."px; height:".($dheight*($dlastgotgraphic/$dlastgoal))."px'></div>
		</div>
		<div style='float:right; padding:3px; border:1px #1c3e54 solid; height:{$dheight}px'>
			<div style='background-color:{$dcurrentcolor}; width:{$dwidth}px; margin-top:".($dheight-$dheight*($dcurrentgotgraphic/$dcurrentgoal))."px; height:".($dheight*($dcurrentgotgraphic/$dcurrentgoal))."px'></div>
		</div>
		<div style='margin-left:".($dwidth+15)."px; margin-right:".($dwidth+15)."px;'>
			<table style='width:100%;'><tr>
				<td>
					<span style='font-weight:bold; color:{$dlastcolor};'>{$flolang->donate_lastmonth_title}: {$dlastgot}&euro; / {$dlastgoal}&euro;</span><br />
					{$dlastdonatorlist}
				</td>
				<td style='text-align:right;'>
					<span style='font-weight:bold; color:{$dcurrentcolor};'>{$flolang->donate_currentmonth_title}: {$dcurrentgot}&euro; / {$dcurrentgoal}&euro;</span><br />
					{$dcurrentdonatorlist}	
				</td>
			</tr></table>
		</div>
	</div>
	<div style='text-align:center; margin-top:5px;'><a href='".$florensia->outlink(array("donate"))."'>{$flolang->donate_linktodonatepage}</a></div>
	</td>
</tr>
</table>
*/
$content = "
<table class='small subtitle' style='width:100%; margin-bottom:10px; border-spacing:3px;'><tr>
<td>
	<div style='min-height:".($dheight-10)."px'>
		<div style='float:left; padding:3px; border:1px #1c3e54 solid; height:{$dheight}px'>
			<div style='background-color:{$dlastcolor}; width:{$dwidth}px; margin-top:".($dheight-$dheight*($dlastgotgraphic/$dlastgoal))."px; height:".($dheight*($dlastgotgraphic/$dlastgoal))."px'></div>
		</div>
		<div style='float:right; padding:3px; border:1px #1c3e54 solid; height:{$dheight}px'>
			<div style='background-color:{$dcurrentcolor}; width:{$dwidth}px; margin-top:".($dheight-$dheight*($dcurrentgotgraphic/$dcurrentgoal))."px; height:".($dheight*($dcurrentgotgraphic/$dcurrentgoal))."px'></div>
		</div>
		<div style='margin-left:".($dwidth+15)."px; margin-right:".($dwidth+15)."px;'>
			<table style='width:100%;'><tr>
				<td>
					<span style='font-weight:bold; color:{$dlastcolor};'>{$flolang->donate_lastmonth_title}: {$dlastgot}&euro; / {$dlastgoal}&euro;</span><br />
					{$dlastdonatorlist}
				</td>
				<td style='text-align:right;'>
					<span style='font-weight:bold; color:{$dcurrentcolor};'>{$flolang->donate_currentmonth_title}: {$dcurrentgot}&euro; / {$dcurrentgoal}&euro;</span><br />
					{$dcurrentdonatorlist}	
				</td>
			</tr></table>
		</div>
	</div>
	<div style='text-align:center; margin-top:5px;'><a href='".$florensia->outlink(array("donate"))."'>{$flolang->donate_linktodonatepage}</a></div>
</td>
</tr></table>
{$news}
{$market}
";


if ($flouser->get_permission("mod_language")) {
	foreach (array_keys($flolang->lang) as $langkey) {
		if (!$flouser->get_permission("mod_language", $langkey)) continue;
		$queryusernotes = MYSQL_QUERY("SELECT varname FROM flobase_languagefiles WHERE lang_{$langkey}_flag=1");
		$amount = MYSQL_NUM_ROWS($queryusernotes);
		if ($amount) $florensia->notice("Language {$langkey}: There are <a href='{$florensia->root}/adminlang.php?lang={$langkey}&amp;new=1'>{$amount} new variable(s)</a> which need to be translated", "warning");
	}
}

$florensia->sitetitle("News");
$florensia->output_page($content);


?>
