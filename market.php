<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

	$florensia->sitetitle("Market");

// 	$flolang->load("market");
	$classusermarket->refresh();

if (!$_GET['itemid'] && !$_GET['search'] && !$_GET['cat']) {
	$florensia->sitetitle("Newest");

	for ($i=1; $i<=2; $i++) {
		if ($i==1) $exchangetype="sell";
		else $exchangetype="buy";

		$querynewest = MYSQL_QUERY("SELECT id as marketid, itemid FROM flobase_usermarket WHERE exchangetype='$exchangetype' ORDER BY createtime DESC LIMIT 10");
		while ($newest = MYSQL_FETCH_ARRAY($querynewest)) {
			$item = new floclass_item($newest['itemid']);
			$newestlist[$exchangetype] .= "<div class='shortinfo_".$florensia->change()."'>".$item->shortinfo(array("marketid"=>$newest['marketid']))."</div>";
		}
	if (!$newestlist[$exchangetype]) $newestlist[$exchangetype] = "<div class='bordered' style='text-align:center;'>{$flolang->market_noentries}</div>";
	}

	//newest
	$content = "
		<div class='subtitle'>{$flolang->market_title_main} &gt; {$flolang->market_title_newest}</div>
		<div>
			<table style='width:100%;' class='small'>
				<tr><td class='bordered' style='text-align:center; width:49%; font-weight:bold;'>{$flolang->market_subtitle_sell}</td><td></td><td class='bordered' style='text-align:center; width:49%; font-weight:bold;'>{$flolang->market_subtitle_buy}</td></tr>
				<tr><td colspan='3' style='height:2px;'></td></tr>
				<tr><td>{$newestlist['sell']}</td><td></td><td>{$newestlist['buy']}</td></tr>
			</table>
		</div>
	";
}
elseif ($_GET['search'] OR ($_GET['cat'] && !$_GET['itemid'])) {
	$florensia->sitetitle("List items");

	if ($_GET['server'] && preg_match('/^[1-9]+$/', $_GET['server'])) {
		$servername = $florensia->get_server($_GET['server']);
		if (strlen($servername)) {
			$serverlink="&amp;server=".$_GET['server'];
			$serverdb = "AND flobase_usermarket.server='".mysql_real_escape_string($servername)."'";
			$serversitetitle = " ($servername)";
		}
	} else unset($serverdb);

	$exchangetype = $_GET['cat'];
	if ($exchangetype=="sell" OR $exchangetype=="buy") {
		$exchangedb = "AND flobase_usermarket.exchangetype='$exchangetype'";
		$selectexchangetype[$exchangetype] = "selected='selected'";
		if ($exchangetype=="buy") {
			$marketsitetitle = "<div style='margin-bottom:5px;' class='subtitle'><a href='".$florensia->outlink(array("market"))."'>{$flolang->market_title_main}</a> &gt; {$flolang->market_title_buy} {$serversitetitle}</div>";
		}
		else {
			$marketsitetitle = "<div style='margin-bottom:5px;' class='subtitle'><a href='".$florensia->outlink(array("market"))."'>{$flolang->market_title_main}</a> &gt; {$flolang->market_title_sell} {$serversitetitle}</div>";
		}
	}
	else {
		$marketsitetitle = "<div style='margin-bottom:5px;' class='subtitle'><a href='".$florensia->outlink(array("market"))."'>{$flolang->market_title_main}</a> &gt; {$flolang->market_title_all} {$serversitetitle}</div>";
		unset($exchangetype, $exchangedb);
	}

	$selectexchangetypeform = "
		<select name='cat'>
			<option value='all'>{$flolang->market_title_all}</option>
			<option value='sell' ".$selectexchangetype['sell'].">{$flolang->market_title_sell}</option>
			<option value='buy' ".$selectexchangetype['buy'].">{$flolang->market_title_buy}</option>
		</select>
	";

	if ($_GET['search']) {
		foreach (explode(" ", $_GET['search']) as $keyword) {
			$searchstring[] = "server_item_idtable.name_{$stringtable->language} LIKE '%".get_searchstring($keyword,0)."%'";
		}
		$searchstring = join(" AND ", $searchstring)." AND";
		$marketsitetitle = "<div style='margin-bottom:5px;' class='subtitle'><a href='".$florensia->outlink(array("market"))."'>{$flolang->market_title_main}</a> &gt; ".$flolang->sprintf($flolang->market_title_searching, $florensia->escape($_GET['search']))."  {$serversitetitle}</div>";
	}

	$query = "SELECT flobase_usermarket.id as marketid, flobase_usermarket.itemid as itemid FROM server_item_idtable, flobase_usermarket WHERE {$searchstring} server_item_idtable.itemid=flobase_usermarket.itemid {$exchangedb} {$serverdb} ORDER BY flobase_usermarket.createtime DESC";
	$querystringlist = MYSQL_QUERY($query);
	$entries = MYSQL_NUM_ROWS($querystringlist);
	$pageselect = $florensia->pageselect($entries, array("market", $exchangetype), array($serverlink));

	$querystringlist = MYSQL_QUERY($query." LIMIT ".$pageselect['pagestart'].",".$florensia->pageentrylimit);
	while ($itemlist = MYSQL_FETCH_ARRAY($querystringlist)) {
		$content .= $florensia->adsense(8);
		$item = new floclass_item($itemlist['itemid']);
		$content .= "<div class='shortinfo_".$florensia->change()."'>".$item->shortinfo(array("marketid"=>$itemlist['marketid']))."</div>";
	}
	if (!$content) { $content = "<div style='text-align:center' class='warning'>{$flolang->market_noentries}</div>"; }

	$content = "
		$marketsitetitle
		<div style='text-align:center; margin-bottom:5px;'>".$florensia->quick_select('marketsearch', array('cat'=>$exchangetype, 'search'=>$_GET['search']), array($flolang->market_title_listitems=>$selectexchangetypeform), $settings=array('namesselect'=>1, 'serverselect'=>1))."</div>
		<div style='margin-bottom:10px;'>".$pageselect['selectbar']."</div>
		$content
		<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
	";
}

$content = "<div style='text-align:center; margin-bottom:15px;''>".$florensia->quicksearch(array('language'=>true))."</div>
		$content
	";
$florensia->output_page($content);

?>