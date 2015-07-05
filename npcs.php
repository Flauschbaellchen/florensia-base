<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
$flolang->load("npc");
$flolang->load("droplist");

###
$notyetincludedworkaround = "AND !(".$florensia->get_columnname("attackmax", "npc")."='0' && ".$florensia->get_columnname("attackmin", "npc")."='0' && ".$florensia->get_columnname("level", "npc")."='1' && ".$florensia->get_columnname("hppoints", "npc")."='0' && ".$florensia->get_columnname("manapoints", "npc")."='0' && ".$florensia->get_columnname("exp", "npc")."='0')";
###
if (!isset($_GET['npcid'])) {

	if ((!isset($_GET['level']) OR !is_numeric($_GET['level'])) && !isset($_GET['search'])) { $_GET['level']=0; }
	$selectoverviewlevel = "
		<select name='level' class='small'>";
			for ($i=0; $i<=100; $i=$i+10) {
				if ($i==$_GET['level']) $selected="selected='selected'";
				else unset($selected);
				$selectoverviewlevel .= "<option value='$i' $selected>$i - ".bcadd($i,9)."</option>";
			}
			$selectoverviewlevel .= "
		</select>
	";
	if (isset($_GET['level'])) {
		$querymonsterlevel = "server_npc.".$florensia->get_columnname("level", "npc").">=".intval($_GET['level'])." && server_npc.".$florensia->get_columnname("level", "npc")."<=".bcadd(intval($_GET['level']),9);
		$and = "AND";
		$endlevel=bcadd(intval($_GET['level']),9);
	}
	else $endlevel=0;


	if (isset($_GET['type']) && $_GET['type']=="Merchant" OR $_GET['type']=="Monster" OR $_GET['type']=="LandMonster" OR $_GET['type']=="SeaMonster" OR $_GET['type']=="Boss" OR $_GET['type']=="Citizen" OR $_GET['type']=="Guard") {
		if ($_GET['type']=="Boss") $querynpctype = "$and npc_bossfactor>='".floclass_npc::$bossfactor."'";
		elseif ($_GET['type']=="LandMonster") $querynpctype = "$and ".$florensia->get_columnname("fielddividing", "npc")." = '0'";
		elseif ($_GET['type']=="SeaMonster") $querynpctype = "$and ".$florensia->get_columnname("fielddividing", "npc")." = '1'";
		else $querynpctype = "$and npc_file = '".mysql_real_escape_string($_GET['type'])."Char'";
		$selectednpctype[$_GET['type']]= "selected='selected'";
	}
	else $_GET['type']="all";
	$selectoverviewtype = "
		<select name='type' class='small'>
			<option value='all' ".$selectednpctype['all'].">All</option>
			<option value='Citizen' ".$selectednpctype['Citizen'].">Citizen</option>
			<option value='Guard' ".$selectednpctype['Guard'].">Guard</option>
			<option value='Merchant' ".$selectednpctype['Merchant'].">Merchant</option>
			<option value='Monster' ".$selectednpctype['Monster'].">Monster</option>
			<option value='LandMonster' ".$selectednpctype['LandMonster'].">Land Monster</option>
			<option value='SeaMonster' ".$selectednpctype['SeaMonster'].">Sea Monster</option>
			<option value='Boss' ".$selectednpctype['Boss'].">Boss</option>
		</select>
	";

	//search
	if (isset($_GET['search'])) {
		foreach (explode(" ", $_GET['search']) as $keyword) {
			$searchstring[] = "name_{$stringtable->language} LIKE '%".get_searchstring($keyword,0)."%'";
		}
		
		$querymonstersearch .= "$and ".join(" AND ", $searchstring);
		$searchtitle = "
			<div class='subtitle' style='margin-bottom:5px;'>".$flolang->sprintf($flolang->npcsearching, $florensia->escape($_GET['search']))."</div>
		";
	}


	$querystringlist_string = "FROM server_npc WHERE $querymonsterlevel $querymonstersearch $querynpctype $notyetincludedworkaround ORDER BY ".$florensia->get_columnname("level", "npc").", name_{$stringtable->language}";
	list($entries) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(".$florensia->get_columnname("npcid", "npc").") ".$querystringlist_string));
	$pageselect = $florensia->pageselect($entries, array("npcoverview", "level-".intval($_GET['level'])."-{$endlevel}", "type-{$_GET['type']}"));

	$querystringlist = MYSQL_QUERY("SELECT * ".$querystringlist_string." LIMIT ".$pageselect['pagestart'].",".$florensia->pageentrylimit);
	while ($monsterlist = MYSQL_FETCH_ARRAY($querystringlist)) {
					$content .= $florensia->adsense(13);
					$npc = new floclass_npc($monsterlist);
					$content .="<div class='shortinfo_".$florensia->change()."'>".$npc->shortinfo()."</div>";
	}
	if (!$content) { $content = "<div style='text-align:center' class='warning'>{$flolang->npcnoresult}</div>"; }
	else { $content = "<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
					$content
					<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
			";
	}

	$content = "<div style='margin-bottom:5px; text-align:center'>".$florensia->quick_select('npcoverview', array('search'=>$_GET['search']), array($flolang->npc_selector_levelrange=>$selectoverviewlevel, $flolang->npc_selector_npctype=>$selectoverviewtype), array('namesselect'=>1))."</div>
			{$searchtitle}
			{$content}";

	$florensia->sitetitle("Monster- and NPC-Database");
	if (isset($_GET['search'])) { $florensia->sitetitle("Searching ".$florensia->escape($_GET['search'])); }
	$florensia->output_page($content);

}
elseif (isset($_GET['npcid'])) {

	$npc = new floclass_npc($_GET['npcid']);
	if ($npc->is_valid()) {
		$tabbar['details'] = array("anchor"=>"details", "name"=>$flolang->tabbar_title_details, "desc"=>$flolang->tabbar_desc_details);
		$tabbar['droplist'] = array("anchor"=>"droplist", "name"=>$flolang->tabbar_title_droplist, "desc"=>"");
		$tabbar['questinfo'] = array("anchor"=>"questinfo", "name"=>$flolang->tabbar_title_questinfo, "desc"=>"");
		$tabbar['store'] = array("anchor"=>"store", "name"=>$flolang->tabbar_title_npcstore, "desc"=>"");
		$tabbar['usernotes'] = array("anchor"=>"usernotes", "name"=>$flolang->tabbar_title_usernotes, "desc"=>"");
		
//addition
			$npcadditionmenu = $floaddition->get_additionmenu("npc", $npc->data[$florensia->get_columnname("npcid", "npc")]);
			$npctitlenotices = $floaddition->get_additionlist("npc", $npc->data[$florensia->get_columnname("npcid", "npc")]);
//bossfactor
			if ($npc->data['npc_bossfactor']>floclass_npc::$bossfactor) $npccolor2=floclass_npc::$bosscolor;
			else $npccolor2=0;

//class land/sea
			if (intval($npc->data[$florensia->get_columnname("fielddividing", "npc")])) $monsterclasssymbol = "<img src='{$florensia->layer_rel}/sealv.gif' height='12' alt='Sea'>";
			else $monsterclasssymbol = "<img src='{$florensia->layer_rel}/land.gif' height='12' alt='Land'>";

//map coordinates
			//only do this if mob is available ingame
			//if (!$npctitlenotices['removed'] && !$npctitlenotices['notimplemented']) {
				if ($_POST['do_save_coordinates']) {
					if (!$flouser->userid) { $florensia->notice($flolang->mapcoordinates_add_error_notloggedin, "warning"); }
					elseif (!$flouser->get_permission("add_coordinates")) { $florensia->notice($flolang->mapcoordinates_add_error_nopermission, "warning"); }
					elseif (preg_match('/^[0-9-;,]+$/', $_POST['rxy'])) {
						floclass_npc::save_coordinates($_POST['rxy'], $npc->data[$florensia->get_columnname("npcid", "npc")], $_POST['mapid']);
					}
				}
	
				$npcmap_allentries = array();
				$contributeduser = array();
				$querynpcmap = MYSQL_QUERY("SELECT mapid, coordinates, contributed FROM flobase_npc_coordinates WHERE npcid='".mysql_real_escape_string($npc->data[$florensia->get_columnname("npcid", "npc")])."'");
				while ($npcmap = MYSQL_FETCH_ARRAY($querynpcmap)) {
					#get contributed users and add array to another (php.net)
					array_splice($contributeduser, count($contributeduser), 0, explode(",", $npcmap['contributed']));
					#get preselected
					$coordsamount = count(explode(";",$npcmap['coordinates']));
					if (!$npcmap_preselected OR $npcmap_preselected['coordsamount']<$coordsamount) {
						$npcmap_preselected['mapid'] = $npcmap['mapid'];
						$npcmap_preselected['coordsamount'] = $coordsamount;
					}
					array_push($npcmap_allentries, array('mapid'=>$npcmap['mapid'], 'coordsamount'=>$coordsamount));
				}
	
				if (count($npcmap_allentries)==1) $npcmaptitle = $stringtable->get_string($npcmap_preselected['mapid'], array('protectionsmall'=>1));
				elseif (count($npcmap_allentries)) {
					$npcmaptitle = "
						<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post' style='text-align:center'>
						<select name='mapid' class='small' onchange='npc_mapprotection_change(\"mapprotection_".$npc->data[$florensia->get_columnname("npcid", "npc")]."\", this.form.mapid.value)'>
					";
					foreach ($npcmap_allentries as $mapvalue) {
						if ($mapvalue['mapid']==$npcmap_preselected['mapid']) $selected_npcmap_preselected = "selected='selected'";
						else unset($selected_npcmap_preselected);
						$npcmaptitle .= "<option value='".$mapvalue['mapid']."' {$selected_npcmap_preselected}>".$flolang->sprintf($flolang->mapcoordinates_display_mapselect, $florensia->escape($stringtable->get_string($mapvalue['mapid'])), $mapvalue['coordsamount'])."</option>";
					}
					$npcmaptitle .= "</select></form>";
				}
				else { $npcmaptitle = $flolang->npcmapnocoordinates; }
	
				#contributed by list
				foreach (array_unique($contributeduser) as $userid) {
					$contributedlist[] = $flouserdata->get_username(intval($userid));
				}
				
				#final mapoverview
				$monstermap = "<div class='subtitle' style='width:512px; margin:auto; margin-bottom:5px;'>{$npcmaptitle}</div>";
				if (count($npcmap_allentries)) {
					$monstermap .= "<div><a href='".$florensia->outlink(array("mapdetails", $npcmap_preselected['mapid'], $stringtable->get_string($npcmap_preselected['mapid'])), array('npcs'=>$npc->data[$florensia->get_columnname("npcid", "npc")]))."'>".$florensia->mapprotection($npcmap_preselected['mapid'], $stringtable->get_string($npcmap_preselected['mapid']), $npc->data[$florensia->get_columnname("npcid", "npc")], array('locatorheight'=>200, 'imageid'=>'mapprotection_'.$npc->data[$florensia->get_columnname("npcid", "npc")]))."</a></div>";
					$monstermap .= "<div  style='width:512px; margin:auto; margin-top:5px;'>{$flolang->contributedby} ".join(", ", $contributedlist)."</div>";
				}
				$monstermap .= "<div class='bordered small' style='width:512px; margin:auto; margin-top:5px;'><a href='{$florensia->root}/npccoords.php?npcid=".$npc->data[$florensia->get_columnname("npcid", "npc")]."'>".$flolang->sprintf($flolang->mapcoordinates_display_addnewlink, $stringtable->get_string($npc->data[$florensia->get_columnname("npcid", "npc")], array("protectionlink"=>1, "protecionsmall"=>1)))."</a></div>";
			//}

//specialeffects
			for ($i=1; $i<3; $i++) {
				if ($npc->data[$florensia->get_columnname("action{$i}ratio", "npc")]=="0") continue;
				$querydescription = MYSQL_QUERY("SELECT ".$florensia->get_columnname("skillid", "skill").", skill_picture FROM server_skill WHERE ".$florensia->get_columnname("skillid", "skill")."='".$npc->data[$florensia->get_columnname("action{$i}id", "npc")]."' ORDER BY ".$florensia->get_columnname("skilllevel", "skill")."");
				if ($description = MYSQL_FETCH_ARRAY($querydescription)) {
					$specialattacks .= "
						<div class='bordered' style='margin-bottom:7px;'>
							<table style='width:100%'>
								<tr><td colspan='2'>".$stringtable->get_string($description[$florensia->get_columnname("skillid", "skill")], array('protection'=>1))."</td></tr>
								<tr>
									<td style='width:30px; vertical-align:top;'>".$florensia->pictureprotection($description['skill_picture'], $stringtable->get_string($description[$florensia->get_columnname("skillid", "skill")]), "skill")."</td>
									<td class='small'>".$florensia->detailsprotection($description[$florensia->get_columnname("skillid", "skill")], "skill")."</td>
								</tr>
							</table>
						</div>";
				}
			}

//droplist
//Everytime visible, also Guards may drop questitems (e.g. Mattan Gun)
	//		if ($npc->data['npc_file']=="MonsterChar") {
				$droplist = "<div style='margin-bottom:15px; margin-top:15px;'>".$classdroplist->get_droplist($npc->data[$florensia->get_columnname("npcid", "npc")], "npc")."</div>";
				$droplist .= $florensia->adsense(0);
				$tabbar['droplist']['desc'] = $classdroplist->get_tabdesc($npc->data[$florensia->get_columnname("npcid", "npc")], "npc");
	/*		}
			else {
				$tabbar['droplist']['inactive'] = TRUE;
			}
	*/		
			
//quest from NPC
			unset($questlist, $questinfoamount);
			$querynpcquestlist = MYSQL_QUERY("SELECT * FROM server_questlist WHERE questsourcenpc='".$npc->data[$florensia->get_columnname("npcid", "npc")]."' ORDER BY questlevel, questtitle_{$classquesttext->language}");
			while ($npcquestlist = MYSQL_FETCH_ARRAY($querynpcquestlist)) {
					if ($classquest->get_shortinfo($npcquestlist)) {
						$questlist .= "
						<div class='shortinfo_".$florensia->change()."'>".$classquest->get_shortinfo($npcquestlist, array('colorchange'=>1))."</div>
						";
						$questinfoamount++;
					}
			}
			if (isset($questlist)) {
					$questlist_from = "
					<div style='margin-top:10px;' class='subtitle'>".$flolang->sprintf($flolang->npcquestfrom, $stringtable->get_string($npc->data[$florensia->get_columnname("npcid", "npc")], array('protection'=>1)))."</div>
					$questlist";
			}

//quest with NPC
			unset($questlist);
			$querynpcquestlist = MYSQL_QUERY("SELECT * FROM server_questreferences as r, server_questlist as q WHERE r.refid='".$npc->data[$florensia->get_columnname("npcid", "npc")]."' AND r.questid=q.questlistid ORDER BY q.questlevel, q.questtitle_{$classquesttext->language}");
			while ($npcquestlist = MYSQL_FETCH_ARRAY($querynpcquestlist)) {
				if ($classquest->get_shortinfo($npcquestlist, array('npclink'=>1))) {
					$questlist .= "<div class='shortinfo_".$florensia->change()."'>".$classquest->get_shortinfo($npcquestlist, array('npclink'=>1))."</div>";
					$questinfoamount++;
				}
			}

			if (isset($questlist)) {
			$questlist_with = "
				<div style='margin-top:10px;' class='subtitle'>".$flolang->sprintf($flolang->npcquestwith, $stringtable->get_string($npc->data[$florensia->get_columnname("npcid", "npc")], array('protection'=>1)))."</div>
				$questlist
			";
			}

			unset($questinfo);
			if ($questlist_from OR $questlist_with) {
				$questinfo = "
					{$questlist_from}
					{$questlist_with}
				";
				$tabbar['questinfo']['desc'] = $flolang->sprintf($flolang->tabbar_desc_questinfo, $questinfoamount);
			}
			else {
				$tabbar['questinfo']['inactive'] = true;
				$tabbar['questinfo']['desc'] = $flolang->tabbar_desc_questinfo_notfound;
			}

//npc storelist
			//some defaults
			$tabbar['store']['inactive'] = true;
			$storeitemamount=0;
			unset($storelisttable);

			$querystorelist = MYSQL_QUERY("SELECT s.itemid FROM server_storelist as s, server_item_idtable as i WHERE s.npcid='".$npc->data[$florensia->get_columnname("npcid", "npc")]."' AND i.itemid=s.itemid");
			while ($storelist = MYSQL_FETCH_ARRAY($querystorelist)) {
				$item = new floclass_item($storelist['itemid']);
				$storelisttable .= "<div class='shortinfo_".$florensia->change()."'>".$item->shortinfo(array('marketlist'=>true))."</div>";
				$storeitemamount++;
			}
			if ($storeitemamount) {
					$storelist = "
						<div style='margin-top:10px;' class='subtitle'>{$flolang->npcshop}</div>
						$storelisttable";
					$tabbar['store']['desc'] = $flolang->sprintf($flolang->tabbar_desc_npcstore, $storeitemamount);
					$tabbar['store']['inactive'] = FALSE;
			}
			
			//usernotes
			$usernotes = $classusernote->display($npc->data[$florensia->get_columnname("npcid", "npc")], "npc");
			$tabbar['usernotes']['desc'] = $classusernote->get_tabdesc($npc->data[$florensia->get_columnname("npcid", "npc")], "npc");
			
			//final tabbar
			$tabbar = $florensia->tabbar($tabbar);
			
			$content = "
			<div style='float:right; text-align:center;'>".$florensia->quick_select('npcdetails', array('npcid'=>$npc->data[$florensia->get_columnname("npcid", "npc")]), array(0=>$stringtable->get_select()))."</div>
			<div style='margin-bottom:5px; padding:1px;' class='subtitle'><a href='".$florensia->outlink(array("npcoverview"))."'>NPC-Database</a> &gt; ".$stringtable->get_string($npc->data[$florensia->get_columnname("npcid", "npc")], array("protection"=>1))."<br />".join("<br />", $npctitlenotices)."</div>

			<div class='bordered' style='float:right; width:50px; height:50px; text-align:center; vertical-align:center;'>".$florensia->pictureprotection($npc->data['npc_picture'], $stringtable->get_string($npc->data[$florensia->get_columnname("npcid", "npc")]), "monster")."</div>
			<div style='padding-top:7px;'>{$tabbar['tabbar']}</div>
			

			
			<div name='details' style='vertical-align:top;'>
				<a name='details'></a>
				<div style='min-height:350px; width:250px; float:left; text-align:center; vertical-align:top;' class='bordered'>
					".$florensia->pictureprotection($npc->data['npc_picture'], $stringtable->get_string($npc->data[$florensia->get_columnname("npcid", "npc")]), "character")."
				</div>
				<div style='margin-left:260px; min-height:350px;'>
					<div style='float:right; padding-top:2px;'>{$monsterclasssymbol}</div>
					<div class='subtitle'>".$flolang->sprintf($flolang->npcdetailssubtitle, $stringtable->get_string($npc->data[$florensia->get_columnname("npcid", "npc")], array('protection'=>1, 'color'=>$npccolor2)))." &nbsp;<span class='small' style='font-weight:normal'>(".preg_replace('/Char$/', '', $npc->data['npc_file']).")</span></div>
					<div>
						<table style='width:100%; border-collapse:0px; border-spacing:0px; padding-bottom:15px;'>
							<tr>
								<td style='vertical-align:top;'>
									<div>".$npc->details()."</div>
								</td>
								<td style='width:3px;'></td>
								<td style='vertical-align:top; width:250px;'>
									{$npcadditionmenu}
									{$merchantnotice}
								</td>
							</tr>
						</table>
					</div>
					{$specialattacks}
					<div style='text-align:center;' class='small'>{$monstermap}</div>
				</div>
				<div>".$florensia->adsense(0)."</div>
			</div>
			
			
			
			
			
			
			
			<a name='droplist'></a>
			<div name='droplist'>{$droplist}</div>
			
			<a name='questinfo'></a>
			<div name='questinfo'>{$questinfo}</div>
			
			<a name='store'></a>
			<div name='store'>{$storelist}</div>
			
			<a name='usernotes'></a>
			<div name='usernotes'>{$usernotes}</div>
			{$tabbar['jscript']}";

		$florensia->sitetitle("Monster- and NPC-Database");
		$florensia->sitetitle($stringtable->get_string($npc->data[$florensia->get_columnname("npcid", "npc")]));
		$florensia->output_page($content);

	}
	else { header("Location: ".$_SERVER['PHP_SELF']); }

}
?>