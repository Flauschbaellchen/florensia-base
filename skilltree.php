<?PHP
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$florensia->sitetitle("Skilltrees");
$flolang->load("skilltree");


if (!isset($_GET['class'])) {

	$content = "<div style='margin-bottom:5px;' class='subtitle'><a href='{$florensia->root}/misc.php'>{$flolang->mainmenu_misc}</a> &gt; {$flolang->skilltree_title}</div>";
	//$content .= "<div class='bordered'><a href='{$florensia->root}/skilltree.php?class=WPENS'>{$flolang->skilltree_overview_allclasses}</a></div>";
/*
	$queryprimaryclass = MYSQL_QUERY("SELECT ".$florensia->get_columnname("classid", "class")." FROM server_class WHERE ".$florensia->get_columnname("classlevel", "class")."='0'");
	while ($primaryclass = MYSQL_FETCH_ARRAY($queryprimaryclass)) {
		if ($primaryclass[$florensia->get_columnname("classid", "class")]=="#") $outlinksymbol="ship";
		else $outlinksymbol = $primaryclass[$florensia->get_columnname("classid", "class")];
		$primary = "<div>".$primaryclass[$florensia->get_columnname("classid", "class")]."<a href='".$florensia->outlink(array("skilloverview", $outlinksymbol, join("",$florensia->get_classname($primaryclass[$florensia->get_columnname("classid", "class")]))))."'>".join("",$florensia->get_classname($primaryclass[$florensia->get_columnname("classid", "class")]))."</a></div>";

			unset($secondary);
			$querysecondaryclass = MYSQL_QUERY("SELECT ".$florensia->get_columnname("classid", "class")." FROM server_class WHERE ".$florensia->get_columnname("requiredclass1", "class")."='".mysql_real_escape_string($primaryclass[$florensia->get_columnname("classid", "class")])."' AND ".$florensia->get_columnname("classlevel", "class")."='1'");
			while ($secondaryclass = MYSQL_FETCH_ARRAY($querysecondaryclass)) {
				$secondary .= "<div style='margin-left:15px;'>".$secondaryclass[$florensia->get_columnname("classid", "class")]."<a href='".$florensia->outlink(array("skilloverview", $secondaryclass[$florensia->get_columnname("classid", "class")], join("",$florensia->get_classname($secondaryclass[$florensia->get_columnname("classid", "class")]))))."'>".join("",$florensia->get_classname($secondaryclass[$florensia->get_columnname("classid", "class")]))."</a></div>";
			}

		$content .= "<div class='bordered' style='margin-bottom:10px;'>
					$primary
					$secondary
					</div>";
	}

/*/
	$classlist = $florensia->get_classlist();
	foreach ($classlist as $classvalue) {
		if ($classvalue['classid'] == "#") $classvalue['classid']="ship";
		$content .= "<div class='bordered'><a href='".$florensia->outlink(array("skilloverview", $classvalue['classid'], $classvalue['classname']))."'>".$classvalue['classname']."</a></div>";
	}

	$florensia->output_page($content);
}
else {
	$classid = $florensia->get_classname($_GET['class']);
	if (count($classid) == 0 OR (strlen($_GET['class'])!=1 && $_GET['class']!="WPENS" && $_GET['class']!="ship")) { header("Location: ".$_SERVER['PHP_SELF']); }

	$predefinedpermlink = $florensia->outlink(array("skilloverview", $_GET['class'], $classid[0]));
	if ($_GET['class']=="ship") {
		$_GET['class']="#";
		$classid[0] = "Ship";
		$predefinedpermlink = $florensia->outlink(array("skilloverview", 'Ship', $classid[0]));
	}

	//loading skilltreeid for image and build rectinfo to find coordinates for displaying popups
	$queryskilltree = MYSQL_QUERY("SELECT skilltree FROM flobase_landclass WHERE classid='".mysql_real_escape_string($_GET['class'])."' LIMIT 1");
	$skilltree = MYSQL_FETCH_ARRAY($queryskilltree);

	if ($skilltree['skilltree']=="UID_SEA_COMBATSKILL_TREE") $skillontent = "sea";
	else $skillontent = "land";

	$queryskilltreerectinfo = MYSQL_QUERY("SELECT skilltreeid,xmltree, rectinfostart FROM server_skilltrees WHERE skilltreeid='".$skilltree['skilltree']."' OR skilltreeid='rectinfolist_$skillontent' LIMIT 2");
	while ($skilltreerectinfo = MYSQL_FETCH_ARRAY($queryskilltreerectinfo)) {
		if ($skilltreerectinfo['skilltreeid']==$skilltree['skilltree']) {
			$rectinfo = simplexml_load_string($skilltreerectinfo['xmltree']);
			$rectinfostart = $skilltreerectinfo['rectinfostart'];
		}
		else {
			$rectinfolist = explode(',',$skilltreerectinfo['xmltree']);
		}
	}
	
	//get predefined levels
	if ($_GET['s'] && preg_match('/^([0-9lb]+)$/', $_GET['s'])) {
		$predefinedpermlink .= $florensia->escape("?s=".$_GET['s']);
		foreach(explode("b", $_GET['s']) as $saved) {
			$saved = explode("l", $saved);
			$predefined[$saved[0]] = $saved[1];
		}
	}

	//cfg  -----------
		$skipborder_width=33-16;
		$skipborder_height=77-16;
		if ($skilltree['skilltree']=="UID_SEA_COMBATSKILL_TREE") {
			$skipborder_width -= 32;
		}
	//--------------

	//for ($x=1; $x<=2; $x++) {
	//	if ($x==1) $dbselect="skill";
	//	else $dbselect="spell";
		$dbselect="skill";
		$ignore_wpens = "AND ".$florensia->get_columnname("classid", $dbselect)."!='WPENS'";
		if ($_GET['class']=="WPENS") unset ($ignore_wpens);


		$queryprimaryskills = MYSQL_QUERY("SELECT * FROM server_skill WHERE ".$florensia->get_columnname("classid", $dbselect)." LIKE '%".$_GET['class']."%' AND ".$florensia->get_columnname("skilllevel", $dbselect)."=0 $ignore_wpens");
		while ($primaryskills = MYSQL_FETCH_ARRAY($queryprimaryskills)) {
			$queryskillbook = MYSQL_QUERY("SELECT ".$florensia->get_columnname("description", "item").", ".$florensia->get_columnname("itemid", "item")." FROM server_item_skillbookitem WHERE ".$florensia->get_columnname("description", "item")."='".$primaryskills[$florensia->get_columnname("bookid", $dbselect)]."' LIMIT 1");
			if (!($skillbook = MYSQL_FETCH_ARRAY($queryskillbook))) continue;

			//--------- JAVAPOPUPS
				//search skill_key in rectinfolist
				$rectinfolist_entry = array_keys($rectinfolist, $primaryskills[$florensia->get_columnname("skillid", $dbselect)]);
				if (!isset($rectinfolist_entry[0])) continue;
				//skill_key - liststart of class
				$skill_key = $rectinfolist_entry[0]-$rectinfostart;
				//fix offset-bug
				unset($ship_height, $ship_width);
				if ($skilltree['skilltree']!="UID_SEA_COMBATSKILL_TREE" && $skilltree['skilltree']!="UID_WARRIOR_COMBATSKILL_TREE") $skill_key = $skill_key-7;
				if ($skilltree['skilltree']=="UID_SEA_COMBATSKILL_TREE") {
					$skill_key = $skill_key+$rectinfostart;
					$ship_height =59;
					$ship_width =44;
				}
				if (!$rectinfo->rectinfo[$skill_key]) continue;
				$coords = explode(',',$rectinfo->rectinfo[$skill_key]);
				$coords[0] = $coords[0]+$skipborder_width+$ship_width;
				$coords[1] = $coords[1]+$skipborder_height+$ship_height;
			//------------

			if ($primaryskills[$florensia->get_columnname("requiredlandlevel", $dbselect)]!=0) $details[$flolang->skilltree_shortview_landlevel] = $primaryskills[$florensia->get_columnname("requiredlandlevel", $dbselect)];
			if ($primaryskills[$florensia->get_columnname("requiredsealevel", $dbselect)]!=0) $details[$flolang->skilltree_shortview_sealevel] = $primaryskills[$florensia->get_columnname("requiredsealevel", $dbselect)];
			$details[$flolang->skilltree_shortview_maxlevel] = $primaryskills[$florensia->get_columnname("masterlevel", $dbselect)];

/*	
			unset($requieredskillbooks);
			for ($i=1; $i<=5; $i++) {
				for ($x=1; $x<=2; $x++) {
					if ($x==1) $dbselectrequiered="skill";
					else $dbselectrequiered="spell";
					if ($primaryskills[$florensia->get_columnname("requieredskill$i", $dbselectrequiered)] == "*") continue;
					$queryskillrequiered = MYSQL_QUERY("SELECT * FROM server_{$dbselectrequiered} WHERE ".$florensia->get_columnname("skillid", $dbselectrequiered)."='".$primaryskills[$florensia->get_columnname("requieredskill$i", $dbselectrequiered)]."' LIMIT 1");
					if (!($skillrequiered = MYSQL_FETCH_ARRAY($queryskillrequiered))) continue;
					
					$queryskillbookrequiered = MYSQL_QUERY("SELECT * FROM server_item_skillbookitem WHERE ".$florensia->get_columnname("description", "item")."='".$skillrequiered[$florensia->get_columnname("bookid", $dbselectrequiered)]."' LIMIT 1");
					if (!($skillbookrequiered = MYSQL_FETCH_ARRAY($queryskillbookrequiered))) continue;
		
					$requieredskillbooks .= "<br /><a href='{$florensia->root}/items.php?itemid=".$skillbookrequiered[$florensia->get_columnname("itemid", "item")]."' target='_blank'>".$stringtable->get_string($skillbookrequiered[$florensia->get_columnname("itemid", "item")], array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
				}
			}
*/
			//description of skillbook
			$querydescription = MYSQL_QUERY("SELECT {$stringtable->language} FROM server_description WHERE Code='".$skillbook[$florensia->get_columnname("description", "item")]."'");
			if ($description = MYSQL_FETCH_ARRAY($querydescription)) {
				$descriptiontext = $florensia->escape($description[$stringtable->language]);
			}
			//----
	
			if ($requieredskillbooks) $requieredskillbooks = "{$flolang->skilltree_shortview_requieredskills}$requieredskillbooks";
			$javaskillbookid = preg_replace('/^[0a-z]+/','', $skillbook[$florensia->get_columnname("itemid", "item")]);
			if ($javaskillbookid=="") $javaskillbookid=0;
			if ($predefined[$javaskillbookid]) {
				$predefinedlevel = $predefined[$javaskillbookid];
				$predefinedjavascript .= "
							skill[$javaskillbookid] = $predefined[$javaskillbookid];";
			}
			else $predefinedlevel = 0;
			
			$skilllist = "
				<div class='shortinfo_".$florensia->change()."' style='width:350px;'>
					<table width='100%' style='border-collapse:0px; border-spacing:0px; padding:0px;'>
						<tr>
							<td style='vertical-align:top;' colspan='3'><a href='".$florensia->outlink(array("itemdetails", $skillbook[$florensia->get_columnname("itemid", "item")], $stringtable->get_string($skillbook[$florensia->get_columnname("itemid", "item")])))."' target='_blank'>".$stringtable->get_string($skillbook[$florensia->get_columnname("itemid", "item")], array('protectionlink'=>1))."</a></td>
							<td rowspan='".bcadd(count($details),1)."' style='text-align:right; vertical-align:top' class='small'>$requieredskillbooks</td>
						</tr>
						<tr>";
							$itempicture = "<td rowspan='".count($details)."' style='width:32px; vertical-align:top'><a href='".$florensia->outlink(array("itemdetails", $skillbook[$florensia->get_columnname("itemid", "item")], $stringtable->get_string($skillbook[$florensia->get_columnname("itemid", "item")])))."' target='_blank'>".$florensia->pictureprotection($primaryskills['skill_picture'], $stringtable->get_string($skillbook[$florensia->get_columnname("itemid", "item")]), $dbselect)."</a></td>";
							foreach ($details as $key => $value) {
								$skilllist .= "<tr>$itempicture<td style='width:10px;'></td><td class='small' style='vertical-align:top'>$key $value</td></tr>";
								unset($itempicture);
							}


			$skilllist .= "
						</tr>
					</table>
				</div>
				<div class='shortinfo_".$florensia->change()."' style='width:350px;'>$descriptiontext</div>
				<div class='shortinfo_".$florensia->change()."' style='width:350px;'>
					<div style='float:right; width:30px; vertical-align:middle;'>
						<table style='width:100%'>
							<tr><td colspan='2' style='text-align:center;'>[<span name='{$javaskillbookid}_level'>0</span>]</td></tr>
							<tr>
								<td style='margin:1px;'><img onclick='skilltree($javaskillbookid,-1,".$primaryskills[$florensia->get_columnname("masterlevel", $dbselect)].")' id='{$javaskillbookid}_minus' src='{$florensia->layer_rel}/skill_minus_inactive.gif' border='0' alt='-'></td>
								<td style='margin:1px;'><img onclick='skilltree($javaskillbookid,1,".$primaryskills[$florensia->get_columnname("masterlevel", $dbselect)].")' id='{$javaskillbookid}_plus' src='{$florensia->layer_rel}/skill_plus_active.gif' border='0' alt='+'></td>
							</tr>
						</table>
					</div>
					<div style='display:inline; margin-right:30px;' id='{$javaskillbookid}_0'><div style='min-height:55px;'><br />{$flolang->skilltree_placeholder_level_null}</div></div>";

			for ($i=1; $i<=$primaryskills[$florensia->get_columnname("masterlevel", $dbselect)]; $i++) {
					if ($i<10) $skilllevelpic="0$i";
					else $skilllevelpic=$i;
					$skilllist .= "<div style='display:none; margin-right:30px;' id='{$javaskillbookid}_$i'><div style='min-height:55px;'>".$florensia->detailsprotection(substr($primaryskills[$florensia->get_columnname("skillid", $dbselect)], 0, -2)."$skilllevelpic", $dbselect)."</div></div>";
			}
			$skilllist .= "</div>";

			$javapopups .= "<div style='position:absolute; left:".$coords[0]."px; top:".$coords[1]."px; width:32px; height:32px;' ".popup($skilllist, "STICKY, MOUSEOFF", "skilltree_rescue({$javaskillbookid}, ".$primaryskills[$florensia->get_columnname("masterlevel", $dbselect)].");")." onclick='window.open(\"".$florensia->outlink(array("itemdetails", $skillbook[$florensia->get_columnname("itemid", "item")], $stringtable->get_string($skillbook[$florensia->get_columnname("itemid", "item")])))."\")'>".$florensia->pictureprotection($primaryskills['skill_picture'], $stringtable->get_string($skillbook[$florensia->get_columnname("itemid", "item")]), "{$dbselect}big")."</div>
			<div class='small' style='position:absolute; left:".bcadd($coords[0],36)."px; top:".$coords[1]."px; width:32px; height:32px;'>[<span name='{$javaskillbookid}_level'>$predefinedlevel</span>]</div>
			";
		}
	//}
	if (!$skilllist) $skilllist="<div class='warning' style='text-align:center'>{$flolang->skilltree_overview_noentry}</div>";
	$content = "
		<div style='margin-bottom:5px;' class='subtitle'>Tools &gt; <a href='".$florensia->outlink(array("skilloverview"))."'>{$flolang->skilltree_title}</a> &gt; ".$classid[0]."</div>
		<div style='margin-bottom:5px; text-align:center;'>".$florensia->quick_select('skilloverviewdetails', array('class'=>$_GET['class']), array(), array('namesselect'=>1))."</div>
		
		<div class='bordered small' style='margin-top:15px; margin-bottom:15px; text-align:center;'><input id='permlink' type='text' style='width:98%;' readonly='readonly' value='{$predefinedpermlink}'></div>
		
		<div style='width:501px; margin:auto; position:relative;'>".$florensia->pictureprotection($skilltree['skilltree'], 'Skilltree '.$classid[0], "skilltree")."
			$javapopups
			<script type='text/javascript'>{$predefinedjavascript}</script>
		</div>";
	$florensia->sitetitle($classid[0]);
	$florensia->output_page($content);
}
?>
