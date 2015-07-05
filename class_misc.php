<?PHP

class class_misc {
	function get_maplist_source($parentmap="init", $substring=FALSE) {
		global $flocache;
		if ($parentmap=="init") $parentmapquery = MYSQL_QUERY("SELECT mappicture, LTWH, mapid FROM server_map WHERE parentmap=''");
		else {
			$parentmapquery = MYSQL_QUERY("SELECT mappicture, LTWH, mapid FROM server_map WHERE parentmap='$parentmap'");
		}

		while ($map = MYSQL_FETCH_ARRAY($parentmapquery)) {
			if (
				$map['mapid'] == "BARRACK_000"
				OR $map['mapid'] == "XC0_DK_000"
				OR $map['mapid'] == "DOCK_000"
				OR $map['mapid'] == "LOGIN_000"
			) continue;
			if (!$substring) $cachesubstring = $map['mapid'];
			else $cachesubstring = "{$substring},submaps,{$map['mapid']}";

 			$flocache->write_cache("maplist,{$cachesubstring},mapid", $map['mapid']);
 			$flocache->write_cache("maplist,{$cachesubstring},mappicture", $map['mappicture']);
 			$flocache->write_cache("maplist,{$cachesubstring},LTWH", $map['LTWH']);

			$trashvar = $this->get_maplist_source($map['mapid'], $cachesubstring);
		}
	}

	function get_maplist($startmap="", $subcount=0, $htmlmaplist=array()) {
		global $flocache, $stringtable, $florensia, $classquesttext;

		if (!$subcount && !$flocache->get_cache("maplist", FALSE)) { $this->get_maplist_source(); }
		$maplist = $flocache->get_cache("maplist{$startmap}", FALSE);

		foreach ($maplist as $mapid => $mapsettings) {
			if ($stringtable->get_string($mapsettings->mapid)==$mapsettings->mapid) continue;

			//ignore last submaps
			$lastsubmap = TRUE;
			if ($flocache->get_cache("maplist{$startmap},{$mapsettings->mapid},submaps", FALSE) === FALSE) { continue; } else { $lastsubmap = FALSE; }

			if (is_file($florensia->images_abs."/".strtolower($mapsettings->mappicture."_Minimap_En.png"))) { $link = "<a href='".$florensia->outlink(array("mapdetails", $mapsettings->mapid, $stringtable->get_string($mapsettings->mapid)))."'>".$stringtable->get_string($mapsettings->mapid, array('protectionlink'=>1))."</a>"; }
			else { $link = FALSE; }

			$newarray['maplink'] = $link;
			$newarray['mapid'] = $mapsettings->mapid;
			$newarray['mapname'] = $stringtable->get_string($mapsettings->mapid);
			$newarray['lastsubmap'] = $lastsubmap;
			$newarray['submap'] = $subcount;

			array_push($htmlmaplist, $newarray);
			$htmlmaplist = $this->get_maplist("{$startmap},{$mapsettings->mapid},submaps", $subcount+1, $htmlmaplist);
		}
		return $htmlmaplist;
	}

}

?>