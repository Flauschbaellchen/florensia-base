<?PHP

class class_quest {

	function get_shortinfo($questlist, $settings=array()) {
		global $florensia, $stringtable, $classquesttext, $flolang, $floaddition;
		$flolang->load("quest");

		if (!is_array($questlist)) {
			$questlistquery = MYSQL_QUERY("SELECT * FROM server_questlist WHERE questlistid='".mysql_real_escape_string($questlist)."'");
			if (!($questlist = MYSQL_FETCH_ARRAY($questlistquery))) return FALSE;
		}
		$questlistxml = simplexml_load_string($questlist['questlistxml']);
/*
		//workaround for "deleted" shellquests
		if (preg_match('/^qsl[0-9]+$/', $questlistxml->Quest->SourceObject)) {
			$querystorelist = MYSQL_QUERY("SELECT npcid FROM server_storelist WHERE storexml LIKE '%{$questlist['questlistid']}%'");
			if (MYSQL_NUM_ROWS($querystorelist)==0) {
				return FALSE;
			}
		}
*/
		//startnpc
		$querystartnpc = MYSQL_QUERY("SELECT ".$florensia->get_columnname("npcid", "npc")." FROM server_npc WHERE ".$florensia->get_columnname("npcid", "npc")."='".$questlistxml->attributes()->SourceObject."'");

		if ($settings['npclink'] && MYSQL_NUM_ROWS($querystartnpc )==1) $queststartnpc = "<a href='".$florensia->outlink(array("npcdetails", $questlistxml->attributes()->SourceObject, $stringtable->get_string($questlistxml->attributes()->SourceObject)))."'>".$stringtable->get_string($questlistxml->attributes()->SourceObject, array('protectionsmall'=>1, 'protectionlink'=>1))."</a>";
		else $queststartnpc = $stringtable->get_string($questlistxml->attributes()->SourceObject, array('protectionsmall'=>1));

		//see / land-quest
		if ($questlist['questtype']=="s") {
			$questpicture = "<img src='{$florensia->layer_rel}/sealv.gif' height='12' alt='{$flolang->questtype_sea}' title='{$flolang->questtype_sea}'>";
		} else { 
			$questpicture = "<img src='{$florensia->layer_rel}/land.gif' height='12' alt='{$flolang->questtype_land}' title='{$flolang->questtype_land}'>";
		}

		//questlevelrange
		if (intval($questlistxml->OccurTerm->attributes()->LvLim)) {
			$questlevelrange = $questlistxml->OccurTerm->attributes()->Lv."-".$questlistxml->OccurTerm->attributes()->LvLim;
			if (intval($questlistxml->OccurTerm->attributes()->LvOptimaize)) $questlevelrange .= " (<b>".$questlistxml->OccurTerm->attributes()->LvOptimaize."</b>)";
		}
		else {
			$questlevelrange = $questlistxml->OccurTerm->attributes()->Lv."+";
		}


		//next quest
		if (strlen($questlist['nextquest'])) {
			$questhistory[] = "{$flolang->nextquest} <a href='".$florensia->outlink(array("questdetails", $questlist['nextquest'], $this->get_title($questlist['nextquest'])))."'>".$this->get_title($questlist['nextquest'])."</a>";
		}
		
		//prev quest
		if (strlen($questlist['privquest'])) {
			$questhistory[] = "{$flolang->previousquest} <a href='".$florensia->outlink(array("questdetails", $questlist['privquest'], $this->get_title($questlist['privquest'])))."'>".$this->get_title($questlist['privquest'])."</a>";
		}
	
		//questclasses
		$questclasses = "";
		foreach(str_split($questlist['questclasses']) as $class) {
			if (strtolower($class)=="p") continue;
			$classname = $florensia->get_classname($class);
			$questclasses.= "<img src='{$florensia->layer_rel}/icon_".strtolower($class).".png' title='".$florensia->escape($classname[0])."' alt='{$class}' style='height:12px; margin-right:2px; border:none;'>";
		}
		


		$addition = $floaddition->get_additionlist("quest", $questlist['questlistid']);
		if ($addition['not_implemented'] OR $addition['removed'] OR $addition['event']) {
			$stringsettings['color'] = "195,195,195";
			$tablecolor = "class='inactiveentry'";
		}
		if (count($addition)) {
			$flag = "<div class='small shortinfo_1'>".join("<br/>", $addition)."</div>";
			$flagicon = "<img src='{$florensia->layer_rel}/flag.gif' boder='0' style='height:10px;' ".popup($flag, "").">";
		}
		
		return "
			<table style='width:100%; border-collapse:0px; border-spacing:0px; padding:0px;' {$tablecolor}>
			<tr>
				<td style='width:450px'>
					<table cellpadding='0' cellspacing='0' width='100%' style='border-collapse:0px; border-spacing:0px; padding:0px;'>
						<tr>
							<td><a href='".$florensia->outlink(array("questdetails", $questlist['questlistid'], $questlist['questtitle_'.$classquesttext->language]))."'>".$questlist['questtitle_'.$classquesttext->language]."</a> {$flagicon}</td>
							<td style='text-align:right'>$questpicture</td>
						</tr>
					</table>
				</td>
				<td class='small'>
					<table cellpadding='0' cellspacing='0' width='100%' style='border-collapse:0px; border-spacing:0px; padding:0px;'>
						<tr>
							<td>{$flolang->quest_shortview_map} <a href='".$florensia->outlink(array("mapdetails", $questlistxml->attributes()->SourceArea, $stringtable->get_string($questlistxml->attributes()->SourceArea)))."'>".$stringtable->get_string($questlistxml->attributes()->SourceArea, array('protectionsmall'=>1, 'protectionlink'=>1))."</a></td>
							<td style='text-align:right'>{$questclasses}</td>
						</tr>
					</table>
					
				</td>
					
			</tr>
			<tr>
				<td style='width:400px; padding-left:20px;' class='small'>{$flolang->quest_shortview_level} $questlevelrange<br />{$questhistory[0]}</td>
				<td class='small'>{$flolang->quest_shortview_npc} {$queststartnpc}<br />{$questhistory[1]}</td>
			</tr>
			</table>
		";
	}

	function get_classlist($questlistxml) {
		$dbclasses=array();
		$i=0;
		foreach ($questlistxml->OccurTerm->attributes() as $key => $value) {
			foreach ($value as $classkey => $classvalue) {
				if (preg_match('/^Class[0-9]$/', $classkey)) {
					if (intval($classvalue)) { //class active
						eval("\$var = \$value->SubClass$i;");
						array_push($dbclasses,$var);
					}
				$i++;
				}
			}
		}
		return join("", $dbclasses);
	}

	function get_title($questlistid) {
		if (!strlen($questlistid)) return;
		global $classquesttext, $flolang;
			$queryquest = MYSQL_QUERY("SELECT questtitle_".$classquesttext->language." FROM server_questlist WHERE questlistid = '$questlistid' LIMIT 1");
			if ($quest = MYSQL_FETCH_ARRAY($queryquest)) {
				return $quest['questtitle_'.$classquesttext->language];
			}
			#$flolang->load("quest");
			#$flolang->questnotitle
			return "-???-";
	}
}

?>