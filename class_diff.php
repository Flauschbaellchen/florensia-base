<?PHP

class class_diff {
	var $difflist = array();

	function diff_parse_xml($xml_old, $xml, $cachestring, $diffstring) {
		global $diffcontent, $florensia, $sqldiff, $flolang;

		foreach (get_object_vars($xml) as $varkey => $varvalue) {
			if (!isset($xml_old->$varkey)) {
				//new!
				if (!strlen("".$varvalue)) { $this->diff_parse_xml($xml_old->$varkey, $xml->$varkey, $cachestring.",{$varkey}", "{$diffstring},{$varkey}"); }
				else {
// echo "NEW: ".preg_replace('/__diffxmlparse__/', 'new', "{$cachestring},$diffstring,{$varkey}")."<br />";
						$sqldiff->write_cache(preg_replace('/__diffxmlparse__/', 'new', "{$cachestring},$diffstring,{$varkey}"), $xml->$varkey);
						$diffcontent .= "<tr>
								<td style='width:33%; border-right:1px solid;'>$diffstring,{$varkey}</td>
								<td style='width:33%; border-right:1px solid;'><i>{$flolang->diff_inentry_noentry}</i></td>
								<td>".$florensia->escape($xml->$varkey)."</td>
							</tr>
						";
				}
			}
			else {
				//recursive
				if (!strlen("".$varvalue)) { $this->diff_parse_xml($xml_old->$varkey, $xml->$varkey, $cachestring.",{$varkey}", "{$diffstring},{$varkey}"); }
				elseif ("".$xml_old->$varkey != "".$xml->$varkey) {
						//diff!
						$sqldiff->write_cache(preg_replace('/__diffxmlparse__/', 'diff', "{$cachestring},$diffstring,{$varkey},oldversion"), $xml_old->$varkey);
						$sqldiff->write_cache(preg_replace('/__diffxmlparse__/', 'diff', "{$cachestring},$diffstring,{$varkey},newversion"), $xml->$varkey);
// echo "DIFF[1]: ".preg_replace('/__diffxmlparse__/', 'diff', "{$cachestring},$diffstring,{$varkey},oldversion")."<br />";
// echo "DIFF[2]: ".preg_replace('/__diffxmlparse__/', 'diff', "{$cachestring},$diffstring,{$varkey},newversion")."<br />";
						$diffcontent .= "<tr>
								<td style='width:33%; border-right:1px solid;'>$diffstring,{$varkey}</td>
								<td style='width:33%; border-right:1px solid;'>".$florensia->escape($xml_old->$varkey)."</td>
								<td>".$florensia->escape($xml->$varkey)."</td>
							</tr>
						";
				}
			}
		}

		//delete?
		foreach (get_object_vars($xml_old) as $varkey => $varvalue) {
			if (!isset($xml->$varkey)) {
				//delete!
				if (!strlen("".$varvalue)) { $this->diff_parse_xml($xml_old->$varkey, $xml->$varkey, $cachestring.",{$varkey}", "{$diffstring},{$varkey}"); }
				else {
// echo "DEL: ".preg_replace('/__diffxmlparse__/', 'del', "{$cachestring},$diffstring,{$varkey}")."<br />";
						$sqldiff->write_cache(preg_replace('/__diffxmlparse__/', 'del', "{$cachestring},$diffstring,{$varkey}"), $xml_old->$varkey);
						$diffcontent .= "<tr>
								<td style='width:33%; border-right:1px solid;'>{$diffkey}::$diffstring,{$varkey}</td>
								<td style='width:33%; border-right:1px solid;'>".$florensia->escape($xml_old->$varkey)."</td>
								<td><i>{$flolang->diff_inentry_removed}</i></td>
							</tr>
						";
				}
			}
		}
	}


	function create_diff_overview($dbtable, $subdiff) {
		global $florensia, $stringtable, $classquest, $flolang;
		
		if (preg_match('/^server_item_(.+)$/', $dbtable, $itemcat)) {
			$this->difflist['items']['title'] = $flolang->diff_subtitle_items;
						foreach(get_object_vars($subdiff->new) as $newkey => $newvalue) {
							$item = new floclass_item($newkey);
 							$this->difflist['items']['new'] .= "
 								<div><a href='".$florensia->outlink(array("itemdetails", $newvalue, $stringtable->get_string($newvalue)), array("escape"=>FALSE))."' target='_blank' ".popup("<div class='shortinfo_".$florensia->change()."' style='width:600px;'>".$item->shortinfo()."</div>").">".$stringtable->get_string($newvalue, array("protectionsmall"=>1, "protectionlink"=>1))."</a></div>
 							";
						}

 						foreach(get_object_vars($subdiff->diff) as $newkey => $newvalue) {
							unset($changes);
							foreach ($newvalue as $changekey => $change) {
								$changekey_lang = $florensia->get_columnname_backwards($changekey,'item');
								if (preg_match("/$changekey_lang/", $changekey) && !preg_match("/^name_/", $changekey_lang)) continue;
								$changes .= "<tr><td style='width:24%; border-right:1px #88a9d4 solid;'>$changekey_lang:</td><td style='width:38%; border-right:1px #88a9d4 solid;'>{$change->oldversion}</td><td>{$change->newversion}</td></tr>";
							}

							if (!$changes) continue;
 							$this->difflist['items']['diff'] .= "
 								<div><a href='".$florensia->outlink(array("itemdetails", $newkey, $stringtable->get_string($newkey)), array("escape"=>FALSE))."' target='_blank' ".popup("<div class='shortinfo_".$florensia->change()."' style='width:600px;'><table style='width:100%;'>$changes</table></div>").">".$stringtable->get_string($newkey, array("protectionsmall"=>1, "protectionlink"=>1))."</a></div>
 							";
 						}
		}
 		elseif ($dbtable=="server_npc") {
 			$this->difflist['npcs']['title'] = $flolang->diff_subtitle_npcs;
 						foreach(get_object_vars($subdiff->new) as $newkey => $newvalue) {
							$npc = new floclass_npc($newkey);
 							$this->difflist['npcs']['new'] .= "
 								<div><a href='".$florensia->outlink(array("npcdetails", $newvalue, $stringtable->get_string($newvalue)), array("escape"=>FALSE))."' target='_blank' ".popup("<div class='shortinfo_".$florensia->change()."' style='width:600px;'>".$npc->shortinfo()."</div>").">".$stringtable->get_string($newvalue, array("protectionsmall"=>1, "protectionlink"=>1))."</a></div>
 							";
 						}

 						foreach(get_object_vars($subdiff->diff) as $newkey => $newvalue) {
							unset($changes);
							foreach ($newvalue as $changekey => $change) {
								$changekey_lang = $florensia->get_columnname_backwards($changekey,'npc');
								if (preg_match("/$changekey_lang/", $changekey) && !preg_match("/^name_/", $changekey_lang)) continue;
								$changes .= "<tr><td style='width:150px; border-right:1px #88a9d4 solid;'>$changekey_lang:</td><td style='width:225px; border-right:1px #88a9d4 solid;'>{$change->oldversion}</td><td>{$change->newversion}</td></tr>";
							}

							if (!$changes) continue;
 							$this->difflist['npcs']['diff'] .= "
 								<div><a href='".$florensia->outlink(array("npcdetails", $newkey, $stringtable->get_string($newkey)), array("escape"=>FALSE))."' target='_blank' ".popup("<div class='shortinfo_".$florensia->change()."' style='width:600px;'><table style='width:100%;'>$changes</table></div>").">".$stringtable->get_string($newkey, array("protectionsmall"=>1, "protectionlink"=>1))."</a></div>
 							";
 						}
 		}
 		elseif ($dbtable=="server_map") {
 			$this->difflist['maps']['title'] = $flolang->diff_subtitle_maps;
 						foreach(get_object_vars($subdiff->new) as $newkey => $newvalue) {
 							$this->difflist['maps']['new'] .= "
 								<div><a href='".$florensia->outlink(array("mapdetails", $newvalue, $stringtable->get_string($newvalue)), array("escape"=>FALSE))."' target='_blank' ".popup("<div class='shortinfo_".$florensia->change()."' style='text-align:center; width:300px;'>".$florensia->mapprotection($newvalue, $stringtable->get_string($newvalue), "", array('maxwidth'=>300))."</div>").">".$stringtable->get_string($newvalue, array("protectionsmall"=>1, "protectionlink"=>1))."</a></div>
 							";
 						}

 						foreach(get_object_vars($subdiff->diff) as $newkey => $newvalue) {
 							$this->difflist['maps']['diff'] .= "
 								<div><a href='".$florensia->outlink(array("mapdetails", $newkey, $stringtable->get_string($newkey)), array("escape"=>FALSE))."' target='_blank' ".popup("<div class='shortinfo_".$florensia->change()."' style='text-align:center; width:300px;'>".$florensia->mapprotection($newkey, $stringtable->get_string($newkey), "", array('maxwidth'=>300))."</div>").">".$stringtable->get_string($newkey, array("protectionsmall"=>1, "protectionlink"=>1))."</a></div>
 							";
 						}
 		}
 		elseif ($dbtable=="server_questlist") {
 			$this->difflist['quests']['title'] = $flolang->diff_subtitle_quests;
 						foreach(get_object_vars($subdiff->new) as $newkey => $newvalue) {
 							$this->difflist['quests']['new'] .= "
 								<div><a href='".$florensia->outlink(array("questdetails", $newvalue, $classquest->get_title($newvalue)), array("escape"=>FALSE))."' target='_blank' ".popup("<div class='shortinfo_".$florensia->change()."' style='width:650px;'>".$classquest->get_shortinfo($newvalue)."</div>").">".$classquest->get_title($newkey)."</a></div>
 							";
 						}

 						foreach(get_object_vars($subdiff->diff) as $newkey => $newvalue) {
 							$this->difflist['quests']['diff'] .= "
 								<div><a href='".$florensia->outlink(array("questdetails", $newkey, $classquest->get_title($newkey)), array("escape"=>FALSE))."' target='_blank' ".popup("<div class='shortinfo_".$florensia->change()."' style='width:650px;'>".$classquest->get_shortinfo($newkey)."</div>").">".$classquest->get_title($newkey)."</a></div>
 							";
 						}
 		}
 		elseif ($dbtable=="server_skill") {
 			$this->difflist['skill']['title'] = "Skills";
 						foreach(get_object_vars($subdiff->new) as $newkey => $newvalue) {
 							$this->difflist['skill']['new'] .= "
 								<div><span ".popup("<div class='shortinfo_".$florensia->change()."' style='width:600px;'><div style='min-height:55px;'>".$florensia->detailsprotection(substr($newkey, 0, -2).substr($newkey,-2), "skill")."</div></div>").">Lvl:".substr($newkey,-2)." ".$stringtable->get_string($newkey, array("protectionsmall"=>1))."</span></div>
 							";
 						}

 						foreach(get_object_vars($subdiff->diff) as $newkey => $newvalue) {
 							$this->difflist['skill']['diff'] .= "
 								<div><span ".popup("<div class='shortinfo_".$florensia->change()."' style='width:600px;'><div style='min-height:55px;'>".$florensia->detailsprotection(substr($newkey, 0, -2).substr($newkey,-2), "skill")."</div></div>").">Lvl:".substr($newkey,-2)." ".$stringtable->get_string($newkey, array("protectionsmall"=>1))."</span></div>
 							";
 						}
 		}

 		elseif ($dbtable=="server_spell") {
 			$this->difflist['spell']['title'] = "Spells";
 						foreach(get_object_vars($subdiff->new) as $newkey => $newvalue) {
 							$this->difflist['spell']['new'] .= "
 								<div><span ".popup("<div class='shortinfo_".$florensia->change()."' style='width:600px;'><div style='min-height:55px;'>".$florensia->detailsprotection(substr($newkey, 0, -2).substr($newkey,-2), "spell")."</div></div>").">Lvl:".substr($newkey,-2)." ".$stringtable->get_string($newkey, array("protectionsmall"=>1))."</span></div>
 							";
 						}

 						foreach(get_object_vars($subdiff->diff) as $newkey => $newvalue) {
 							$this->difflist['spell']['diff'] .= "
 								<div><span ".popup("<div class='shortinfo_".$florensia->change()."' style='width:600px;'><div style='min-height:55px;'>".$florensia->detailsprotection(substr($newkey, 0, -2).substr($newkey,-2), "spell")."</div></div>").">Lvl:".substr($newkey,-2)." ".$stringtable->get_string($newkey, array("protectionsmall"=>1))."</span></div>
 							";
 						}
 		}
	}

	function watch_diff_overview() {
		foreach ($this->difflist as $cat => $catvalue) {
			if ($this->difflist[$cat]['new']) $this->difflist[$cat]['new'] = "<div class='diff_new'>".$this->difflist[$cat]['new']."</div>";
			if ($this->difflist[$cat]['diff']) $this->difflist[$cat]['diff'] = "<div class='diff_changed'>".$this->difflist[$cat]['diff']."</div>";
			if ($this->difflist[$cat]['del']) $this->difflist[$cat]['del'] = "<div class='diff_deleted'>".$this->difflist[$cat]['del']."</div>";
	
			$return .= "
				<div class='border small' style='margin-top:10px;'>
					<div><b>".$this->difflist[$cat]['title']."</b></div>
					".$this->difflist[$cat]['new']."
					".$this->difflist[$cat]['diff']."
					".$this->difflist[$cat]['del']."
				</div>
			";
		}
		return $return;
	}

}
$diff = new class_diff;

?>