<?PHP

class floclass {

	function create_item($item) {
		return new floclass_item($item);
	}
}









class floclass_item {
	

	public static $type = array();
	
	static function get_type($typeid) {
		global $flolang;
		if (!in_array($typeid, self::$type)) {
			$querytypes = MYSQL_QUERY("SELECT name_{$flolang->language} FROM flobase_item_types WHERE itemtypeid='".mysql_real_escape_string($typeid)."'");
			if ($types = MYSQL_FETCH_ARRAY($querytypes)) { self::$type[$typeid] = $types['name_'.$flolang->language]; }
			else self::$type[$typeid] = $typeid;
		}		
		return self::$type[$typeid];
	}
	
	static function get_typeselect($specialtypes="all") {
		global $flolang;
		$flolang->load("item");

		if ($specialtypes!="all") $specialtypes = explode(",", $specialtypes);
		$orderselected[$_GET['itemtype']]="selected='selected'";
		$querytypes = MYSQL_QUERY("SELECT * FROM flobase_item_types");
		while ($types = MYSQL_FETCH_ARRAY($querytypes)) {
			if ($specialtypes!="all" && !in_array($types['itemtypeid'],$specialtypes)) continue;
			$selecttypes .= "<option value='".$types['itemtypeid']."' ".$orderselected[$types['itemtypeid']].">".$types['name_'.$flolang->language]."</option>";
		}
		$typeselect = "
			<select name='itemtype' class='small'>
				<option value='all'>{$flolang->item_select_alltypes}</option>
				$selecttypes
			</select>
		";
		return $typeselect;
	}
	
	public function get_upgradeoverview() {
		global $flolang, $florensia, $stringtable;
		$upgradeid = $this->data[$florensia->get_columnname("upgraderule", "item")];
		if (!$upgradeid OR $upgradeid=="#") return false;
			
		preg_match('/^(.+)([0-9]{2})$/', $upgradeid, $upgraderuleid);
		$upgrades = array();
		$queryupgraderule = MYSQL_QUERY("SELECT * FROM server_upgraderule WHERE ".$florensia->get_columnname("server_upgraderule_ruleid", "misc")." LIKE '".$upgraderuleid[1]."__' AND ".$florensia->get_columnname("server_upgraderule_upgradelevel", "misc")."!='0'");
		for ($x=1; $upgraderule = MYSQL_FETCH_ARRAY($queryupgraderule); $x++) {
			$upgradeeffecttable = new floclass_detailstable();
			for ($i=0; $i<=3; $i++) {
				if ($upgraderule[$florensia->get_columnname("server_upgraderule_effectid{$i}", "misc")]=="4294967295") continue;
				$queryupgradeeffect = MYSQL_QUERY("SELECT name_".$flolang->language.", code_operator FROM flobase_item_effect WHERE effectid='".$upgraderule[$florensia->get_columnname("server_upgraderule_effectid{$i}", "misc")]."' LIMIT 1");
				if ($upgradeeffect = MYSQL_FETCH_ARRAY($queryupgradeeffect)) {
		// 			unset($startoperator, $endoperator);
					$amount = bin2float($upgraderule[$florensia->get_columnname("server_upgraderule_effectvalue{$i}", "misc")]);
		//			switch ($upgradeeffect['code_operator']) {
		// 				case "*": { $endoperator="%"; break;}
		// 				case "+": { $startoperator="+"; break;	}
		// 			}

					$upgradeeffecttable->add($upgradeeffect['name_'.$flolang->language], "{$startoperator}{$amount}{$endoperator}", "server_upgraderule_effectvalue{$i}");
				}
			}
			$upgrades[] = $upgradeeffecttable->display();
		}
		
		if (count($upgrades)) {
			for ($i=1; $i<=count($upgrades); $i++) {
				$tabbarupgrade['up_'.$i] = array("anchor"=>"up_{$i}", "name"=>"+{$i}", "desc"=>false);
				$upgrades[$i-1] = "<div name='up_{$i}'>".$upgrades[$i-1]."</div>";
			}
			$upgradecontent['tabbar'] = $florensia->tabbar($tabbarupgrade, array('small'=>true, 'desc'=>false));
			return "
				<div class='small' style='margin-top:10px'>
					<div style='font-weight:bold;'>{$flolang->itemupgraderule}</div>
					<div style='font-size:85%'>{$upgradecontent['tabbar']['tabbar']}</div>
					".join("\n", $upgrades)."
				</div>
				{$upgradecontent['tabbar']['jscript']}
			";
		}
	}
	
	public $data = false;
	//construct
	function __construct($item) {
		global $florensia;
		if (is_string($item)) {
			#item need to be loaded
			$data = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM server_item_idtable WHERE itemid='".mysql_real_escape_string($item)."'"));
			if ($data) {
				$details = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM server_item_{$data['tableid']} as d, server_stringtable as s WHERE d.".$florensia->get_columnname("itemid", "item")."=s.Code AND d.".$florensia->get_columnname("itemid", "item")."='".mysql_real_escape_string($item)."'"));
				if ($details) {
					$this->data = array_merge($data, $details);
				} #else, invalid
			} #else, item cannot be found
		} elseif (is_array($item)) $this->data = $item; #already loaded
	}
	

	#return if the item has valid data or cannot be found
	public function is_valid() {
		return $this->data ? true : false;
	}
	
	#returns name of the item
	public function get_name() {
		global $stringtable;
		return $this->data[$stringtable->language];
	}
	
	#creates a shortinfo-box
	public function shortinfo($settings=array(), $specialattributes=array()) {
		global $florensia, $stringtable, $flolang, $flouserdata, $floaddition;
		$flolang->load("item,market");
		
		$defaultsettings = array('namemaxlength'=>0);
		$settings = array_merge($defaultsettings, $settings);

		if (!is_array($this->data)) return "";


			if (isset($this->data[$florensia->get_columnname("lvlland", "item")])) {
				$details[] = array("key"=>$flolang->item_shortinfo_landlvl, "value"=>$this->data[$florensia->get_columnname("lvlland", "item")]);
			}
			if (isset($this->data[$florensia->get_columnname("landclass", "item")])) {
						$details[] = array("key"=>$flolang->item_shortinfo_class, "value"=>join(", ", $florensia->get_classname($this->data[$florensia->get_columnname("landclass", "item")], "land")));
			}
			if (isset($this->data[$florensia->get_columnname("lvlsea", "item")])) {
				$details[] = array("key"=>$flolang->item_shortinfo_sealvl, "value"=>$this->data[$florensia->get_columnname("lvlsea", "item")]);
			}
			if (isset($this->data[$florensia->get_columnname("seaclass", "item")])) {
						$details[] = array("key"=>$flolang->item_shortinfo_class, "value"=>join(", ", $florensia->get_classname($this->data[$florensia->get_columnname("seaclass", "item")], "sea")));

			}
			if (isset($this->data[$florensia->get_columnname("duration", "item")]) && $this->data[$florensia->get_columnname("duration", "item")]!="4294967295") {
						$details[] = array("key"=>$flolang->item_shortinfo_duration, "value"=>round($this->data[$florensia->get_columnname("duration", "item")]/60)." h");
			}
			if (isset($this->data[$florensia->get_columnname("gender", "item")]) && $this->data[$florensia->get_columnname("gender", "item")]!="4294967295") {
						if ($this->data[$florensia->get_columnname("gender", "item")]==1) $details[] = array("key"=>$flolang->item_shortinfo_gender, "value"=>"Female");
						else $details[] = array("key"=>$flolang->item_shortinfo_gender, "value"=>"Male");
			}

		$queryitemcategorie = MYSQL_QUERY("SELECT selectby_itemtype FROM flobase_item_categories WHERE name_table='".$this->data['tableid']."'");
		$itemcategorie = MYSQL_FETCH_ARRAY($queryitemcategorie);
		if ($itemcategorie['selectby_itemtype']) {
			$details[] = array("key"=>$flolang->item_shortinfo_itemtype, "value"=>$this->get_type($this->data[$florensia->get_columnname("itemtype", "item")]));
		}

		if ($this->data['tableid']=="skillbookitem") {
			$queryskill = MYSQL_QUERY("SELECT ".$florensia->get_columnname("masterlevel", "skill").", ".$florensia->get_columnname("classid", "skill")." FROM server_skill WHERE ".$florensia->get_columnname("bookid", "skill")."='".$this->data[$florensia->get_columnname("description", "item")]."' AND ".$florensia->get_columnname("skilllevel", "skill")."!='0' ORDER BY ".$florensia->get_columnname("skilllevel", "skill")."");
				if ($skill = MYSQL_FETCH_ARRAY($queryskill)) {
					$details[] = array("key"=>$flolang->item_shortinfo_skillmaxlvl, "value"=>$skill[$florensia->get_columnname("masterlevel", "skill")]);
					unset($class);
					$class .= join(", ", $florensia->get_classname($skill[$florensia->get_columnname("classid", "skill")], "land"));
					$class .= join(", ", $florensia->get_classname($skill[$florensia->get_columnname("classid", "skill")], "sea"));
					$details[] = array("key"=>$flolang->item_shortinfo_class, "value"=>$class);
				}
		}

		foreach ($specialattributes as $key => $value) {
			$details[] = array("key"=>"$key:", "value"=>$value);
		}

		$shortinfolink = $florensia->outlink(array("itemdetails", $this->data['itemid'], $this->get_name()));

		//-------- end normal shortinfo --------------------
		unset($exchangestatus);
		$maxlength = 0;

		if (intval($settings['marketid'])) {
			global $parser;

			$querymarket = MYSQL_QUERY("SELECT userid, itemid, itemamount, exchange, exchangegelt, exchangetype, server, charname, timeout, marketlanguage FROM flobase_usermarket as m, flobase_character as c WHERE id='{$settings['marketid']}' AND m.characterid=c.characterid");
			if ($market = MYSQL_FETCH_ARRAY($querymarket)) {

				unset($marketlanguage);
				$marketlanguagelist = explode(",", $market['marketlanguage']);
				foreach ($marketlanguagelist as $languageid) {
					$marketlanguage .= "<img src='{$florensia->layer_rel}/flags/png/".$flolang->lang[$languageid]->flagid.".png' alt='".$flolang->lang[$languageid]->languagename."' title='".$flolang->lang[$languageid]->languagename."' border='0'> ";
				}

				$details2[] = array("key"=>$flolang->market_shortinfo_user, "value"=>$flouserdata->get_username($market['userid'])." $marketlanguage");
				$details2[] = array("key"=>"", "value"=>"<a href='".$florensia->outlink(array("usermarket", $market['userid'], $flouserdata->get_username($market['userid'], array('rawoutput'=>true))))."'>".$flolang->sprintf($flolang->market_details_info_usermarketplace, $florensia->escape($flouserdata->get_username($market['userid'], array('rawoutput'=>true))))."</a>");
				$details2[] = array("key"=>$flolang->market_shortinfo_character, "value"=>"<a href='".$florensia->outlink(array('characterdetails', $market['charname']))."'>".$florensia->escape($market['charname'])."</a>");
				$details2[] = array("key"=>$flolang->market_shortinfo_server, "value"=>"<a href='".$florensia->outlink(array('statistics', $market['server']))."'>{$market['server']}</a>");
				if ($market['exchangegelt']) $details2[] = array("key"=>"Gelt:", "value"=>get_geltstring($market['exchangegelt'], $market['itemamount']));
				$details2[] = array("key"=>$flolang->market_shortinfo_amount, "value"=>$market['itemamount']);
				$details2[] = array("key"=>$flolang->market_shortinfo_until, "value"=>date("m.d.", $market['timeout']));
				if ($market['exchangetype']=="sell") $market_exchangestatus = $flolang->market_shortinfo_exchangetype_sell." ";
				else $market_exchangestatus = $flolang->market_shortinfo_exchangetype_buy." ";
	
				$parser_options = array(
					'allow_html' => 0,
					'allow_mycode' => 1,
					'allow_smilies' => 0,
					'allow_imgcode' => 0,
					'filter_badwords' => 1
				);

				$addinfo = strip_tags($parser->parse_message($market['exchange'], $parser_options));
				if (strlen($addinfo)>0 && !$settings['fullmarketexchange']) {
					if (strlen($addinfo)>120) $details3 = substr($addinfo, 0, 120)."...";
					else $details3 = $addinfo;
				}
				$shortinfolink = $florensia->outlink(array("itemdetails", $market['itemid'], $this->get_name()), array(), array("anchor"=>"market"));
			}
			
		} elseif ($settings['marketlist']) {
			$marketlist=array();
			$querymarket = MYSQL_QUERY("SELECT id FROM flobase_usermarket WHERE itemid='{$this->data['itemid']}' AND exchangetype='buy'");
			if (($buyamount = MYSQL_NUM_ROWS($querymarket)) > 0) {
				$details2[] = array("key"=>"&nbsp;", "value"=>"{$flolang->market_subtitle_buy}:</span> <a href='".$florensia->outlink(array("itemdetails", $this->data['itemid'], $this->get_name()), array(), array("anchor"=>"market"))."'>".$flolang->sprintf($flolang->market_itemdetails_summary_buy, $buyamount)."</a>");
			}
			$querymarket = MYSQL_QUERY("SELECT id FROM flobase_usermarket WHERE itemid='{$this->data['itemid']}' AND exchangetype='sell'");
			if (($sellamount = MYSQL_NUM_ROWS($querymarket)) > 0) {
				$details2[] = array("key"=>"&nbsp;", "value"=>"{$flolang->market_subtitle_sell}:</span> <a href='".$florensia->outlink(array("itemdetails", $this->data['itemid'], $this->get_name()), array(), array("anchor"=>"market"))."'>".$flolang->sprintf($flolang->market_itemdetails_summary_sell, $sellamount)."</a>");
			}
		}
		
		if (!intval($settings['marketid'])) {
			$limit = 4-count($details2); //if marketlist get some results...
			$querydropnpc = MYSQL_QUERY("SELECT s.{$stringtable->language} as name, d.npcid, COUNT(v.userid) as verified FROM flobase_droplist as d INNER JOIN server_stringtable as s ON (d.npcid=s.Code) LEFT JOIN flobase_droplist_verified as v ON (v.dropid=d.dropid) WHERE d.itemid='{$this->data['itemid']}' GROUP BY d.dropid ORDER BY verified DESC, thumpsup DESC, thumpsdown LIMIT {$limit}");
			while ($dropnpc = MYSQL_FETCH_ARRAY($querydropnpc)) {
				$details2[] = array("key"=>"&nbsp;", "value"=>"<a href='".$florensia->outlink(array("npcdetails", $dropnpc['npcid'], $dropnpc['name']))."'>".$florensia->escape($dropnpc['name'])."</a>");
			}
		}

		//-------- additional details

		if (!isset($details)) $details=array();
		unset($detailstable);
		if (intval($settings['marketid'])) {
			foreach ($details as $key => $value) {
				$detailstable .= "<tr><td>{$value['key']} {$value['value']}</td></tr>";
			}
		} else {
			for ($i=0; $i<count($details); $i=$i+2) {
				$detailstable .= "<tr><td style='width:50%'>".$details[$i]['key']." ".$details[$i]['value']."</td><td>".$details[$i+1]['key']." ".$details[$i+1]['value']."</td></tr>";
			}
		}

		if (!isset($details2)) $details2['']="";
		unset($detailstable2);
		foreach ($details2 as $key => $value) {
			$detailstable2 .= "<tr><td style='text-align:right;'>{$value['key']} {$value['value']}</td></tr>";
		}

		unset($detailstable3);
		if (isset($details3)) {
			$detailstable3 = "<tr><td colspan='2'>$details3</td></tr>";
		}

		
		$stringsettings = array('protectionlink'=>1, 'itemgrade'=>$this->data[$florensia->get_columnname("raregrade", "item")], 'maxlength'=>$settings['namemaxlength']);
		
		$addition = $floaddition->get_additionlist("item", $this->data['itemid']);
		if ($addition['not_implemented'] OR $addition['removed'] OR $addition['event']) {
			$stringsettings['color'] = "195,195,195";
			$tablecolor = "class='inactiveentry'";
		}
		if (count($addition)) {
			$flag = "<div class='small shortinfo_1'>".join("<br/>", $addition)."</div>";
			$flagicon = "<img src='{$florensia->layer_rel}/flag.gif' boder='0' style='height:12px;' ".popup($flag, "").">";
		}
	
		return "
			<table style='width:100%;' class='small {$tablecolor}'>
				<tr>
					<td style='height:10px;'><a href='$shortinfolink'>{$market_exchangestatus}".$stringtable->get_string($this->data['itemid'], $stringsettings)."</a> {$flagicon}</td>
					<td style='text-align:right; vertical-align:top' rowspan='2'><table style='width:100%;'>$detailstable2</table></td>
				</tr>
				<tr>
					<td style='vertical-align:top; width:60%;'>
						<table style='width:100%'>
							<tr>
								<td style='width:32px; vertical-align:top'><a href='$shortinfolink'>".$florensia->pictureprotection($this->data['itempicture'], $this->get_name())."</a></td>
								<td style='vertical-align:top; margin-left:10px;'>
									<table style='width:100%;'>
										$detailstable
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				$detailstable3	
			</table>
		";
	}
	
	
	public function details() {
		global $florensia, $flolang;
		
		$details = array();
		$detailstable = new floclass_detailstable();
		
		if (!$this->data) return $detailstable->display();
		
		foreach ($this->data as $key => $value) {
			$details[strtolower($key)] = $value;
		}
		
		$querycolumns = MYSQL_QUERY("SELECT * FROM flobase_item_columns WHERE secret='0' ORDER BY vieworder");
		while ($columns = MYSQL_FETCH_ARRAY($querycolumns)) {

			if ($columns['code_korean']===NULL && isset($write) && !$placeholder) {
				$detailstable->add();
				continue;
			}

			//set korean-code to tolower
			$columns['code_korean'] = strtolower($columns['code_korean']);

			//ausslassen wenn defaultwert oder nicht gesetzt
			if ($columns['defaultvalue']==$details[$columns['code_korean']] OR !isset($details[$columns['code_korean']])) continue;
			if ($columns['floatvalue']) $details[$columns['code_korean']] = bin2float($details[$columns['code_korean']]);
			elseif ($details[$columns['code_korean']]=="4294967286" OR $details[$columns['code_korean']]=="4294967293") continue;

					if (preg_match('/^itembonus([0-9]{1})$/', $columns['code_english'], $itembonus)) {
						if ($details[$florensia->get_columnname("itembonus".$itembonus['1']."code", "item")] == "4294967295") continue;
						$querybonusname = MYSQL_QUERY("SELECT name_".$flolang->language.", end_operator, effectid FROM flobase_item_effect WHERE effectid='".intval($details[$florensia->get_columnname("itembonus".$itembonus['1']."code", "item")])."'");
						if (!($bonusname = MYSQL_FETCH_ARRAY($querybonusname))) $bonusname['effectid'] = intval($details[$florensia->get_columnname("itembonus".$itembonus['1']."code", "item")]);
						switch (intval($bonusname['effectid'])) {
							case 117: continue; //not defined
							case 20: //Melee distance
							case 21: //Range distance
							case 22: {//Magic distance
								$details[$columns['code_korean']] = bcdiv($details[$columns['code_korean']], 100, 2);
								break;
							}
							//next ones are not defined in our database, manual overwrite
							case 155: {
								$details[$florensia->get_columnname("itembonus".$itembonus['1']."operator", "item")] = "+";
								$bonusname['name_'.$flolang->language] = "Kon";
								break;
							}
							case 156: {
								$details[$florensia->get_columnname("itembonus".$itembonus['1']."operator", "item")] = "+";
								$bonusname['name_'.$flolang->language] = "Str";
								break;
							}
							case 157: {
								$details[$florensia->get_columnname("itembonus".$itembonus['1']."operator", "item")] = "+";
								$bonusname['name_'.$flolang->language] = "Int";
								break;
							}
							case 158: {
								$details[$florensia->get_columnname("itembonus".$itembonus['1']."operator", "item")] = "+";
								$bonusname['name_'.$flolang->language] = "Dex";
								break;
							}
							case 159: {
								$details[$florensia->get_columnname("itembonus".$itembonus['1']."operator", "item")] = "+";
								$bonusname['name_'.$flolang->language] = "Wis";
								break;
							}
							case 160: {
								$details[$florensia->get_columnname("itembonus".$itembonus['1']."operator", "item")] = "+";
								$bonusname['name_'.$flolang->language] = "Will";
								break;
							}
							
						}
						if ($bonusname['effectid']==117) continue;
						unset($startoperator, $endoperator);
						switch ($details[$florensia->get_columnname("itembonus".$itembonus['1']."operator", "item")]) {
							case "*": { $endoperator="%"; $details[$columns['code_korean']] = round($details[$columns['code_korean']]*100); break;}
							case "+": { $startoperator="+";  break;}
						}
						if ($bonusname['end_operator']) { $endoperator=$bonusname['end_operator']; }

						$detailstable->add($bonusname['name_'.$flolang->language], $startoperator.$details[$columns['code_korean']].$endoperator, $columns['code_english']);
						continue;
					}

					switch ($columns['code_english']) {
						case "physicalphmax": {
							if (intval($details[strtolower($florensia->get_columnname("physicalphmin", "item"))])==0 && intval($details[$columns['code_korean']])==0) continue;
							if (intval($details[$columns['code_korean']])==0) { $details[$columns['code_korean']]=$details[strtolower($florensia->get_columnname("physicalphmin", "item"))]; }
							$detailstable->add($columns['name_'.$flolang->language], $details[strtolower($florensia->get_columnname("physicalphmin", "item"))]." ~ ".$details[$columns['code_korean']], $columns['code_english'], "physicalphmin");
							break;
						}
						case "spellphmax": {
							if (intval($details[strtolower($florensia->get_columnname("spellphmin", "item"))])==0 && intval($details[$columns['code_korean']])==0) continue;
							if (intval($details[$columns['code_korean']])==0) { $details[$columns['code_korean']]=$details[strtolower($florensia->get_columnname("spellphmin", "item"))]; }
							$detailstable->add($columns['name_'.$flolang->language], $details[strtolower($florensia->get_columnname("spellphmin", "item"))]." ~ ".$details[$columns['code_korean']], $columns['code_english'], "spellphmin");
							break;
						}
						case "exchange": {
							if (intval($details[$columns['code_korean']])==0) { $value = $flolang->no; }
							else { $value = $flolang->yes; }
							$detailstable->add($columns['name_'.$flolang->language], $value, $columns['code_english']);
							break; 
						}
						case "trading": {
							if (intval($details[$columns['code_korean']])==0) { $value = $flolang->no; }
							else { $value = $flolang->yes; }
							$detailstable->add($columns['name_'.$flolang->language], $value, $columns['code_english']);
							break; 
						}
						case "tradingshop": {
							if (intval($details[$columns['code_korean']])==0) { $value = $flolang->no; }
							else { $value = $flolang->yes; }
							$detailstable->add($columns['name_'.$flolang->language], $value, $columns['code_english']);
							break; 
						}
						case "questitem": {
							if (intval($details[$columns['code_korean']])==0) { $value = $flolang->no; }
							else { $value = $flolang->yes; }
							$detailstable->add($columns['name_'.$flolang->language], $value, $columns['code_english']);
							break; 
						}
						case "landclass": {
							foreach ($florensia->get_classname($details[$columns['code_korean']], "land") as $key => $classid) {
								$detailstable->add(bcadd($key,1).". ".$columns['name_'.$flolang->language], $classid, $columns['code_english']);
							}
							break;
						}
						case "seaclass": {
							foreach ($florensia->get_classname($details[$columns['code_korean']], "sea") as $key => $classid) {
								$detailstable->add(bcadd($key,1).". ".$columns['name_'.$flolang->language], $classid, $columns['code_english']);
							}
							break;
						}
						case "spellph": {
							if (intval($details[$florensia->get_columnname("spellphmax", "item")])!=0) { continue; }
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "physicalph": {
							if ($column[$columns['physicalphmin']]!=0 OR $column[$columns['physicalphmax']]!=0) { continue; }
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "acceleration": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "deceleration": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "shiprange": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "shiphitrange": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "shipgunspeed": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "reloadspeed": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/1000;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "explosiveradius": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/100;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "balance": {
							if ($details[$columns['code_korean']]=="4294967295" && $itemtable['tableid']=="shipfrontitem") $details[$columns['code_korean']]="-1";
							elseif ($details[$columns['code_korean']]=="4294967295") continue;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "duration": {
							$details[$columns['code_korean']]=round($details[$columns['code_korean']]/60);
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "gender": {
							if ($details[$columns['code_korean']]==1) $details[$columns['code_korean']] = $flolang->female;
							else $details[$columns['code_korean']] = $flolang->male;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "range": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/100;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "attackspeed": {
							if ($details[$columns['code_korean']]=="0") continue;
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/1000; //why there was a +1 at the end?
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "itemtype": {
							$querytypes = MYSQL_QUERY("SELECT * FROM flobase_item_types WHERE itemtypeid='".$details[$columns['code_korean']]."'");
							if ($types = MYSQL_FETCH_ARRAY($querytypes)) { $details[$columns['code_korean']] = $types['name_'.$flolang->language]; }
							else $details[$columns['code_korean']] = $typeid;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						default : { $detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']); }
					}		
		}
		if (isset($itemtable['tableid']) && $itemtable['tableid']=="skillbookitem") {
			//for ($i=1; $i<3; $i++) {
			//	if ($i==1) $querydescription = MYSQL_QUERY("SELECT * FROM server_skill WHERE ".$florensia->get_columnname("bookid", "skill")."='".$details[$florensia->get_columnname("description", "item")]."' ORDER BY ".$florensia->get_columnname("skillid", "skill")."");
			//	else $querydescription = MYSQL_QUERY("SELECT * FROM server_spell WHERE ".$florensia->get_columnname("bookid", "skill")."='".$details[$florensia->get_columnname("description", "item")]."' ORDER BY ".$florensia->get_columnname("skillid", "skill")."");
				$querydescription = MYSQL_QUERY("SELECT ".$florensia->get_columnname("masterlevel", "skill").",".$florensia->get_columnname("classid", "skill")." FROM server_skill WHERE ".$florensia->get_columnname("bookid", "skill")."='".$details[$florensia->get_columnname("description", "item")]."' ORDER BY ".$florensia->get_columnname("skillid", "skill")."");
				if ($description = MYSQL_FETCH_ARRAY($querydescription)) {
					foreach ($florensia->get_classname($description[$florensia->get_columnname("classid", "skill")], "land") as $key => $classid) {
						$detailstable->add("Class", $classid, "skillclass_".$key);
					}
					foreach ($florensia->get_classname($description[$florensia->get_columnname("classid", "skill")], "sea") as $key => $classid) {
						$detailstable->add("Class", $classid, "skillclass_".$key);
					}
					$detailstable->add("Max. Level", $description[$florensia->get_columnname("masterlevel", "skill")], "skillmasterlevel");
					//break;
				}
			//}
		}
		
		return $detailstable->display();
	}


}

class floclass_detailstable {
	var $detailslist = array();
	
	function __construct() {}
	
	public function add($title="", $value="", $columnname="-", $gotitle=false) {	
		if ($gotitle) $columnname = $gotitle;
		$this->detailslist[$columnname] = array("name"=>$title, "value"=>$value);
	}
	
	public function display() {
		$list = "";
		foreach($this->detailslist as $columnname => $v) {
			$list .= "
				<tr>
					<td style='vertical-align:top;'>{$v['name']}</td>
					<td style='text-align:right; vertical-align:top;'>{$v['value']}</td>
				</tr>
			";
		}
		return "<div class='small subtitle' style='font-weight:normal; padding:2px;'>
				<table style='width:100%;'>
					{$list}
				</table>
			</div>";
	}	
	
}





class floclass_guild {
    
	static function check_privacy($priv, $guildid) {
		global $flouser;
		
		if ($flouser->get_permission("character", "owneroverride") || $flouser->get_permission("guild", "owneroverride")) return true;
		
		//check always both ways, so it's faster in some cases
			$guildid = intval($guildid);
			if (!strlen($priv)) $priv = "a";

			if ($priv=="a") return true;
			elseif (!$flouser->userid) return false;
			
			if ($priv=="r") return true;
			elseif (!strlen($flouser->user['flobase_characterkey'])) return false; //no verified chars. nothing to check
			
			$privacy = $flouser->get_privacy();
			
			if (!in_array($guildid, $flouser->privacy['guildlist'])) return false;
			elseif ($priv=="g") return true;
			elseif ($priv=="l") {
				list($hits) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(d.characterid) FROM flobase_character as c, flobase_character_data as d WHERE c.characterid=d.characterid AND d.ownerid='{$flouser->userid}' AND guildid='{$guildid}' AND guildgrade='5'"));
				if ($hits) return true;
			}
		return false;
	}
	
	static function get_privacy_desc($priv) {
		global $flolang;
		switch ($priv) {
			case "a": return $flolang->guild_privacy_all;
			case "r": return $flolang->guild_privacy_registered;
			case "g": return $flolang->guild_privacy_guildmates;
			case "l": return $flolang->guild_privacy_leader;
		}
		return "-";
	}
	
	static function get_language_pic($flag) {
		global $florensia, $flolang;
		if (!strlen($flag)) return "";
		
                if ($flag == "--") {
		    return " <img src='{$florensia->layer_rel}/flags/png/--.png' style='border:none;' alt='{$flolang->guild_language_international}' title='{$flolang->guild_language_international}'>";
                    return " [{$flolang->guild_language_international}]";
                } elseif (preg_match("/^[a-z]{2}$/i", $flag) && is_file("{$florensia->layer_abs}/flags/png/".strtolower($flag).".png")) {
                    return " <img src='{$florensia->layer_rel}/flags/png/{$flag}.png' style='border:none;' alt='{$flag}' title='{$flag}'>";
                } else {
                    return "";
                }
	}
}



class floclass_npc {
	static $bossfactor="200";
	static $bosscolor="255,189,143";
	
	public $data = false;
	//construct
	function __construct($npc) {
		global $florensia;
		if (is_string($npc)) {
			$this->data = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM server_npc WHERE ".$florensia->get_columnname("npcid", "npc")."='".mysql_real_escape_string($npc)."'"));
		} elseif (is_array($npc)) $this->data = $npc; #already loaded
	}
	
	
	#return if the item has valid data or cannot be found
	public function is_valid() {
		return $this->data ? true : false;
	}
	
	public function shortinfo($settings=array(), $specialattributes=array()) {
		global $florensia, $stringtable, $flolang, $floaddition, $flouser;
		$flolang->load("npc");
		if (!$this->is_valid()) return "";

		$details[] = array("key"=>$flolang->npc_shortinfo_level, "value"=>$this->data[$florensia->get_columnname("level", "npc")]);
		$details[] = array("key"=>$flolang->npc_shortinfo_type, "value"=>preg_replace('/Char$/', '', $this->data['npc_file']));

		//class land/sea
		if (intval($this->data[$florensia->get_columnname("fielddividing", "npc")])) $details2[] = array("key"=>"", "value"=>"<img src='{$florensia->layer_rel}/sealv.gif' height='12' alt='Sea'>");
		else $details2[] = array("key"=>"", "value"=>"<img src='{$florensia->layer_rel}/land.gif' height='12' alt='Land'>");
		
		//maplocations of possible
		$maploc = array();
		$querymap = MYSQL_QUERY("SELECT mapid FROM flobase_npc_coordinates WHERE npcid='".mysql_real_escape_string($this->data[$florensia->get_columnname("npcid", "npc")])."'");
		while ($map = MYSQL_FETCH_ARRAY($querymap)) {
			$maploc[] = "<a href='".$florensia->outlink(array("mapdetails", $map['mapid'], $stringtable->get_string($map['mapid'])), array('npcs'=>$this->data[$florensia->get_columnname("npcid", "npc")]))."'>".$stringtable->get_string($map['mapid'], array("protectionlink"=>1, "protectionsmall"=>1))."</a>";
		}
		if (count($maploc)) {
			$details2[] = array("key"=>"", "value"=>join(", ", $maploc));
		}


		foreach ($specialattributes as $key => $value) {
			$details[] = array("key"=>"$key:", "value"=>$value);
		}
		
		if ($this->data['npc_bossfactor']>floclass_npc::$bossfactor) $npccolor=floclass_npc::$bosscolor;
		else $npccolor=0;

		if (!isset($details)) $details=array();
		unset($detailstable);
		for ($i=0; $i<count($details); $i=$i+2) {
			$detailstable .= "<tr><td style='width:50%'>".$details[$i]['key']." ".$details[$i]['value']."</td><td>".$details[$i+1]['key']." ".$details[$i+1]['value']."</td></tr>";
		}

		if (!isset($details2)) $details2['']="";
		unset($detailstable2);
		foreach ($details2 as $key => $value) {
			$detailstable2 .= "<tr><td style='text-align:right;'>{$value['key']} {$value['value']}</td></tr>";
		}

		unset($detailstable3);
		if (isset($details3)) {
			$detailstable3 = "<tr><td colspan='2'>$details3</td></tr>";
		}
		
		
		
		$stringsettings = array('protectionlink'=>1, 'color'=>$npccolor);

		$addition = $floaddition->get_additionlist("npc", $this->data[$florensia->get_columnname("npcid", "npc")]);
		if ($addition['not_implemented'] OR $addition['removed'] OR $addition['event']) {
			$stringsettings['color'] = "195,195,195";
			$tablecolor = "class='inactiveentry'";
		}
		if (count($addition)) {
			$flag = "<div class='small shortinfo_1'>".join("<br/>", $addition)."</div>";
			$flagicon = "<img src='{$florensia->layer_rel}/flag.gif' boder='0' style='height:12px;' ".popup($flag, "").">";
		}

		return "
			<table style='width:100%;' class='small {$tablecolor}'>
				<tr>
					<td style='height:10px;'><a href='".$florensia->outlink(array("npcdetails",$this->data[$florensia->get_columnname("npcid", "npc")], $stringtable->get_string($this->data[$florensia->get_columnname("npcid", "npc")])))."'>".$stringtable->get_string($this->data[$florensia->get_columnname("npcid", "npc")], $stringsettings)."</a>{$flagicon}</td>
					<td style='text-align:right; vertical-align:top' rowspan='2'><table style='width:100%;'>$detailstable2</table></td>
				</tr>
				<tr>
					<td style='vertical-align:top; width:60%;'>
						<table style='width:100%'>
							<tr>
								<td style='width:32px; vertical-align:top'><a href='".$florensia->outlink(array("npcdetails",$this->data[$florensia->get_columnname("npcid", "npc")], $stringtable->get_string($this->data[$florensia->get_columnname("npcid", "npc")])))."'>".$florensia->pictureprotection($this->data['npc_picture'], $stringtable->get_string($this->data[$florensia->get_columnname("npcid", "npc")]), "monster", array('maxheight'=>30))."</a></td>
								<td style='vertical-align:top; margin-left:10px;'>
									<table style='width:100%;'>
										$detailstable
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				$detailstable3	
			</table>
		";
	}
	
	public function details() {
		global $florensia, $flolang;
		
		$details = array();
		$detailstable = new floclass_detailstable();
		
		foreach ($this->data as $key => $value) {
			$details[strtolower($key)] = $value;
		}
		
		$querycolumns = MYSQL_QUERY("SELECT * FROM flobase_npc_columns WHERE secret='0' ORDER BY vieworder");
		while ($columns = MYSQL_FETCH_ARRAY($querycolumns)) {

			if ($columns['code_korean']===NULL && isset($write) && !$placeholder) {
				$detailstable->add();
				continue;
			}

			//set korean-code to tolower
			$columns['code_korean'] = strtolower($columns['code_korean']);

			//ausslassen wenn defaultwert oder nicht gesetzt
			if ($columns['defaultvalue']==$details[$columns['code_korean']] OR !isset($details[$columns['code_korean']])) continue;
			if ($columns['floatvalue']) $details[$columns['code_korean']] = bin2float($details[$columns['code_korean']]);
			
					switch ($columns['code_english']) {
						case "attackmax": {
							if (intval($details[$columns['code_korean']])==0) { $details[$columns['code_korean']]=$details[$florensia->get_columnname("attackmin", "npc")]; }
							$detailstable->add($columns['name_'.$flolang->language], $details[$florensia->get_columnname("attackmin", "npc")]." ~ ".$details[$columns['code_korean']], $columns['code_english'], "attackmin");
							break;
						}
						case "attackrange": {
							if (!intval($details[$columns['code_korean']])) { $details[$columns['code_korean']]="Melee"; }
							else { $details[$columns['code_korean']]="Range"; }
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']], $columns['code_english']);
							break;
						}
						case "airmonster": {
							if (intval($details[$columns['code_korean']])!=0) { $value = $flolang->yes; }
							else { $value = $flolang->no; }
							$detailstable->add($columns['name_'.$flolang->language], $value, $columns['code_english']);
							break; 
						}

						case "navalguns": {
							if (!intval($details[$florensia->get_columnname("attackrange", "npc")])) continue; //melee
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						}
						case "navalgunspeed": {
							if (!intval($details[$florensia->get_columnname("navalguns", "npc")])) continue; //no guns
							$detailstable->add($columns['name_'.$flolang->language], bcdiv($details[$columns['code_korean']],1000,1).$columns['endvalue'], $columns['code_english']);
							break; 
						}
						case "navalgunscope": {
							if (!intval($details[$florensia->get_columnname("navalguns", "npc")])) continue; //no guns
							$detailstable->add($columns['name_'.$flolang->language], bcdiv($details[$columns['code_korean']],100).$columns['endvalue'], $columns['code_english']);
							break; 
						}

 						case "exp": {

							//class land/sea
							if (intval($details[$florensia->get_columnname("fielddividing", "npc")])) $expclass = "sea";
							else $expclass = "land";

 							$expprocent = $florensia->get_exp($details[$columns['code_korean']], $details[$florensia->get_columnname("level", "npc")], $expclass);
 							$detailstable->add($columns['name_'.$flolang->language], "".$details[$columns['code_korean']]." ($expprocent/".$details[$florensia->get_columnname("level", "npc")].")", $columns['code_english']);
  							break; 
 						}
/*
						case "attackcooldown1": { }
						case "attackcooldown2": { }
						case "attackcooldown3": {
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/100;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']]."s", $columns['code_english']);
							break;
						}
*/
						case "attackpowerelement":
						case "resistenceelement":
						case "attackpowerillusion":
						case "resistenceillusion":
						case "attackpowerholy":
						case "resistenceholy":
						case "attackpowerdark":
						case "resistencedark":
						case "attackpowerphysical":
						case "resistencephysical":
						case "attackpowerpoison":
						case "resistencepoison":
						case "attackpowerfire":
						case "resistencefire":
						case "attackpowerice":
						case "resistenceice":
						case "attackpowerlighning":
						case "resistencelighning":
						case "attackpowerholyph":
						case "resistenceholyph":
						case "attackpowerdark":
						case "resistancedark":
						case "attackpowerabsolute":
						case "resistanceabsolute":
							$details[$columns['code_korean']]=$details[$columns['code_korean']]/10;
							$detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']);
							break;
						default : { $detailstable->add($columns['name_'.$flolang->language], $details[$columns['code_korean']].$columns['endvalue'], $columns['code_english']); break; }
					}		
		}
		
		return $detailstable->display();
	}
	
	
	static function save_coordinates($coords, $npcid, $mapid) {
		global $flouser, $florensia, $flolang, $flolog;
		$flolang->load("npc");

		$querymap = MYSQL_QUERY("SELECT LTWH FROM server_map WHERE mapid='".mysql_real_escape_string($mapid)."'");
		if ($map = MYSQL_FETCH_ARRAY($querymap)) {
			//map exists
			$coords = explode(";",$coords);

			$querynpcmap = MYSQL_QUERY("SELECT coordinates, contributed FROM flobase_npc_coordinates WHERE npcid='".mysql_real_escape_string($npcid)."' AND mapid='".mysql_real_escape_string($mapid)."'");
			if ($npcmap = MYSQL_FETCH_ARRAY($querynpcmap)) {
				$npcmap['coordinates'] = explode(";", $npcmap['coordinates']);
				foreach ($coords as $coordentry) {
					if (!preg_match('/^-?[0-9]+,-?[0-9]+$/', $coordentry)) continue;
					array_push($npcmap['coordinates'],$coordentry.",".$flouser->userid);
				}
				$npcmap['coordinates'] = join(";",array_unique($npcmap['coordinates']));
				
				$npcmap['contributed'] = explode(",", $npcmap['contributed']);
				array_push($npcmap['contributed'], $flouser->userid);
				$npcmap['contributed'] = join(",", array_unique($npcmap['contributed']));

				if (MYSQL_QUERY("UPDATE flobase_npc_coordinates SET lastchange='".date("U")."-{$flouser->userid}', coordinates='".mysql_real_escape_string($npcmap['coordinates'])."', contributed='{$npcmap['contributed']}' WHERE mapid='".mysql_real_escape_string($mapid)."' AND npcid='".mysql_real_escape_string($npcid)."'")) {
					$flolog->add("map:update", "{user:{$flouser->userid}} updated coordinates of {npc:{$npcid}} on {map:{$mapid}}");
					$florensia->notice($flolang->mapcoordinates_add_successful, "successful");
				}
				else {
					$flolog->add("error:map", "MySQL-Update-Error while adding coordinates to {npc:{$npcid}} on {map:{$mapid}}");
					$florensia->notice($flolang->mapcoordinates_add_error, "warning");
				}
			}
			else {
				$npcmap['coordinates']=array();
				foreach ($coords as $coordentry) {
					if (!preg_match('/^-?[0-9]+,-?[0-9]+$/', $coordentry)) continue;
					array_push($npcmap['coordinates'],$coordentry.",".$flouser->userid);
				}
				$npcmap['coordinates'] = join(";",$npcmap['coordinates']);

				if (MYSQL_QUERY("INSERT INTO flobase_npc_coordinates (npcid, mapid, coordinates, lastchange, contributed) VALUES('".mysql_real_escape_string($npcid)."', '".mysql_real_escape_string($mapid)."', '".mysql_real_escape_string($npcmap['coordinates'])."', '".date("U")."-{$flouser->userid}', '{$flouser->userid}')")) {
					$flolog->add("map:new", "{user:{$flouser->userid}} added coordinates of {npc:{$npcid}} on {map:{$mapid}}");
					$florensia->notice($flolang->mapcoordinates_add_successful, "successful");
				}
				else {
					$flolog->add("error:map", "MySQL-Insert-Error while adding coordinates to {npc:{$npcid}} on {map:{$mapid}}");
					$florensia->notice($flolang->mapcoordinates_add_error, "warning");
				}
			}
		}
	}
	
	static function get_maplist($npcid) {
		global $florensia, $stringtable;
		$maplist = array('ids'=>array(), 'list'=>array());
		$querymap = MYSQL_QUERY("SELECT mapid FROM flobase_npc_coordinates WHERE npcid='".mysql_real_escape_string($npcid)."'");
		while ($map = MYSQL_FETCH_ARRAY($querymap)) {
			$maplist['ids'][] = $map['mapid'];
			$maplist['list'][] = "<a href='".$florensia->outlink(array('mapdetails', $map['mapid'], $stringtable->get_string($map['mapid'])), array('npcs'=>$npcid))."' target='_blank'>".$stringtable->get_string($map['mapid'], array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
		}
		return $maplist;
	}
}



?>