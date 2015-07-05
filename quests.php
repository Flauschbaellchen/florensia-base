<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
$flolang->load("quest");
if (!isset($_GET['questid'])) {

	//questlevel
	if ((!isset($_GET['level']) OR !is_numeric($_GET['level'])) && !isset($_GET['search'])) { $_GET['level']=0; }
	$selectoverviewlevel = "
		<select name='level' class='small'>";
			for ($i=0; $i<100; $i=$i+10) {
				if ($i==$_GET['level']) $selected="selected='selected'";
				else unset($selected);
				$selectoverviewlevel .= "<option value='$i' $selected>$i - ".bcadd($i,9)."</option>";
			}
			$selectoverviewlevel .= "
		</select>
	";

	if (isset($_GET['level'])) {
		$queryquestlevel = "server_questlist.questlevel>='".intval($_GET['level'])."' && server_questlist.questlevel<='".bcadd(intval($_GET['level']),9)."'"; $and = "AND";
		$endlevel=bcadd(intval($_GET['level']),9);
	}
	else $endlevel=0;

	//questtype
	if ($_GET['questtype']!="all") {
		if ($_GET['questtype']=="sea") { $queryquesttype = "$and server_questlist.questtype='s'"; $and = "AND"; }
		elseif ($_GET['questtype']=="land") { $queryquesttype = "$and server_questlist.questtype='l'"; $and = "AND"; }
		else $_GET['questtype']="all";
	}
	$questtypeselected[$_GET['questtype']]="selected='selected'";
	$selectoverviewquesttype = "
		<select name='questtype' class='small'>
			<option value='all'>{$flolang->questtype_all}</option>
			<option value='land' ".$questtypeselected['land'].">{$flolang->questtype_land}</option>
			<option value='sea' ".$questtypeselected['sea'].">{$flolang->questtype_sea}</option>
		</select>
	";

	//search
	if (isset($_GET['search']) && trim($_GET['search'])!="") {
		foreach (explode(" ", $_GET['search']) as $keyword) {
			$searchstring[] = "server_questlist.questtitle_{$classquesttext->language} LIKE '%".get_searchstring($keyword,0)."%'";
		}
		
		$queryquestsearch .= "$and ".join(" AND ", $searchstring);
		$searchtitle = "
			<div class='subtitle' style='margin-bottom:5px;'>".$flolang->sprintf($flolang->questsearching, $florensia->escape($_GET['search']))."</div>
		";
	}

	$querystringquest = "SELECT * FROM server_questlist WHERE $queryquestlevel $queryquesttype $queryquestsearch ORDER BY questlevel, questtitle_".$classquesttext->language;
	$maxquests = MYSQL_NUM_ROWS(MYSQL_QUERY($querystringquest));
	$pageselect = $florensia->pageselect($maxquests, array("questoverview", "level-".intval($_GET['level'])."-{$endlevel}", "type-{$_GET['questtype']}"));

	$queryquestlist = MYSQL_QUERY($querystringquest." LIMIT ".$pageselect['pagestart'].",".$florensia->pageentrylimit);	
	while ($questlist = MYSQL_FETCH_ARRAY($queryquestlist)) {
		//workaround "deleted" quests
		if ($classquest->get_shortinfo($questlist, array('npclink'=>1))) {
			$content .= $florensia->adsense(13);
			$content .= "
				<div class='shortinfo_".$florensia->change()."'>".$classquest->get_shortinfo($questlist, array('npclink'=>1))."</div>
			";
		}
	}
	if (!isset($content)) { $content = "<div style='text-align:center' class='warning'>{$flolang->questnoresult}</div>"; }

	$content = "<div style='text-align:center; margin-bottom:5px;'>".$florensia->quick_select('questoverview', array('search'=>$_GET['search'], 'page'=>$_GET['page']), array($flolang->selector_questtitle=>$classquesttext->get_select(), $flolang->selector_levelrange=>$selectoverviewlevel, $flolang->selector_questtype=>$selectoverviewquesttype), $settings=array('namesselect'=>1))."</div>
			<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
			$searchtitle
			$content
			<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
			";

	$florensia->sitetitle("Questlist");
	if (isset($_GET['search'])) { $florensia->sitetitle("Searching ".$florensia->escape($_GET['search'])); }
	$florensia->output_page($content);
}
elseif (isset($_GET['questid'])) {

	$queryquest = MYSQL_QUERY("SELECT * FROM server_questlist WHERE questlistid = '".mysql_real_escape_string($_GET['questid'])."'");
	if ($quest = MYSQL_FETCH_ARRAY($queryquest)) {
		
		$tabbar['details'] = array("anchor"=>"details", "name"=>$flolang->tabbar_title_details, "desc"=>$flolang->tabbar_desc_details);
		$tabbar['questtext'] = array("anchor"=>"questtext", "name"=>$flolang->tabbar_title_questdialogues, "desc"=>"");
		$tabbar['usernotes'] = array("anchor"=>"usernotes", "name"=>$flolang->tabbar_title_usernotes, "desc"=>"");
		
		$questadditionmenu = $floaddition->get_additionmenu("quest", $quest['questlistid']);
		$questtitlenotices = $floaddition->get_additionlist("quest", $quest['questlistid']);
		//one additional <br> due to next_quest/prev_quest notices
		if (count($questtitlenotices)) $questtitlenotices = "<br />".join("<br />", $questtitlenotices);
		else unset($questtitlenotices);
		
	$questxml = simplexml_load_string($quest['questlistxml']);
		
	//startnpc
	$querystartnpc = MYSQL_QUERY("SELECT ".$florensia->get_columnname("npcid", "npc")." FROM server_npc WHERE ".$florensia->get_columnname("npcid", "npc")."='".$questxml->attributes()->SourceObject."'");
	if (MYSQL_NUM_ROWS($querystartnpc )==1) {
		$startnpc = "<a href='".$florensia->outlink(array("npcdetails", $questxml->attributes()->SourceObject, $stringtable->get_string($questxml->attributes()->SourceObject)))."'>".$stringtable->get_string($questxml->attributes()->SourceObject, array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
		$maploc = floclass_npc::get_maplist($questxml->attributes()->SourceObject);
		$startnpc .= count($maploc['ids']) ?  " (".join(", ", $maploc['list']).")" : "";
	}
	else { 
		$startnpc = $stringtable->get_string($questxml->attributes()->SourceObject);
	}
	
	//endnpc
	$endnpc = "<a href='".$florensia->outlink(array("npcdetails", $questxml->RewardDesc->attributes()->Supplier, $stringtable->get_string($questxml->RewardDesc->attributes()->Supplier)))."'>".$stringtable->get_string($questxml->RewardDesc->attributes()->Supplier, array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
	$maploc = floclass_npc::get_maplist($questxml->RewardDesc->attributes()->Supplier);
	$endnpc .= count($maploc['ids']) ?  " (".join(", ", $maploc['list']).")" : "";
	
	//see / land-quest
	 if ($quest['questtype']=="s") {
		$questpicture = "<img src='{$florensia->layer_rel}/sealv.gif' height='12' alt='{$flolang->questtype_sea}' title='{$flolang->questtype_sea}'>";
		$questtype = "sea";
	} else { 
		$questpicture = "<img src='{$florensia->layer_rel}/land.gif' height='12' alt='{$flolang->questtype_land}' title='{$flolang->questtype_land}'>";
		$questtype = "land";
	}

	if (intval($questxml->OccurTerm->attributes()->MustParty)) $MustParty=$flolang->yes; else $MustParty=$flolang->no;
	if (intval($questxml->attributes()->MustPlay)) $MustPlay=$flolang->yes; else $MustPlay=$flolang->no;
	if (intval($questxml->attributes()->RepeatableCount)) $RepeatableCount=$flolang->no; else $RepeatableCount=$flolang->yes;
	if (intval($questxml->attributes()->Tutorial)) $Tutorial=$flolang->yes; else $Tutorial=$flolang->no;

	//prev quest
	if (strlen($quest['privquest'])) {
		$prev_quest = "<span class='small'><br />{$flolang->previousquest} <a href='".$florensia->outlink(array("questdetails", $quest['privquest'], strip_tags($classquest->get_title($quest['privquest']))))."'>".$florensia->escape($classquest->get_title($quest['privquest']))."</a></span>";
	}

	//next quest
	if (strlen($quest['nextquest'])) {
		$next_quest = "<span class='small'><br />{$flolang->nextquest} <a href='".$florensia->outlink(array("questdetails", $quest['nextquest'], strip_tags($classquest->get_title($quest['nextquest']))))."'>".$florensia->escape($classquest->get_title($quest['nextquest']))."</a></span>";
	}

	//questlevelrange
	if (intval($questxml->OccurTerm->attributes()->LvLim)) {
		$questlevelrange = $questxml->OccurTerm->attributes()->Lv."-".$questxml->OccurTerm->attributes()->LvLim;
		if (intval($questxml->OccurTerm->attributes()->LvOptimaize)) $questlevelrange .= " (<b>".$questxml->OccurTerm->attributes()->LvOptimaize."</b>)";
	}
	else {
		$questlevelrange = $questxml->OccurTerm->attributes()->Lv."+";
	}
	
	//questclasses
	$questclasses = "";
	foreach(str_split($quest['questclasses']) as $class) {
		if (strtolower($class)=="p") continue;
		$classname = $florensia->get_classname($class);
		$questclasses.= "<img src='{$florensia->layer_rel}/icon_".strtolower($class).".png' title='".$florensia->escape($classname[0])."' alt='{$class}' style='height:12px; margin-right:2px; border:none;'>";
	}

	//questexpreward
	if ($questxml->RewardDesc->attributes()->Exp=="0") $questexpreward = "-";
	else {
		$questexpreward = "{$questxml->RewardDesc->attributes()->Exp}: ".$florensia->get_exp($questxml->RewardDesc->attributes()->Exp,$questxml->OccurTerm->attributes()->Lv, $questtype)."/Lv".$questxml->OccurTerm->attributes()->Lv;
		if (intval($questxml->OccurTerm->attributes()->LvOptimaize)) $questexpreward .=", ".$florensia->get_exp($questxml->RewardDesc->attributes()->Exp,$questxml->OccurTerm->attributes()->LvOptimaize, $questtype)."/Lv".$questxml->OccurTerm->attributes()->LvOptimaize;
		if (intval($questxml->OccurTerm->attributes()->LvLim)) $questexpreward .=", ".$florensia->get_exp($questxml->RewardDesc->attributes()->Exp,$questxml->OccurTerm->attributes()->LvLim, $questtype)."/Lv".$questxml->OccurTerm->attributes()->LvLim;
	}

	//missions
		$i=0;
		foreach ($questxml->Mission->Work as $key) {
			unset($additionaltablerow);
			unset($additionalrow);
			switch($questxml->Mission->Work[$i]->attributes()->WorkType) {
				case "0": {
					$mission=$flolang->questworktype_0; break;
				}
				case "1": {
					$mission=$flolang->questworktype_1; break;
				}
				case "2": {
					$mission=$flolang->questworktype_2;
					break;
				}
				case "3": {
					unset($loottable);
					$mission=$flolang->questworktype_3;
					foreach ($questxml->LootDesc->Loot as $lootkey => $lootvalue) {
						if (trim($questxml->Mission->Work[$i]->attributes()->WorkValue) != trim($lootvalue->attributes()->ItemCode)) { continue; }
							$maploc = floclass_npc::get_maplist($lootvalue->attributes()->MonsterCode);
							$maplist = count($maploc['ids']) ?  " (".join(", ", $maploc['list']).")" : "";
							$loottable .= "
								<tr>
									<td>{$flolang->questworktype_3_monster}</td>
									<td><a href='".$florensia->outlink(array("npcdetails", $lootvalue->attributes()->MonsterCode, $stringtable->get_string($lootvalue->attributes()->MonsterCode)))."'>".$stringtable->get_string($lootvalue->attributes()->MonsterCode, array('protectionlink'=>1, 'protectionsmall'=>1))."</a>{$maplist}</td>
								</tr>
								<tr>
									<td>{$flolang->questworktype_3_item}</td>
									<td><a href='".$florensia->outlink(array("itemdetails", $lootvalue->attributes()->ItemCode, $stringtable->get_string($lootvalue->attributes()->ItemCode)))."'>".$stringtable->get_string($lootvalue->attributes()->ItemCode, array('protectionlink'=>1, 'protectionsmall'=>1))."</a></td>
								</tr>
								<tr>
									<td>{$flolang->questworktype_3_rate}</td>
									<td>".bcdiv($lootvalue->attributes()->Rate,10)."%</td>
								</tr>
							";
					}
 					if (isset($loottable)) { $loottable="<tr><td></td><td>$loottable</td></tr>"; }
					break;
				}
				case "4": {
					$mission=$flolang->questworktype_4; break;
				}
				case "5": {
					$mission=$flolang->questworktype_5; break;
				}
//6-7 not set
				case "8": {
					$mission=$flolang->questworktype_8; break;
				}
				case "9": {
					$mission=$flolang->questworktype_9;
					$questxml->Mission->Work[$i]->attributes()->WorkValue = $questxml->Item->attributes()->ItemCode;
					break;
				}
				case "10": {
					$mission=$flolang->questworktype_10;
					$questxml->Mission->Work[$i]->attributes()->WorkValue = $questxml->Item->attributes()->ItemCode;
					break;
				}
				case "11": {
					$mission=$flolang->questworktype_11; break;
				}
				//12 not set
				case "13": {
					$mission=$flolang->questworktype_13; break;
				}
				case "14": {
					$mission=$flolang->questworktype_14; break;
				}
				case "15": {
					$mission=$flolang->questworktype_15; break;
				}
				case "16": {
					$mission=$flolang->questworktype_16; break;
				}
				default: { $mission=$flolang->sprintf($flolang->questworktype_notset, $questxml->Mission->Work[$i]->attributes()->WorkType); }
			}

			//WorkValue, only if not looting (if loot mob is already displayed)
			if (isset($questxml->Mission->Work[$i]->attributes()->WorkValue) && $questxml->Mission->Work[$i]->attributes()->WorkType!="3") {
				$workvalue = $questxml->Mission->Work[$i]->attributes()->WorkValue;
				if (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT ".$florensia->get_columnname("npcid", "npc")."  FROM server_npc WHERE ".$florensia->get_columnname("npcid", "npc")."='$workvalue'"))>0) {
					$workvalue = "<a href='".$florensia->outlink(array("npcdetails", $workvalue, $stringtable->get_string($workvalue)))."'>".$stringtable->get_string($workvalue, array('protectionlink'=>1))."</a>";
					$maploc = floclass_npc::get_maplist($questxml->Mission->Work[$i]->attributes()->WorkValue);
					$workvalue .= count($maploc['ids']) ?  " (".join(", ", $maploc['list']).")" : "";
				}
				elseif (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT itemid FROM server_item_idtable WHERE itemid='$workvalue'"))>0) { $workvalue = "<a href='".$florensia->outlink(array("itemdetails", $workvalue, $stringtable->get_string($workvalue)))."'>".$stringtable->get_string($workvalue, array('protectionlink'=>1))."</a>"; }
				else $workvalue = $stringtable->get_string($workvalue, array('protection'=>1));
				$workvalue = "
						<tr>
							<td>{$flolang->questvalue}</td>
							<td>$workvalue</td>
						</tr>
				";
			} else unset($workvalue);
			$table_missions.="
					<table class='subtitle small' style='width:100%; font-weight:normal;'>
						<tr><td colspan='2' style='border-bottom:1px solid; font-weight:bold;'>".$flolang->sprintf($flolang->questmission, bcadd($i,1))." ($mission)</td></tr>
						$loottable
						$workvalue
						$additionalrow
						<tr>
							<td style='width:120px;'>{$flolang->questamount}</td>
							<td>".$questxml->Mission->Work[$i]->attributes()->Count."</td>
						</tr>
					</table>
			";
			/*
						<tr>
							<td>{$flolang->questpriority}</td>
							<td>".$questxml->Mission->Work[$i]->attributes()->Priority."</td>
						</tr>
			*/
			$i++;
		}

		if ($questxml->RunTerm->attributes()->LimTimeSec != "0") {
			$Timelimit = $questxml->RunTerm->attributes()->LimTimeSec."s";
			$table_timelimit = "
				<table class='subtitle small' style='width:100%; font-weight:normal;'>
					<tr><td colspan='2' style='border-bottom:1px solid; font-weight:bold;'>{$flolang->questtimelimit}</td></tr>
					<tr>
						<td style='width:80px;'>{$flolang->questtimelimit}</td>
						<td>".$flolang->sprintf($flolang->questtimelimit_seconds, $questxml->RunTerm->attributes()->LimTimeSec)."</td>
					</tr>
				</table>
			";
		}


		if ($questxml->RewardDesc->attributes()->Money=="0") $questxml->RewardDesc->attributes()->Money = "-";
		//rewarditemselect
		if ($questxml->RewardDesc->attributes()->SelectableCount != "0") {
			$questxml->RewardDesc->attributes()->SelectableCount = $questxml->RewardDesc->attributes()->SelectableCount+0;
	
			foreach ($questxml->RewardDesc->SelectItems->Item as $key => $value) {
				if ($value->Return) continue;
				$rewarditemlist .= $value->attributes()->Amount . " <a href='".$florensia->outlink(array("itemdetails", $value->attributes()->ItemCode, $stringtable->get_string($value->attributes()->ItemCode)))."'>". $stringtable->get_string($value->attributes()->ItemCode, array('protectionlink'=>1, 'protectionsmall'=>1))."</a><br />";
	
			}
			$rewarditemselect = "
				<tr>
					<td valign='top'>".$flolang->sprintf($flolang->questreward, $questxml->RewardDesc->attributes()->SelectableCount)."</td>
					<td>$rewarditemlist</td>
				</tr>
			";
		}

		//usernotes
		$usernotes = $classusernote->display($quest['questlistid'], "quest");
		$tabbar['usernotes']['desc'] = $classusernote->get_tabdesc($quest['questlistid'], "quest");
			
		//final tabbar
		$tabbar = $florensia->tabbar($tabbar);
		
		$quickselect = "
		<div class='small' style='margin-bottom:2px;'>
			{$flolang->global_select_langquesttext}: ".$stringtable->get_select()."<br />
			{$flolang->global_select_langnames}: ".$classquesttext->get_select()."
		</div>
		";

		$content = "
			<div style='float:right; margin:3px;'>$questpicture</div>
			<div style='margin-bottom:5px;' class='subtitle'><a href='".$florensia->outlink(array("questoverview"))."'>Quest-Database</a> &gt; ".$florensia->escape($quest['questtitle_'.$classquesttext->language]).$questtitlenotices."{$prev_quest}{$next_quest}</div>
			
			<div style='float:right; text-align:right'>".$florensia->quick_select('questdetails', array('questid'=>$quest['questlistid'], 'search'=>$_GET['search'], 'usernotes'=>$_GET['usernotes']), array(0=>$quickselect))."</div>
			<div style='margin-top:10px;'>{$tabbar['tabbar']}</div>
			
			<a name='details'></a>
			<div name='details'>
				<div style='float:right; width:300px;'>
					{$questadditionmenu}
					{$table_timelimit}
					<table class='subtitle small' style='width:100%; font-weight:normal;'>
						<tr><td colspan='2' style='border-bottom:1px solid; font-weight:bold;'>{$flolang->questrewardsubtitle}</td></tr>
						<tr>
							<td style='width:80px;'>{$flolang->questexp}</td>
							<td>$questexpreward</td>
						</tr>
						<tr>
							<td>{$flolang->questgelt}</td>
							<td>".$questxml->RewardDesc->attributes()->Money."</td>
						</tr>
						{$rewarditemselect}
					</table>
				</div>
				<div style='margin-right:305px;'>
					<div style='float:right; margin-top:5px; margin-right:3px;'>
						{$questclasses}
					</div>
					<table class='subtitle small' style='width:100%; font-weight:normal;'>
						<tr><td colspan='2' style='border-bottom:1px solid; font-weight:bold;'>{$flolang->questdetails}</td></tr>
						<tr>
							<td width='120'>{$flolang->queststartnpc}</td>
							<td>$startnpc</td>
						</tr>
						<tr>
							<td>{$flolang->questendnpc}</td>
							<td>$endnpc</td>
						</tr>
						<tr>
							<td>{$flolang->questmaplocation}</td>
							<td><a href='".$florensia->outlink(array("mapdetails", $questxml->attributes()->SourceArea, $stringtable->get_string($questxml->attributes()->SourceArea)))."'>".$stringtable->get_string($questxml->attributes()->SourceArea, array('protectionlink'=>1, 'protectionsmall'=>1))."</a></td>
						</tr>
						<tr>
							<td>{$flolang->questreqlevel}</td>
							<td>{$questlevelrange}</td>
						</tr>
						<tr>
							<td>{$flolang->questparty}</td>
							<td>$MustParty</td>
						</tr>
						<tr>
							<td>{$flolang->questtutorial}</td>
							<td>$Tutorial</td>
						</tr>
						<tr>
							<td>{$flolang->questmustplay}</td>
							<td>$MustPlay</td>
						</tr>
						<tr>
							<td>{$flolang->questrepeatable}</td>
							<td>$RepeatableCount</td>
						</tr>
					</table>
					<div>".$florensia->adsense(0)."</div>
					{$table_missions}
				</div>
			</div>
			
			<a name='questtext'></a>
			<div name='questtext'>
				<div class='subtitle' style='margin-top:10px;'>{$flolang->questtextsubtitle}</div>
				".$classquesttext->get_string($quest['questlistid'])."
			</div>
			
			<a name='usernotes'></a>
			<div name='usernotes'>{$usernotes}</div>
			{$tabbar['jscript']}";

		$florensia->sitetitle("Questdetails");
		$florensia->sitetitle($florensia->escape($quest['questtitle_'.$classquesttext->language]));
		$florensia->output_page($content);
	}
	else { header("Location: ".$florensia->outlink(array("questoverview"), array(), array("escape"=>FALSE))); }
}

?>