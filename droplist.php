<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');


$flolang->load("droplist");
if (!$mybb->user['uid']) { $florensia->output_page($flouser->noaccess($flolang->droplist_updateentry_error_notloggedin)); }

$flolang->load("item");
$flolang->load("npc");

###
$notyetincludedworkaround = "AND !(".$florensia->get_columnname("attackmax", "npc")."='0' && ".$florensia->get_columnname("attackmin", "npc")."='0' && ".$florensia->get_columnname("level", "npc")."='1' && ".$florensia->get_columnname("hppoints", "npc")."='0' && ".$florensia->get_columnname("manapoints", "npc")."='0' && ".$florensia->get_columnname("exp", "npc")."='0')";
###


if (($_GET['itemid'] && MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT itemid FROM server_item_idtable WHERE itemid='".mysql_real_escape_string($_GET['itemid'])."' LIMIT 1"))) OR ($_GET['npcid'] && MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT ".$florensia->get_columnname("npcid", "npc")." FROM server_npc WHERE ".$florensia->get_columnname("npcid", "npc")."='".mysql_real_escape_string($_GET['npcid'])."' LIMIT 1")))) {
	if (strlen($_GET['search'])==0) {
		if ($_GET['itemid']) header("Location: ".$florensia->outlink(array("itemdetails", $_GET['itemid'], $stringtable->get_string($_GET['itemid'])), array(), array("escape"=>FALSE)));
		else header("Location: ".$florensia->outlink(array("npcdetails", $_GET['npcid'], $stringtable->get_string($_GET['npcid'])), array(), array("escape"=>FALSE)));
	}

	if ($_GET['itemid']) {
		$item = new floclass_item($_GET['itemid']);
		$addtoentry = $item->shortinfo();
		
		foreach (explode(" ", $_GET['search']) as $keyword) {
			$searchstring[] = "name_{$stringtable->language} LIKE '%".get_searchstring($keyword,0)."%'";
		}
		$querystring = "SELECT ".$florensia->get_columnname("npcid", "npc")." FROM server_npc WHERE ".join(" AND ", $searchstring)." $notyetincludedworkaround AND npc_file='MonsterChar' ORDER BY name_{$stringtable->language}";
		$formlink = $florensia->outlink(array("itemdetails", $_GET['itemid'], $stringtable->get_string($_GET['itemid'])), array(), array("anchor"=>"droplist"));
		$dropentryaddtolist = "
			<input type='hidden' value='".$florensia->escape($_GET['itemid'])."' name='itemid'>
			<input type='submit' value='{$flolang->droplist_search_npc}'>
		";
	}
	else {
		$npc = new floclass_npc($_GET['npcid']);
		$addtoentry = $npc->shortinfo();
		
		foreach (explode(" ", $_GET['search']) as $keyword) {
			$searchstring[] = "name_{$stringtable->language} LIKE '%".get_searchstring($keyword,0)."%'";
		}
		#ignore cashshoptables
		$querystring = "SELECT itemid FROM server_item_idtable WHERE tableid!='cloakitem' AND tableid!='hatitem' AND tableid!='upgradehelpitem' AND tableid!='sealhelpbreakitem' AND ".join(" AND ", $searchstring)." ORDER BY name_{$stringtable->language}";
		$formlink = $florensia->outlink(array("npcdetails", $_GET['npcid'], $stringtable->get_string($_GET['npcid'])), array(), array("anchor"=>"droplist"));
		$dropentryaddtolist = "
			<input type='hidden' value='".$florensia->escape($_GET['npcid'])."' name='npcid'>
			<input type='submit' value='{$flolang->droplist_search_item}'>
		";
	}

		$querystringlist = MYSQL_QUERY($querystring);
		for ($i=1; $entrylist = MYSQL_FETCH_ARRAY($querystringlist); $i++) {
			if ($_GET['itemid']) {
				list($amount) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(d.dropid) FROM flobase_droplist as d, flobase_droplist_ratings as r WHERE d.dropid=r.dropid AND d.npcid='".$entrylist[$florensia->get_columnname("npcid", "npc")]."' AND d.itemid='".mysql_real_escape_string($_GET['itemid'])."' AND r.userid='{$flouser->userid}'"));
				if ($amount) continue;
				$npc = new floclass_npc($entrylist[$florensia->get_columnname("npcid", "npc")]);
				$entryshortinfo = $npc->shortinfo();
				$checkbox = "droplist_thumpsup_".$florensia->escape($_GET['itemid'])."_".$entrylist[$florensia->get_columnname("npcid", "npc")]."_x";
			}
			else {
				list($amount) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(d.dropid) FROM flobase_droplist as d, flobase_droplist_ratings as r WHERE d.dropid=r.dropid AND d.npcid='".mysql_real_escape_string($_GET['npcid'])."' AND d.itemid='".$entrylist['itemid']."' AND r.userid='{$flouser->userid}'"));
				if ($amount) continue;
				$item = new floclass_item($entrylist['itemid']);
				$entryshortinfo = $item->shortinfo();
				$checkbox = "droplist_thumpsup_".$entrylist['itemid']."_".$florensia->escape($_GET['npcid'])."_x";
			}

			$content .= $florensia->adsense(10);

			$colorchange = $florensia->change();
			$content .="
					<div style='margin-bottom:5px;'>
						<table style='width:100%' class='small'>
							<tr>
								<td class='shortinfo_{$colorchange}' style='text-align:center; vertical-align:middle; width:50px;'>
									<input type='checkbox' value='1' name='{$checkbox}'>
								</td>
								<td style='width:10px;'></td>
								<td class='shortinfo_{$colorchange}' style='vertical-align:top;'>
									{$entryshortinfo}
								</td>
							</tr>
						</table>
					</div>
			";
			if ($i>30) {
				$content .= "<div style='text-align:center' class='warning'>{$flolang->droplist_addform_toomanyresults}</div>";
				break;
			}
		}
		if (!$content) { $content = "<div style='text-align:center' class='warning'>{$flolang->droplist_addform_noentrys}</div>"; }

		$content = "
			<div class='bordered' style='margin-bottom:15px;'>{$addtoentry}</div>

			<div class='bordered small' style='text-align:right'>
				<form action='{$florensia->root}/droplist.php' method='get'>
					<input type='text' name='search' value='".$florensia->escape($_GET['search'])."'>
					<input type='hidden' value='{$stringtable->language}' name='names'>
					{$dropentryaddtolist}
				</form>
			</div>

			<div class='subtitle' style='margin-bottom:5px; margin-top:5px; height:5px;'></div>
			<form action='{$formlink}' method='post'>
				$content
				<div><input type='submit' value='{$flolang->droplist_addform_doverify}' name='do_verify'></div>
			</form>
		";
}
else { header("Location: {$florensia->root}"); }

$florensia->sitetitle("Add to droplist");
$florensia->output_page($content);



?>